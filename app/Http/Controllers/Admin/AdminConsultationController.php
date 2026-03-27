<?php
// ══════════════════════════════════════════════════════════
//  app/Http/Controllers/Admin/ConsultationController.php
//  Côté ADMIN — gestion complète des consultations
// ══════════════════════════════════════════════════════════
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminConsultationController extends Controller
{
    private function checkAccess(): void
    {
        abort_unless(
            Auth::user()->hasAnyRole(['super-admin','admin','consultant']),
            403, 'Accès non autorisé.'
        );
    }

    // ══════════════════════════════════
    //  INDEX — Liste + filtres + stats
    // ══════════════════════════════════
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = Consultation::with(['user','consultant'])->latest();

        if ($request->filled('statut'))   $query->where('statut', $request->statut);
        if ($request->filled('projet'))   $query->where('project_type', $request->projet);
        if ($request->filled('urgent'))   $query->where('urgent', true);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('destination_country', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($u) =>
                      $u->where('name','like',"%{$s}%")->orWhere('email','like',"%{$s}%")
                  );
            });
        }

        $consultations = $query->paginate(15)->withQueryString();

        $stats = [
            'total'      => Consultation::count(),
            'en_attente' => Consultation::where('status','en_attente')->count(),
            'approuvees' => Consultation::where('status','approuve')->count(),
            'declinees'  => Consultation::where('status','decline')->count(),
            'terminees'  => Consultation::where('status','terminee')->count(),

            // 'urgentes'   => Consultation::where('urgent', true)->whereNotIn('status', ['terminee','annulee','declinee'])->count(),

            'ce_mois' => Consultation::whereMonth('created_at', now()->month)->count(),
        ];

        $consultants = User::role(['consultant','admin','super-admin'])->get();

        return view('admin.consultations.index', compact('consultations','stats','consultants'));
    }

    // ══════════════════════════════════
    //  SHOW — Dossier complet
    // ══════════════════════════════════
    public function show(Consultation $consultation)
    {
        $this->checkAccess();
        $consultation->load('user','consultant');
        $consultants = User::role(['consultant','admin','super-admin'])->get();
        return view('admin.consultations.show', compact('consultation','consultants'));
    }

    // ══════════════════════════════════
    //  EN COURS D'EXAMEN
    // ══════════════════════════════════
    public function enCours(Consultation $consultation)
    {
        $this->checkAccess();
        $consultation->update(['statut' => 'en_cours', 'status' => 0]);
        return back()->with('success', 'Consultation passée en cours d\'examen.');
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
            .\Carbon\Carbon::parse($request->date_confirmee)->locale('fr')->isoFormat('dddd D MMMM YYYY [à] HH:mm').'.');
    }

    // ══════════════════════════════════
    //  DÉCLINER
    // ══════════════════════════════════
    public function decliner(Request $request, Consultation $consultation)
    {
        $this->checkAccess();

        $request->validate([
            'motif_declin' => 'required|string|min:10|max:1000',
        ]);

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
    //  ASSIGNER UN CONSULTANT
    // ══════════════════════════════════
    public function assigner(Request $request, Consultation $consultation)
    {
        $this->checkAccess();
        $request->validate(['consultant_id' => 'required|exists:users,id']);

        $c = User::findOrFail($request->consultant_id);
        $consultation->update(['consultant_id' => $c->id]);

        return back()->with('success', "{$c->name} assigné(e) à cette consultation.");
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
    //  SUPPRIMER (soft delete, super-admin uniquement)
    // ══════════════════════════════════
    public function destroy(Consultation $consultation)
    {
        abort_unless(Auth::user()->hasRole('super-admin'), 403);
        $consultation->delete();
        return redirect()->route('admin.consultations.index')
            ->with('success', 'Consultation supprimée.');
    }

    // ══════════════════════════════════
    //  EXPORT CSV
    // ══════════════════════════════════
    public function export(Request $request)
    {
        $this->checkAccess();

        $rows = Consultation::with('user','consultant')
            ->when($request->statut, fn($q) => $q->where('statut', $request->statut))
            ->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="consultations_'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($rows) {
            $f = fopen('php://output','w');
            fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($f,['ID','Nom','Email','Téléphone','Nationalité','Pays dest.',
                'Projet','Statut','Urgent','Canal','Date confirmée','Consultant','Créé le']);
            foreach ($rows as $c) {
                fputcsv($f,[
                    $c->id, $c->client_name, $c->client_email,
                    $c->phone, $c->nationality, $c->destination_country,
                    $c->projetLabel(), $c->statutLabel(),
                    $c->urgent ? 'Oui' : 'Non',
                    $c->canalLabel(),
                    $c->date_confirmee?->format('d/m/Y H:i') ?? '',
                    $c->consultant?->name ?? '',
                    $c->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}