<?php
// ═══════════════════════════════════════════════════
//  app/Http/Controllers/ProfileController.php
//  ✅ COMPATIBLE HYBRIDE WEB & FLUTTER (SANCTUM)
// ═══════════════════════════════════════════════════
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // ══════════════════════════════════════
    //  Afficher / Récupérer le profil
    // ══════════════════════════════════════
    public function edit(Request $request)
    {
        $user = Auth::user();

        // 📱 Si l'appel vient de Flutter, on renvoie les données utilisateur brutes
        if ($request->wantsJson()) {
            return response()->json([
                'user' => [
                    'id'            => $user->id,
                    'name'          => $user->first_name . ' ' . $user->last_name,
                    'first_name'    => $user->first_name,
                    'last_name'     => $user->last_name,
                    'email'         => $user->email,
                    'phone'         => $user->phone,
                    'country'       => $user->country,
                    'language'      => $user->language,
                    'referral_code' => $user->referral_code,
                    'avatar_url'    => $user->avatar ? asset('storage/' . $user->avatar) : null,
                ]
            ], 200);
        }

        // 💻 Sinon, fonctionnement Web classique
        return view('profils.profil', compact('user'));
    }

    // ══════════════════════════════════════
    //  Mettre à jour les infos générales
    // ══════════════════════════════════════
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'      => 'nullable|string|max:25',
            'country'    => 'nullable|string|max:100',
            'language'   => 'nullable|in:fr,en,de,pt',
        ];

        $messages = [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required'  => 'Le nom est obligatoire.',
            'email.required'      => 'L\'email est obligatoire.',
            'email.unique'        => 'Cet email est déjà pris par un autre utilisateur.',
        ];

        // 📱 Gestion d'erreur propre pour Flutter
        if ($request->wantsJson()) {
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
        } else {
            $request->validate($rules, $messages);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'country'    => $request->country,
            'language'   => $request->language ?? 'fr',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Profil mis à jour avec succès ✨',
                'user' => [
                    'name'       => $user->first_name . ' ' . $user->last_name,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'email'      => $user->email,
                    'phone'      => $user->phone,
                    'country'    => $user->country,
                ]
            ], 200);
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // ══════════════════════════════════════
    //  Mettre à jour le mot de passe
    // ══════════════════════════════════════
    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ];

        $messages = [
            'current_password.required' => 'Veuillez saisir votre mot de passe actuel.',
            'password.min'              => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'        => 'La confirmation ne correspond pas.',
        ];

        if ($request->wantsJson()) {
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }
        } else {
            $request->validate($rules, $messages);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Mot de passe actuel incorrect.'], 401);
            }
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Mot de passe modifié avec succès 🎉'], 200);
        }

        return back()->with('success_password', 'Mot de passe modifié avec succès.');
    }

    // ══════════════════════════════════════
    //  Mettre à jour l'avatar
    // ══════════════════════════════════════
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.max'   => 'L\'image ne doit pas dépasser 2 Mo.',
            'avatar.mimes' => 'Format accepté : jpg, jpeg, png, webp.',
        ]);

        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Photo de profil mise à jour.',
                'avatar_url' => asset('storage/' . $path)
            ], 200);
        }

        return back()->with('success', 'Photo de profil mise à jour.');
    }

    // ══════════════════════════════════════
    //  Supprimer l'avatar
    // ══════════════════════════════════════
    public function deleteAvatar(Request $request)
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Photo de profil supprimée.'], 200);
        }

        return back()->with('success', 'Photo de profil supprimée.');
    }
}