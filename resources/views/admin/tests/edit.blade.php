@extends('layouts.dashboard')
@section('title', 'Modifier le test — VisaFly')

@push('styles')
<style>
.form-card{background:#fff;border-radius:16px;border:1px solid #eee;padding:26px;
           box-shadow:0 2px 12px rgba(27,58,107,.05);margin-bottom:24px;}
.form-label-custom{font-size:12px;font-weight:700;color:#1B3A6B;margin-bottom:6px;
                    text-transform:uppercase;letter-spacing:.4px;}
.form-control-custom{border:1.5px solid #eee;border-radius:10px;padding:10px 14px;
                      font-size:14px;width:100%;}
.switch-row{display:flex;align-items:center;gap:10px;margin-bottom:14px;}

.question-card{background:#fff;border-radius:14px;border:1px solid #eee;padding:18px 20px;
               margin-bottom:14px;}
.question-num{width:26px;height:26px;border-radius:50%;background:#1B3A6B;color:#fff;
              display:inline-flex;align-items:center;justify-content:center;
              font-size:12px;font-weight:700;margin-right:8px;}
.reponse-row{display:flex;align-items:center;gap:10px;padding:8px 12px;
             border:1.5px solid #eee;border-radius:10px;margin-bottom:8px;}
.reponse-row.correcte{border-color:#1cc88a;background:rgba(28,200,138,.04);}
.btn-icon{border:none;background:transparent;color:#888;font-size:15px;padding:4px 8px;}
.btn-icon:hover{color:#1B3A6B;}
.add-reponse-btn{font-size:12px;color:#1B3A6B;font-weight:600;border:1.5px dashed #ccc;
                  border-radius:8px;padding:6px 12px;background:transparent;cursor:pointer;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.tests.index') }}" style="color:#888;font-size:20px;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">{{ $serie->titre }}</h2>
        <p class="text-muted mb-0" style="font-size:13px;">
            {{ $serie->discipline?->langue?->nom }} · {{ $serie->discipline?->nom }}
        </p>
    </div>
</div>

@if(session('success'))
<div class="alert rounded-3 mb-3" style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

{{-- ══ Métadonnées du test ══ --}}
<div class="form-card">
    <h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:16px;">
        <i class="bi bi-gear me-2" style="color:#F5A623;"></i>Informations générales
    </h3>
    <form method="POST" action="{{ route('admin.tests.update', $serie) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <div class="form-label-custom">Langue</div>
            <select id="langue-select" class="form-control-custom" required>
                @foreach($langues as $l)
                <option value="{{ $l->id }}" {{ $serie->discipline?->langue_id === $l->id ? 'selected' : '' }}>
                    {{ $l->nom }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <div class="form-label-custom">Discipline</div>
            <select name="discipline_id" id="discipline-select" class="form-control-custom" required>
                @foreach($serie->discipline?->langue?->disciplines ?? [] as $d)
                <option value="{{ $d->id }}" {{ $serie->discipline_id === $d->id ? 'selected' : '' }}>
                    {{ $d->nom }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <div class="form-label-custom">Titre du test</div>
            <input type="text" name="titre" class="form-control-custom" required value="{{ $serie->titre }}">
        </div>

        <div class="mb-3">
            <div class="form-label-custom">Description</div>
            <textarea name="description" class="form-control-custom" rows="3">{{ $serie->description }}</textarea>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="form-label-custom">Niveau (1-5)</div>
                <input type="number" name="niveau" class="form-control-custom" min="1" max="5" value="{{ $serie->niveau }}" required>
            </div>
            <div class="col-md-4">
                <div class="form-label-custom">Durée (minutes)</div>
                <input type="number" name="duree_minutes" class="form-control-custom" min="5" max="240" value="{{ $serie->duree_minutes }}" required>
            </div>
            <div class="col-md-4">
                <div class="form-label-custom">Ordre</div>
                <input type="number" name="ordre" class="form-control-custom" min="0" value="{{ $serie->ordre }}">
            </div>
        </div>

        <div class="switch-row">
            <input type="checkbox" name="gratuite" id="gratuite" value="1" {{ $serie->gratuite ? 'checked' : '' }}>
            <label for="gratuite" style="font-size:13px;color:#444;">Test gratuit</label>
        </div>
        <div class="switch-row">
            <input type="checkbox" name="active" id="active" value="1" {{ $serie->active ? 'checked' : '' }}>
            <label for="active" style="font-size:13px;color:#444;">Test actif</label>
        </div>

        <button type="submit" style="background:#1B3A6B;color:#fff;padding:10px 22px;
                border-radius:20px;border:none;font-size:13px;font-weight:700;">
            <i class="bi bi-check-lg me-1"></i>Enregistrer les informations
        </button>
    </form>
</div>

{{-- ══ Questions existantes ══ --}}
<h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:14px;">
    <i class="bi bi-list-ol me-2" style="color:#F5A623;"></i>
    Questions ({{ $serie->questions->count() }})
</h3>

@foreach($serie->questions as $i => $question)
<div class="question-card">
    <form method="POST" action="{{ route('admin.tests.questions.update', $question) }}">
        @csrf
        @method('PUT')

        <div class="d-flex align-items-center mb-2">
            <span class="question-num">{{ $i + 1 }}</span>
            <span style="font-size:12px;color:#888;">{{ $question->points }} point(s) · {{ $question->duree_secondes }}s</span>
            <button type="button" class="btn-icon ms-auto"
                    onclick="document.getElementById('edit-q-{{ $question->id }}').classList.toggle('d-none')">
                <i class="bi bi-pencil-square"></i>
            </button>
            <form method="POST" action="{{ route('admin.tests.questions.destroy', $question) }}"
                  onsubmit="return confirm('Supprimer cette question ?');" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn-icon"><i class="bi bi-trash"></i></button>
            </form>
        </div>

        <div id="edit-q-{{ $question->id }}" class="d-none">
            <div class="mb-2">
                <div class="form-label-custom">Contexte (optionnel)</div>
                <textarea name="contexte" class="form-control-custom" rows="2">{{ $question->contexte }}</textarea>
            </div>
            <div class="mb-2">
                <div class="form-label-custom">Énoncé</div>
                <textarea name="enonce" class="form-control-custom" rows="2" required>{{ $question->enonce }}</textarea>
            </div>

            <div class="form-label-custom">Réponses (cochez la bonne)</div>
            @foreach($question->reponses as $ri => $reponse)
            <div class="reponse-row {{ $reponse->correcte ? 'correcte' : '' }}">
                <input type="radio" name="correcte" value="{{ $ri }}" {{ $reponse->correcte ? 'checked' : '' }} required>
                <input type="hidden" name="reponses[{{ $ri }}][id]" value="{{ $reponse->id }}">
                <input type="text" name="reponses[{{ $ri }}][texte]" class="form-control-custom"
                       value="{{ $reponse->texte }}" required style="border:none;background:transparent;">
            </div>
            @endforeach

            <div class="row g-2 mt-2 mb-2">
                <div class="col-md-6">
                    <div class="form-label-custom">Points</div>
                    <input type="number" name="points" class="form-control-custom" min="1" max="10" value="{{ $question->points }}">
                </div>
                <div class="col-md-6">
                    <div class="form-label-custom">Durée (secondes)</div>
                    <input type="number" name="duree_secondes" class="form-control-custom" min="10" max="600" value="{{ $question->duree_secondes }}">
                </div>
            </div>

            <div class="mb-2">
                <div class="form-label-custom">Explication (affichée après réponse)</div>
                <textarea name="explication" class="form-control-custom" rows="2">{{ $question->explication }}</textarea>
            </div>

            <button type="submit" style="background:#1B3A6B;color:#fff;padding:8px 18px;
                    border-radius:18px;border:none;font-size:12px;font-weight:700;">
                Enregistrer la question
            </button>
        </div>

        @if(!request()->has('edit-' . $question->id))
        <div style="font-size:13px;color:#333;font-weight:600;">{{ $question->enonce }}</div>
        <div style="font-size:12px;color:#888;margin-top:4px;">
            {{ $question->reponses->firstWhere('correcte', true)?->texte }}
            <span style="color:#1cc88a;"><i class="bi bi-check-circle-fill ms-1"></i></span>
        </div>
        @endif
    </form>
</div>
@endforeach

{{-- ══ Ajouter une nouvelle question ══ --}}
<div class="form-card">
    <h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:16px;">
        <i class="bi bi-plus-circle me-2" style="color:#F5A623;"></i>Ajouter une question
    </h3>
    <form method="POST" action="{{ route('admin.tests.questions.store', $serie) }}" id="new-question-form">
        @csrf

        <div class="mb-2">
            <div class="form-label-custom">Contexte (optionnel — texte/audio de référence)</div>
            <textarea name="contexte" class="form-control-custom" rows="2"></textarea>
        </div>

        <div class="mb-2">
            <div class="form-label-custom">Énoncé de la question</div>
            <textarea name="enonce" class="form-control-custom" rows="2" required></textarea>
        </div>

        <div class="form-label-custom">Réponses (cochez la bonne)</div>
        <div id="reponses-container">
            @for($i = 0; $i < 4; $i++)
            <div class="reponse-row">
                <input type="radio" name="correcte" value="{{ $i }}" {{ $i === 0 ? 'required' : '' }}>
                <input type="text" name="reponses[]" class="form-control-custom"
                       placeholder="Réponse {{ $i + 1 }}" required style="border:none;background:transparent;">
            </div>
            @endfor
        </div>
        <button type="button" class="add-reponse-btn mb-3" onclick="ajouterReponse()">
            <i class="bi bi-plus-lg me-1"></i>Ajouter une réponse
        </button>

        <div class="row g-2 mb-2">
            <div class="col-md-6">
                <div class="form-label-custom">Points</div>
                <input type="number" name="points" class="form-control-custom" min="1" max="10" value="1">
            </div>
            <div class="col-md-6">
                <div class="form-label-custom">Durée (secondes)</div>
                <input type="number" name="duree_secondes" class="form-control-custom" min="10" max="600" value="60">
            </div>
        </div>

        <div class="mb-3">
            <div class="form-label-custom">Explication (affichée après réponse)</div>
            <textarea name="explication" class="form-control-custom" rows="2"></textarea>
        </div>

        <button type="submit" style="background:#F5A623;color:#1B3A6B;padding:10px 24px;
                border-radius:20px;border:none;font-size:13px;font-weight:700;">
            <i class="bi bi-plus-lg me-1"></i>Ajouter cette question
        </button>
    </form>
</div>

@endsection

@push('scripts')
<script>
let reponseIndex = 4;
function ajouterReponse() {
    if (reponseIndex >= 6) { alert('Maximum 6 réponses.'); return; }
    const container = document.getElementById('reponses-container');
    const div = document.createElement('div');
    div.className = 'reponse-row';
    div.innerHTML = `
        <input type="radio" name="correcte" value="${reponseIndex}">
        <input type="text" name="reponses[]" class="form-control-custom"
               placeholder="Réponse ${reponseIndex + 1}" required style="border:none;background:transparent;">
    `;
    container.appendChild(div);
    reponseIndex++;
}

// Dépendance langue -> discipline sur le formulaire d'édition des infos générales
const languesData = @json($langues->mapWithKeys(fn($l) => [
    $l->id => $l->disciplines->map(fn($d) => ['id' => $d->id, 'nom' => $d->nom])
]));

document.getElementById('langue-select').addEventListener('change', function () {
    const langueId = this.value;
    const disciplineSelect = document.getElementById('discipline-select');
    disciplineSelect.innerHTML = '';

    (languesData[langueId] || []).forEach(function (d) {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.nom;
        disciplineSelect.appendChild(opt);
    });
});
</script>
@endpush