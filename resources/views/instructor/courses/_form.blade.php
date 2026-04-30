{{-- resources/views/instructeur/courses/_form.blade.php
     Identique à admin/courses/_form.blade.php mais SANS le bloc instructeur.
     Variables : $cours (null = création), $routeAction, $method
--}}
@push('styles')
<style>
    .form-section {
        background: #fff; border-radius: 14px; padding: 1.5rem;
        box-shadow: 0 1px 8px rgba(27,58,107,.07); margin-bottom: 1.5rem;
    }
    .form-section-title {
        font-size: .78rem; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: #1B3A6B;
        margin-bottom: 1.1rem; padding-bottom: .5rem;
        border-bottom: 2px solid #f0f4ff;
        display: flex; align-items: center; gap: .45rem;
    }
    .niveau-radio { display: none; }
    .niveau-label {
        display: inline-flex; align-items: center; justify-content: center;
        width: 48px; height: 36px; border-radius: 8px;
        border: 2px solid #dee2e6; font-weight: 700; font-size: .85rem;
        cursor: pointer; transition: all .15s; color: #6c757d;
    }
    .niveau-radio:checked + .niveau-label {
        border-color: #1B3A6B; background: #1B3A6B; color: #fff;
    }
</style>
@endpush

<form action="{{ $routeAction }}" method="POST" id="form-cours-inst">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">

            <div class="form-section">
                <div class="form-section-title"><i class="bi bi-info-circle-fill"></i>Informations générales</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror"
                            value="{{ old('titre', $cours->titre ?? '') }}"
                            placeholder="ex : Allemand A1 — Débutant" required>
                        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Sous-titre</label>
                        <input type="text" name="sous_titre" class="form-control"
                            value="{{ old('sous_titre', $cours->sous_titre ?? '') }}"
                            placeholder="ex : Les bases de l'allemand en 20 leçons">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Objectifs et contenu du cours...">{{ old('description', $cours->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title"><i class="bi bi-bar-chart-steps"></i>Niveau CECRL</div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['A1','A2','B1','B2','C1','C2'] as $n)
                    <div>
                        <input type="radio" name="niveau" id="niveau_{{ $n }}" value="{{ $n }}" class="niveau-radio"
                            {{ old('niveau', $cours->niveau ?? '') === $n ? 'checked' : '' }}>
                        <label for="niveau_{{ $n }}" class="niveau-label">{{ $n }}</label>
                    </div>
                    @endforeach
                </div>
                @error('niveau')<p class="text-danger small mt-1">{{ $message }}</p>@enderror
            </div>

        </div>
        <div class="col-lg-4">

            <div class="form-section">
                <div class="form-section-title"><i class="bi bi-palette"></i>Apparence</div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Couleur accent</label>
                    <div class="d-flex align-items-center gap-2">
                        <div id="color-preview" style="width:36px;height:36px;border-radius:8px;border:2px solid #dee2e6;background:{{ old('couleur', $cours->couleur ?? '#1B3A6B') }}"></div>
                        <input type="color" name="couleur" id="color-input" class="form-control form-control-color"
                            value="{{ old('couleur', $cours->couleur ?? '#1B3A6B') }}">
                        <span class="text-muted small" id="color-hex">{{ old('couleur', $cours->couleur ?? '#1B3A6B') }}</span>
                    </div>
                </div>
                <div>
                    <label class="form-label fw-semibold small">Icône Bootstrap</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" id="icon-preview">
                            <i class="bi {{ old('icone', $cours->icone ?? 'bi-book') }}"></i>
                        </span>
                        <input type="text" name="icone" id="icon-input" class="form-control"
                            value="{{ old('icone', $cours->icone ?? 'bi-book') }}" placeholder="bi-book">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title"><i class="bi bi-sliders"></i>Paramètres</div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Durée estimée (min)</label>
                    <input type="number" name="duree_estimee_minutes" class="form-control" min="1"
                        value="{{ old('duree_estimee_minutes', $cours->duree_estimee_minutes ?? '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Ordre</label>
                    <input type="number" name="ordre" class="form-control" min="0"
                        value="{{ old('ordre', $cours->ordre ?? '') }}">
                </div>
                <div class="d-flex flex-column gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="gratuit" id="ck-gratuit" value="1"
                            {{ old('gratuit', $cours->gratuit ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="ck-gratuit">Cours gratuit</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="publie" id="ck-publie" value="1"
                            {{ old('publie', $cours->publie ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="ck-publie">Publié</label>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn fw-bold py-2" style="background:#1B3A6B;color:#fff">
                    <i class="bi bi-check-lg me-2"></i>{{ isset($cours) ? 'Enregistrer' : 'Créer le cours' }}
                </button>
                <a href="{{ route('instructor.dashboard') }}" class="btn btn-light fw-semibold">Annuler</a>
            </div>

        </div>
    </div>
</form>

@push('scripts')
<script>
    const colorInput   = document.getElementById('color-input');
    const colorPreview = document.getElementById('color-preview');
    const colorHex     = document.getElementById('color-hex');
    colorInput.addEventListener('input', () => {
        colorPreview.style.background = colorInput.value;
        colorHex.textContent = colorInput.value;
    });
    const iconInput   = document.getElementById('icon-input');
    const iconPreview = document.getElementById('icon-preview');
    iconInput.addEventListener('input', () => {
        iconPreview.innerHTML = `<i class="bi ${iconInput.value}"></i>`;
    });
</script>
@endpush