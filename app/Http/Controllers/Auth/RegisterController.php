<?php
// ═══════════════════════════════════════════════════
//  app/Http/Controllers/Auth/RegisterController.php
//  ✅ AVEC INTÉGRATION AFFILIATION
// ═══════════════════════════════════════════════════
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use App\Services\AffiliationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    // ✅ NOUVEAU: Injecter le service d'affiliation
    protected $affiliationService;

    /**
     * Constructeur avec injection de dépendances
     */
    public function __construct(AffiliationService $affiliationService)
    {
        $this->affiliationService = $affiliationService;
    }

    // ── Affiche la page d'inscription ──
    public function show()
    {
        // ✅ NOUVEAU: Passer l'info du parrain à la vue
        $referralCode = request()->query('ref');
        $referrer = null;

        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)
                           ->where('is_active_affiliate', true)
                           ->first();
        }

        return view('register', [
            'referrer' => $referrer,
            'referralCode' => $referralCode,
        ]);
    }

    // ── Traite l'inscription ──
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
            // ✅ NOUVEAU: Valider le code de parrainage s'il existe
            'ref'        => 'nullable|string|exists:users,referral_code',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required'  => 'Le nom est obligatoire.',
            'email.required'      => 'L\'email est obligatoire.',
            'email.unique'        => 'Cet email est déjà utilisé.connectez-vous ou changer d\'email.',
            'password.min'        => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'  => 'La confirmation du mot de passe ne correspond pas.',
            // ✅ NOUVEAU: Message pour code invalide
            'ref.exists'          => 'Ce code de parrainage est invalide.',
        ]);

        $plainPassword = $request->password;

        // ✅ NOUVEAU: Préparer les données pour le service d'affiliation
        $userData = [
            'name'      => $request->first_name . ' ' . $request->last_name,
            'first_name'=> $request->first_name,
            'last_name' => $request->last_name,
            'email'     => $request->email,
            'password'  => $request->password,
            'avatar'    => null,
            'country'   => $request->country   ?? null,
            'language'  => $request->language  ?? 'fr',
            'timezone'  => $request->timezone  ?? 'Africa/Douala',
            'phone'     => $request->phone      ?? null,
        ];

        // ✅ NOUVEAU: Récupérer le code de parrainage
        $referralCode = $request->input('ref') ?? request()->query('ref');

        try {
            $user = $this->affiliationService->registerWithAffiliation(
                $userData,
                $referralCode
            );

            // ✅ NOUVEAU : Notifier le parrain
            if ($referralCode) {
                // On cherche le propriétaire du code
                $referrer = User::where('referral_code', $referralCode)->first();
                
                if ($referrer) {
                    try {
                        Mail::to($referrer->email)->send(new \App\Mail\NewReferralNotification($referrer, $user));
                    } catch (\Exception $e) {
                        Log::warning("Notification parrain non envoyée : " . $e->getMessage());
                    }
                }
            }

            Log::info("Utilisateur inscrit avec affiliation", [
                'user_id' => $user->id,
                'email' => $user->email,
                'referral_code' => $referralCode ?? 'none',
            ]);
        } catch (\Exception $e) {
            // ✅ NOUVEAU: En cas d'erreur d'affiliation, créer l'utilisateur quand même
            Log::warning("Erreur affiliation, création sans: " . $e->getMessage());
            
            $user = User::create($userData);
        }

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
        // return redirect()->route('home')->with('success', 'Bienvenue sur VisaFly, ' . $user->first_name . ' !');
        return redirect()->route('affiliate.dashboard');
    }
}