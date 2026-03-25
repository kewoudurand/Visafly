<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            // Tentative de connexion
            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                $user = Auth::user();

                if (!$user) {
                    // Sécurité : jamais censé arriver
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Erreur inattendue, veuillez réessayer.'
                    ]);
                }

                // Récupération des rôles assignés
                $roles = $user->getRoleNames(); // Collection
                $primaryRole = $roles->first();  // Premier rôle attribué

                // Redirection selon le rôle
                switch ($primaryRole) {
                    case 'super-admin':
                    case 'admin':
                        return redirect()->route('dashboard.index')
                            ->with('success', 'Bienvenue Administrateur');
                    case 'secretaire':
                        return redirect()->route('dashboard.index')
                            ->with('success', 'Bienvenue Secrétaire');
                    case 'instructor':
                        return redirect()->route('dashboard.index')
                            ->with('success', 'Bienvenue Formateur');
                    case 'consultant':
                        return redirect()->route('dashboard.index')
                            ->with('success', 'Bienvenue Consultant');
                    case 'student':
                    case 'user':
                        return redirect()->route('dashboard.index')
                            ->with('success', 'Bienvenue sur votre espace utilisateur');
                    default:
                        // Si aucun rôle valide
                        Auth::logout();
                        return back()->withErrors([
                            'email' => 'Votre rôle utilisateur est invalide. Contactez l’administrateur.'
                        ]);
                }
        }

        // En cas d'échec de connexion
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
