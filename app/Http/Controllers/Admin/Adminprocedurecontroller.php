<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientProcedure;
use App\Models\Consultation;
use App\Models\Procedure;
use App\Models\ProcedurePaiement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProcedureController extends Controller
{
    /**
     * Seuls Admin / Super-admin / Secrétaire peuvent gérer ce module.
     * Adapter le slug du rôle "secretaire" si différent dans Spatie.
     */
    private function checkAccess(): void
    {
        abort_unless(
            Auth::user()->hasAnyRole(['super-admin', 'admin', 'secretaire']),
            403,
            'Accès non autorisé.'
        );
    }

    // ══════════════════════════════════════════════
    //  INDEX — Liste des procédures attribuées + filtres + stats
    // ══════════════════════════════════════════════
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = ClientProcedure::with(['procedure', 'consultation', 'client', 'assignePar', 'paiements'])
            ->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->orWhereHas('client', fn($u) =>
                        $u->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")
                    )
                    ->orWhere('destination_country', 'like', "%{$s}%");
            });
        }

        $attributions = $query->paginate(15)->withQueryString();

        $stats = [
            'total'         => ClientProcedure::count(),
            'en_cours'      => ClientProcedure::where('statut', 'en_cours')->count(),
            'terminees'     => ClientProcedure::where('statut', 'terminee')->count(),
            'montant_total' => ClientProcedure::sum('prix_total'),
            'montant_verse' => ProcedurePaiement::where('statut', 'recu')->sum('montant'),
        ];

        // Catalogue (toujours dispo en repli si pas de consultation)
        // $procedures = Consultation::where('status', 'en_attente')->orderBy('full_name')->get();

        // Uniquement les clients (rôles "student" ou "user")
        $clients = User::role(['student', 'user'])->orderBy('first_name')->get();

        return view('admin.procedures.index', compact(
            'attributions', 'stats', 'clients'
        ));
    }

    // ══════════════════════════════════════════════
    //  AJAX — Récupérer la consultation en cours d'un client
    //  (pays de destination + montant total à auto-remplir)
    // ══════════════════════════════════════════════
    public function clientConsultation(User $user)
    {
        $this->checkAccess();

        // On veut trouver les consultations qui sont en cours de traitement
        $statutsActifs = ['en_attente', 'en_cours', 'approuvee']; 

        $consultation = Consultation::where('user_id', $user->id)
            ->whereIn('status', $statutsActifs) // On cherche uniquement dans ceux-là
            ->latest()
            ->first();

        if (!$consultation) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'               => true,
            'consultation_id'     => $consultation->id,
            'destination_country' => $consultation->destination_country,
            'montant_total'       => $consultation->montant_total,
            'devise'              => $consultation->devise ?? 'XAF',
        ]);
    }

    // ══════════════════════════════════════════════
    //  SHOW — Détail d'une procédure attribuée + paiements
    // ══════════════════════════════════════════════
    public function show(ClientProcedure $clientProcedure)
    {
        $this->checkAccess();

        $clientProcedure->load([
            'procedure',
            'consultation',
            'client',
            'assignePar',
            'paiements' => fn($q) => $q->latest('date_paiement'),
            'paiements.enregistrePar',
        ]);

        $totalVerse       = $clientProcedure->totalVerse();
        $resteAPayer      = $clientProcedure->resteAPayer();
        $pourcentagePaye  = $clientProcedure->pourcentagePaye();

        return view('admin.procedures.show', compact(
            'clientProcedure', 'totalVerse', 'resteAPayer', 'pourcentagePaye'
        ));
    }

    // ══════════════════════════════════════════════
    //  ATTRIBUER — Assigner une procédure à un client
    //  (depuis une consultation en cours, ou saisie manuelle)
    // ══════════════════════════════════════════════
    public function store(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'user_id'              => 'required|exists:users,id',
            'consultation_id'      => 'nullable|exists:consultations,id',
            'procedure_id'         => 'required|exists:procedures,id',
            'destination_country'  => 'required|string|max:150',
            'prix_total'           => 'required|numeric|min:0',
            'devise'               => 'required|string|max:5',
            'date_debut'           => 'nullable|date',
            'note'                 => 'nullable|string|max:1000',
        ]);

        $clientProcedure = ClientProcedure::create([
            'user_id'              => $request->user_id,
            'consultation_id'      => $request->consultation_id,
            'destination_country'  => $request->destination_country,
            'assigne_par'          => Auth::id(),
            'prix_total'           => $request->prix_total,
            'devise'               => $request->devise,
            'statut'               => 'en_cours',
            'date_debut'           => $request->date_debut ?? now(),
            'note'                 => $request->note,
        ]);

        return redirect()
            ->route('admin.procedures.show', $clientProcedure)
            ->with('success', 'Procédure attribuée avec succès.');
    }

    // ══════════════════════════════════════════════
    //  MODIFIER L'ATTRIBUTION — prix total / statut / note
    // ══════════════════════════════════════════════
    public function update(Request $request, ClientProcedure $clientProcedure)
    {
        $this->checkAccess();

        $request->validate([
            'prix_total' => 'required|numeric|min:0',
            'devise'     => 'required|string|max:5',
            'statut'     => 'required|in:en_cours,terminee,annulee',
            'date_debut' => 'nullable|date',
            'note'       => 'nullable|string|max:1000',
        ]);

        $clientProcedure->update($request->only([
            'prix_total', 'devise', 'statut', 'date_debut', 'note',
        ]));

        return back()->with('success', 'Procédure mise à jour.');
    }

    // ══════════════════════════════════════════════
    //  SUPPRIMER L'ATTRIBUTION
    // ══════════════════════════════════════════════
    public function destroy(ClientProcedure $clientProcedure)
    {
        abort_unless(Auth::user()->hasRole('super-admin'), 403);
        $clientProcedure->delete();

        return redirect()->route('admin.procedures.index')
            ->with('success', 'Attribution supprimée.');
    }

    // ══════════════════════════════════════════════
    //  PAIEMENTS — Ajouter un versement
    // ══════════════════════════════════════════════
    public function addPaiement(Request $request, ClientProcedure $clientProcedure)
    {
        $this->checkAccess();

        $request->validate([
            'montant'       => 'required|numeric|min:1',
            'devise'        => 'required|string|max:5',
            'nom_payeur'    => 'nullable|string|max:150',
            'mode'          => 'required|in:especes,virement,mobile_money,carte,autre',
            'statut'        => 'required|in:recu,en_attente,annule',
            'date_paiement' => 'required|date',
            'reference'     => 'nullable|string|max:100',
            'note'          => 'nullable|string|max:500',
        ]);

        ProcedurePaiement::create([
            'client_procedure_id' => $clientProcedure->id,
            'enregistre_par'      => Auth::id(),
            'montant'             => $request->montant,
            'devise'              => $request->devise,
            'nom_payeur'          => $request->nom_payeur,
            'mode'                => $request->mode,
            'statut'              => $request->statut,
            'date_paiement'       => $request->date_paiement,
            'reference'           => $request->reference,
            'note'                => $request->note,
        ]);

        return back()->with('success',
            'Versement de ' . number_format($request->montant, 0, ',', ' ')
            . ' ' . $request->devise . ' enregistré.');
    }

    // ══════════════════════════════════════════════
    //  PAIEMENTS — Modifier un versement
    // ══════════════════════════════════════════════
    public function updatePaiement(Request $request, ClientProcedure $clientProcedure, ProcedurePaiement $paiement)
    {
        $this->checkAccess();
        abort_if($paiement->client_procedure_id !== $clientProcedure->id, 403);

        $request->validate([
            'montant'       => 'required|numeric|min:1',
            'devise'        => 'required|string|max:5',
            'nom_payeur'    => 'nullable|string|max:150',
            'mode'          => 'required|in:especes,virement,mobile_money,carte,autre',
            'statut'        => 'required|in:recu,en_attente,annule',
            'date_paiement' => 'required|date',
            'reference'     => 'nullable|string|max:100',
            'note'          => 'nullable|string|max:500',
        ]);

        $paiement->update($request->only([
            'montant', 'devise', 'nom_payeur', 'mode', 'statut', 'date_paiement', 'reference', 'note',
        ]));

        return back()->with('success', 'Versement mis à jour.');
    }

    // ══════════════════════════════════════════════
    //  PAIEMENTS — Supprimer un versement
    // ══════════════════════════════════════════════
    public function deletePaiement(ClientProcedure $clientProcedure, ProcedurePaiement $paiement)
    {
        $this->checkAccess();
        abort_if($paiement->client_procedure_id !== $clientProcedure->id, 403);
        $paiement->delete();

        return back()->with('success', 'Versement supprimé.');
    }
}