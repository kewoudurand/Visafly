<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * Affiche la page d'inscription
     */
    public function show()
    {
        return view('register');
    }

    /**
     * Gère la création de compte
     */
    public function register(Request $request)
    {
        //dd($request->all());
        // Validation
        $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6|confirmed',
        ]);

        // mot de passe en clair avant hashage
        $plainPassword = $request->password;
        
        // Création utilisateur
        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'     => $request->last_name,
            'email'    => $request->email,
            'password'   => $request->password,
            'avatar'     => null,         
            'country'    => $request->country ?? null,
            'language'   => $request->language ?? 'fr',
            'timezone'   => $request->timezone ?? 'Africa/Douala',
            'phone'      => $request->phone ?? null,
        ]);

        // 📩 ENVOI DE L’EMAIL DE BIENVENUE
        Mail::to($user->email)->send(new WelcomeUserMail($user,$plainPassword));


        // Connexion automatique
        Auth::login($user);

        // Redirection selon le rôle
        return redirect()->route('dashboard')->with('success', 'Bienvenue dans l’espace administrateur.');
    }
}
