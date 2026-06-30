<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // --- CODE POUR PARTIE FLUTTER (DEMANDE JSON) ---
        if ($request->wantsJson()) {
            if (Auth::validate($credentials)) {
                $user = User::where('email', $request->email)->first();
                
                // Récupération sécurisée du rôle Spatie
                $primaryRole = $user->getRoleNames()->first() ?? 'student';

                // Transformation interne pour correspondre au HomeNavigator Flutter
                $flutterRole = $primaryRole;
                if (in_array($primaryRole, ['instructor', 'professeur', 'consultant', 'secretaire'])) {
                    $flutterRole = 'mentor';
                } elseif (in_array($primaryRole, ['super-admin', 'admin'])) {
                    $flutterRole = 'admin';
                }

                $token = $user->createToken('visafly_mobile_token')->plainTextToken;

                return response()->json([
                    'message' => 'Connexion réussie',
                    'token'   => $token,
                    'user'    => [
                        'id'            => $user->id,
                        'first_name'          => $user->first_name,
                        'email'         => $user->email,
                        'phone'         => $user->phone,
                        'country'       => $user->country,
                        'referral_code' => $user->referral_code,
                        'role'          => $flutterRole,
                    ]
                ], 200);
            }

            return response()->json([
                'message' => 'Les identifiants ne correspondent pas à nos enregistrements.'
            ], 401);
        }

        // --- CODE ORIGINAL POUR LA PARTIE WEB (SESSIONS & COOKIES) ---
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Erreur inattendue, veuillez réessayer.'
                ]);
            }

            // Utilisation native des rôles Spatie
            $primaryRole = $user->getRoleNames()->first();  

            switch ($primaryRole) {
                case 'super-admin':
                case 'admin':
                    return redirect()->route('dashboard.index')->with('success', 'Bienvenue Administrateur');
                case 'secretaire':
                    return redirect()->route('dashboard.index')->with('success', 'Bienvenue Secrétaire');
                case 'instructor':
                case 'professeur':
                    return redirect()->route('dashboard.index')->with('success', 'Bienvenue Formateur');
                case 'consultant':
                    return redirect()->route('dashboard.index')->with('success', 'Bienvenue Consultant');
                case 'student':
                case 'user':
                    return redirect()->route('dashboard.index')->with('success', 'Bienvenue sur votre espace utilisateur');
                default:
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Votre rôle utilisateur est invalide. Contactez l’administrateur.'
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas à nos enregistrements.',
        ]);
    }

    // 🆕 FONCTION MANQUANTE : Récupérer les détails de l'utilisateur connecté via Sanctum (/auth/me)
    public function me(Request $request)
    {
        $user = $request->user();
        $primaryRole = $user->getRoleNames()->first() ?? 'student';
        
        $flutterRole = $primaryRole;
        if (in_array($primaryRole, ['instructor', 'professeur', 'consultant', 'secretaire'])) {
            $flutterRole = 'mentor';
        } elseif (in_array($primaryRole, ['super-admin', 'admin'])) {
            $flutterRole = 'admin';
        }

        return response()->json([
            'user' => [
                'id'            => $user->id,
                'first_name'          => $user->first_name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'country'       => $user->country,
                'referral_code' => $user->referral_code,
                'role'          => $flutterRole,
            ]
        ], 200);
    }

    // 🆕 FONCTION MANQUANTE : Demande de réinitialisation de mot de passe (Envoi de lien ou code)
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Logique de base Laravel Password Broker (renvoie un lien par défaut par email)
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Lien de réinitialisation envoyé par email.'], 200);
        }

        return response()->json(['message' => 'Impossible d\'envoyer l\'email à cette adresse.'], 400);
    }

    // 🆕 FONCTION MANQUANTE : Traitement de la modification du mot de passe
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Votre mot de passe a été réinitialisé avec succès.'], 200);
        }

        return response()->json(['message' => __($status)], 400);
    }

    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }
            return response()->json(['message' => 'Déconnexion réussie et token révoqué.'], 200);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnexion réussie 👋');
    }
}