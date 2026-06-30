<?php
// ═══════════════════════════════════════════════════
//  app/Http/Controllers/Auth/RegisterController.php
//  ✅ AVEC INTÉGRATION AFFILIATION & DOUBLE CASQUETTE FLUTTER / WEB
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
    protected $affiliationService;

    /**
     * Constructeur avec injection de dépendances
     */
    public function __construct(AffiliationService $affiliationService)
    {
        $this->affiliationService = $affiliationService;
    }

    // ── Affiche la page d'inscription (Web Uniquement) ──
    public function show()
    {
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

    // ── Traite l'inscription (Hybride Web & Flutter) ──
    public function register(Request $request)
    {
        // Si c'est Flutter/API (wantsJson), le last_name devient facultatif
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => $request->wantsJson() ? 'nullable|string|max:255' : 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => $request->wantsJson() ? 'required|min:6' : 'required|min:6|confirmed',
            'ref'        => 'nullable|string|exists:users,referral_code',
        ];

        $messages = [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required'  => 'Le nom est obligatoire.',
            'email.required'      => 'L\'email est obligatoire.',
            'email.unique'        => 'Cet email est déjà utilisé.',
            'password.min'        => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'  => 'La confirmation du mot de passe ne correspond pas.',
            'ref.exists'          => 'Ce code de parrainage est invalide.',
        ];

        if ($request->wantsJson()) {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    // On récupère le premier message d'erreur explicite
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
        } else {
            $request->validate($rules, $messages);
        }

        $plainPassword = $request->password;

        // Gestion propre si l'application mobile envoie un nom complet dans 'first_name'
        $firstName = $request->first_name;
        $lastName = $request->last_name ?? '';

        if (empty($lastName) && str_contains($firstName, ' ')) {
            $parts = explode(' ', $firstName, 2);
            $firstName = $parts[0];
            $lastName = $parts[1];
        }

        $userData = [
            'name'       => trim($firstName . ' ' . $lastName),
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $request->email,
            'password'   => $request->password,
            'avatar'     => null,
            'country'    => $request->country   ?? null,
            'language'   => $request->language  ?? 'fr',
            'timezone'   => $request->timezone  ?? 'Africa/Douala',
            'phone'      => $request->phone     ?? null,
        ];

        // Récupérer le code de parrainage
        $referralCode = $request->input('ref') ?? request()->query('ref');

        try {
            $user = $this->affiliationService->registerWithAffiliation($userData, $referralCode);

            if ($referralCode) {
                $referrer = User::where('referral_code', $referralCode)->first();
                if ($referrer) {
                    try {
                        Mail::to($referrer->email)->send(new \App\Mail\NewReferralNotification($referrer, $user));
                    } catch (\Exception $e) {
                        Log::warning("Notification parrain non envoyée : " . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("Erreur affiliation, création sans: " . $e->getMessage());
            $userData['password'] = bcrypt($request->password);
            $user = User::create($userData);
        }

        // Assigner le rôle venant de l'application mobile (converti selon tes rôles Spatie)
        // Flutter envoie 'etudiant' ou 'professeur', Spatie attend 'student' ou 'teacher'
        $roleName = 'user';
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user->assignRole($role);

        try {
            Mail::to($user->email)->send(new WelcomeUserMail($user, $plainPassword));
        } catch (\Exception $e) {
            Log::warning('Email de bienvenue non envoyé : ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            $token = $user->createToken('visafly_mobile_token')->plainTextToken;
            return response()->json([
                'message' => 'Inscription réussie 🎉',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'email' => $user->email,
                    'role' => $roleName,
                ]
            ], 201);
        }

        Auth::login($user);
        return redirect()->route('affiliate.dashboard');
    }
}