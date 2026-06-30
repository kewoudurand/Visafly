<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeUserMail;
use App\Models\Consultation;
use App\Models\User;
use App\Models\PipelineEtape;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Carbon;

class ConsultationController extends Controller
{
    // ─────────────────────────────────────────────
    //  Dashboard du consultant
    // ─────────────────────────────────────────────
    public function dashboard(Request $request)
    {
        $consultant = Auth::user();
        $query = Consultation::where('consultant_id', $consultant->id)->with('user');

    // Logique de recherche
    if ($request->filled('search')) {
        $searchTerm = $request->search;

        // Si le format correspond à VF-YYYYMM-ID
        if (preg_match('/VF-(\d{6})-(\d+)/', $searchTerm, $matches)) {
            $yearMonth = $matches[1]; // ex: 202606
            $id = (int)$matches[2];   // ex: 1

            $query->whereYear('created_at', substr($yearMonth, 0, 4))
                  ->whereMonth('created_at', substr($yearMonth, 4, 2))
                  ->where('id', $id);
        } else {
            // Recherche simple par ID ou par nom de client
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('user', function($u) use ($searchTerm) {
                      $u->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
    }

    $mesConsultations = $query->latest()->get();

        $mesConsultations = Consultation::where('consultant_id', $consultant->id)
            ->with('user')
            ->latest()
            ->get();

        $stats = [
            'consultations_en_attente' => $mesConsultations->where('status', 'en_attente')->count(),
            'consultations_en_cours'   => $mesConsultations->where('status', 'en_cours')->count(),
            'consultations_terminees'  => $mesConsultations->whereIn('status', ['terminee', 'approuvee'])->count(),
        ];

        // Listes pour les selects du wizard
        $nationalities = $this->listNationalities();
        $countries     = $this->listCountries();

        return view('consultants.dashboard', compact(
            'mesConsultations',
            'stats',
            'nationalities',
            'countries'
        ));
    }

    // ─────────────────────────────────────────────
    //  Création d'une consultation + user client
    // ─────────────────────────────────────────────
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
                ->route('consultant.dashboard')
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

    // ─────────────────────────────────────────────
    //  Mise à jour du statut d'une consultation
    // ─────────────────────────────────────────────
    public function updateStatus(Request $request, Consultation $consultation)
    {
        // Seul le consultant assigné peut changer le statut
        if ($consultation->consultant_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'status' => 'required|in:en_attente,en_cours,approuvee,declinee,annulee,terminee',
        ]);

        $consultation->update(['status' => $request->status]);

        $labels = [
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'approuvee'  => 'Approuvée',
            'declinee'   => 'Déclinée',
            'annulee'    => 'Annulée',
            'terminee'   => 'Terminée',
        ];

        return redirect()
            ->back()
            ->with('success', "Statut mis à jour : {$labels[$request->status]}.");
    }

    // ─────────────────────────────────────────────
    //  Vue d'une consultation
    // ─────────────────────────────────────────────

    public function show(Consultation $consultation)
    {
        // 1. Sécurité : Seul le consultant assigné peut y accéder
        if ($consultation->consultant_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // 2. Chargement optimisé des relations pour l'analyse
        $consultation->load([
            // Charge l'utilisateur/client qui a demandé la consultation
            'user', 
            
            // Les étapes du pipeline triées par leur ordre d'exécution
            'pipelineEtapes' => function ($query) {
                $query->orderBy('ordre', 'asc');
            },
            
            // L'historique des rendez-vous (du plus récent au plus ancien)
            'rendezVous' => function ($query) {
                // On trie par date_heure, et si besoin par created_at (sans caractères chinois !)
                $query->orderBy('date_heure', 'asc')->orderBy('created_at', 'desc');
            },
            
            // Le fil de notes internes/clients avec l'auteur de la note (si existant)
            'notes' => function ($query) {
                $query->with(['user', 'etape'])->latest();
            },
            
            // Les documents liés à cette consultation
            'documents' => function ($query) {
                $query->orderBy('etape_index', 'asc');
            }
        ]);

        // 3. Extraction de l'étape actuellement active pour l'action rapide du consultant
        $etapeCourante = $consultation->pipelineEtapes()
            ->where('statut', 'en_cours')
            ->first();

        $totalEtapes = $consultation->pipelineEtapes->count();
        if ($totalEtapes > 0) {
            $etapesValidees = $consultation->pipelineEtapes->where('statut', 'valide')->count();
            $consultation->progression = round(($etapesValidees / $totalEtapes) * 100);
        } else {
            $consultation->progression = 0;
        }

        return view('consultants.show', compact('consultation', 'etapeCourante'));
    }

    //
    // Traitement d'une étape du pipeline (validation/rejet par le consultant)
    //
    public function traiterEtape(Request $request, PipelineEtape $etape)
    {
        $request->validate([
            'action'             => 'required|in:demander_docs,valider,rejeter',
            'note_consultant'    => 'required_if:action,rejeter|nullable|string|max:1000',
            'documents_requis'   => 'nullable|array',
            'documents_requis.*' => 'string'
        ]);

        $consultantId = auth()->id();
        $note = $request->input('note_consultant');

        // ÉTAPE A : On enregistre TOUJOURS les documents cochés par le consultant
        $etape->update([
            'documents_requis' => $request->input('documents_requis', [])
        ]);

        // ÉTAPE B : On gère l'action
        if ($request->action === 'valider') {
        $etape->valider($consultantId, $note);
        $message = "L'étape a été validée.";
        
        // 🔔 NOTIFICATION CLIENT (Validation)
        $this->notifyClient($etape->consultation, "Étape validée : " . $etape->titre, "Votre consultant a validé l'étape {$etape->titre}.");
        
        } elseif ($request->action === 'rejeter') {
            $etape->rejeter($consultantId, $note);
            $message = "L'étape a été rejetée.";
            
            // 🔔 NOTIFICATION CLIENT (Rejet)
            $this->notifyClient($etape->consultation, "Modification requise : " . $etape->titre, "Votre étape {$etape->titre} a été rejetée : {$note}");
            
        } else {
            // Demander documents
            $etape->update(['note_consultant' => $note]);
            $message = "Exigences mises à jour.";
            
            // 🔔 NOTIFICATION CLIENT (Demande de documents)
            $this->notifyClient($etape->consultation, "Nouveaux documents requis", "Votre consultant demande de nouvelles pièces pour l'étape {$etape->titre}.");
        }

        return redirect()->back()->with('success', $message);
    }

    private function notifyClient($consultation, $title, $message)
    {
        // 1. Notification en base de données pour l'espace client
        $notif = \App\Models\Notification::create([
            'user_id' => $consultation->user_id, // L'ID du client
            'type'    => 'consultation_update',
            'title'   => $title,
            'message' => $message,
            'icon'    => 'info',
            'action_url' => route('user.dashboard'), // Lien vers l'espace client
        ]);

        // 2. Notification en temps réel (si tu utilises des Events)
        event(new \App\Events\NotificationCreated($notif));

        // 3. (Optionnel) Envoi d'un Email
        try {
            \Illuminate\Support\Facades\Mail::raw($message, function ($m) use ($consultation, $title) {
                $m->to($consultation->email)->subject("VisaFly - " . $title);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur email notification : " . $e->getMessage());
        }
    }

    //
    //
    //

    public function storeNote(Request $request, Consultation $consultation)
    {
        // 1. Validation du contenu de la note
        $validated = $request->validate([
            'contenu' => 'required|string|min:3|max:2000',
            'visible_client' => 'nullable|boolean', // Si tu gères la visibilité client
        ]);

        // 2. Création de la note liée à la consultation
        $consultation->notes()->create([
            'contenu' => $validated['contenu'],
            'visible_client' => $request->has('visible_client'), // true si la case est cochée
            'auteur_id' => auth()->id(), // Optionnel: pour savoir quel consultant a écrit la note
        ]);

        // 3. Redirection avec un message flash de succès
        return redirect()->back()->with('success', 'La note a été ajoutée avec succès.');
    }

    // ─────────────────────────────────────────────
    //  Programmation d'un rendez-vous pour la consultation
    // ─────────────────────────────────────────────
    public function programmerRdv(Request $request, Consultation $consultation)
    {
        // 1. Validation des champs du rendez-vous
        $validated = $request->validate([
            'date_heure' => 'required|date|after:now',
            'canal' => 'required|in:video,telephone,presentiel',
            'lien_visio' => 'nullable|url|required_if:canal,video',
            'duree_minutes' => 'nullable|integer|min:15',
        ]);

        // 2. Création du rendez-vous en profitant du système de notification automatique
        \App\Models\RendezVous::programmer(
            $consultation,
            auth()->id(), // ID du consultant connecté
            $validated['date_heure'],
            $validated['canal'],
            $validated['lien_visio'],
            $validated['duree_minutes'] ?? 45
        );

        // 3. Optionnel : On met aussi à jour la table consultation 
        // pour que ton tableau de bord soit synchronisé instantanément
        $consultation->update([
            'date_confirmee' => $validated['date_heure'],
            'canal' => $validated['canal'],
            'lien_visio' => $validated['lien_visio'],
            'duree_minutes' => $validated['duree_minutes'] ?? 45,
        ]);

        // 🔔 4. NOTIFICATION CLIENT (Rendez-vous programmé)
        $dateFormatee = \Carbon\Carbon::parse($validated['date_heure'])->format('d/m/Y à H:i');
        
        $this->notifyClient(
            $consultation, 
            "Nouveau rendez-vous programmé", 
            "Votre consultant a programmé un nouveau rendez-vous via {$validated['canal']} le {$dateFormatee}."
        );

        // 5. Redirection avec message de succès
        return redirect()->back()->with('success', 'Le rendez-vous a été programmé avec succès.');
    }


    // ─────────────────────────────────────────────
    //  Changement de statut d'un document
    //  (validation / rejet par le consultant)
    // ─────────────────────────────────────────────
    public function updateDocumentStatus(Request $request, $id)
    {
        $doc = Document::findOrFail($id);
        $doc->update(['status' => $request->status, 'comment' => $request->comment]);

        $this->notifyClient($doc->consultation, "Document " . ucfirst($request->status), "Votre document {$doc->name} a été marqué comme {$request->status}.");

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }
 
    // ─────────────────────────────────────────────
    //  Réactivation d'une étape rejetée
    // ─────────────────────────────────────────────
    public function reactiverEtape(PipelineEtape $etape)
    {
        $etape->update(['statut' => 'en_cours']);
 
        return redirect()->back()->with('success', 'L\'étape a été remise en cours.');
    }

    // ─────────────────────────────────────────────
    //  Vue de suivi pour le client (rôle user)
    // ─────────────────────────────────────────────
    public function suiviClient()
    {
        $user = Auth::user();

        $consultations = Consultation::where('user_id', $user->id)
            ->with('consultant')
            ->latest()
            ->get();

        return view('client.suivi', compact('consultations'));
    }

    // ─────────────────────────────────────────────
    //  Helpers – Listes pays / nationalités
    // ─────────────────────────────────────────────
    private function listNationalities(): array
    {
        return [
            'Afghane', 'Albanaise', 'Algérienne', 'Américaine', 'Angolaise',
            'Belge', 'Béninoise', 'Burkinabè', 'Burundaise',
            'Camerounaise', 'Canadienne', 'Centrafricaine', 'Comorienne', 'Congolaise',
            'Ivoirienne', 'Djiboutienne', 'Égyptienne', 'Érythréenne', 'Éthiopienne',
            'Française', 'Gabonaise', 'Gambienne', 'Ghanéenne', 'Guinéenne',
            'Haïtienne', 'Kenyane', 'Libanaise', 'Libérienne', 'Libyenne',
            'Malgache', 'Malawite', 'Malienne', 'Marocaine', 'Mauritanienne',
            'Mozambicaine', 'Namibienne', 'Nigériane', 'Nigérienne',
            'Ougandaise', 'Rwandaise', 'Sénégalaise', 'Sierra-Léonaise',
            'Somalienne', 'Soudanaise', 'Sud-Africaine', 'Sud-Soudanaise',
            'Tanzanienne', 'Tchadienne', 'Togolaise', 'Tunisienne',
            'Zambienne', 'Zimbabwéenne',
        ];
    }

    private function listCountries(): array
    {
        return [
            'Allemagne', 'Australie', 'Autriche', 'Belgique', 'Bénin',
            'Burkina Faso', 'Cameroun', 'Canada', 'Chine', 'Congo (RDC)',
            'Congo (Brazzaville)', 'Côte d\'Ivoire', 'Danemark', 'Espagne',
            'États-Unis', 'Finlande', 'France', 'Gabon', 'Ghana', 'Italie',
            'Japon', 'Kenya', 'Maroc', 'Mauritanie', 'Nigéria', 'Niger',
            'Norvège', 'Pays-Bas', 'Portugal', 'Qatar', 'Royaume-Uni',
            'Rwanda', 'Sénégal', 'Suède', 'Suisse', 'Togo', 'Tunisie',
            'Turquie', 'Ukraine', 'Uruguay',
        ];
    }
}