<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TcfAbonnement;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    // ── Liste des utilisateurs ──
    public function index(Request $request)
    {
        $this->authorize('manage users');

        $query = User::with('roles')
            ->withCount('roles');

        // Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filtre abonnement
        if ($request->filled('abonnement')) {
            if ($request->abonnement === 'actif') {
                $query->whereHas('abonnements', fn($q) =>
                    $q->where('actif', true)->where('fin_at', '>=', now())
                );
            } else {
                $query->whereDoesntHave('abonnements', fn($q) =>
                    $q->where('actif', true)->where('fin_at', '>=', now())
                );
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    // ── Formulaire création ──
    public function create()
    {
        $this->authorize('manage users');
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // ── Enregistrer un nouvel utilisateur ──
    public function store(Request $request)
    {
        $this->authorize('manage users');

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required'  => 'Le nom est obligatoire.',
            'email.required'      => 'L\'email est obligatoire.',
            'email.unique'        => 'Cet email est déjà utilisé.',
            'password.min'        => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'  => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $plainPassword = $request->password;

        // ── Créer l'utilisateur ──
        $user = User::create([
            'name'      => $request->first_name . ' ' . $request->last_name, // champ name unifié
            'first_name'=> $request->first_name,
            'last_name' => $request->last_name,
            'email'     => $request->email,
            'password'  => $request->password,   // ← IMPORTANT : hasher le mot de passe
            'avatar'    => null,
            'country'   => $request->country   ?? null,
            'language'  => $request->language  ?? 'fr',
            'timezone'  => $request->timezone  ?? 'Africa/Douala',
            'phone'     => $request->phone      ?? null,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$user->name} créé avec succès.");
    }

    // ── Afficher un utilisateur ──
    public function show(User $user)
    {
        $this->authorize('manage users');

        $user->load('roles', 'permissions');
        $abonnement = TcfAbonnement::where('user_id', $user->id)
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->latest()->first();

        $historique = TcfAbonnement::where('user_id', $user->id)
            ->latest()->get();

        return view('admin.users.show', compact('user', 'abonnement', 'historique'));
    }

    // ── Formulaire modification ──
    public function edit(User $user)
    {
        $this->authorize('manage users');
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    // ── Mettre à jour un utilisateur ──
    public function update(Request $request, User $user)
    {
        $this->authorize('manage users');

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,'.$user->id,
            'password'=> 'nullable|min:8',
            'role'    => 'required|exists:roles,name',
            'phone'   => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'country' => $request->country,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Changer le rôle
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$user->name} mis à jour.");
    }

    // ── Supprimer un utilisateur ──
    public function destroy(User $user)
    {
        $this->authorize('manage users');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$name} supprimé.");
    }

    // ── Changer le rôle (AJAX) ──
    public function changeRole(Request $request, User $user)
    {
        $this->authorize('assign roles');

        $request->validate(['role' => 'required|exists:roles,name']);

        $user->load('roles', 'permissions');
        $abonnement = TcfAbonnement::where('user_id', $user->id)
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->latest()->first();

        $historique = TcfAbonnement::where('user_id', $user->id)
            ->latest()->get();

        return view('admin.users.show', compact('user', 'abonnement', 'historique'));
    }

    // ── Activer / Désactiver un abonnement manuel ──
    public function toggleAbonnement(Request $request, User $user)
    {
        $this->authorize('manage users');

        $request->validate([
            'forfait' => 'required|in:mensuel,trimestriel,annuel',
        ]);

        $durees = ['mensuel' => 1, 'trimestriel' => 3, 'annuel' => 12];
        $prix   = ['mensuel' => 5000, 'trimestriel' => 12000, 'annuel' => 40000];

        // Désactiver les anciens
        TcfAbonnement::where('user_id', $user->id)->update(['actif' => false]);

        TcfAbonnement::create([
            'user_id'   => $user->id,
            'forfait'   => $request->forfait,
            'montant'   => $prix[$request->forfait],
            'devise'    => 'XAF',
            'debut_at'  => now(),
            'fin_at'    => now()->addMonths($durees[$request->forfait]),
            'actif'     => true,
            'reference_paiement' => 'ADMIN-'.strtoupper(uniqid()),
        ]);

        return back()->with('success', "Abonnement {$request->forfait} activé pour {$user->first_name}.");
    }
}
