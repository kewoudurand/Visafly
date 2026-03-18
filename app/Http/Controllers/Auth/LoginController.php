<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validation des données
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentative de connexion
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            $user = Auth::user();

            // 🟦 REDIRECTION SELON LE ROLE
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Bienvenue Administrateur');
            }

            if ($user->role === 'secretaire') {
                return redirect()->route('secretaire.dashboard')
                    ->with('success', 'Bienvenue Secrétaire');
            }

            // 🟥 Si le rôle n’est pas reconnu
            Auth::logout();
            return back()->withErrors([
                'email' => 'Votre rôle utilisateur est invalide. Contactez l’administrateur.'
            ]);
        }

        // En cas d'échec
        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas à nos enregistrements.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnexion réussie 👋');
    }
}
