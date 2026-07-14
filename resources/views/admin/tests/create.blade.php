@extends('layouts.dashboard')
@section('title', 'Créer un test — VisaFly')

@push('styles')
<style>
.form-card{background:#fff;border-radius:16px;border:1px solid #eee;padding:28px;
           box-shadow:0 2px 12px rgba(27,58,107,.05);max-width:720px;}
.form-label-custom{font-size:12px;font-weight:700;color:#1B3A6B;margin-bottom:6px;
                    text-transform:uppercase;letter-spacing:.4px;}
.form-control-custom{border:1.5px solid #eee;border-radius:10px;padding:10px 14px;
                      font-size:14px;width:100%;}
.form-control-custom:focus{outline:none;border-color:#1B3A6B;}
.switch-row{display:flex;align-items:center;gap:10px;margin-bottom:14px;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.tests.index') }}" style="color:#888;font-size:20px;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Créer un test</h2>
        <p class="text-muted mb-0" style="font-size:13px;">Étape 1 : informations générales du test</p>
    </div>
</div>

@if($errors->any())
<div class="alert rounded-3 mb-3" style="background:rgba(226,75,74,.08);border:1px solid rgba(226,75,74,.3);color:#a32d2d;">
    <ul class="mb-0" style="font-size:13px;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-card">
    <form method="POST" action="{{ route('admin.tests.store') }}">
        @csrf

        <div class="mb-3">
            <div class="form-label-custom">Langue</div>
            <select id="langue-select" class="form-control-custom" required>
                <option value="">-- Choisir une langue --</option>
                @foreach($langues as $l)
                <option value="{{ $l->id }}">{{ $l->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <div class="form-label-custom">Discipline</div>
            <select name="discipline_id" id="discipline-select" class="form-control-custom" required disabled>
                <option value="">-- Choisissez d'abord une langue --</option>
            </select>
        </div>

        <div class="mb-3">
            <div class="form-label-custom">Titre du test</div>
            <input type="text" name="titre" class="form-control-custom" required
                   placeholder="Ex : Série 154" value="{{ old('titre') }}">
        </div>

        <div class="mb-3">
            <div class="form-label-custom">Description</div>
            <textarea name="description" class="form-control-custom" rows="3"
                      placeholder="Brève description du test">{{ old('description') }}</textarea>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="form-label-custom">Niveau (1-5)</div>
                <input type="number" name="niveau" class="form-control-custom" min="1" max="5"
                       value="{{ old('niveau', 2) }}" required>
            </div>
            <div class="col-md-4">
                <div class="form-label-custom">Durée (minutes)</div>
                <input type="number" name="duree_minutes" class="form-control-custom" min="5" max="240"
                       value="{{ old('duree_minutes', 60) }}" required>
            </div>
            <div class="col-md-4">
                <div class="form-label-custom">Ordre d'affichage</div>
                <input type="number" name="ordre" class="form-control-custom" min="0"
                       value="{{ old('ordre', 0) }}">
            </div>
        </div>

        <div class="switch-row">
            <input type="checkbox" name="gratuite" id="gratuite" value="1" {{ old('gratuite') ? 'checked' : '' }}>
            <label for="gratuite" style="font-size:13px;color:#444;">Test gratuit (accessible sans abonnement)</label>
        </div>

        <div class="switch-row">
            <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
            <label for="active" style="font-size:13px;color:#444;">Test actif (visible immédiatement)</label>
        </div>

        <button type="submit" style="background:#1B3A6B;color:#fff;padding:12px 28px;
                border-radius:25px;border:none;font-size:14px;font-weight:700;margin-top:10px;">
            <i class="bi bi-arrow-right-circle me-2"></i>Continuer — ajouter les questions
        </button>
    </form>
</div>

@endsection

@push('scripts')
<script>
// Données langue -> disciplines, injectées depuis le contrôleur
const languesData = @json($langues->mapWithKeys(fn($l) => [
    $l->id => $l->disciplines->map(fn($d) => ['id' => $d->id, 'nom' => $d->nom])
]));

const langueSelect     = document.getElementById('langue-select');
const disciplineSelect = document.getElementById('discipline-select');

langueSelect.addEventListener('change', function () {
    const langueId = this.value;
    disciplineSelect.innerHTML = '';

    if (!langueId || !languesData[langueId] || languesData[langueId].length === 0) {
        disciplineSelect.innerHTML = '<option value="">Aucune discipline disponible</option>';
        disciplineSelect.disabled = true;
        return;
    }

    disciplineSelect.disabled = false;
    disciplineSelect.innerHTML = '<option value="">-- Choisir une discipline --</option>';

    languesData[langueId].forEach(function (d) {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.nom;
        disciplineSelect.appendChild(opt);
    });
});
</script>
@endpush