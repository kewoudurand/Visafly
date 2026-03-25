<?php
// app/Http/Controllers/Tcf/TcfEpreuveController.php

namespace App\Http\Controllers\Tcf;

use App\Http\Controllers\Controller;
use App\Models\TcfPassage;
use App\Models\TcfPassageReponse;
use App\Models\TcfReponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TcfEpreuveController extends Controller
{
    // ✅ Pas de __construct() — middleware dans routes/web.php

    public function show(TcfPassage $passage, int $question = 1)
    {
        $this->autoriser($passage);

        // ✅ Calcul basé sur debut_at en base — toujours cohérent
        $debut        = $passage->debut_at->timestamp;
        $maintenant   = now()->timestamp;
        $dureeMax     = (int) ($passage->discipline->duree_minutes * 60);
        $tempsEcoule  = (int) ($maintenant - $debut);
        $tempsRestant = (int) max(0, $dureeMax - $tempsEcoule);

        if ($tempsRestant <= 0) {
            return redirect()->route('tcf.terminer', $passage->id);
        }

        $questions = $passage->discipline->questions()->with('reponses')->get();
        $totalQ    = $questions->count();

        if ($totalQ === 0) {
            return back()->with('error', 'Cette discipline ne contient pas encore de questions.');
        }

        $numero           = max(1, min($question, $totalQ));
        $questionCourante = $questions->firstWhere('numero', $numero);

        if (!$questionCourante) {
            $questionCourante = $questions->first();
            $numero           = $questionCourante->numero;
        }

        $reponsesDonnees    = $passage->passageReponses->pluck('reponse_id', 'question_id')->toArray();
        $questionsRepondues = array_keys($reponsesDonnees);

        return view('tcf.epreuve', [
            'passage'            => $passage,
            'question'           => $questionCourante,
            'questions'          => $questions,
            'totalQ'             => $totalQ,
            'numero'             => $numero,
            'tempsRestant'       => $tempsRestant,
            'debutTimestamp'     => $debut,      // ✅ nouveau
            'dureeMax'           => $dureeMax,   // ✅ nouveau
            'reponsesDonnees'    => $reponsesDonnees,
            'questionsRepondues' => $questionsRepondues,
        ]);
    }

    public function repondre(Request $request, TcfPassage $passage)
    {
        $this->autoriser($passage);

        $request->validate([
            'question_id' => 'required|exists:tcf_questions,id',
            'reponse_id'  => 'nullable|exists:tcf_reponses,id',
            'numero'      => 'required|integer|min:1',
        ]);

        $reponse = $request->reponse_id
            ? TcfReponse::find($request->reponse_id)
            : null;

        TcfPassageReponse::updateOrCreate(
            ['passage_id' => $passage->id, 'question_id' => $request->question_id],
            ['reponse_id' => $request->reponse_id, 'est_correcte' => $reponse?->est_correcte ?? false]
        );

        $totalQ = $passage->discipline->nb_questions;

        if ($request->numero >= $totalQ) {
            return redirect()->route('tcf.terminer', $passage->id);
        }

        return redirect()->route('tcf.epreuve', [
            'passage'  => $passage->id,
            'question' => $request->numero + 1,
        ]);
    }

    public function terminer(TcfPassage $passage)
    {
        $this->autoriser($passage);

        if ($passage->statut === 'termine') {
            return redirect()->route('tcf.resultat', $passage->id);
        }

        $nbCorrectes  = $passage->passageReponses()->where('est_correcte', true)->count();
        $tempsUtilise = now()->diffInSeconds($passage->debut_at);

        $passage->update([
            'fin_at'        => now(),
            'statut'        => 'termine',
            'nb_correctes'  => $nbCorrectes,
            'temps_utilise' => $tempsUtilise,
            'score'         => round(($nbCorrectes / max(1, $passage->discipline->nb_questions)) * 100),
        ]);

        return redirect()->route('tcf.terminer', $passage->id);
    }

    public function resultat(TcfPassage $passage)
    {
        $this->autoriser($passage);

        $passage->load('discipline.serie', 'passageReponses.reponse', 'passageReponses.question.reponses');

        return view('tcf.resultat', compact('passage'));
    }

    private function autoriser(TcfPassage $passage): void
    {
        abort_unless($passage->user_id === Auth::id(), 403);
    }
}