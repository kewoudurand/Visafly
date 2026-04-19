{{-- resources/views/shared/lessons/_form_lecon.blade.php
     Variables attendues :
       $cours                → instance Course
       $lesson               → instance Lesson (édition) ou null (création)
       $routeAction          → URL d'action du formulaire
       $method               → 'POST' ou 'PUT'
       $showInstructeurSelect → bool — true = admin (peut changer l'instructeur)
       $instructeurs         → collection User — requis si $showInstructeurSelect = true
--}}
@push('styles')
<style>
    .card-mot, .card-exercice {
        border-left: 4px solid #1B3A6B;
        background: #f8f9fa;
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.1rem;
        margin-bottom: .65rem;
        position: relative;
        transition: box-shadow .15s;
    }
    .card-mot:hover, .card-exercice:hover { box-shadow: 0 3px 10px rgba(27,58,107,.08); }
    .card-exercice { border-left-color: #F5A623; }
    .btn-remove-row {
        position: absolute; top: .6rem; right: .6rem;
        width: 26px; height: 26px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; padding: 0; border: none;
        background: #fee2e2; color: #dc3545;
        transition: background .15s;
    }
    .btn-remove-row:hover { background: #dc3545; color: #fff; }
    .form-panel {
        background: #fff; border-radius: 14px; padding: 1.5rem;
        box-shadow: 0 1px 8px rgba(27,58,107,.07); margin-bottom: 1.5rem;
    }
    .form-panel-title {
        font-size: .78rem; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: #1B3A6B;
        margin-bottom: 1.1rem; padding-bottom: .5rem;
        border-bottom: 2px solid #f0f4ff;
        display: flex; align-items: center; gap: .45rem;
    }
    .tab-nav .nav-link {
        border: none; border-radius: 8px; color: #6c757d;
        font-weight: 600; font-size: .85rem; padding: .5rem 1rem;
    }
    .tab-nav .nav-link.active { background: #1B3A6B; color: #fff; }
    .tab-nav .nav-link:hover:not(.active) { background: #f0f4ff; color: #1B3A6B; }
    .type-only-audio { display: none; }
    #audio-preview { width: 100%; border-radius: 40px; margin-top: .5rem; }
</style>
@endpush

<form action="{{ $routeAction }}" method="POST" enctype="multipart/form-data" id="form-lecon">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    <div class="row g-4">

        {{-- ════════════ COLONNE GAUCHE (75%) ════════════ --}}
        <div class="col-lg-8">

            {{-- ── Onglets ───────────────────────────────── --}}
            <ul class="nav tab-nav gap-1 mb-3" id="lessonTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-target="#tab-general"   data-bs-toggle="tab">Général</button></li>
                <li class="nav-item"><button class="nav-link"        data-bs-target="#tab-mots"      data-bs-toggle="tab">
                    Vocabulaire <span class="badge ms-1" style="background:#1cc88a" id="badge-mots">{{ count($lesson->mots ?? []) }}</span>
                </button></li>
                <li class="nav-item"><button class="nav-link"        data-bs-target="#tab-exercices" data-bs-toggle="tab">
                    Exercices <span class="badge ms-1" style="background:#F5A623;color:#000" id="badge-ex">{{ count($lesson->exercices ?? []) }}</span>
                </button></li>
                <li class="nav-item type-only-audio" id="tab-nav-audio">
                    <button class="nav-link" data-bs-target="#tab-audio" data-bs-toggle="tab">Audio</button>
                </li>
            </ul>

            <div class="tab-content">

                {{-- ── TAB GÉNÉRAL ── --}}
                <div class="tab-pane fade show active" id="tab-general">
                    <div class="form-panel">
                        <div class="form-panel-title"><i class="bi bi-file-text"></i>Informations</div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror"
                                value="{{ old('titre', $lesson->titre ?? '') }}"
                                placeholder="ex : Les salutations — Grüße" required>
                            @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Contenu de la leçon <span class="text-muted fw-normal">(Markdown)</span></label>
                            <textarea name="contenu" class="form-control font-monospace" rows="9"
                                placeholder="# Titre de la leçon&#10;&#10;Expliquez le contenu...&#10;&#10;**Gras**, *italique*, > citation, ## sous-titre">{{ old('contenu', $lesson->contenu ?? '') }}</textarea>
                            <small class="text-muted">Supporte le Markdown complet.</small>
                        </div>
                    </div>
                </div>

                {{-- ── TAB VOCABULAIRE ── --}}
                <div class="tab-pane fade" id="tab-mots">
                    <div class="form-panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-panel-title mb-0"><i class="bi bi-alphabet"></i>Mots de vocabulaire</div>
                            <button type="button" class="btn btn-sm fw-bold" id="btn-ajouter-mot"
                                    style="background:#e8f8f0;color:#198754;border:none">
                                <i class="bi bi-plus-lg me-1"></i>Ajouter
                            </button>
                        </div>
                        <div id="liste-mots">
                            @foreach(old('mots', $lesson->mots ?? []) as $i => $mot)
                                @include('shared.lessons._mot_row', ['i' => $i, 'mot' => $mot])
                            @endforeach
                        </div>
                        <p class="text-muted small text-center py-3" id="hint-mots"
                           @if(count(old('mots', $lesson->mots ?? []))) hidden @endif>
                            <i class="bi bi-info-circle me-1"></i>Aucun mot. Cliquez sur "Ajouter".
                        </p>
                    </div>
                </div>

                {{-- ── TAB EXERCICES ── --}}
                <div class="tab-pane fade" id="tab-exercices">
                    <div class="form-panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-panel-title mb-0"><i class="bi bi-pencil-square"></i>Exercices</div>
                            <button type="button" class="btn btn-sm fw-bold" id="btn-ajouter-ex"
                                    style="background:#fff8e8;color:#856404;border:none">
                                <i class="bi bi-plus-lg me-1"></i>Ajouter
                            </button>
                        </div>
                        <div id="liste-exercices">
                            @foreach(old('exercices', $lesson->exercices ?? []) as $i => $ex)
                                @include('shared.lessons._exercice_row', ['i' => $i, 'ex' => $ex])
                            @endforeach
                        </div>
                        <p class="text-muted small text-center py-3" id="hint-ex"
                           @if(count(old('exercices', $lesson->exercices ?? []))) hidden @endif>
                            <i class="bi bi-info-circle me-1"></i>Aucun exercice. Cliquez sur "Ajouter".
                        </p>
                    </div>
                </div>

                {{-- ── TAB AUDIO ── --}}
                <div class="tab-pane fade" id="tab-audio">
                    <div class="form-panel">
                        <div class="form-panel-title"><i class="bi bi-headphones"></i>Fichier Audio</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Téléverser un fichier</label>
                                <input type="file" name="fichier_audio" class="form-control"
                                       accept=".mp3,.wav,.ogg,.m4a" id="audio-file-input">
                                <small class="text-muted">MP3, WAV, OGG, M4A — max 50 Mo</small>
                                @if(isset($lesson) && $lesson->fichier_audio)
                                    <audio controls id="audio-preview" class="mt-2">
                                        <source src="{{ $lesson->urlAudio() }}">
                                    </audio>
                                    <p class="text-muted small mt-1">
                                        <i class="bi bi-file-earmark-music me-1"></i>
                                        Fichier actuel : {{ basename($lesson->fichier_audio) }}
                                    </p>
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

        {{-- ════════════ COLONNE DROITE (25%) ════════════ --}}
        <div class="col-lg-4">

            {{-- Instructeur — admin seulement --}}
            @if(!empty($showInstructeurSelect) && !empty($instructeurs))
            <div class="form-panel">
                <div class="form-panel-title"><i class="bi bi-person-badge-fill"></i>Instructeur</div>
                <select name="instructeur_id" class="form-select @error('instructeur_id') is-invalid @enderror">
                    <option value="">— Hériter du cours —</option>
                    @foreach($instructeurs as $inst)
                        <option value="{{ $inst->id }}"
                            {{ old('instructeur_id', $lesson->instructeur_id ?? $cours->instructeur_id ?? '') == $inst->id ? 'selected' : '' }}>
                            {{ $inst->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Par défaut : l'instructeur du cours.</small>
                @error('instructeur_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            @endif

            {{-- Type --}}
            <div class="form-panel">
                <div class="form-panel-title"><i class="bi bi-tags"></i>Type de leçon</div>
                <select name="type" id="select-type" class="form-select mb-0" required>
                    @foreach(['vocabulaire' => ['✏️','Vocabulaire'], 'dialogue' => ['💬','Dialogue'], 'grammaire' => ['📐','Grammaire'], 'audio' => ['🎧','Audio'], 'lecture' => ['📖','Lecture']] as $val => [$icon, $label])
                        <option value="{{ $val }}" {{ old('type', $lesson->type ?? 'vocabulaire') === $val ? 'selected' : '' }}>
                            {{ $icon }} {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Paramètres --}}
            <div class="form-panel">
                <div class="form-panel-title"><i class="bi bi-sliders"></i>Paramètres</div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Points de récompense</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-trophy" style="color:#F5A623"></i></span>
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
                <div class="mb-0">
                    <label class="form-label fw-semibold small">Ordre d'affichage</label>
                    <input type="number" name="ordre" class="form-control" min="1"
                        value="{{ old('ordre', $lesson->ordre ?? '') }}">
                </div>
            </div>

            {{-- Visibilité --}}
            <div class="form-panel">
                <div class="form-panel-title"><i class="bi bi-eye"></i>Visibilité</div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 fw-semibold small">Leçon gratuite</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem">Accessible sans abonnement</p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="gratuite" id="ck-gratuite" value="1"
                                {{ old('gratuite', $lesson->gratuite ?? false) ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 fw-semibold small">Publiée</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem">Visible par les étudiants</p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="publiee" id="ck-publiee" value="1"
                                {{ old('publiee', $lesson->publiee ?? true) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn fw-bold py-2" style="background:#1B3A6B;color:#fff">
                    <i class="bi bi-check-lg me-2"></i>
                    {{ isset($lesson) ? 'Enregistrer les modifications' : 'Créer la leçon' }}
                </button>
                <a href="{{ route(request()->is('admin/*') ? 'admin.cours.lessons.index' : 'instructeur.cours.lessons.index', $cours) }}"
                   class="btn btn-light fw-semibold">
                    Annuler
                </a>
            </div>

        </div>
    </div>
</form>

{{-- Templates JS pour l'ajout dynamique de lignes --}}
<template id="tmpl-mot">
    @include('shared.lessons._mot_row', ['i' => '__IDX__', 'mot' => []])
</template>
<template id="tmpl-exercice">
    @include('shared.lessons._exercice_row', ['i' => '__IDX__', 'ex' => []])
</template>

@push('scripts')
<script>
let motIdx = {{ count(old('mots', $lesson->mots ?? [])) }};
let exIdx  = {{ count(old('exercices', $lesson->exercices ?? [])) }};

// Type audio toggle
document.getElementById('select-type').addEventListener('change', function () {
    const isAudio = this.value === 'audio';
    document.querySelectorAll('.type-only-audio').forEach(el => {
        el.style.display = isAudio ? '' : 'none';
    });
});
// Init au chargement
if (document.getElementById('select-type').value === 'audio') {
    document.querySelectorAll('.type-only-audio').forEach(el => el.style.display = '');
}

// Ajouter mot
document.getElementById('btn-ajouter-mot').addEventListener('click', () => {
    const html = document.getElementById('tmpl-mot').innerHTML.replaceAll('__IDX__', motIdx++);
    document.getElementById('liste-mots').insertAdjacentHTML('beforeend', html);
    document.getElementById('hint-mots').hidden = true;
    syncBadges();
});

// Ajouter exercice
document.getElementById('btn-ajouter-ex').addEventListener('click', () => {
    const html = document.getElementById('tmpl-exercice').innerHTML.replaceAll('__IDX__', exIdx++);
    document.getElementById('liste-exercices').insertAdjacentHTML('beforeend', html);
    document.getElementById('hint-ex').hidden = true;
    syncBadges();
});

// Délégation : supprimer + toggle QCM
document.addEventListener('click', e => {
    const removeBtn = e.target.closest('.btn-remove-row');
    if (removeBtn) {
        removeBtn.closest('.card-mot, .card-exercice')?.remove();
        syncBadges();
    }
});

document.addEventListener('change', e => {
    if (e.target.matches('.select-ex-type')) {
        const choixWrap = e.target.closest('.card-exercice')?.querySelector('.choix-wrap');
        if (choixWrap) choixWrap.style.display = e.target.value === 'qcm' ? '' : 'none';
    }
});

function syncBadges() {
    document.getElementById('badge-mots').textContent = document.querySelectorAll('.card-mot').length;
    document.getElementById('badge-ex').textContent   = document.querySelectorAll('.card-exercice').length;
}

// Preview audio
document.getElementById('audio-file-input')?.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    let preview = document.getElementById('audio-preview');
    if (!preview) {
        preview = document.createElement('audio');
        preview.id = 'audio-preview'; preview.controls = true;
        this.insertAdjacentElement('afterend', preview);
    }
    preview.src = URL.createObjectURL(file);
});
</script>
@endpush