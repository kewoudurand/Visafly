<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LanguePassage;
use App\Models\LangueAbonnement;
use App\Models\Consultation;
use App\Models\CourseProgression;
use App\Models\ConsultationPaiement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Point d'entrée unique du Dashboard - Aiguilleur selon le rôle
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Récupération du rôle principal Spatie
        $primaryRole = $user->getRoleNames()->first();

        switch ($primaryRole) {
            case 'super-admin':
            case 'admin':
                return $this->adminDashboard($user);

            case 'secretaire':
                return $this->secretaireDashboard($user);

            case 'consultant':
                return $this->consultantDashboard($user);

            case 'instructor':
            case 'professeur':
                return $this->teacherDashboard($user);

            case 'user':
                return $this->userDashboard($user);
            default:
                return $this->studentDashboard($user);
        }
    }

    /**
     * Espace Étudiant / User lambda
     */
    private function studentDashboard($user)
    {
        $stats = [
            'tests_passes' => LanguePassage::where('user_id', $user->id)->where('statut', 'termine')->count(),
            'score_moyen' => (int) round(LanguePassage::where('user_id', $user->id)->where('statut', 'termine')->avg('score') ?? 0),
            'consultations_total' => Consultation::where('user_id', $user->id)->count(),
            'abonnement_actif' => LangueAbonnement::where('user_id', $user->id)->where('actif', true)->where('fin_at', '>=', now())->exists(),
        ];

        $passages = LanguePassage::with(['serie:id,titre', 'discipline:id,nom', 'langue:id,code,nom,couleur'])
            ->where('user_id', $user->id)->where('statut', 'termine')->latest('created_at')->limit(10)->get();

        $consultations = Consultation::where('user_id', $user->id)->latest('created_at')->limit(5)->get();

        $abonnement = LangueAbonnement::with(['plan', 'langue'])
            ->where('user_id', $user->id)->where('actif', true)->where('fin_at', '>=', now())->latest('debut_at')->first();

        return view('student.dashboard', compact('stats', 'passages', 'consultations', 'abonnement'));
    }

    /**
     * User lambda
     */
    public function userDashboard()
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
                $query->where('visible_client', true)->with(['user', 'etape'])->latest();
            },
            'documents'
        ]);

        // 3. Extraction de l'étape active pour focus sur le dashboard du client
        // Correction de la relation d'appel : on fouille dans la collection fraîchement chargée
        $etapeCourante = $consultation
            ? $consultation->pipelineEtapes->firstWhere('statut', 'en_cours')
            : null;

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


    /**
     * Espace Consultant
     */
    private function consultantDashboard($user)
    {
        $consultant = Auth::user();

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

    /**
     * Espace Secrétaire
     */
    private function secretaireDashboard($user)
    {
        // Logique globale (Ex: Total utilisateurs, revenus, etc.)
        $stats = [
            'total_users' => \App\Models\User::count(),
        ];

        return view('secretaire.dashboard', compact('stats'));
    }

    /**
     * Espace Admin 
     */
    private function adminDashboard($user)
    {
        // On récupère uniquement les consultations "en cours" (ajustez le statut selon votre DB)
         abort_unless(
            Auth::user()->hasAnyRole(['super-admin', 'admin', 'consultant']),
            403
        );
 
        // ── KPIs ────────────────────────────────────────────────────
        $stats = [
            // Chiffre d'affaires ce mois (paiements reçus)
            'ca_mois' => ConsultationPaiement::where('statut', 'recu')
                ->whereMonth('date_paiement', now()->month)
                ->whereYear('date_paiement', now()->year)
                ->sum('montant'),
 
            // Abonnés
            'nouveaux_abonnes' => $this->getNouveauxAbonnes(),
 
            // Consultations
            'consultations_attente'       => Consultation::where('status', 'en_attente')->count(),
            'consultations_terminees_mois' => Consultation::where('status', 'terminee')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
 
            // Cours / étudiants
            'total_etudiants'          => $this->getTotalEtudiants(),
            'nouveaux_etudiants_mois'  => $this->getNouveauxEtudiantsMois(),
 
            // Utilisateurs
            'total_users'         => User::role('user')->count(),
            'nouveaux_users_mois' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
 
            // Répartition statuts consultations
            's_attente'  => Consultation::where('status', 'en_attente')->count(),
            's_en_cours' => Consultation::where('status', 'en_cours')->count(),
            's_approuve' => Consultation::where('status', 'approuvee')->count(),
            's_decline'  => Consultation::where('status', 'declinee')->count(),
            's_termine'  => Consultation::where('status', 'terminee')->count(),
        ];
 
        // ── Dossiers urgents (5 derniers en attente ou urgents) ─────
        $consultationsUrgentes = Consultation::with('consultant')
            ->where(function ($q) {
                $q->where('urgent', true)
                  ->orWhere('status', 'en_attente');
            })
            ->whereNotIn('status', ['terminee', 'declinee', 'annulee'])
            ->orderByRaw("urgent DESC") // urgents d'abord
            ->orderBy('created_at', 'asc') // les plus anciens en priorité
            ->limit(5)
            ->get();
 
        // ── Consultants actifs avec comptage dossiers ────────────────
        $consultantsStats = User::role(['consultant', 'admin', 'super-admin'])
            ->withCount(['consultations' => fn($q) =>
                $q->whereNotIn('status', ['terminee', 'declinee', 'annulee'])
            ])
            ->having('consultations_count', '>', 0)
            ->orderByDesc('consultations_count')
            ->limit(4)
            ->get();
 
        // ── Activité récente ─────────────────────────────────────────
 
        // Derniers abonnés (nécessite un modèle Subscription/Abonnement)
        $derniersAbonnes = $this->getDerniersAbonnes();
 
        // Dernières inscriptions cours
        $dernieresInscriptions = $this->getDernieresInscriptions();
 
        // Derniers paiements de consultations
        $derniersPaiements = ConsultationPaiement::with('consultation')
            ->where('statut', 'recu')
            ->latest('date_paiement')
            ->limit(5)
            ->get();
 
        return view('admin.dashboard', compact(
            'stats',
            'consultationsUrgentes',
            'consultantsStats',
            'derniersAbonnes',
            'dernieresInscriptions',
            'derniersPaiements'
        ));
    }

    private function getNouveauxAbonnes(): int
    {
        // Si tu as un modèle Subscription :
        return LangueAbonnement::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        // return 0;
    }
 
    private function getDerniersAbonnes(): \Illuminate\Support\Collection
    {
        // Exemple avec un modèle Subscription :
        return \App\Models\LangueAbonnement::with('user')
            ->latest()->limit(5)->get()
            ->map(fn($s) => (object)[
                'name'          => $s->user?->name ?? '—',
                'plan'          => $s->plan?->nom ?? '—',
                'montant'       => $s->montant ?? 0,
                'subscribed_at' => $s->created_at,
            ]);
        return collect(); // retourne vide par défaut
    }
 
    private function getTotalEtudiants(): int
    {
        // Exemple si tu as une table user_cours / inscriptions :
        return DB::table('user_course_progress')->distinct('user_id')->count('user_id');
        // return 0;
    }
 
    private function getNouveauxEtudiantsMois(): int
    {
        return DB::table('user_course_progress')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('user_id')->count('user_id');
        return 0;
    }
 
    private function getDernieresInscriptions(): \Illuminate\Support\Collection
    {
        // Exemple avec un modèle Inscription / UserCours :
        return \App\Models\CourseProgression::with(['user', 'cours'])
            ->latest()->limit(5)->get();
        return collect();
    }
    /**
     * Espace Formateur / Professeur
     */
    private function teacherDashboard($user)
    {
        return view('instructor.dashboard');
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