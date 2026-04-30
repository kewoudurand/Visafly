{{-- resources/views/courses/lecon.blade.php --}}
@extends('layouts.app')
@section('title', $lecon->titre . ' — ' . $cours->titre)

@push('styles')
<style>
:root { --c: {{ $cours->couleur ?? '#1B3A6B' }}; --marine:#1B3A6B; --or:#F5A623; }

.lecon-page { max-width:820px; margin:0 auto; padding:24px 20px 80px; }

.lb-breadcrumb { display:flex; align-items:center; gap:6px; font-size:.75rem; color:#888; margin-bottom:14px; flex-wrap:wrap; }
.lb-breadcrumb a { color:#888; text-decoration:none; }
.lb-breadcrumb a:hover { color:var(--marine); }
.lb-breadcrumb span { opacity:.4; }

.lb-top-nav { display:flex; align-items:center; gap:12px; margin-bottom:20px; }
.lb-back { width:36px;height:36px;border-radius:10px;background:#fff;border:1.5px solid #e8e8e8;display:flex;align-items:center;justify-content:center;color:var(--marine);text-decoration:none;flex-shrink:0;transition:all .2s; }
.lb-back:hover { background:var(--marine); color:#fff; }
.lb-top-info { flex:1; }
.lb-top-cours { font-size:.72rem; color:#888; margin-bottom:2px; }
.lb-top-titre { font-size:1rem; font-weight:800; color:var(--marine); }

.lecon-header-card { background:var(--c); border-radius:16px; padding:26px 24px; margin-bottom:20px; position:relative; overflow:hidden; }
.lecon-header-card::before { content:'';position:absolute;right:-30px;top:-30px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none; }
.lh-type-badge { display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.2);color:#fff;border-radius:20px;font-size:.72rem;font-weight:700;padding:4px 12px;margin-bottom:12px; }
.lh-titre { font-size:1.4rem; font-weight:900; color:#fff; margin-bottom:10px; line-height:1.3; }
.lh-meta { display:flex; gap:18px; flex-wrap:wrap; }
.lh-meta-item { display:flex; align-items:center; gap:6px; font-size:.78rem; color:rgba(255,255,255,.8); font-weight:600; }

.lecon-content-card { background:#fff; border-radius:14px; border:1px solid #eee; padding:22px 24px; margin-bottom:24px; font-size:.88rem; line-height:1.85; color:#444; }

.section-header { display:flex; align-items:center; gap:8px; margin-bottom:16px; }
.section-header-line { flex:1; height:1px; background:#eee; }
.section-header-title { font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--marine);white-space:nowrap;display:flex;align-items:center;gap:6px; }
.section-header-title i { color:var(--c); }

/* ── AUDIO PLAYER ──────────────────────────────────── */
.audio-player-card {
    background:#fff; border-radius:16px; border:1px solid #eee;
    padding:24px; margin-bottom:24px;
    box-shadow:0 4px 16px rgba(0,0,0,.06);
}
.audio-player-wrap {
    background:linear-gradient(135deg, var(--c)15, var(--c)05);
    border:2px solid var(--c)30;
    border-radius:14px; padding:20px; margin-bottom:16px;
}
/* ✅ Style de l'élément audio natif — cross-browser */
.audio-player-wrap audio {
    width: 100%;
    min-height: 54px;
    border-radius: 10px;
    outline: none;
    display: block;
    /* Force l'affichage des contrôles */
    -webkit-appearance: none;
}
.audio-error-box {
    display:none; background:#fde8e8; border:1.5px solid #dc3545;
    border-radius:10px; padding:14px 16px; margin-top:12px;
    font-size:.82rem; color:#842029;
}
.audio-error-box.visible { display:flex; align-items:flex-start; gap:10px; }
.transcription-box { background:#f8f9fb; border-radius:12px; padding:16px; font-size:.85rem; line-height:1.75; color:#444; max-height:220px; overflow-y:auto; border:1px solid #eee; }

/* ── VOCABULAIRE ───────────────────────────────────── */
.mots-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:12px; margin-bottom:28px; }
.mot-card { background:#fff; border-radius:12px; border:1px solid #eee; padding:16px; transition:all .2s; cursor:default; }
.mot-card:hover { border-color:var(--c); transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.08); }
.mot-de { font-size:1.05rem; font-weight:900; color:var(--marine); margin-bottom:2px; }
.mot-phonetique { font-size:.72rem; color:#aaa; font-style:italic; margin-bottom:5px; font-family:monospace; }
.mot-fr { font-size:.8rem; color:#555; font-weight:700; margin-bottom:8px; }
.mot-exemple { font-size:.76rem; color:#777; background:#f8f9fb; border-left:3px solid var(--c); padding:6px 10px; border-radius:0 8px 8px 0; line-height:1.5; }

/* ── EXERCICES ─────────────────────────────────────── */
.exercice-block { background:#fff; border-radius:14px; border:1px solid #eee; padding:22px; margin-bottom:12px; transition:border-color .2s; }
.ex-num-badge { display:inline-block; font-size:.65rem; font-weight:900; letter-spacing:.08em; text-transform:uppercase; color:var(--c); margin-bottom:8px; }
.ex-question { font-size:.92rem; font-weight:800; color:var(--marine); margin-bottom:16px; }
.choix-list { display:flex; flex-direction:column; gap:8px; }
.choix-btn { display:flex;align-items:center;gap:12px;padding:11px 16px;background:#f8f9fb;border:1.5px solid #eaeaea;border-radius:10px;cursor:pointer;font-size:.85rem;color:#333;text-align:left;width:100%;transition:all .15s; }
.choix-btn:hover { border-color:var(--c); background:var(--c)08; }
.choix-btn.selected { border-color:var(--c); background:var(--c)10; }
.choix-btn.correct  { border-color:#1cc88a; background:rgba(28,200,138,.09); color:#0f6e56; }
.choix-btn.wrong    { border-color:#E24B4A; background:rgba(226,75,74,.07); color:#a32d2d; }
.choix-btn:disabled { cursor:default; }
.choix-letter { width:28px;height:28px;border-radius:50%;background:#e0e0e0;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;flex-shrink:0;transition:all .15s; }
.choix-btn.selected .choix-letter { background:var(--c); color:#fff; }
.choix-btn.correct  .choix-letter { background:#1cc88a; color:#fff; }
.choix-btn.wrong    .choix-letter { background:#E24B4A; color:#fff; }
.libre-input { width:100%;border:1.5px solid #eaeaea;border-radius:10px;padding:11px 14px;font-size:.85rem;outline:none;transition:all .2s;background:#fafafa; }
.libre-input:focus { border-color:var(--c); background:#fff; }
.explication-box { display:none;margin-top:12px;background:#fffbf0;border-left:3px solid var(--or);border-radius:0 10px 10px 0;padding:10px 14px;font-size:.8rem;color:#5a3e00;line-height:1.65; }
.btn-valider { width:100%;padding:14px;background:var(--c);color:#fff;border:none;border-radius:25px;font-size:.92rem;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:24px;transition:all .2s; }
.btn-valider:hover { filter:brightness(1.08); transform:translateY(-2px); }
.btn-valider:disabled { opacity:.5; cursor:not-allowed; transform:none; }
.result-box { display:none;background:#fff;border-radius:16px;padding:32px;text-align:center;border:1px solid #eee;box-shadow:0 6px 24px rgba(0,0,0,.08);margin-top:20px; }
.result-emoji { font-size:2.5rem; margin-bottom:10px; }
.result-score { font-size:3rem; font-weight:900; line-height:1; margin-bottom:8px; }
.result-msg   { font-size:.9rem; color:#666; margin-bottom:6px; }
.result-pts   { font-size:.85rem; font-weight:700; color:var(--or); }

.lecon-nav { display:flex; gap:12px; margin-top:28px; }
.nav-btn { flex:1;padding:12px 18px;border-radius:25px;font-size:.85rem;font-weight:700;cursor:pointer;border:none;transition:all .2s;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px; }
.nav-btn:hover { transform:translateY(-1px); text-decoration:none; }
.nav-prev { background:#f0f0f0; color:#555; }
.nav-prev:hover { background:#e0e0e0; color:#333; }
.nav-next { background:var(--c); color:#fff; }
.nav-next:hover { color:#fff; filter:brightness(1.08); }

@media(max-width:600px) { .mots-grid { grid-template-columns:1fr 1fr; } .lh-meta { gap:10px; } }
</style>
@endpush

@section('content')
<div class="lecon-page">

    {{-- Breadcrumb --}}
    <div class="lb-breadcrumb">
        <a href="{{ route('cours.list') }}">Cours</a>
        <span>›</span>
        <a href="{{ route('cours.allemand.show', $cours->slug) }}">{{ $cours->titre }}</a>
        <span>›</span>
        <span style="color:#555;font-weight:600">{{ $lecon->titre }}</span>
    </div>

    {{-- Nav top --}}
    <div class="lb-top-nav">
        <a href="{{ route('cours.allemand.show', $cours->slug) }}" class="lb-back">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="lb-top-info">
            <div class="lb-top-cours">
                <span style="color:{{ $cours->couleur ?? '#1B3A6B' }};font-weight:800">{{ $cours->niveau }}</span>
                · {{ $cours->titre }}
            </div>
            <div class="lb-top-titre">{{ $lecon->titre }}</div>
        </div>
        @auth
        @if($lecon->estTermineePar(auth()->id()))
            <span style="font-size:.75rem;font-weight:700;background:#e8f8f0;color:#198754;border-radius:20px;padding:4px 12px;flex-shrink:0">
                <i class="bi bi-check-circle-fill me-1"></i>Terminée
            </span>
        @endif
        @endauth
    </div>

    {{-- Header coloré --}}
    <div class="lecon-header-card">
        <div class="lh-type-badge">
            <i class="bi {{ $lecon->iconeType() }}"></i>
            {{ ucfirst($lecon->type) }}
        </div>
        <h1 class="lh-titre">{{ $lecon->titre }}</h1>
        <div class="lh-meta">
            @if($lecon->duree_estimee_minutes)
            <div class="lh-meta-item"><i class="bi bi-clock"></i>{{ $lecon->duree_estimee_minutes }} min</div>
            @endif
            @if($lecon->nombreMots())
            <div class="lh-meta-item"><i class="bi bi-alphabet"></i>{{ $lecon->nombreMots() }} mots</div>
            @endif
            @if($lecon->nombreExercices())
            <div class="lh-meta-item"><i class="bi bi-pencil"></i>{{ $lecon->nombreExercices() }} exercices</div>
            @endif
            <div class="lh-meta-item"><i class="bi bi-star"></i>{{ $lecon->points_recompense }} pts</div>
        </div>
    </div>

    {{-- Contenu texte --}}
    @if($lecon->contenu)
    <div class="lecon-content-card">
        {!! \Illuminate\Support\Str::markdown($lecon->contenu) !!}
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════
         SECTION AUDIO — Reconstruite entièrement
    ═══════════════════════════════════════════════════════════════ --}}
    @if($lecon->type === 'audio')
    <div class="audio-player-card">
        <div class="section-header mb-3">
            <div class="section-header-title">
                <i class="bi bi-headphones"></i>
                Écouter l'audio
            </div>
            <div class="section-header-line"></div>
        </div>

        @php
            $audioUrl = $lecon->urlAudio();
        @endphp

        @if($audioUrl)
        <div class="audio-player-wrap">
            {{-- ✅ Attribut controls obligatoire + preload + type MIME explicite --}}
            <audio
                id="audio-lecon"
                controls
                preload="metadata"
                style="width:100%;display:block;border-radius:10px"
                onerror="showAudioError(this)">
                {{-- ✅ On met le src directement sur la balise ET dans source pour max compatibilité --}}
                <source src="{{ $audioUrl }}" type="{{ $lecon->mimeTypeAudio() }}">
                {{-- Fallback si <source> ne marche pas --}}
                <source src="{{ $audioUrl }}">
                Votre navigateur ne supporte pas la lecture audio.
            </audio>

            {{-- Boîte d'erreur cachée, affichée par JS si échec --}}
            <div class="audio-error-box" id="audio-error-box">
                <i class="bi bi-exclamation-triangle-fill" style="font-size:1.2rem;flex-shrink:0"></i>
                <div>
                    <strong>Impossible de lire l'audio.</strong><br>
                    <span id="audio-error-detail">Vérifiez votre connexion ou essayez de rafraîchir la page.</span>
                    <br>
                    <a href="{{ $audioUrl }}" target="_blank" download
                       style="font-size:.8rem;color:#842029;font-weight:700;margin-top:6px;display:inline-block">
                        <i class="bi bi-download me-1"></i>Télécharger l'audio
                    </a>
                </div>
            </div>
        </div>

        {{-- Contrôles personnalisés (optionnels, en plus du lecteur natif) --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div style="font-size:.8rem;color:#888;">
                <i class="bi bi-info-circle me-1"></i>
                <span id="audio-duration">Chargement...</span>
            </div>
            <a href="{{ $audioUrl }}" download
               style="font-size:.78rem;color:var(--marine);font-weight:600;text-decoration:none">
                <i class="bi bi-download me-1"></i>Télécharger
            </a>
        </div>

        {{-- Transcription --}}
        @if($lecon->transcription_audio)
        <details>
            <summary style="font-size:.82rem;font-weight:700;color:var(--marine);cursor:pointer;padding:8px 0;list-style:none;display:flex;align-items:center;gap:6px">
                <i class="bi bi-file-text"></i>
                Afficher la transcription
                <i class="bi bi-chevron-down ms-auto" style="font-size:.7rem"></i>
            </summary>
            <div class="transcription-box mt-2">
                {{ $lecon->transcription_audio }}
            </div>
        </details>
        @endif

        @else
        {{-- Aucun fichier audio en base --}}
        <div style="background:#fff8e8;border:1.5px solid var(--or);border-radius:12px;padding:20px;text-align:center">
            <i class="bi bi-exclamation-circle" style="font-size:2rem;color:var(--or);display:block;margin-bottom:10px"></i>
            <p style="font-size:.88rem;color:#856404;font-weight:600;margin:0">
                Le fichier audio de cette leçon n'est pas encore disponible.
            </p>
        </div>
        @endif
    </div>
    @endif

    {{-- Vocabulaire --}}
    @if($lecon->mots && count($lecon->mots))
    <div class="mb-4">
        <div class="section-header">
            <div class="section-header-title">
                <i class="bi bi-alphabet"></i>
                Vocabulaire ({{ count($lecon->mots) }} mots)
            </div>
            <div class="section-header-line"></div>
        </div>
        <div class="mots-grid">
            @foreach($lecon->mots as $mot)
            <div class="mot-card">
                <div class="mot-de">{{ $mot['de'] }}</div>
                @if(!empty($mot['phonetique']))
                <div class="mot-phonetique">/ {{ $mot['phonetique'] }} /</div>
                @endif
                <div class="mot-fr">🇫🇷 {{ $mot['fr'] }}</div>
                @if(!empty($mot['exemple']))
                <div class="mot-exemple">
                    <i class="bi bi-chat-quote" style="font-size:.65rem;margin-right:4px;color:var(--c)"></i>
                    {{ $mot['exemple'] }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Exercices --}}
    @if($lecon->exercices && count($lecon->exercices))
    <div id="exercicesZone">
        <div class="section-header mb-3">
            <div class="section-header-title">
                <i class="bi bi-check2-circle"></i>
                Exercices ({{ count($lecon->exercices) }} questions)
            </div>
            <div class="section-header-line"></div>
        </div>

        @foreach($lecon->exercices as $idx => $ex)
        <div class="exercice-block" id="ex-{{ $idx }}">
            <div class="ex-num-badge">Question {{ $idx + 1 }}</div>
            <div class="ex-question">{{ $ex['question'] }}</div>

            @if($ex['type'] === 'qcm')
            <div class="choix-list" data-idx="{{ $idx }}" data-reponse="{{ $ex['reponse'] }}">
                @foreach($ex['choix'] as $ci => $choix)
                <button class="choix-btn"
                        onclick="selectChoix({{ $idx }}, this, '{{ addslashes($choix) }}')"
                        data-value="{{ $choix }}">
                    <div class="choix-letter">{{ chr(65 + $ci) }}</div>
                    <span>{{ $choix }}</span>
                </button>
                @endforeach
            </div>
            @else
            <input type="text" class="libre-input"
                   id="libre-{{ $idx }}"
                   data-idx="{{ $idx }}"
                   data-reponse="{{ $ex['reponse'] }}"
                   placeholder="Votre réponse...">
            @endif

            <div class="explication-box" id="expl-{{ $idx }}">
                <strong>💡 Explication :</strong> {{ $ex['explication'] ?? '' }}
            </div>
        </div>
        @endforeach

        @auth
        <button class="btn-valider" id="btnValider" onclick="validerExercices()">
            <i class="bi bi-check-circle-fill"></i>
            Valider mes réponses
        </button>
        @else
        <div style="background:#f0f4ff;border-radius:12px;padding:16px 20px;text-align:center;font-size:.85rem;color:var(--marine)">
            <a href="{{ route('login') }}" style="color:var(--marine);font-weight:700">Connectez-vous</a>
            pour valider vos réponses et gagner des points.
        </div>
        @endauth

        <div class="result-box" id="resultBox">
            <div class="result-emoji" id="resultEmoji">🎉</div>
            <div class="result-score" id="resultScore">0%</div>
            <div class="result-msg" id="resultMsg"></div>
            <div class="result-pts" id="resultPts"></div>
        </div>
    </div>

    @else
    @auth
    @if(! $lecon->estTermineePar(auth()->id()))
    <button class="btn-valider" id="btnTerminer" onclick="terminerLecon()">
        <i class="bi bi-check-circle-fill"></i>
        Marquer comme terminée
    </button>
    @else
    <div style="background:#e8f8f0;border-radius:12px;padding:16px 20px;text-align:center;font-size:.85rem;color:#198754;font-weight:700">
        <i class="bi bi-check-circle-fill me-2"></i>
        Leçon terminée — +{{ $lecon->points_recompense }} points gagnés
    </div>
    @endif
    @endauth
    @endif

    {{-- Navigation --}}
    <div class="lecon-nav">
        @if($precedente)
        <a href="{{ route('cours.allemand.lecon', [$cours->slug, $precedente->slug]) }}" class="nav-btn nav-prev">
            <i class="bi bi-arrow-left"></i> Précédente
        </a>
        @endif
        @if($suivante)
        <a href="{{ route('cours.allemand.lecon', [$cours->slug, $suivante->slug]) }}" class="nav-btn nav-next">
            Suivante <i class="bi bi-arrow-right"></i>
        </a>
        @else
        <a href="{{ route('cours.allemand.show', $cours->slug) }}" class="nav-btn nav-next">
            <i class="bi bi-flag-fill"></i> Fin du cours
        </a>
        @endif
    </div>

</div>
@push('scripts')
<script>
const VALIDER_URL  = '{{ route("cours.allemand.valider", $lecon->id) }}';
const TERMINER_URL = '{{ route("cours.allemand.valider", $lecon->id) }}';
const CSRF         = '{{ csrf_token() }}';
const NB_EX        = {{ count($lecon->exercices ?? []) }};
const reponses     = {};

// ── Gestion erreur audio ────────────────────────────────────────
function showAudioError(audioEl) {
    const box    = document.getElementById('audio-error-box');
    const detail = document.getElementById('audio-error-detail');
    if (!box) return;

    // Déterminer la cause de l'erreur
    const err = audioEl.error;
    const messages = {
        1: 'Chargement interrompu.',
        2: 'Erreur réseau. Vérifiez votre connexion.',
        3: 'Erreur de décodage. Le fichier est peut-être corrompu.',
        4: 'Format non supporté par votre navigateur. Essayez Chrome ou Firefox.',
    };
    if (detail) detail.textContent = messages[err?.code] ?? 'Une erreur inconnue est survenue.';
    box.classList.add('visible');
}

// ── Durée audio ─────────────────────────────────────────────────
const audioEl = document.getElementById('audio-lecon');
if (audioEl) {
    audioEl.addEventListener('loadedmetadata', () => {
        const dur = audioEl.duration;
        const durLabel = document.getElementById('audio-duration');
        if (durLabel && !isNaN(dur)) {
            const m = Math.floor(dur / 60);
            const s = Math.floor(dur % 60).toString().padStart(2, '0');
            durLabel.textContent = `Durée : ${m}:${s}`;
        }
    });

    audioEl.addEventListener('error', () => showAudioError(audioEl));

    // ✅ Forcer le rechargement si la source n'a pas été chargée
    if (audioEl.readyState === 0) {
        audioEl.load();
    }
}

// ── Exercices ───────────────────────────────────────────────────
function selectChoix(idx, btn, valeur) {
    btn.closest('.choix-list').querySelectorAll('.choix-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    reponses[idx] = valeur;
}

async function validerExercices() {
    document.querySelectorAll('.libre-input').forEach(inp => {
        reponses[inp.dataset.idx] = inp.value.trim();
    });

    if (Object.keys(reponses).length < NB_EX) {
        alert('Veuillez répondre à toutes les questions.');
        return;
    }

    let bonnes = 0;
    document.querySelectorAll('.exercice-block').forEach((block, idx) => {
        const list  = block.querySelector('.choix-list');
        const libre = block.querySelector('.libre-input');
        const rep   = (reponses[idx] ?? '').toLowerCase().trim();
        const bonne = (list ? list.dataset.reponse : libre?.dataset.reponse ?? '').toLowerCase().trim();
        const isOk  = rep === bonne;
        if (isOk) bonnes++;

        if (list) {
            list.querySelectorAll('.choix-btn').forEach(b => {
                b.disabled = true;
                if (b.dataset.value.toLowerCase() === bonne) b.classList.add('correct');
                else if (b.classList.contains('selected') && !isOk) b.classList.add('wrong');
            });
        } else if (libre) {
            libre.disabled = true;
            libre.style.borderColor = isOk ? '#1cc88a' : '#E24B4A';
        }

        const expl = document.getElementById('expl-' + idx);
        if (expl) expl.style.display = 'block';
    });

    const score = Math.round((bonnes / NB_EX) * 100);
    const resultBox = document.getElementById('resultBox');
    resultBox.style.display = 'block';
    document.getElementById('resultScore').textContent = score + '%';
    document.getElementById('resultScore').style.color = score >= 70 ? '#1cc88a' : (score >= 50 ? '#F5A623' : '#E24B4A');
    document.getElementById('resultEmoji').textContent = score >= 90 ? '🏆' : (score >= 70 ? '🎉' : (score >= 50 ? '💪' : '📚'));
    document.getElementById('resultMsg').textContent   = score >= 70 ? 'Excellent ! Leçon validée !' : 'Continuez à pratiquer !';
    document.getElementById('btnValider').disabled = true;

    try {
        const res  = await fetch(VALIDER_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ reponses })
        });
        const data = await res.json();
        if (data.points_gagnes) {
            document.getElementById('resultPts').textContent = '+' + data.points_gagnes + ' points gagnés !';
        }
    } catch(e) { console.error(e); }

    resultBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

async function terminerLecon() {
    const btn = document.getElementById('btnTerminer');
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
    try {
        const res  = await fetch(TERMINER_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ reponses: {} })
        });
        const data = await res.json();
        btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Terminée ! +' + (data.points_gagnes || 0) + ' pts';
        btn.style.background = '#1cc88a';
    } catch(e) {
        btn.disabled = false;
        btn.innerHTML = 'Réessayer';
    }
}
</script>
@endpush

@endsection