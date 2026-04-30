{{-- resources/views/shared/lessons/_form_lecon.blade.php --}}
@push('styles')
<style>
:root { --marine:#1B3A6B; --or:#F5A623; }
.card-mot, .card-exercice {
    border-left:4px solid var(--marine); background:#f8f9fa;
    border-radius:0 10px 10px 0; padding:1rem 1.1rem;
    margin-bottom:.65rem; position:relative; transition:box-shadow .15s;
}
.card-mot:hover, .card-exercice:hover { box-shadow:0 3px 10px rgba(27,58,107,.08); }
.card-exercice { border-left-color:var(--or); }
.btn-remove-row {
    position:absolute; top:.6rem; right:.6rem;
    width:26px; height:26px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:12px; padding:0; border:none;
    background:#fee2e2; color:#dc3545; cursor:pointer; transition:background .15s;
}
.btn-remove-row:hover { background:#dc3545; color:#fff; }
.form-panel {
    background:#fff; border-radius:14px; padding:1.5rem;
    box-shadow:0 1px 8px rgba(27,58,107,.07); margin-bottom:1.5rem;
}
.form-panel-title {
    font-size:.78rem; font-weight:700; letter-spacing:.08em;
    text-transform:uppercase; color:var(--marine);
    margin-bottom:1.1rem; padding-bottom:.5rem;
    border-bottom:2px solid #f0f4ff;
    display:flex; align-items:center; gap:.45rem;
}
/* ── Tabs ──────────────────────────────────────────── */
.tab-nav .nav-link {
    border:none; border-radius:8px; color:#6c757d;
    font-weight:600; font-size:.85rem; padding:.5rem 1rem;
    transition:all .15s; position:relative;
}
.tab-nav .nav-link.active { background:var(--marine); color:#fff; }
.tab-nav .nav-link:hover:not(.active) { background:#f0f4ff; color:var(--marine); }

/* ── Indicateur de type actif ──────────────────────── */
.type-indicator {
    display:none; /* caché par défaut */
    align-items:center; gap:8px; padding:8px 14px;
    border-radius:10px; font-size:.82rem; font-weight:600;
    margin-bottom:1rem; border:1.5px solid;
}
.type-indicator.visible { display:flex; }
.type-indicator.audio   { background:#e8f9ff; border-color:#0dcaf0; color:#0a6c82; }
.type-indicator.lecture { background:#f5f5f5; border-color:#aaa;    color:#555; }
.type-indicator.grammaire{ background:#fff8e8; border-color:var(--or); color:#856404; }
.type-indicator.dialogue { background:#e8f0ff; border-color:#4a6cf7; color:#2d49b5; }

/* ── Sections conditionnelles ──────────────────────── */
/* Toujours visibles sauf si on les cache explicitement */
</style>
@endpush

<form action="{{ $routeAction }}" method="POST" enctype="multipart/form-data" id="form-lecon">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    <div class="row g-4">

        {{-- ══════ COLONNE GAUCHE ══════ --}}
        <div class="col-lg-8">

            {{-- ── Onglets ──────────────────────────────────────── --}}
            <ul class="nav tab-nav gap-1 mb-3" id="lessonTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-target="#tab-general" data-bs-toggle="tab" type="button">
                        Général
                    </button>
                </li>
                <li class="nav-item" id="tab-nav-mots">
                    <button class="nav-link" data-bs-target="#tab-mots" data-bs-toggle="tab" type="button">
                        Vocabulaire
                        <span class="badge ms-1" style="background:#1cc88a;font-size:.65rem" id="badge-mots">{{ count($lesson->mots ?? []) }}</span>
                    </button>
                </li>
                <li class="nav-item" id="tab-nav-exercices">
                    <button class="nav-link" data-bs-target="#tab-exercices" data-bs-toggle="tab" type="button">
                        Exercices
                        <span class="badge ms-1" style="background:var(--or);color:#000;font-size:.65rem" id="badge-ex">{{ count($lesson->exercices ?? []) }}</span>
                    </button>
                </li>
                <li class="nav-item" id="tab-nav-audio">
                    {{-- ✅ toujours dans le DOM, caché/visible par JS --}}
                    <button class="nav-link" data-bs-target="#tab-audio" data-bs-toggle="tab" type="button">
                        <i class="bi bi-headphones me-1"></i>Audio
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                {{-- ── TAB GÉNÉRAL ──────────────────────────────── --}}
                <div class="tab-pane fade show active" id="tab-general">
                    <div class="form-panel">
                        <div class="form-panel-title"><i class="bi bi-file-text"></i>Informations de la leçon</div>

                        {{-- Indicateur de type courant --}}
                        <div class="type-indicator" id="type-indicator">
                            <i class="bi" id="type-indicator-icon"></i>
                            <span id="type-indicator-text"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror"
                                value="{{ old('titre', $lesson->titre ?? '') }}"
                                placeholder="ex : Les salutations — Grüße" required>
                            @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">
                                Contenu de la leçon
                                <span class="text-muted fw-normal small">(Markdown supporté)</span>
                            </label>
                            <textarea name="contenu" class="form-control font-monospace" rows="9"
                                placeholder="# Titre de la leçon&#10;&#10;Expliquez le contenu en Markdown...">{{ old('contenu', $lesson->contenu ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ── TAB VOCABULAIRE ───────────────────────────── --}}
                <div class="tab-pane fade" id="tab-mots">
                    <div class="form-panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-panel-title mb-0"><i class="bi bi-alphabet"></i>Mots de vocabulaire</div>
                            <button type="button" class="btn btn-sm fw-bold" id="btn-ajouter-mot"
                                    style="background:#e8f8f0;color:#198754;border:none">
                                <i class="bi bi-plus-lg me-1"></i>Ajouter un mot
                            </button>
                        </div>
                        <div id="liste-mots">
                            @foreach(old('mots', $lesson->mots ?? []) as $i => $mot)
                                @include('shared.lessons._mot_row', ['i' => $i, 'mot' => $mot])
                            @endforeach
                        </div>
                        <p class="text-muted small text-center py-3" id="hint-mots"
                           @if(count(old('mots', $lesson->mots ?? []))) hidden @endif>
                            Aucun mot. Cliquez sur "Ajouter un mot".
                        </p>
                    </div>
                </div>

                {{-- ── TAB EXERCICES ─────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-exercices">
                    <div class="form-panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-panel-title mb-0"><i class="bi bi-pencil-square"></i>Exercices</div>
                            <button type="button" class="btn btn-sm fw-bold" id="btn-ajouter-ex"
                                    style="background:#fff8e8;color:#856404;border:none">
                                <i class="bi bi-plus-lg me-1"></i>Ajouter un exercice
                            </button>
                        </div>
                        <div id="liste-exercices">
                            @foreach(old('exercices', $lesson->exercices ?? []) as $i => $ex)
                                @include('shared.lessons._exercice_row', ['i' => $i, 'ex' => $ex])
                            @endforeach
                        </div>
                        <p class="text-muted small text-center py-3" id="hint-ex"
                           @if(count(old('exercices', $lesson->exercices ?? []))) hidden @endif>
                            Aucun exercice. Cliquez sur "Ajouter un exercice".
                        </p>
                    </div>
                </div>

                {{-- ── TAB AUDIO ─────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-audio">
                    <div class="form-panel">
                        <div class="form-panel-title"><i class="bi bi-headphones"></i>Fichier Audio</div>

                        {{-- Alerte si type != audio --}}
                        <div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3" id="audio-type-warning" style="display:none!important">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Sélectionnez le type <strong>Audio</strong> dans les paramètres pour activer ce champ.</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Téléverser un fichier audio</label>
                                <input type="file" name="fichier_audio" class="form-control"
                                       accept=".mp3,.wav,.ogg,.m4a" id="audio-file-input">
                                <small class="text-muted">MP3, WAV, OGG, M4A — max 50 Mo</small>

                                @if(isset($lesson) && $lesson->fichier_audio)
                                <div class="mt-2">
                                    <audio controls class="w-100" id="audio-preview">
                                        <source src="{{ $lesson->urlAudio() }}">
                                    </audio>
                                    <p class="text-muted small mt-1">
                                        <i class="bi bi-file-earmark-music me-1"></i>
                                        Fichier actuel : {{ basename($lesson->fichier_audio) }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Transcription de l'audio</label>
                                <textarea name="transcription_audio" class="form-control" rows="6"
                                    placeholder="Texte lu dans l'audio (affiché sous le player)...">{{ old('transcription_audio', $lesson->transcription_audio ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /tab-content --}}
        </div>

        {{-- ══════ COLONNE DROITE ══════ --}}
        <div class="col-lg-4">

            {{-- Instructeur (admin seulement) --}}
            @if(!empty($showInstructeurSelect) && !empty($instructeurs) && $instructeurs->count())
            <div class="form-panel">
                <div class="form-panel-title"><i class="bi bi-person-badge-fill"></i>Instructeur</div>
                <select name="instructeur_id" class="form-select @error('instructor_id') is-invalid @enderror">
                    <option value="">— Hériter du cours —</option>
                    @foreach($instructeurs as $inst)
                        <option value="{{ $inst->id }}"
                            {{ old('instructor_id', $lesson->instructor_id ?? $cours->instructor_id ?? '') == $inst->id ? 'selected' : '' }}>
                            {{ $inst->first_name }}
                        </option>
                    @endforeach
                </select>
                @error('instructor_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            @endif

            {{-- ── Type ──────────────────────────────────────────── --}}
            <div class="form-panel">
                <div class="form-panel-title"><i class="bi bi-tags"></i>Type de leçon</div>
                <select name="type" id="select-type" class="form-select" required>
                    @foreach([
                        'vocabulaire' => ['✏️', 'Vocabulaire',  'Fiches de mots bilingues'],
                        'dialogue'    => ['💬', 'Dialogue',     'Conversations simulées'],
                        'grammaire'   => ['📐', 'Grammaire',    'Règles & exercices'],
                        'audio'       => ['🎧', 'Audio',        'Fichier MP3/WAV + exercices'],
                        'lecture'     => ['📖', 'Lecture',      'Texte + compréhension'],
                    ] as $val => [$ico, $lbl, $desc])
                        <option value="{{ $val }}"
                            data-icon="{{ $ico }}" data-label="{{ $lbl }}" data-desc="{{ $desc }}"
                            {{ old('type', $lesson->type ?? 'vocabulaire') === $val ? 'selected' : '' }}>
                            {{ $ico }} {{ $lbl }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-1" id="type-desc-hint">
                    Fiches de mots bilingues
                </small>
            </div>

            {{-- ── Paramètres ────────────────────────────────────── --}}
            <div class="form-panel">
                <div class="form-panel-title"><i class="bi bi-sliders"></i>Paramètres</div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Points de récompense</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-trophy" style="color:var(--or)"></i></span>
                        <input type="number" name="points_recompense" class="form-control" min="0" max="100"
                            value="{{ old('points_recompense', $lesson->points_recompense ?? 10) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Durée estimée (min)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                        <input type="number" name="duree_estimee_minutes" class="form-control" min="1"
                            value="{{ old('duree_estimee_minutes', $lesson->duree_estimee_minutes ?? '') }}">
                    </div>
                </div>
                <div>
                    <label class="form-label fw-semibold small">Ordre d'affichage</label>
                    <input type="number" name="ordre" class="form-control" min="1"
                        value="{{ old('ordre', $lesson->ordre ?? '') }}">
                </div>
            </div>

            {{-- ── Visibilité ────────────────────────────────────── --}}
            <div class="form-panel">
                <div class="form-panel-title"><i class="bi bi-eye"></i>Visibilité</div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 fw-semibold small">Leçon gratuite</p>
                            <p class="mb-0 text-muted" style="font-size:.73rem">Accessible sans abonnement</p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="gratuite" id="ck-gratuite" value="1"
                                {{ old('gratuite', $lesson->gratuite ?? false) ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 fw-semibold small">Publiée</p>
                            <p class="mb-0 text-muted" style="font-size:.73rem">Visible par les étudiants</p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="publiee" id="ck-publiee" value="1"
                                {{ old('publiee', $lesson->publiee ?? true) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Actions ───────────────────────────────────────── --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn fw-bold py-2" style="background:var(--marine);color:#fff">
                    <i class="bi bi-check-lg me-2"></i>
                    {{ isset($lesson) && $lesson->id ? 'Enregistrer les modifications' : 'Créer la leçon' }}
                </button>
                <a href="{{ route(
                    request()->is('admin/*') ? 'admin.cours.lessons.index' : 'instructeur.cours.lessons.index',
                    $cours
                ) }}" class="btn btn-light fw-semibold">
                    Annuler
                </a>
            </div>

        </div>
    </div>
</form>

{{-- Templates JS --}}
<template id="tmpl-mot">
    @include('shared.lessons._mot_row', ['i' => '__IDX__', 'mot' => []])
</template>
<template id="tmpl-exercice">
    @include('shared.lessons._exercice_row', ['i' => '__IDX__', 'ex' => []])
</template>

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════════════
// Config par type : quels onglets afficher + indicateur visuel
// ═══════════════════════════════════════════════════════════════
const TYPE_CONFIG = {
    vocabulaire: {
        showMots: true,  showExercices: true,  showAudio: false,
        indicator: { cls: '', icon: 'bi-alphabet',         text: 'Vocabulaire — Fiches de mots bilingues + exercices QCM' },
    },
    dialogue: {
        showMots: true,  showExercices: true,  showAudio: false,
        indicator: { cls: 'dialogue',  icon: 'bi-chat-dots',         text: 'Dialogue — Mots de conversation + exercices' },
    },
    grammaire: {
        showMots: false, showExercices: true,  showAudio: false,
        indicator: { cls: 'grammaire', icon: 'bi-pencil-square',     text: 'Grammaire — Contenu Markdown + exercices de vérification' },
    },
    audio: {
        showMots: false, showExercices: true,  showAudio: true,
        indicator: { cls: 'audio',    icon: 'bi-headphones',         text: 'Audio — Importez un fichier MP3/WAV + exercices de compréhension' },
    },
    lecture: {
        showMots: false, showExercices: true,  showAudio: false,
        indicator: { cls: 'lecture',  icon: 'bi-book',               text: 'Lecture — Texte Markdown + questions de compréhension' },
    },
};

let motIdx = {{ count(old('mots', $lesson->mots ?? [])) }};
let exIdx  = {{ count(old('exercices', $lesson->exercices ?? [])) }};

// ── Appliquer la configuration d'un type ────────────────────────
function applyType(type, autoSwitchTab = false) {
    const cfg = TYPE_CONFIG[type] ?? TYPE_CONFIG.vocabulaire;

    // 1. Onglet "Audio" : visible uniquement si type = audio
    const audioTab = document.getElementById('tab-nav-audio');
    if (cfg.showAudio) {
        audioTab.style.display = '';
        if (autoSwitchTab) {
            // ✅ Activer automatiquement l'onglet audio
            const audioBtn = audioTab.querySelector('button');
            const tab = new bootstrap.Tab(audioBtn);
            tab.show();
        }
    } else {
        audioTab.style.display = 'none';
        // Si l'onglet audio était actif, revenir à Général
        const audioPane = document.getElementById('tab-audio');
        if (audioPane && audioPane.classList.contains('active')) {
            const generalBtn = document.querySelector('[data-bs-target="#tab-general"]');
            new bootstrap.Tab(generalBtn).show();
        }
        // Masquer l'alerte dans le tab audio
        const warn = document.getElementById('audio-type-warning');
        if (warn) warn.style.setProperty('display', 'none', 'important');
    }

    // 2. Onglet "Vocabulaire" : caché pour grammaire, audio, lecture
    const motsTab = document.getElementById('tab-nav-mots');
    if (cfg.showMots) {
        motsTab.style.display = '';
    } else {
        motsTab.style.display = 'none';
        // Si actif, revenir à Général
        const motsPane = document.getElementById('tab-mots');
        if (motsPane && motsPane.classList.contains('active')) {
            new bootstrap.Tab(document.querySelector('[data-bs-target="#tab-general"]')).show();
        }
    }

    // 3. Indicateur visuel dans le tab Général
    const ind     = document.getElementById('type-indicator');
    const indIcon = document.getElementById('type-indicator-icon');
    const indText = document.getElementById('type-indicator-text');
    if (ind && cfg.indicator.cls) {
        ind.className = `type-indicator visible ${cfg.indicator.cls}`;
        if (indIcon) indIcon.className = `bi ${cfg.indicator.icon}`;
        if (indText) indText.textContent = cfg.indicator.text;
    } else if (ind) {
        ind.className = 'type-indicator';
    }

    // 4. Mettre à jour la description sous le select
    const hint = document.getElementById('type-desc-hint');
    if (hint) {
        const opt = document.querySelector(`#select-type option[value="${type}"]`);
        hint.textContent = opt ? opt.dataset.desc : '';
    }
}

// ── Écouter le changement de type ──────────────────────────────
const selectType = document.getElementById('select-type');
if (selectType) {
    selectType.addEventListener('change', function () {
        applyType(this.value, true);  // ✅ autoSwitchTab = true
    });

    // Initialisation au chargement de la page
    applyType(selectType.value, false);
}

// ── Ajouter un mot ─────────────────────────────────────────────
document.getElementById('btn-ajouter-mot')?.addEventListener('click', () => {
    const html = document.getElementById('tmpl-mot').innerHTML.replaceAll('__IDX__', motIdx++);
    document.getElementById('liste-mots').insertAdjacentHTML('beforeend', html);
    document.getElementById('hint-mots').hidden = true;
    syncBadges();
});

// ── Ajouter un exercice ────────────────────────────────────────
document.getElementById('btn-ajouter-ex')?.addEventListener('click', () => {
    const html = document.getElementById('tmpl-exercice').innerHTML.replaceAll('__IDX__', exIdx++);
    document.getElementById('liste-exercices').insertAdjacentHTML('beforeend', html);
    document.getElementById('hint-ex').hidden = true;
    syncBadges();
});

// ── Délégation : supprimer + toggle QCM ───────────────────────
document.addEventListener('click', e => {
    const rm = e.target.closest('.btn-remove-row');
    if (rm) {
        rm.closest('.card-mot, .card-exercice')?.remove();
        syncBadges();
    }
});

document.addEventListener('change', e => {
    if (e.target.matches('.select-ex-type')) {
        const wrap = e.target.closest('.card-exercice')?.querySelector('.choix-wrap');
        if (wrap) wrap.style.display = e.target.value === 'qcm' ? '' : 'none';
    }
});

function syncBadges() {
    const bm = document.getElementById('badge-mots');
    const be = document.getElementById('badge-ex');
    if (bm) bm.textContent = document.querySelectorAll('#liste-mots .card-mot').length;
    if (be) be.textContent = document.querySelectorAll('#liste-exercices .card-exercice').length;
}

// ── Preview audio live ─────────────────────────────────────────
document.getElementById('audio-file-input')?.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    let preview = document.getElementById('audio-preview');
    if (!preview) {
        preview = document.createElement('audio');
        preview.id = 'audio-preview';
        preview.controls = true;
        preview.style.cssText = 'width:100%;border-radius:40px;margin-top:.5rem';
        this.insertAdjacentElement('afterend', preview);
    }
    preview.src = URL.createObjectURL(file);
    preview.play().catch(() => {});
});
</script>
@endpush