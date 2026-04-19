{{-- resources/views/admin/lessons/_mot_row.blade.php --}}
<div class="card-mot">
    <button type="button" class="btn btn-danger btn-sm btn-remove-row" title="Supprimer">
        <i class="bi bi-x"></i>
    </button>
    <div class="row g-2">
        <div class="col-md-3">
            <label class="form-label small mb-1 fw-bold">🇩🇪 Allemand *</label>
            <input type="text" name="mots[{{ $i }}][de]"
                class="form-control form-control-sm"
                value="{{ $mot['de'] ?? '' }}" placeholder="Guten Morgen" required>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1 fw-bold">🇫🇷 Français *</label>
            <input type="text" name="mots[{{ $i }}][fr]"
                class="form-control form-control-sm"
                value="{{ $mot['fr'] ?? '' }}" placeholder="Bonjour (matin)" required>
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1 fw-bold">Phonétique</label>
            <input type="text" name="mots[{{ $i }}][phonetique]"
                class="form-control form-control-sm font-monospace"
                value="{{ $mot['phonetique'] ?? '' }}" placeholder="ˈɡuːtən ˈmɔʁɡən">
        </div>
        <div class="col-md-4">
            <label class="form-label small mb-1 fw-bold">Exemple d'usage</label>
            <input type="text" name="mots[{{ $i }}][exemple]"
                class="form-control form-control-sm"
                value="{{ $mot['exemple'] ?? '' }}" placeholder="Guten Morgen! Wie geht's?">
        </div>
    </div>
</div>
