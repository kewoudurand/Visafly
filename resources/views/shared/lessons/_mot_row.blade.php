{{-- resources/views/shared/lessons/_mot_row.blade.php --}}
<div class="card-mot">
    <button type="button" class="btn-remove-row" title="Supprimer ce mot">
        <i class="bi bi-x"></i>
    </button>
    <div class="row g-2">
        <div class="col-sm-3">
            <label class="form-label small fw-bold mb-1">🇩🇪 Allemand <span class="text-danger">*</span></label>
            <input type="text" name="mots[{{ $i }}][de]" class="form-control form-control-sm"
                value="{{ $mot['de'] ?? '' }}" placeholder="Guten Morgen" required>
        </div>
        <div class="col-sm-3">
            <label class="form-label small fw-bold mb-1">🇫🇷 Français <span class="text-danger">*</span></label>
            <input type="text" name="mots[{{ $i }}][fr]" class="form-control form-control-sm"
                value="{{ $mot['fr'] ?? '' }}" placeholder="Bonjour (matin)" required>
        </div>
        <div class="col-sm-2">
            <label class="form-label small fw-bold mb-1">Phonétique</label>
            <input type="text" name="mots[{{ $i }}][phonetique]" class="form-control form-control-sm font-monospace"
                value="{{ $mot['phonetique'] ?? '' }}" placeholder="ˈɡuːtən">
        </div>
        <div class="col-sm-4">
            <label class="form-label small fw-bold mb-1">Exemple d'usage</label>
            <input type="text" name="mots[{{ $i }}][exemple]" class="form-control form-control-sm"
                value="{{ $mot['exemple'] ?? '' }}" placeholder="Guten Morgen! Wie geht's?">
        </div>
    </div>
</div>


