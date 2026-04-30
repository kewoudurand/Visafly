{{-- resources/views/progression/lecon.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Résultats — ' . $lecon->titre)

@push('styles')
<style>
:root { --c:{{ $cours->couleur ?? '#1B3A6B' }}; --marine:#1B3A6B; --or:#F5A623; }
.pl-wrap { max-width:760px;margin:0 auto;padding:28px 20px 80px; }
.pl-breadcrumb { display:flex;align-items:center;gap:6px;font-size:.75rem;color:#888;margin-bottom:18px;flex-wrap:wrap; }
.pl-breadcrumb a { color:#888;text-decoration:none; }
.pl-breadcrumb a:hover { color:var(--marine); }
.pl-breadcrumb span { opacity:.4; }

/* Header résultats */
.pl-result-header {
    background:var(--or);border-radius:16px;padding:28px;margin-bottom:24px;
    display:flex;align-items:center;gap:24px;flex-wrap:wrap;
    position:relative;overflow:hidden;
}
.pl-result-header::before { content:'';position:absolute;right:-30px;top:-30px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,.08); }
.pl-score-circle {
    width:90px;height:90px;border-radius:50%;
    border:5px solid rgba(255,255,255,.3);
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    background:rgba(255,255,255,.15);flex-shrink:0;
}
.pl-score-val { font-size:1.5rem;font-weight:900;color:#fff;line-height:1; }
.pl-score-lbl { font-size:.62rem;color:rgba(255,255,255,.7);font-weight:600;margin-top:2px; }
.pl-result-info {}
.pl-result-titre { font-size:1.1rem;font-weight:900;color:#fff;margin-bottom:6px; }
.pl-result-meta { display:flex;flex-wrap:wrap;gap:14px; }
.pl-result-meta-item { display:flex;align-items:center;gap:6px;font-size:.8rem;color:rgba(255,255,255,.8);font-weight:600; }

/* Correction exercices */
.exo-item { background:#fff;border-radius:14px;border:1px solid #eee;padding:20px;margin-bottom:12px; }
.exo-num { font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#888;margin-bottom:8px; }
.exo-question { font-size:.9rem;font-weight:800;color:var(--marine);margin-bottom:14px; }

/* Réponse correcte/incorrecte */
.rep-row { display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;margin-bottom:8px;font-size:.85rem; }
.rep-row.correct   { background:#e8f8f0;border:1.5px solid #1cc88a; }
.rep-row.incorrect { background:#fde8e8;border:1.5px solid #dc3545; }
.rep-row.expected  { background:#f0f4ff;border:1.5px solid var(--marine)40; }
.rep-icon { font-size:1rem;flex-shrink:0; }
.rep-label { font-size:.72rem;color:#888;font-weight:600;margin-bottom:2px; }
.rep-val   { font-weight:700; }
.rep-val.correct-text   { color:#198754; }
.rep-val.incorrect-text { color:#dc3545; }
.rep-val.expected-text  { color:var(--marine); }

.explication-banner {
    display:flex;align-items:flex-start;gap:10px;background:#fffbf0;
    border-left:3px solid var(--or);border-radius:0 10px 10px 0;
    padding:10px 14px;margin-top:10px;font-size:.82rem;color:#5a3e00;line-height:1.6;
}

/* Non répondu */
.rep-row.skipped { background:#f8f9fa;border:1.5px solid #dee2e6; }

/* Badge score global */
.score-badge-lg {
    display:inline-flex;align-items:center;gap:6px;
    padding:6px 16px;border-radius:20px;font-size:.85rem;font-weight:800;
}
</style>
@endpush

@section('content')
<div class="pl-wrap">

    {{-- Breadcrumb --}}
    <div class="pl-breadcrumb">
        <a href="{{ route('progression.index') }}">Mon parcours</a>
        <span>›</span>
        <a href="{{ route('progression.cours', $cours) }}">{{ $cours->titre }}</a>
        <span>›</span>
        <span style="color:#555;font-weight:600">{{ $lecon->titre }}</span>
    </div>

    {{-- Header résultat --}}
    <div class="pl-result-header">
        <div class="pl-score-circle">
            <div class="pl-score-val">{{ $progression->score }}%</div>
            <div class="pl-score-lbl">Score</div>
        </div>
        <div class="pl-result-info">
            <div class="pl-result-titre">{{ $lecon->titre }}</div>
            <div class="pl-result-meta">
                <div class="pl-result-meta-item">
                    <i class="bi bi-check-circle-fill" style="color:#1cc88a"></i>
                    {{ $progression->bonnes_reponses }}/{{ $progression->total_questions }} bonnes réponses
                </div>
                <div class="pl-result-meta-item">
                    <i class="bi bi-trophy-fill" style="color:#F5A623"></i>
                    +{{ $progression->points_gagnes }} points
                </div>
                <div class="pl-result-meta-item">
                    <i class="bi bi-arrow-repeat"></i>
                    {{ $progression->tentatives }} tentative{{ $progression->tentatives > 1 ? 's' : '' }}
                </div>
                @if($progression->terminee_le)
                <div class="pl-result-meta-item">
                    <i class="bi bi-calendar-check"></i>
                    {{ $progression->terminee_le->format('d/m/Y à H:i') }}
                </div>
                @endif
            </div>

            {{-- Badge mention --}}
            <div class="mt-3">
                @if($progression->score >= 90)
                    <span class="score-badge-lg" style="background:rgba(255,255,255,.2);color:#fff">🏆 Excellent</span>
                @elseif($progression->score >= 70)
                    <span class="score-badge-lg" style="background:rgba(255,255,255,.2);color:#fff">🎉 Réussi</span>
                @elseif($progression->score >= 50)
                    <span class="score-badge-lg" style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9)">💪 Peut mieux faire</span>
                @else
                    <span class="score-badge-lg" style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9)">📚 À retravailler</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Correction détaillée --}}
    @if($exercices->isNotEmpty())
    <div style="font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--marine);margin-bottom:14px;display:flex;align-items:center;gap:8px">
        <i class="bi bi-clipboard-check"></i> Correction détaillée
        <div style="flex:1;height:1px;background:#eee"></div>
    </div>

    @foreach($exercices as $idx => $ex)
    <div class="exo-item">
        <div class="exo-num">Question {{ $idx + 1 }}</div>
        <div class="exo-question">{{ $ex['question'] }}</div>

        @php
            $repDonnee = $ex['reponse_donnee'] ?? null;
            $repCorrecte = $ex['reponse'];
            $isCorrect = $ex['correct'] ?? null;
        @endphp

        @if($repDonnee !== null)
            {{-- Réponse de l'étudiant --}}
            <div class="rep-row {{ $isCorrect ? 'correct' : 'incorrect' }}">
                <span class="rep-icon">{{ $isCorrect ? '✅' : '❌' }}</span>
                <div>
                    <div class="rep-label">Votre réponse</div>
                    <div class="rep-val {{ $isCorrect ? 'correct-text' : 'incorrect-text' }}">{{ $repDonnee ?: '(sans réponse)' }}</div>
                </div>
            </div>

            {{-- Bonne réponse si incorrect --}}
            @if(! $isCorrect)
            <div class="rep-row expected">
                <span class="rep-icon">💡</span>
                <div>
                    <div class="rep-label">Bonne réponse</div>
                    <div class="rep-val expected-text">{{ $repCorrecte }}</div>
                </div>
            </div>
            @endif
        @else
            {{-- Question non répondue --}}
            <div class="rep-row skipped">
                <span class="rep-icon">⏭</span>
                <div>
                    <div class="rep-label">Non répondu</div>
                    <div class="rep-val" style="color:#888">Réponse attendue : {{ $repCorrecte }}</div>
                </div>
            </div>
        @endif

        {{-- Explication --}}
        @if(!empty($ex['explication']))
        <div class="explication-banner">
            <i class="bi bi-lightbulb-fill" style="color:var(--or);flex-shrink:0;margin-top:1px"></i>
            <div><strong>Explication :</strong> {{ $ex['explication'] }}</div>
        </div>
        @endif
    </div>
    @endforeach

    @else
    {{-- Leçon sans exercices --}}
    <div style="background:#fff;border-radius:14px;border:1px solid #eee;padding:32px;text-align:center">
        <div style="font-size:2.5rem;margin-bottom:12px">📖</div>
        <p style="color:#888;font-size:.88rem">Cette leçon ne contient pas d'exercices. Elle a été validée à la lecture.</p>
    </div>
    @endif

    {{-- Actions --}}
    <div style="display:flex;gap:12px;margin-top:24px;flex-wrap:wrap">
        <a href="{{ route('cours.allemand.lecon', [$cours->slug, $lecon->slug]) }}"
           style="flex:1;min-width:140px;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:12px;background:var(--or);color:#fff;font-weight:700;font-size:.88rem;text-decoration:none">
            <i class="bi bi-arrow-repeat"></i> Refaire la leçon
        </a>
        <a href="{{ route('progression.cours', $cours) }}"
           style="flex:1;min-width:140px;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:12px;background:#f0f0f0;color:#555;font-weight:700;font-size:.88rem;text-decoration:none">
            <i class="bi bi-arrow-left"></i> Retour au cours
        </a>
    </div>

</div>
@endsection