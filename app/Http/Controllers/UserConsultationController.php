<?php
// ══════════════════════════════════════════════════════════
//  app/Http/Controllers/ConsultationController.php
//  Côté USER — formulaire public + mes consultations
// ══════════════════════════════════════════════════════════
namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\LanguePassage;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\LangueAbonnement;

class UserConsultationController extends Controller
{
    // ══════════════════════════════════
    //  GET /consultation — Afficher le formulaire
    // ══════════════════════════════════
    public function create()
    {
        [$nationalities, $countries] = Cache::remember('countries_data', now()->addHours(24), function () {
            try {
                $response = Http::timeout(5)->get('https://restcountries.com/v3.1/all?fields=name,demonyms');
                if ($response->successful()) {
                    $data = $response->json();
                    $nationalities = collect($data)
                        ->map(fn($c) => $c['demonyms']['fra']['m'] ?? $c['demonyms']['eng']['m'] ?? $c['name']['common'] ?? null)
                        ->filter()->unique()->sort()->values();
                    $countries = collect($data)
                        ->map(fn($c) => $c['name']['common'] ?? null)
                        ->filter()->unique()->sort()->values();
                    return [$nationalities, $countries];
                }
            } catch (\Exception) {}

            // ── Fallback liste locale ──
            $nat = collect(['Camerounais','Français','Canadien','Allemand','Belge',
                'Portugais','Sénégalais','Ivoirien','Congolais','Malien',
                'Burkinabè','Togolais','Béninois','Nigérian','Ghanéen',
                'Gabonais','Centrafricain','Tchadien','Tunisien','Marocain',
                'Algérien','Américain','Anglais','Espagnol','Italien'])->sort()->values();

            $cty = collect(['Cameroun','France','Canada','Allemagne','Belgique',
                'Portugal','Sénégal','Côte d\'Ivoire','Congo','Mali',
                'Burkina Faso','Togo','Bénin','Nigéria','Ghana',
                'Gabon','Centrafrique','Tchad','Tunisie','Maroc',
                'Algérie','États-Unis','Royaume-Uni','Espagne','Italie'])->sort()->values();

            return [$nat, $cty];
        });

        return view('users.consultation', compact('nationalities', 'countries'));
    }

    // ══════════════════════════════════
    //  POST /consultation — Enregistrer la demande
    // ══════════════════════════════════
    public function store(Request $request)
    {
        $data = $request->validate([
            // Identité
            'full_name'            => 'required|string|max:255',
            'birth_date'           => 'nullable|date',
            'nationality'          => 'nullable|string|max:100',
            'residence_country'    => 'nullable|string|max:100',
            'phone'                => 'nullable|string|max:30',
            'email'                => 'nullable|email|max:255',
            'profession'           => 'nullable|string|max:150',
            // Projet
            'project_type'         => 'nullable|string|max:100',
            'destination_country'  => 'nullable|string|max:100',
            // Historique
            'visa_history'         => 'nullable|boolean',
            'visa_history_details' => 'nullable|string|max:2000',
            // Formation
            'last_degree'          => 'nullable|string|max:150',
            'graduation_year'      => 'nullable|string|max:4',
            'field_of_study'       => 'nullable|string|max:150',
            'language_level'       => 'nullable|string|max:20',
            'work_experience'      => 'nullable|string|max:2000',
            // Documents
            'passport_valid'       => 'nullable|boolean',
            'documents_available'  => 'nullable|boolean',
            'admission_or_contract'=> 'nullable|boolean',
            'financial_proof'      => 'nullable|boolean',
            // Autres
            'budget'               => 'nullable|string|max:100',
            'departure_date'       => 'nullable|string|max:50',
            'referral_source'      => 'nullable|string|max:100',
            'message'              => 'nullable|string|max:3000',
            'need_consultation'    => 'nullable|boolean',
        ]);

        // Ajouter l'ID user si connecté
        $data['user_id'] = Auth::id();
        $data['status']  = 'en_attente';
        //$data['status']  = 0;

        Consultation::create($data);

        return redirect()->route('consultation.merci')
            ->with('success', 'Votre demande a bien été envoyée. Nous vous contacterons sous 48h.');
    }

    // ══════════════════════════════════
    //  GET /consultation/merci
    // ══════════════════════════════════
    public function merci()
    {
        return view('admin/consultations.merci');
    }

    // ══════════════════════════════════
    //  GET /mes-consultations — Liste pour le user connecté
    // ══════════════════════════════════
    public function index()
    {
        $user = Auth::user();
 
        // ── Admin / Super-admin → dashboard admin existant ──
        if ($user->hasRole(['super-admin', 'admin'])) {
            $service = new DashboardService();
            $widgets = $service->widgetsFor($user);
            $stats   = $service->statsFor($user);
            $consultations =Consultation::latest()->get();
            return view('admin.dashboard', compact('user', 'widgets', 'stats', 'consultations'));
        }
 
        // ── Consultant → dashboard admin allégé ──
        if ($user->hasRole('consultant')) {
            $service = new DashboardService();
            $widgets = $service->widgetsFor($user);
            $stats   = $service->statsFor($user);
            return view('users.dashboard', compact('user', 'widgets', 'stats'));
        }
 
        // ── Student / Tous les autres → dashboard utilisateur ──
        return $this->studentDashboard($user);
    }
 
    // ══════════════════════════════════════
    //  Dashboard student
    // ══════════════════════════════════════
    private function studentDashboard($user)
    {
        // Passages TCF récents (5 derniers)
        $passages = LanguePassage::with(['serie', 'discipline'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
 
        // Consultations récentes (5 dernières)
        // Compatible avec l'ancien model (champ status) ET le nouveau (champ statut)
        $consultations = Consultation::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email); // ancien système sans user_id
            })
            ->latest()
            ->take(5)
            ->get();
 
        // Abonnement actif
        $abonnement = LangueAbonnement::where('user_id', $user->id)
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->latest()
            ->first();
 
        // Stats
        $stats = [
            'tests_passes'        => LanguePassage::where('user_id', $user->id)
                                        ->where('statut', 'termine')->count(),
            'score_moyen'         => (int) round(
                                        LanguePassage::where('user_id', $user->id)
                                            ->where('statut', 'termine')
                                            ->avg('score') ?? 0
                                    ),
            'consultations_total' => $consultations->count(),
            'abonnement_actif'    => (bool) $abonnement,
        ];
 
        return view('users.dashboard',
            compact('user', 'passages', 'consultations', 'abonnement', 'stats'));
    }

}