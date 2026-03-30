{{-- resources/views/admin/langues/questions/create.blade.php --}}
{{-- Réutilisée pour edit : $question est optionnel --}}
@extends('layouts.dashboard')
@section('title', isset($question) ? 'Modifier la question' : 'Nouvelle question')

@push('styles')
<style>
/* ─── Layout général ─── */
.vf-card{background:#fff;border-radius:14px;border:1px solid #eee;padding:22px 24px;
         margin-bottom:18px;box-shadow:0 2px 8px rgba(27,58,107,.04);}
.vf-card-title{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:18px;
               display:flex;align-items:center;gap:8px;padding-bottom:12px;
               border-bottom:1.5px solid rgba(27,58,107,.06);}
.vf-label{font-size:11px;font-weight:700;color:#1B3A6B;display:block;
          margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px;}
.vf-input{border:1.5px solid #e8e8e8;border-radius:10px;padding:11px 14px;
          font-size:13px;width:100%;outline:none;transition:all .2s;
          background:#fafafa;color:#333;}
.vf-input:focus{border-color:#F5A623;background:#fff;
                box-shadow:0 0 0 3px rgba(245,166,35,.08);}
.vf-input.is-invalid{border-color:#E24B4A;}
.error-msg{font-size:11px;color:#E24B4A;margin-top:4px;}

/* ─── Type question selector ─── */
.type-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:8px;}
.type-opt{padding:12px 14px;border:1.5px solid #e8e8e8;border-radius:10px;
          cursor:pointer;transition:all .2s;background:#fafafa;display:flex;align-items:center;gap:10px;}
.type-opt:has(input:checked){border-color:#1B3A6B;background:rgba(27,58,107,.05);}
.type-opt input{display:none;}
.type-opt-icon{width:34px;height:34px;border-radius:8px;background:rgba(27,58,107,.08);
               display:flex;align-items:center;justify-content:center;
               font-size:15px;color:#1B3A6B;flex-shrink:0;}
.type-opt-label{font-size:12px;font-weight:700;color:#1B3A6B;}
.type-opt-sub{font-size:11px;color:#888;margin-top:1px;}

/* ─── Zone upload ─── */
.upload-zone{border:2px dashed #ddd;border-radius:12px;padding:22px;text-align:center;
             cursor:pointer;transition:all .2s;background:#fafafa;position:relative;}
.upload-zone:hover,.upload-zone.dragover{border-color:#F5A623;background:rgba(245,166,35,.04);}
.upload-zone input[type=file]{display:none;}
.upload-icon{font-size:28px;color:#ddd;margin-bottom:8px;}
.upload-title{font-size:13px;font-weight:600;color:#666;margin-bottom:3px;}
.upload-hint{font-size:11px;color:#aaa;}

/* Preview médias */
.img-preview-wrap{position:relative;display:inline-block;margin-top:10px;}
.img-preview{max-height:180px;max-width:100%;border-radius:10px;
             border:1px solid #eee;object-fit:contain;}
.del-media-btn{position:absolute;top:-8px;right:-8px;width:24px;height:24px;
               border-radius:50%;background:#E24B4A;border:2px solid #fff;
               color:#fff;font-size:11px;cursor:pointer;display:flex;
               align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.2);}
.audio-preview-wrap{margin-top:10px;padding:12px;background:#f8f9fb;
                    border-radius:10px;border:1px solid #eee;}

/* ─── Réponses ─── */
.rep-item{display:flex;align-items:center;gap:10px;padding:11px 14px;
          background:#f8f9fb;border-radius:10px;border:1.5px solid #eee;
          margin-bottom:8px;transition:all .2s;}
.rep-item:has(.rep-radio:checked){background:rgba(28,200,138,.05);
                                   border-color:rgba(28,200,138,.4);}
.rep-radio{width:18px;height:18px;accent-color:#1cc88a;flex-shrink:0;cursor:pointer;}
.rep-input{border:none;background:transparent;font-size:13px;flex:1;
           outline:none;color:#333;padding:2px 0;}
.rep-input::placeholder{color:#ccc;}
.rep-del{width:26px;height:26px;border-radius:7px;border:1px solid #eee;
         background:#fff;color:#E24B4A;font-size:11px;cursor:pointer;
         display:flex;align-items:center;justify-content:center;flex-shrink:0;
         transition:all .15s;}
.rep-del:hover{background:#E24B4A;color:#fff;border-color:#E24B4A;}
.btn-add-rep{width:100%;padding:10px;border:1.5px dashed #ddd;border-radius:10px;
             background:none;font-size:12px;color:#888;cursor:pointer;
             display:flex;align-items:center;justify-content:center;gap:6px;
             transition:all .2s;}
.btn-add-rep:hover{border-color:#1B3A6B;color:#1B3A6B;}

/* ─── Bouton save ─── */
.btn-save{background:#1B3A6B;color:#fff;border:none;border-radius:25px;
          padding:12px 30px;font-size:13px;font-weight:700;cursor:pointer;
          transition:all .2s;display:inline-flex;align-items:center;gap:8px;}
.btn-save:hover{background:#152d54;transform:translateY(-1px);}

/* Sticky sidebar */
.sticky-sidebar{position:sticky;top:80px;}
</style>
@endpush

@section('content')

@php
  $isEdit   = isset($question);
  $serie     = $isEdit ? $question->serie : $serie;
  $disc      = $serie->discipline;
  $langue    = $disc->langue;
  $action    = $isEdit
      ? route('admin.questions.update', $question)
      : route('admin.questions.store', $serie);

  // Réponses existantes ou par défaut
  $reponses = $isEdit
      ? $question->reponses->toArray()
      : [
          ['id'=>null,'texte'=>'','correcte'=>true,  'ordre'=>0],
          ['id'=>null,'texte'=>'','correcte'=>false, 'ordre'=>1],
          ['id'=>null,'texte'=>'','correcte'=>false, 'ordre'=>2],
          ['id'=>null,'texte'=>'','correcte'=>false, 'ordre'=>3],
        ];
  $correcteIdx = collect($reponses)->search(fn($r) => (bool)($r['correcte'] ?? false));
@endphp

{{-- Fil d'Ariane --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.series.show', $serie) }}"
       style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;
              color:#1B3A6B;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:11px;color:#888;margin-bottom:2px;">
            <span style="color:{{ $langue->couleur }};font-weight:700;">{{ $langue->nom }}</span>
            <span style="margin:0 5px;">·</span>{{ $disc->nom }}
            <span style="margin:0 5px;">·</span>{{ $serie->titre }}
        </div>
        <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.2rem;">
            {{ $isEdit ? 'Modifier la question' : 'Nouvelle question' }}
        </h2>
    </div>
</div>

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="questionForm">
    @csrf
    @if($isEdit) @method('PUT') @endif

<div class="row g-4">
{{-- ══ COLONNE PRINCIPALE ══ --}}
<div class="col-lg-8">

    {{-- ── 1. Type de question ── --}}
    <div class="vf-card">
        <div class="vf-card-title">
            <i class="bi bi-ui-radios-grid" style="color:#F5A623;"></i>
            Type de question *
        </div>
        <div class="type-grid">
            @foreach([
                'qcm'         => ['bi-ui-radios',       'QCM',             'Choix multiple avec 4 options'],
                'vrai_faux'   => ['bi-toggles',          'Vrai / Faux',     'Deux options uniquement'],
                'texte_libre' => ['bi-pencil-square',    'Réponse libre',   'L\'étudiant saisit sa réponse'],
                'audio'       => ['bi-mic',              'Réponse audio',   'Réponse enregistrée'],
            ] as $val => [$icon, $label, $sub])
            <label class="type-opt">
                <input type="radio" name="type_question" value="{{ $val }}"
                       {{ old('type_question', $isEdit ? $question->type_question : 'qcm') == $val ? 'checked' : '' }}
                       onchange="onTypeChange()">
                <div class="type-opt-icon"><i class="bi {{ $icon }}"></i></div>
                <div>
                    <div class="type-opt-label">{{ $label }}</div>
                    <div class="type-opt-sub">{{ $sub }}</div>
                </div>
            </label>
            @endforeach
        </div>
        @error('type_question')<div class="error-msg">{{ $message }}</div>@enderror
    </div>

    {{-- ── 2. Médias (Image + Audio) ── --}}
    @if($disc->has_image || $disc->has_audio)
    <div class="vf-card">
        <div class="vf-card-title">
            <i class="bi bi-paperclip" style="color:#F5A623;"></i>
            Médias associés
        </div>
        <div class="row g-3">

            @if($disc->has_image)
            <div class="col-md-6">
                <label class="vf-label">Image</label>

                {{-- Prévisualisation existante --}}
                @if($isEdit && $question->image)
                <div class="img-preview-wrap" id="existingImgWrap">
                    <img src="{{ asset('storage/'.$question->image) }}" class="img-preview">
                    <button type="button" class="del-media-btn"
                            onclick="deleteExistingMedia('image')">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <input type="hidden" name="delete_image" id="deleteImageInput" value="">
                @endif

                <div class="upload-zone mt-2" id="imageZone"
                     onclick="document.getElementById('imageFile').click()"
                     ondragover="dragOver(event,this)"
                     ondragleave="dragLeave(this)"
                     ondrop="dropFile(event,'imageFile',this)">
                    <div class="upload-icon"><i class="bi bi-cloud-upload"></i></div>
                    <div class="upload-title">Cliquer ou glisser une image</div>
                    <div class="upload-hint">JPG, PNG, WEBP — Max 5 Mo</div>
                    <input type="file" id="imageFile" name="image" accept="image/*"
                           onchange="previewImage(this)">
                </div>
                <div id="newImgPreview" style="display:none;margin-top:8px;">
                    <div class="img-preview-wrap">
                        <img id="newImgPreviewEl" class="img-preview">
                        <button type="button" class="del-media-btn"
                                onclick="clearNewMedia('image')">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
                @error('image')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            @endif

            @if($disc->has_audio)
            <div class="col-md-6">
                <label class="vf-label">Audio</label>

                {{-- Audio existant --}}
                @if($isEdit && $question->audio)
                <div class="audio-preview-wrap" id="existingAudioWrap">
                    <audio controls style="width:100%;">
                        <source src="{{ asset('storage/'.$question->audio) }}">
                    </audio>
                    <button type="button"
                            onclick="deleteExistingMedia('audio')"
                            style="font-size:11px;color:#E24B4A;background:none;border:none;
                                   cursor:pointer;display:flex;align-items:center;gap:4px;
                                   margin-top:6px;">
                        <i class="bi bi-trash"></i>Supprimer l'audio
                    </button>
                </div>
                <input type="hidden" name="delete_audio" id="deleteAudioInput" value="">
                @endif

                <div class="upload-zone mt-2" id="audioZone"
                     onclick="document.getElementById('audioFile').click()"
                     ondragover="dragOver(event,this)"
                     ondragleave="dragLeave(this)"
                     ondrop="dropFile(event,'audioFile',this)">
                    <div class="upload-icon"><i class="bi bi-music-note-beamed"></i></div>
                    <div class="upload-title">Cliquer pour ajouter un audio</div>
                    <div class="upload-hint">MP3, WAV, OGG, M4A — Max 20 Mo</div>
                    <input type="file" id="audioFile" name="audio" accept="audio/*"
                           onchange="previewAudio(this)">
                </div>
                <div id="newAudioPreview" style="display:none;margin-top:8px;">
                    <div class="audio-preview-wrap">
                        <audio id="newAudioEl" controls style="width:100%;"></audio>
                        <button type="button"
                                onclick="clearNewMedia('audio')"
                                style="font-size:11px;color:#E24B4A;background:none;border:none;
                                       cursor:pointer;display:flex;align-items:center;gap:4px;
                                       margin-top:6px;">
                            <i class="bi bi-trash"></i>Retirer
                        </button>
                    </div>
                </div>
                @error('audio')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            @endif

        </div>
    </div>
    @endif

    {{-- ── 3. Contenu de la question ── --}}
    <div class="vf-card">
        <div class="vf-card-title">
            <i class="bi bi-question-circle" style="color:#F5A623;"></i>
            Contenu de la question
        </div>

        <div class="mb-3">
            <label class="vf-label">Texte de contexte / Transcript</label>
            <textarea name="contexte" class="vf-input" rows="3"
                      placeholder="Texte de l'extrait, transcript audio, consigne spécifique... (facultatif)">{{ old('contexte', $isEdit ? $question->contexte : '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="vf-label">Énoncé de la question *</label>
            <textarea name="enonce"
                      class="vf-input @error('enonce') is-invalid @enderror" rows="3"
                      placeholder="Saisissez la question...">{{ old('enonce', $isEdit ? $question->enonce : '') }}</textarea>
            @error('enonce')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="vf-label">Points *</label>
                <input type="number" name="points"
                       class="vf-input @error('points') is-invalid @enderror"
                       value="{{ old('points', $isEdit ? $question->points : 1) }}"
                       min="1" max="10">
            </div>
            <div class="col-md-4">
                <label class="vf-label">Durée (secondes) *</label>
                <input type="number" name="duree_secondes"
                       class="vf-input @error('duree_secondes') is-invalid @enderror"
                       value="{{ old('duree_secondes', $isEdit ? $question->duree_secondes : 60) }}"
                       min="10" max="600">
            </div>
        </div>
    </div>

    {{-- ── 4. Réponses (QCM / Vrai-Faux) ── --}}
    <div class="vf-card" id="reponsesCard">
        <div class="vf-card-title">
            <i class="bi bi-ui-checks" style="color:#F5A623;"></i>
            Réponses
            <span style="font-size:11px;color:#888;font-weight:400;margin-left:4px;">
                — Sélectionnez la bonne réponse (radio)
            </span>
        </div>

        <div id="reponsesList">
            @foreach($reponses as $i => $rep)
            <div class="rep-item" id="rep-{{ $i }}">
                <input type="radio" name="correcte" value="{{ $i }}"
                       class="rep-radio"
                       {{ ($correcteIdx === $i) ? 'checked' : '' }}>
                <input type="text" name="reponses[{{ $i }}][texte]"
                       class="rep-input"
                       value="{{ old('reponses.'.$i.'.texte', $rep['texte'] ?? '') }}"
                       placeholder="Option {{ chr(65+$i) }}...">
                <button type="button" class="rep-del"
                        onclick="removeRep({{ $i }})" title="Supprimer">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn-add-rep" onclick="addRep()">
            <i class="bi bi-plus-circle"></i>Ajouter une option
        </button>
    </div>

    {{-- ── 5. Explication ── --}}
    <div class="vf-card">
        <div class="vf-card-title">
            <i class="bi bi-lightbulb" style="color:#F5A623;"></i>
            Explication de la bonne réponse (facultatif)
        </div>
        <textarea name="explication" class="vf-input" rows="2"
                  placeholder="Explication affichée à l'étudiant après sa réponse...">{{ old('explication', $isEdit ? $question->explication : '') }}</textarea>
    </div>

</div>

{{-- ══ COLONNE DROITE (sticky) ══ --}}
<div class="col-lg-4">
<div class="sticky-sidebar">

    {{-- Bouton save --}}
    <div style="margin-bottom:14px;">
        <button type="submit" class="btn-save w-100">
            <i class="bi bi-check-circle-fill"></i>
            {{ $isEdit ? 'Enregistrer' : 'Ajouter la question' }}
        </button>
        <a href="{{ route('admin.series.show', $serie) }}"
           style="display:block;text-align:center;margin-top:10px;font-size:13px;
                  color:#888;text-decoration:none;">
            Annuler
        </a>
    </div>

    {{-- Aide contextuelle --}}
    <div style="background:rgba(27,58,107,.04);border:1px solid rgba(27,58,107,.08);
                border-radius:12px;padding:18px;">
        <div style="font-size:12px;font-weight:700;color:#1B3A6B;margin-bottom:12px;">
            <i class="bi bi-info-circle me-2" style="color:#F5A623;"></i>Aide
        </div>
        <div style="font-size:12px;color:#666;line-height:1.7;display:flex;flex-direction:column;gap:8px;">
            <div>
                <strong style="color:#1B3A6B;">Radio (●)</strong>
                = bonne réponse. Cliquez sur le rond pour la sélectionner.
            </div>
            <div>
                <strong style="color:#1B3A6B;">Image</strong> :
                affichée au-dessus de la question pendant l'épreuve.
            </div>
            <div>
                <strong style="color:#1B3A6B;">Audio</strong> :
                joué automatiquement au chargement de la question.
            </div>
            <div>
                <strong style="color:#1B3A6B;">Contexte</strong> :
                texte/transcript visible en grisé avant l'énoncé.
            </div>
        </div>

        <div style="margin-top:14px;padding-top:12px;border-top:1px solid rgba(27,58,107,.08);">
            <div style="font-size:11px;color:#aaa;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
                Discipline
            </div>
            @foreach([
                ['bi-translate',  'Examen',     $langue->nom],
                ['bi-collection', 'Discipline', $disc->nom],
                ['bi-collection', 'Série',      $serie->titre],
                ['bi-headphones', 'Audio',      $disc->has_audio ? 'Supporté' : 'Non'],
                ['bi-image',      'Image',      $disc->has_image ? 'Supportée' : 'Non'],
            ] as [$icon, $label, $val])
            <div style="display:flex;justify-content:space-between;font-size:12px;
                        padding:4px 0;border-bottom:1px solid rgba(27,58,107,.04);">
                <span style="color:#888;"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</span>
                <span style="color:#1B3A6B;font-weight:600;">{{ $val }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>
</div>
</div>
</form>

@push('scripts')
<script>
let repCount = {{ count($reponses) }};

// ─── Type question → afficher/masquer les réponses ───
function onTypeChange() {
    const type = document.querySelector('[name="type_question"]:checked')?.value;
    const card  = document.getElementById('reponsesCard');

    if (type === 'vrai_faux') {
        card.style.display = 'block';
        document.getElementById('reponsesList').innerHTML = `
            <div class="rep-item" id="rep-0">
                <input type="radio" name="correcte" value="0" class="rep-radio" checked>
                <input type="text" name="reponses[0][texte]" class="rep-input" value="Vrai" readonly>
                <button type="button" class="rep-del" disabled style="opacity:.3;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="rep-item" id="rep-1">
                <input type="radio" name="correcte" value="1" class="rep-radio">
                <input type="text" name="reponses[1][texte]" class="rep-input" value="Faux" readonly>
                <button type="button" class="rep-del" disabled style="opacity:.3;">
                    <i class="bi bi-x"></i>
                </button>
            </div>`;
        repCount = 2;
    } else if (type === 'qcm') {
        card.style.display = 'block';
    } else {
        card.style.display = 'none';
    }
}

// ─── Ajouter une option ───
function addRep() {
    const i   = repCount++;
    const div = document.createElement('div');
    div.className = 'rep-item';
    div.id = 'rep-' + i;
    div.innerHTML = `
        <input type="radio" name="correcte" value="${i}" class="rep-radio">
        <input type="text" name="reponses[${i}][texte]" class="rep-input"
               placeholder="Option ${String.fromCharCode(65+i)}...">
        <button type="button" class="rep-del" onclick="removeRep(${i})">
            <i class="bi bi-x"></i>
        </button>`;
    document.getElementById('reponsesList').appendChild(div);
}

// ─── Supprimer une option ───
function removeRep(i) {
    const items = document.querySelectorAll('.rep-item');
    if (items.length <= 2) { alert('Il faut au moins 2 réponses.'); return; }
    document.getElementById('rep-' + i)?.remove();
}

// ─── Preview image ───
function previewImage(input) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('newImgPreview').style.display = 'block';
        document.getElementById('newImgPreviewEl').src = e.target.result;
        document.getElementById('imageZone').style.borderColor = '#1cc88a';
    };
    reader.readAsDataURL(input.files[0]);
}

// ─── Preview audio ───
function previewAudio(input) {
    if (!input.files[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    document.getElementById('newAudioPreview').style.display = 'block';
    document.getElementById('newAudioEl').src = url;
    document.getElementById('audioZone').style.borderColor = '#1cc88a';
}

// ─── Supprimer media existant ───
function deleteExistingMedia(type) {
    if (type === 'image') {
        document.getElementById('deleteImageInput').value = '1';
        document.getElementById('existingImgWrap')?.remove();
    } else {
        document.getElementById('deleteAudioInput').value = '1';
        document.getElementById('existingAudioWrap')?.remove();
    }
}

// ─── Effacer nouveau media ───
function clearNewMedia(type) {
    if (type === 'image') {
        document.getElementById('newImgPreview').style.display = 'none';
        document.getElementById('imageFile').value = '';
        document.getElementById('imageZone').style.borderColor = '#ddd';
    } else {
        document.getElementById('newAudioPreview').style.display = 'none';
        document.getElementById('audioFile').value = '';
        document.getElementById('audioZone').style.borderColor = '#ddd';
    }
}

// ─── Drag & Drop ───
function dragOver(e, zone) { e.preventDefault(); zone.classList.add('dragover'); }
function dragLeave(zone)    { zone.classList.remove('dragover'); }
function dropFile(e, inputId, zone) {
    e.preventDefault(); zone.classList.remove('dragover');
    const input = document.getElementById(inputId);
    const dt    = new DataTransfer();
    dt.items.add(e.dataTransfer.files[0]);
    input.files = dt.files;
    input.dispatchEvent(new Event('change'));
}

// Init
onTypeChange();
</script>
@endpush

@endsection