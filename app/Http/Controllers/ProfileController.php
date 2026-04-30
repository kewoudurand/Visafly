<?php
// ═══════════════════════════════════════════════════
//  app/Http/Controllers/ProfileController.php
// ═══════════════════════════════════════════════════
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // ══════════════════════════════════════
    //  Afficher le formulaire de profil
    // ══════════════════════════════════════
    public function edit()
    {
        $user = Auth::user();
        return view('users.profil', compact('user'));
    }

    // ══════════════════════════════════════
    //  Mettre à jour les infos générales
    // ══════════════════════════════════════
    public function update(Request $request)
    {
        //dd($request->all());
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'phone'      => 'nullable|string|max:25',
            'country'    => 'nullable|string|max:100',
            'language'   => 'nullable|in:fr,en,de,pt',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required'  => 'Le nom est obligatoire.',
            'email.required'      => 'L\'email est obligatoire.',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'country'    => $request->country,
            'language'   => $request->language ?? 'fr',
        ]);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // ══════════════════════════════════════
    //  Mettre à jour le mot de passe
    // ══════════════════════════════════════
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Veuillez saisir votre mot de passe actuel.',
            'password.min'              => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'        => 'La confirmation ne correspond pas.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Mot de passe actuel incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

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
            'avatar.image'    => 'Le fichier doit être une image.',
            'avatar.max'      => 'L\'image ne doit pas dépasser 2 Mo.',
            'avatar.mimes'    => 'Format accepté : jpg, jpeg, png, webp.',
        ]);

        $user = Auth::user();

        // Supprimer l'ancien avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Enregistrer le nouveau
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Photo de profil mise à jour.');
    }

    // ══════════════════════════════════════
    //  Supprimer l'avatar
    // ══════════════════════════════════════
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return back()->with('success', 'Photo de profil supprimée.');
    }
}