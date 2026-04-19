{{-- resources/views/student/lessons/show.blade.php --}}

@extends('layouts.student')

@section('title', $lesson->titre . ' — ' . $cours->titre)

@push('styles')
<style>
    :root {
        --marine: #1B3A6B;
        --or:     #F5A623;
    }

    /* ── Header leçon ───────────────────── */
    .lesson-header {
        background: linear-gradient(135deg, var(--marine) 0%, #243f70 100%);
        color: #fff;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
    }
    .lesson-header .badge-type {
        background: rgba(255,255,255,.15);
        color: #fff;
        border-radius: 20px;
        padding: .3rem .9rem;
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    /* ── Progression cours ─────────────── */
    .cours-progress-bar { height: 8px; border-radius: 4px; }

    /* ── Carte mot vocabulaire ─────────── */
    .mot-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-left: 5px solid var(--marine);
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        transition: box-shadow .2s, transform .2s;
        height: 100%;
    }
    .mot-card:hover { box-shadow: 0 6px 20px rgba(27,58,107,.12); transform: translateY(-2px); }
    .mot-card .mot-de { font-size: 1.15rem; font-weight: 700; color: var(--marine); }
    .mot-card .mot-phonetique { font-family: 'Courier New', monospace; color: #6c757d; font-size: .82rem; }
    .mot-card .mot-fr { font-size: .9rem; color: #495057; font-weight: 600; }
    .mot-card .mot-exemple {
        background: #f0f4ff;
        border-left: 3px solid var(--marine);
        padding: .35rem .65rem;
        border-radius: 0 6px 6px 0;
        font-size: .82rem;
        color: #343a40;
        font-style: italic;
    }

    /* ── Contenu Markdown ──────────────── */
    .lesson-body h1,.lesson-body h2,.lesson-body h3 { color: var(--marine); }
    .lesson-body blockquote {
        border-left: 4px solid var(--or);
        background: #fffbf2;
        padding: .75rem 1rem;
        border-radius: 0 8px 8px 0;
        color: #5a4d00;
    }

    /* ── Audio player ───────────────────── */
    .audio-player-wrap audio { width: 100%; border-radius: 40px; }
    .transcription-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 1.25rem;
        font-size: .9rem;
        line-height: 1.7;
        max-height: 250px;
        overflow-y: auto;
    }

    /* ── Exercices ──────────────────────── */
    .exercice-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .exercice-card .num-ex {
        background: var(--or);
        color: #fff;
        font-weight: 700;
        border-radius: 50%;
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem;
        flex-shrink: 0;
    }
    .choix-radio label {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .65rem 1rem;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        cursor: pointer;
        transition: border-color .15s, background .15s;
        margin-bottom: .5rem;
    }
    .choix-radio input[type=radio]:checked + label {
        border-color: var(--marine);
        background: #f0f4ff;
    }
    /* Corrections */
    .choix-radio.correct label  { border-color: #198754!important; background: #d1e7dd!important; }
    .choix-radio.incorrect label{ border-color: #dc3545!important; background: #f8d7da!important; }
    .feedback-box {
        border-radius: 10px;
        padding: .7rem 1rem;
        font-size: .85rem;
        margin-top: .75rem;
        display: none;
    }
    .feedback-box.show { display: flex; align-items: flex-start; gap: .5rem; }

    /* ── Boutons nav ────────────────────── */
    .btn-lecon-nav {
        min-width: 130px;
        border-radius: 10px;
        font-weight: 600;
    }

    /* ── Toast résultat ─────────────────── */
    #toast-resultat {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
        min-width: 280px;
    }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width:900px">

    {{-- ── Header ──────────────────────────────────────────── --}}
    <div class="lesson-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-type"><i class="bi {{ $lesson->iconeType() }} me-1"></i>{{ ucfirst($lesson->type) }}</span>
                    @if($lesson->gratuite)
                        <span class="badge bg-success">Gratuite</span>
                    @endif
                    @if($progression->estTerminee())
                        <span class="badge" style="background:var(--or);color:#000">
                            <i class="bi bi-check-circle-fill me-1"></i>Terminée
                        </span>
                    @endif
                </div>
                <h2 class="fw-bold mb-1">{{ $lesson->titre }}</h2>
                <p class="mb-0 opacity-75 small">
                    {{ $cours->titre }}
                    @if($lesson->duree_estimee_minutes)
                        · <i class="bi bi-clock me-1"></i>{{ $lesson->duree_estimee_minutes }} min
                    @endif
                    @if($lesson->nombreMots())
                        · <i class="bi bi-alphabet me-1"></i>{{ $lesson->nombreMots() }} mots
                    @endif
                    @if($lesson->nombreExercices())
                        · <i class="bi bi-pencil me-1"></i>{{ $lesson->nombreExercices() }} exercices
                    @endif
                </p>
            </div>
            <div class="text-end">
                <div class="fw-bold fs-5" style="color:var(--or)">
                    <i class="bi bi-trophy"></i> {{ $lesson->points_recompense }} pts
                </div>
                @if($progression->estTerminee())
                    <small class="opacity-75">Score : {{ $progression->score }}%</small>
                @endif
            </div>
        </div>

        {{-- Progression du cours --}}
        @if($coursProgression)
        <div class="mt-3 pt-3 border-top border-white border-opacity-25">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="opacity-75">Progression du cours</small>
                <small class="fw-bold">{{ $coursProgression->pourcentage }}%</small>
            </div>
            <div class="progress cours-progress-bar" style="background:rgba(255,255,255,.2)">
                <div class="progress-bar" style="background:var(--or);width:{{ $coursProgression->pourcentage }}%"></div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Contenu Markdown ─────────────────────────────────── --}}
    @if($lesson->contenu)
    <div class="lesson-body mb-4">
        {!! \Illuminate\Support\Str::markdown($lesson->contenu) !!}
    </div>
    @endif

    {{-- ── Section AUDIO ───────────────────────────────────── --}}
    @if($lesson->type === 'audio' && $lesson->fichier_audio)
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3" style="color:var(--marine)">
                <i class="bi bi-headphones me-2"></i>Écouter l'audio
            </h5>
            <div class="audio-player-wrap mb-3">
                <audio controls>
                    <source src="{{ $lesson->urlAudio() }}">
                    Votre navigateur ne supporte pas l'audio HTML5.
                </audio>
            </div>
            @if($lesson->transcription_audio)
            <details>
                <summary class="fw-bold small text-muted cursor-pointer mb-2">📄 Afficher la transcription</summary>
                <div class="transcription-box mt-2">{{ $lesson->transcription_audio }}</div>
            </details>
            @endif
        </div>
    </div>
    @endif

    {{-- ── Section VOCABULAIRE ─────────────────────────────── --}}
    @if($lesson->mots && count($lesson->mots) > 0)
    <div class="mb-4">
        <h5 class="fw-bold mb-3" style="color:var(--marine)">
            <i class="bi bi-alphabet me-2"></i>Vocabulaire
            <span class="badge bg-light text-dark fw-normal ms-1">{{ $lesson->nombreMots() }} mots</span>
        </h5>
        <div class="row g-3">
            @foreach($lesson->mots as $mot)
            <div class="col-md-6 col-lg-4">
                <div class="mot-card">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="mot-de">{{ $mot['de'] }}</span>
                        @if(!empty($mot['phonetique']))
                            <span class="mot-phonetique">/ {{ $mot['phonetique'] }} /</span>
                        @endif
                    </div>
                    <div class="mot-fr mb-2">
                        <i class="bi bi-arrow-right-short text-muted"></i> {{ $mot['fr'] }}
                    </div>
                    @if(!empty($mot['exemple']))
                        <div class="mot-exemple">
                            <i class="bi bi-chat-quote me-1"></i>{{ $mot['exemple'] }}
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bouton terminer si pas d'exercices --}}
        @if(empty($lesson->exercices) && !$progression->estTerminee())
        <div class="text-center mt-4">
            <button class="btn btn-success px-4 fw-bold" id="btn-terminer-lecon">
                <i class="bi bi-check-circle me-2"></i>J'ai appris ce vocabulaire
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- ── Section EXERCICES ───────────────────────────────── --}}
    @if($lesson->exercices && count($lesson->exercices) > 0)
    <div class="mb-4">
        <h5 class="fw-bold mb-1" style="color:var(--marine)">
            <i class="bi bi-pencil-square me-2"></i>Exercices
        </h5>
        @if($progression->estTerminee())
            <p class="text-success small mb-3">
                <i class="bi bi-check-circle-fill me-1"></i>
                Déjà validée — Score : {{ $progression->score }}% — {{ $progression->tentatives }} tentative(s)
            </p>
        @else
            <p class="text-muted small mb-3">{{ $lesson->nombreExercices() }} exercice(s) · Seuil de réussite : 60%</p>
        @endif

        <form id="form-exercices">
            @csrf
            @foreach($lesson->exercices as $i => $ex)
            <div class="exercice-card" data-index="{{ $i }}" data-reponse="{{ $ex['reponse'] }}">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="num-ex">{{ $i + 1 }}</div>
                    <p class="mb-0 fw-semibold">{{ $ex['question'] }}</p>
                </div>

                @if($ex['type'] === 'qcm' && !empty($ex['choix']))
                    <div class="ps-4">
                        @foreach($ex['choix'] as $ci => $choix)
                        <div class="choix-radio" data-value="{{ $choix }}">
                            <input type="radio" name="reponses[{{ $i }}]"
                                id="ex{{ $i }}_c{{ $ci }}"
                                value="{{ $choix }}"
                                class="d-none ex-radio"
                                {{ $progression->estTerminee() ? 'disabled' : '' }}>
                            <label for="ex{{ $i }}_c{{ $ci }}">
                                <span class="badge bg-light text-dark" style="width:24px">{{ chr(65+$ci) }}</span>
                                {{ $choix }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="ps-4">
                        <input type="text" name="reponses[{{ $i }}]"
                            class="form-control ex-texte"
                            placeholder="Votre réponse..."
                            {{ $progression->estTerminee() ? 'disabled' : '' }}>
                    </div>
                @endif

                {{-- Feedback affiché après correction --}}
                <div class="feedback-box alert-success" id="fb-ok-{{ $i }}">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>
                        <strong>Correct !</strong>
                        @if(!empty($ex['explication']))
                            <br><span class="text-muted">{{ $ex['explication'] }}</span>
                        @endif
                    </div>
                </div>
                <div class="feedback-box alert-danger" id="fb-ko-{{ $i }}">
                    <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                    <div>
                        <strong>Incorrect.</strong> Bonne réponse : <strong class="text-danger">{{ $ex['reponse'] }}</strong>
                        @if(!empty($ex['explication']))
                            <br><span class="text-muted">{{ $ex['explication'] }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            @if(!$progression->estTerminee())
            <div class="text-center mt-2">
                <button type="submit" class="btn btn-primary px-5 fw-bold" id="btn-soumettre">
                    <i class="bi bi-send me-2"></i>Valider mes réponses
                </button>
            </div>
            @endif
        </form>

        {{-- Résultats déjà disponibles --}}
        @if($progression->estTerminee() && $progression->reponses_etudiant)
        <div class="alert alert-success d-flex align-items-center gap-2 mt-3">
            <i class="bi bi-trophy-fill fs-4 text-warning"></i>
            <div>
                Leçon validée avec <strong>{{ $progression->score }}%</strong>
                ({{ $progression->bonnes_reponses }}/{{ $progression->total_questions }})
                — +{{ $progression->points_gagnes }} pts gagnés
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ── Navigation leçons ───────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
        @if($leconPrecedente)
            <a href="{{ route('student.cours.lessons.show', [$cours, $leconPrecedente]) }}"
               class="btn btn-outline-primary btn-lecon-nav">
                <i class="bi bi-chevron-left me-1"></i>Précédente
            </a>
        @else
            <a href="{{ route('student.cours.show', $cours) }}" class="btn btn-outline-secondary btn-lecon-nav">
                <i class="bi bi-grid me-1"></i>Sommaire
            </a>
        @endif

        @if($leconSuivante)
            <a href="{{ route('student.cours.lessons.show', [$cours, $leconSuivante]) }}"
               class="btn btn-primary btn-lecon-nav" id="btn-lecon-suivante">
                Suivante <i class="bi bi-chevron-right ms-1"></i>
            </a>
        @else
            <a href="{{ route('student.cours.show', $cours) }}"
               class="btn btn-success btn-lecon-nav">
                <i class="bi bi-flag-fill me-1"></i>Fin du cours
            </a>
        @endif
    </div>

</div>

{{-- Toast résultat --}}
<div id="toast-resultat" class="toast align-items-center text-white border-0" role="alert">
    <div class="d-flex">
        <div class="toast-body fw-bold" id="toast-msg"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
</div>
@endsection

@push('scripts')
<script>
const SOUMETTRE_URL = "{{ route('student.cours.lessons.soumettre', [$cours, $lesson]) }}";
const TERMINER_URL  = "{{ route('student.cours.lessons.terminer', [$cours, $lesson]) }}";
const CSRF          = "{{ csrf_token() }}";

// ── Toast helper ─────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast-resultat');
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
    toast.classList.add('bg-' + type);
    document.getElementById('toast-msg').textContent = msg;
    new bootstrap.Toast(toast, { delay: 4000 }).show();
}

// ── Soumettre exercices ──────────────────────────────────────
const formEx = document.getElementById('form-exercices');
if (formEx) {
    formEx.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-soumettre');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Correction...';

        const formData = new FormData(formEx);
        const reponses = {};
        for (const [k, v] of formData.entries()) {
            const m = k.match(/reponses\[(\d+)\]/);
            if (m) reponses[m[1]] = v;
        }

        try {
            const res = await fetch(SOUMETTRE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ reponses })
            });
            const data = await res.json();

            // Afficher corrections
            data.reponses.forEach(r => {
                const card = document.querySelector(`.exercice-card[data-index="${r.index}"]`);
                if (!card) return;

                // Highlight choix QCM
                card.querySelectorAll('.choix-radio').forEach(div => {
                    if (div.dataset.value === r.reponse_correcte) div.classList.add('correct');
                    if (div.dataset.value === r.reponse_donnee && !r.correct) div.classList.add('incorrect');
                });

                // Feedback
                document.getElementById('fb-ok-' + r.index).classList.toggle('show', r.correct);
                document.getElementById('fb-ko-' + r.index).classList.toggle('show', !r.correct);

                // Désactiver inputs
                card.querySelectorAll('input').forEach(i => i.disabled = true);
            });

            btn.style.display = 'none';
            showToast(data.message, data.termine ? 'success' : 'warning');

            if (data.termine) {
                // Afficher résumé
                const summary = document.createElement('div');
                summary.className = 'alert alert-success d-flex align-items-center gap-2 mt-3';
                summary.innerHTML = `<i class="bi bi-trophy-fill fs-4 text-warning"></i>
                    <div>Leçon validée avec <strong>${data.score}%</strong>
                    (${data.bonnes}/${data.total}) — <strong>+${data.points} pts</strong></div>`;
                formEx.appendChild(summary);
            }
        } catch (err) {
            showToast('Une erreur est survenue. Réessayez.', 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send me-2"></i>Valider mes réponses';
        }
    });
}

// ── Terminer leçon sans exercice ─────────────────────────────
const btnTerminer = document.getElementById('btn-terminer-lecon');
if (btnTerminer) {
    btnTerminer.addEventListener('click', async () => {
        btnTerminer.disabled = true;
        btnTerminer.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
        const res = await fetch(TERMINER_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        });
        const data = await res.json();
        showToast(data.message, 'success');
        btnTerminer.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Leçon validée !';
        btnTerminer.classList.replace('btn-success', 'btn-outline-success');
    });
}
</script>
@endpush