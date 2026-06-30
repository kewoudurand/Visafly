<?php

// ══════════════════════════════════════════════════════════
//  app/Http/Controllers/UserConsultationController.php
//  Côté USER — Formulaire public, API Flutter & Gestion des PDF
// ══════════════════════════════════════════════════════════

namespace App\Http\Controllers;

use App\Mail\WelcomeUserMail;
use App\Models\Consultation;
use App\Models\User;
use App\Models\Document;
use App\Models\LanguePassage;
use App\Models\LangueAbonnement;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Notifications\NotificationFactory; // <--- Vérifie cet import
use App\Events\NotificationCreated;

class UserConsultationController extends Controller
{
    /**
     * 🌐 WEB & 📱 API FLUTTER : Récupérer la liste des pays et nationalités.
     */
    public function create(Request $request)
    {
        [$nationalities, $countries] = $this->getCountriesAndNationalities();

        // Si la requête demande du JSON (Flutter)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success'       => true,
                'nationalities' => $nationalities,
                'countries'     => $countries
            ]);
        }

        // Sinon, retourne la vue Web
        return view('users.consultation', compact('nationalities', 'countries'));
    }

    /**
     * 💾 HYBRIDE (WEB & API) : Enregistrer la demande et téléverser les fichiers PDF.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Étape 1 – Infos personnelles
            'full_name'          => 'required|string|max:255',
            'birth_date'         => 'required|date',
            'nationality'        => 'required|string|max:100',
            'residence_country'  => 'required|string|max:100',
            'phone'              => 'required|string|max:30',
            'email'              => 'required|email|max:255',
            'profession'         => 'required|string|max:255',

            // Étape 2 – Projet visa
            'project_type'           => 'required|string|max:100',
            'destination_country'    => 'required|string|max:100',
            'visa_history'           => 'required|boolean',
            'visa_history_details'   => 'nullable|string|max:1000',

            // Étape 3 – Profil académique
            'last_degree'        => 'required|string|max:50',
        ], [
            'full_name.required'         => 'Le nom complet est obligatoire.',
            'birth_date.required'        => 'La date de naissance est obligatoire.',
            'nationality.required'       => 'La nationalité est obligatoire.',
            'residence_country.required' => 'Le pays de résidence est obligatoire.',
            'phone.required'             => 'Le téléphone est obligatoire.',
            'email.required'             => 'L\'email est obligatoire.',
            'email.email'                => 'L\'email n\'est pas valide.',
            'profession.required'        => 'La profession est obligatoire.',
            'project_type.required'      => 'L\'objectif principal est obligatoire.',
            'destination_country.required' => 'Le pays de destination est obligatoire.',
            'visa_history.required'      => 'Veuillez indiquer l\'historique visa.',
            'last_degree.required'       => 'Le dernier diplôme est obligatoire.',
        ]);

        DB::beginTransaction();

        try {
            // ── 1. Trouver ou créer le compte utilisateur client ──────────────
            $motDePasseParDefaut = '1234567890';
            $clientExistant      = false;

            $clientUser = User::where('email', $request->email)->first();

            if (!$clientUser) {
                // Séparer prénom / nom depuis le champ full_name
                $parts     = explode(' ', trim($request->full_name), 2);
                $firstName = $parts[0];
                $lastName  = $parts[1] ?? '';

                $clientUser = User::create([
                    'name'       => trim($request->full_name),
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $request->email,
                    'password'   => Hash::make($motDePasseParDefaut),
                    'phone'      => $request->phone,
                    'country'    => $request->nationality,
                    'language'   => 'fr',
                    'timezone'   => 'Africa/Douala',
                    // Générer un code de parrainage unique
                    'referral_code' => strtoupper(Str::random(8)),
                ]);

                // Assigner le rôle "user" par défaut (Spatie)
                $role = Role::firstOrCreate(
                    ['name' => 'user', 'guard_name' => 'web']
                );
                $clientUser->assignRole($role);

                // Envoyer l'email de bienvenue avec les identifiants
                try {
                    Mail::to($clientUser->email)->send(
                        new WelcomeUserMail($clientUser, $motDePasseParDefaut)
                    );
                } catch (\Exception $e) {
                    Log::warning('Email de bienvenue non envoyé : ' . $e->getMessage());
                }
            } else {
                // Le client existe déjà, on crée quand même la consultation
                $clientExistant = true;
            }

            // ── 2. Créer la consultation et la lier au consultant + client ────
            $consultation = Consultation::create([
                'user_id'              => $clientUser->id,
                'consultant_id'        => Auth::id(),

                // Infos personnelles
                'full_name'            => $request->full_name,
                'birth_date'           => $request->birth_date,
                'nationality'          => $request->nationality,
                'residence_country'    => $request->residence_country,
                'phone'                => $request->phone,
                'email'                => $request->email,
                'profession'           => $request->profession,

                // Projet visa
                'project_type'         => $request->project_type,
                'destination_country'  => $request->destination_country,
                'visa_history'         => (bool) $request->visa_history,
                'visa_history_details' => $request->visa_history_details,

                // Profil académique
                'last_degree'          => $request->last_degree,

                // Statut initial
                'statut'               => 'en_attente',

                // Objet de la consultation (pour l'affichage dans le dashboard)
                'objet'                => $request->project_type . ' – ' . $request->destination_country,
            ]);

            DB::commit();

            // ── 3. Message de succès contextuel ──────────────────────────────
            if ($clientExistant) {
                $message = "✅ Consultation créée et liée au compte existant de {$clientUser->name}.";
            } else {
                $message = "✅ Consultation créée. Un compte a été ouvert pour {$clientUser->name} "
                         . "(email : {$clientUser->email} / mot de passe : {$motDePasseParDefaut}). "
                         . "Un email de bienvenue lui a été envoyé.";
            }

            return redirect()
                ->route('consultation.merci')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création consultation : ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création. Veuillez réessayer.');
        }
    }


    public function monAvancement()
    {
        $user = Auth::user();

        // 1. Récupérer LA dernière consultation du client (avec sécurité stricte)
        $consultation = Consultation::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                ->orWhere('email', $user->email);
            })
            ->latest()
            ->first();

        // Si aucune consultation n'a été créée, on renvoie la vue sans données
        if (!$consultation) {
            return view('users.dashboard', ['consultation' => null, 'etapeCourante' => null]);
        }

        // 2. Chargement optimisé des relations (Identique à ta logique consultant, mais adaptée aux relations du user)
        $consultation->load([
            'pipelineEtapes' => function ($query) {
                $query->orderBy('ordre', 'asc');
            },
            'rendezVous' => function ($query) {
                $query->where('statut', '!=', 'annule') // Optionnel : ne pas stresser le client avec les RDV annulés
                    ->orderBy('date_heure', 'asc');
            },
            'notes' => function ($query) {
                // Sécurité : Le User ne voit QUE les notes cochées "visible_client"
                $query->where('visible_client', true)->with(['auteur', 'pipelineEtape'])->latest();
            },
            'documents'
        ]);

        // 3. Extraction de l'étape active pour focus sur le dashboard du client
        // Correction de la relation d'appel : on fouille dans la collection fraîchement chargée
        $etapeCourante = $consultation->pipelineEtapes
            ->where('status', 'en_cours')
            ->first();

        // 4. Calcul dynamique du pourcentage si tu ne veux pas le figer en BDD
        // total validé / total étapes * 100
        $totalEtapes = $consultation->pipelineEtapes->count();
        if ($totalEtapes > 0) {
            $etapesValidees = $consultation->pipelineEtapes->where('statut', 'valide')->count();
            $consultation->progression = round(($etapesValidees / $totalEtapes) * 100);
        } else {
            $consultation->progression = 0;
        }

        return view('users.dashboard', compact('consultation', 'etapeCourante'));
    }

    public function storeDocument(Request $request , $id = null)
    {
       // dd($request->all());
        if ($id) {
            $request->merge(['consultation_id' => $id]);
        }
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'etape_index'     => 'required|integer',
            'files'           => 'required|array',
            'files.*'         => 'required|file|mimes:pdf|max:5120', // 5 Mo max, PDF uniquement
        ], [
            'files.*.mimes' => 'Seuls les fichiers PDF sont acceptés.',
            'files.*.max'   => 'Chaque fichier ne doit pas dépasser 5 Mo.',
        ]);

        $consultation = Consultation::findOrFail($request->consultation_id);

        // Sécurité : le client ne peut uploader que pour sa propre consultation
        if ($consultation->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $etapeIndex = (int) $request->etape_index;

        foreach ($request->file('files') as $typeDoc => $fichier) {
            // Chemin : consultations/{id}/etape_{n}/{type}_{timestamp}.pdf
            $dossier    = "consultations/{$consultation->id}/etape_{$etapeIndex}";
            $nomFichier = Str::slug($typeDoc) . '_' . time() . '.pdf';
            $chemin     = $fichier->storeAs($dossier, $nomFichier, 'public');

            // Vérifie si un document du même type pour cette étape existe déjà
            $documentExistant = Document::where('consultation_id', $consultation->id)
                ->where('etape_index', $etapeIndex)
                ->where('type', $typeDoc)
                ->first();

            if ($documentExistant) {
                // On écrase l'ancien fichier et on remet en "en_attente"
                Storage::disk('public')->delete($documentExistant->file_path);
                
                $documentExistant->update([
                    'file_path' => $chemin,
                    'name'      => $fichier->getClientOriginalName(),
                    'status'    => 'en_attente',
                    'comment'   => null,
                ]);
            } else {
                Document::create([
                    'consultation_id' => $consultation->id,
                    'etape_index'     => $etapeIndex,
                    'type'            => $typeDoc,
                    'name'            => $fichier->getClientOriginalName(),
                    'file_path'       => $chemin,
                    'status'          => 'en_attente',
                ]);
            }
        }

        if ($consultation->consultant) {
            $clientName = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            
            // 1. Création via la Factory
            $notif = \App\Notifications\NotificationFactory::newDocumentSubmitted(
                $consultation->consultant, 
                $consultation, 
                $clientName
            );
            
            // 2. Déclenchement de l'événement pour le temps réel (Pusher/WebSockets)
            event(new \App\Events\NotificationCreated($notif));
        }

        return redirect()->back()->with('success', 'Vos documents ont été envoyés avec succès. Votre consultant les examinera prochainement.');
    }

    /**
     * 🌐 WEB & 📱 API FLUTTER : Liste paginée ou Données du Dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user() ?? Auth::user();

        // --- TRAITEMENT SI REQUÊTE MOBILE (API FLUTTER) ---
        if ($request->wantsJson() || $request->is('api/*')) {
            $query = Consultation::with('documents');

            if (!$user->hasRole(['admin', 'super-admin'])) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('email', $user->email);
                });
            }

            return response()->json([
                'success' => true,
                'data'    => $query->latest()->paginate(10)
            ]);
        }

        // --- TRAITEMENT SI REQUÊTE WEB (BLADE) ---
        if ($user->hasRole(['super-admin', 'admin'])) {
            $service = new DashboardService();
            $widgets = $service->widgetsFor($user);
            $stats   = $service->statsFor($user);
            $consultations = Consultation::with('documents')->latest()->get();
            return view('admin.dashboard', compact('user', 'widgets', 'stats', 'consultations'));
        }

        if ($user->hasRole('consultant')) {
            $service = new DashboardService();
            $widgets = $service->widgetsFor($user);
            $stats   = $service->statsFor($user);
            return view('users.dashboard', compact('user', 'widgets', 'stats'));
        }

        return $this->studentDashboard($user);
    }

    /**
     * 🌐 WEB & 📱 API FLUTTER : Voir le détail complet d'un dossier.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user() ?? Auth::user();
        $consultation = Consultation::with('documents')->find($id);

        if (!$consultation) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['success' => false, 'message' => 'Consultation introuvable'], 404);
            }
            abort(404);
        }

        // Vérification de sécurité
        if (!$user->hasRole(['admin', 'super-admin']) && 
            $consultation->user_id !== $user->id && 
            $consultation->email !== $user->email) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
            }
            abort(403);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data'    => $consultation
            ]);
        }

        return view('users.consultation_detail', compact('consultation'));
    }

    public function update(Request $request,Consultation $consultation) {

        $consultation->update(
            $request->all()
        );

        return response()->json([
            'success' => true,
            'message' => 'Demande mise à jour',
            'data' => $consultation,
        ]);
    }


    /**
     * 🌐 WEB : Redirection vers la page de remerciement.
     */
    public function merci()
    {
        return view('admin/consultations.merci');
    }

    /**
     * 🔒 MÉTHODE PRIVÉE : Données du tableau de bord étudiant (Web).
     */
    private function studentDashboard($user)
    {
        $passages = LanguePassage::with(['serie', 'discipline'])->where('user_id', $user->id)->latest()->take(5)->get();
        $consultations = Consultation::with('documents')->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('email', $user->email);
        })->latest()->take(5)->get();
        $abonnement = LangueAbonnement::where('user_id', $user->id)->where('actif', true)->where('fin_at', '>=', now())->latest()->first();

        $stats = [
            'tests_passes'        => LanguePassage::where('user_id', $user->id)->where('statut', 'termine')->count(),
            'score_moyen'         => (int) round(LanguePassage::where('user_id', $user->id)->where('statut', 'termine')->avg('score') ?? 0),
            'consultations_total' => $consultations->count(),
            'abonnement_actif'    => (bool) $abonnement,
        ];

        return view('users.dashboard', compact('user', 'passages', 'consultations', 'abonnement', 'stats'));
    }

    /**
     * 🔒 MÉTHODE PRIVÉE : Récupérer et mettre en cache la liste mondiale des pays.
     */
    private function getCountriesAndNationalities()
    {
        return Cache::remember('countries_data', now()->addHours(24), function () {
            try {
                // On demande aussi les translations pour avoir les pays en Français
                $response = Http::timeout(8)->get('https://restcountries.com/countries/v5/fields=name,demonyms,translations');
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (!empty($data) && is_array($data)) {
                        $nationalities = collect($data)
                            ->map(fn($c) => $c['demonyms']['fra']['m'] ?? $c['demonyms']['eng']['m'] ?? null)
                            ->filter()->unique()->sort()->values();

                        $countries = collect($data)
                            ->map(fn($c) => $c['translations']['fra']['common'] ?? $c['name']['common'] ?? null)
                            ->filter()->unique()->sort()->values();

                        // On vérifie qu'on a bien récupéré des données avant de valider
                        if ($nationalities->isNotEmpty() && $countries->isNotEmpty()) {
                            return [$nationalities->toArray(), $countries->toArray()];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Optionnel : Log l'erreur pour comprendre si l'API est down
                \Log::error("Erreur RestCountries API: " . $e->getMessage());
            }

            // Si l'API a échoué, on retourne le plan de secours, 
            // mais on peut aussi décider de ne pas le mettre en cache pour réessayer au prochain rafraîchissement.
            return [
                ['Allemand', 'Belge', 'Camerounais', 'Canadien', 'Congolais', 'Français', 'Italien', 'Ivoirien', 'Sénégalais'],
                ['Allemagne', 'Belgique', 'Cameroun', 'Canada', 'Congo', 'Côte d\'Ivoire', 'France', 'Italie', 'Sénégal']
            ];
        });
    }
}