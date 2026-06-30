<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationPaiement;
use App\Models\PipelineEtape;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminConsultationController extends Controller
{
    private function checkAccess(): void
    {
        abort_unless(
            Auth::user()->hasAnyRole(['super-admin', 'admin', 'consultant']),
            403, 'Accès non autorisé.'
        );
    }

    // ══════════════════════════════════
    //  INDEX — Liste + filtres + stats
    // ══════════════════════════════════
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = Consultation::with(['user', 'consultant'])->latest();

        if ($request->filled('statut'))  $query->where('statut', $request->statut);
        if ($request->filled('projet'))  $query->where('project_type', $request->projet);
        if ($request->filled('urgent'))  $query->where('urgent', true);
        if ($request->filled('consultant_id')) {
            $query->where('consultant_id', $request->consultant_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('destination_country', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($u) =>
                      $u->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")
                  );
            });
        }

        $consultations = $query->paginate(15)->withQueryString();

        $stats = [
            'total'      => Consultation::count(),
            'en_attente' => Consultation::where('status', 'en_attente')->count(),
            'approuvees' => Consultation::where('status', 'approuvee')->count(),
            'declinees'  => Consultation::where('status', 'declinee')->count(),
            'terminees'  => Consultation::where('status', 'terminee')->count(),
            'ce_mois'    => Consultation::whereMonth('created_at', now()->month)->count(),
        ];

        $consultants = User::role(['consultant', 'admin', 'super-admin'])->get();

        return view('admin.consultations.index', compact('consultations', 'stats', 'consultants'));
    }

    // ══════════════════════════════════
    //  SHOW — Dossier complet
    //  Inclut : pipeline, consultant, paiements
    // ══════════════════════════════════
    public function show(Consultation $consultation)
    {
        $this->checkAccess();

        $consultation->load([
            'user',
            'consultant',
            'pipelineEtapes' => fn($q) => $q->orderBy('ordre'),
            // Charger TOUS les documents liés à cette consultation
            // La vue filtre ensuite par etape_index + type — pas par nom de fichier
            'documents',
            'paiements'      => fn($q) => $q->latest('date_paiement'),
            'paiements.enregistrePar',
        ]);

        $consultants   = User::role(['consultant', 'admin', 'super-admin'])->get();
        $totalPaye     = $consultation->paiements->where('statut', 'recu')->sum('montant');
        $resteAPayer   = max(0, ($consultation->montant_total ?? 0) - $totalPaye);
        $pourcentagePaye = $consultation->montant_total > 0
            ? round(($totalPaye / $consultation->montant_total) * 100)
            : 0;

        return view('admin.consultations.show', compact(
            'consultation',
            'consultants',
            'totalPaye',
            'resteAPayer',
            'pourcentagePaye'
        ));
    }

    // ══════════════════════════════════
    //  EN COURS D'EXAMEN
    // ══════════════════════════════════
    public function enCours(Consultation $consultation)
    {
        $this->checkAccess();
        $consultation->update(['statut' => 'en_cours', 'status' => 0]);
        return back()->with('success', "Consultation passée en cours d'examen.");
    }

    // ══════════════════════════════════
    //  APPROUVER
    // ══════════════════════════════════
    public function approuver(Request $request, Consultation $consultation)
    {
        $this->checkAccess();

        $request->validate([
            'date_confirmee' => 'required|date|after:now',
            'duree_minutes'  => 'required|integer|min:15|max:240',
            'canal'          => 'required|in:video,telephone,presentiel',
            'consultant_id'  => 'nullable|exists:users,id',
            'lien_visio'     => 'nullable|url',
            'note_admin'     => 'nullable|string|max:2000',
        ]);

        $consultation->update([
            'statut'         => 'approuvee',
            'status'         => 1,
            'date_confirmee' => $request->date_confirmee,
            'duree_minutes'  => $request->duree_minutes,
            'canal'          => $request->canal,
            'consultant_id'  => $request->consultant_id ?? Auth::id(),
            'lien_visio'     => $request->lien_visio,
            'note_admin'     => $request->note_admin,
        ]);

        return back()->with('success',
            "✓ Consultation de {$consultation->client_name} approuvée pour le "
            . \Carbon\Carbon::parse($request->date_confirmee)->locale('fr')->isoFormat('dddd D MMMM YYYY [à] HH:mm') . '.');
    }

    // ══════════════════════════════════
    //  DÉCLINER
    // ══════════════════════════════════
    public function decliner(Request $request, Consultation $consultation)
    {
        $this->checkAccess();
        $request->validate(['motif_declin' => 'required|string|min:10|max:1000']);

        $consultation->update([
            'statut'       => 'declinee',
            'status'       => 0,
            'motif_declin' => $request->motif_declin,
        ]);

        return back()->with('success', "Consultation de {$consultation->client_name} déclinée.");
    }

    // ══════════════════════════════════
    //  TERMINER
    // ══════════════════════════════════
    public function terminer(Request $request, Consultation $consultation)
    {
        $this->checkAccess();
        $consultation->update([
            'statut'     => 'terminee',
            'status'     => 1,
            'note_admin' => $request->note_admin ?? $consultation->note_admin,
        ]);
        return back()->with('success', 'Consultation marquée comme terminée.');
    }

    // ══════════════════════════════════
    //  ASSIGNER / CHANGER DE CONSULTANT
    // ══════════════════════════════════
    public function assigner(Request $request, Consultation $consultation)
    {
        $this->checkAccess();
        $request->validate(['consultant_id' => 'required|exists:users,id']);

        $ancien = $consultation->consultant?->name ?? '—';
        $c = User::findOrFail($request->consultant_id);
        $consultation->update(['consultant_id' => $c->id]);

        $msg = $ancien === '—'
            ? "{$c->name} assigné(e) à cette consultation."
            : "Consultant changé : {$ancien} → {$c->name}.";

        return back()->with('success', $msg);
    }

    // ══════════════════════════════════
    //  MONTANT TOTAL — Définir / Modifier
    // ══════════════════════════════════
    public function setMontantTotal(Request $request, Consultation $consultation)
    {
        $this->checkAccess();
        $request->validate([
            'montant_total' => 'required|numeric|min:0',
            'devise'        => 'required|string|max:5',
        ]);

        $consultation->update([
            'montant_total' => $request->montant_total,
            'devise'        => $request->devise,
        ]);

        return back()->with('success', 'Montant total mis à jour : '
            . number_format($request->montant_total, 0, ',', ' ') . ' ' . $request->devise . '.');
    }

    // ══════════════════════════════════
    //  PAIEMENTS — Ajouter une tranche
    // ══════════════════════════════════
    public function addPaiement(Request $request, Consultation $consultation)
    {
        $this->checkAccess();
        $request->validate([
            'montant'       => 'required|numeric|min:1',
            'devise'        => 'required|string|max:5',
            'mode'          => 'required|in:especes,virement,mobile_money,carte,autre',
            'statut'        => 'required|in:recu,en_attente,annule',
            'date_paiement' => 'required|date',
            'reference'     => 'nullable|string|max:100',
            'note'          => 'nullable|string|max:500',
        ]);

        ConsultationPaiement::create([
            'consultation_id' => $consultation->id,
            'enregistre_par'  => Auth::id(),
            'montant'         => $request->montant,
            'devise'          => $request->devise,
            'mode'            => $request->mode,
            'statut'          => $request->statut,
            'date_paiement'   => $request->date_paiement,
            'reference'       => $request->reference,
            'note'            => $request->note,
        ]);

        return back()->with('success',
            'Tranche de ' . number_format($request->montant, 0, ',', ' ')
            . ' ' . $request->devise . ' enregistrée.');
    }

    // ══════════════════════════════════
    //  PAIEMENTS — Modifier une tranche
    // ══════════════════════════════════
    public function updatePaiement(Request $request, Consultation $consultation, ConsultationPaiement $paiement)
    {
        $this->checkAccess();
        abort_if($paiement->consultation_id !== $consultation->id, 403);

        $request->validate([
            'montant'       => 'required|numeric|min:1',
            'devise'        => 'required|string|max:5',
            'mode'          => 'required|in:especes,virement,mobile_money,carte,autre',
            'statut'        => 'required|in:recu,en_attente,annule',
            'date_paiement' => 'required|date',
            'reference'     => 'nullable|string|max:100',
            'note'          => 'nullable|string|max:500',
        ]);

        $paiement->update($request->only([
            'montant', 'devise', 'mode', 'statut', 'date_paiement', 'reference', 'note',
        ]));

        return back()->with('success', 'Tranche de paiement mise à jour.');
    }

    // ══════════════════════════════════
    //  PAIEMENTS — Supprimer une tranche
    // ══════════════════════════════════
    public function deletePaiement(Consultation $consultation, ConsultationPaiement $paiement)
    {
        $this->checkAccess();
        abort_if($paiement->consultation_id !== $consultation->id, 403);
        $paiement->delete();
        return back()->with('success', 'Tranche supprimée.');
    }

    // ══════════════════════════════════
    //  NOTE INTERNE
    // ══════════════════════════════════
    public function note(Request $request, Consultation $consultation)
    {
        $this->checkAccess();
        $request->validate(['note_admin' => 'required|string|min:3|max:2000']);
        $consultation->update(['note_admin' => $request->note_admin]);
        return back()->with('success', 'Note interne enregistrée.');
    }

    // ══════════════════════════════════
    //  LIEN VISIO
    // ══════════════════════════════════
    public function lienVisio(Request $request, Consultation $consultation)
    {
        $this->checkAccess();
        $request->validate(['lien_visio' => 'required|url']);
        $consultation->update(['lien_visio' => $request->lien_visio]);
        return back()->with('success', 'Lien de visioconférence mis à jour.');
    }

    // ══════════════════════════════════
    //  TOGGLE URGENT
    // ══════════════════════════════════
    public function toggleUrgent(Consultation $consultation)
    {
        $this->checkAccess();
        $consultation->update(['urgent' => !$consultation->urgent]);
        return back()->with('success',
            $consultation->urgent ? 'Consultation marquée urgente.' : 'Urgence retirée.');
    }

    // ══════════════════════════════════
    //  SUPPRIMER (super-admin uniquement)
    // ══════════════════════════════════
    public function destroy($id)
    {
        abort_unless(Auth::user()->hasRole('super-admin'), 403);
        $consultation = Consultation::findOrFail($id);
        Storage::disk('public')->deleteDirectory('consultations/' . $consultation->id);
        $consultation->delete();
        return redirect()->route('admin.consultations.index')
            ->with('success', 'Consultation supprimée avec succès.');
    }

    // ══════════════════════════════════
    //  EXPORT CSV
    // ══════════════════════════════════
    public function export(Request $request)
    {
        $this->checkAccess();

        $rows = Consultation::with('user', 'consultant')
            ->when($request->statut, fn($q) => $q->where('statut', $request->statut))
            ->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="consultations_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($rows) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($f, ['ID', 'Nom', 'Email', 'Téléphone', 'Nationalité', 'Pays dest.',
                'Projet', 'Statut', 'Urgent', 'Canal', 'Date confirmée', 'Consultant',
                'Montant total', 'Total payé', 'Créé le']);
            foreach ($rows as $c) {
                $totalPaye = $c->paiements?->where('statut', 'recu')->sum('montant') ?? 0;
                fputcsv($f, [
                    $c->id, $c->client_name, $c->client_email,
                    $c->phone, $c->nationality, $c->destination_country,
                    $c->projetLabel(), $c->statutLabel(),
                    $c->urgent ? 'Oui' : 'Non',
                    $c->canalLabel(),
                    $c->date_confirmee?->format('d/m/Y H:i') ?? '',
                    $c->consultant?->name ?? '',
                    $c->montant_total ?? 0,
                    $totalPaye,
                    $c->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}