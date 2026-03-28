<?php
// ═══════════════════════════════════════════════════
//  app/Http/Controllers/Auth/RegisterController.php
// ═══════════════════════════════════════════════════
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    // ── Affiche la page d'inscription ──
    public function show()
    {
        return view('register');   // resources/views/auth/register.blade.php
    }

    // ── Traite l'inscription ──
    public function register(Request $request)
    {
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

        // ── Assigner le rôle 'student' automatiquement ──
        // S'assurer que le rôle existe (sécurité)
        $role = Role::firstOrCreate(
            ['name' => 'student', 'guard_name' => 'web']
        );
        $user->assignRole($role);

        // ── Email de bienvenue ──
        try {
            Mail::to($user->email)->send(new WelcomeUserMail($user, $plainPassword));
        } catch (\Exception $e) {
            // Ne pas bloquer l'inscription si l'email échoue
            Log::warning('Email de bienvenue non envoyé : ' . $e->getMessage());
        }

        // ── Connexion automatique ──
        Auth::login($user);

        // ── Redirection selon le rôle ──
        // student → dashboard utilisateur
        // admin/super-admin → dashboard admin (géré par DashboardController)
        return redirect()->route('dashboard')
            ->with('success', 'Bienvenue sur VisaFly, ' . $user->first_name . ' !');
    }
}