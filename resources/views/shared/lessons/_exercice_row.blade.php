{{-- resources/views/shared/lessons/_exercice_row.blade.php --}}
<div class="card-exercice">
    <button type="button" class="btn-remove-row" title="Supprimer cet exercice">
        <i class="bi bi-x"></i>
    </button>
    <div class="row g-2">
        <div class="col-md-7">
            <label class="form-label small fw-bold mb-1">Question <span class="text-danger">*</span></label>
            <input type="text" name="exercices[{{ $i }}][question]" class="form-control form-control-sm"
                value="{{ $ex['question'] ?? '' }}" placeholder="Comment dit-on … ?" required>
        </div>
        <div class="col-md-5">
            <label class="form-label small fw-bold mb-1">Type <span class="text-danger">*</span></label>
            <select name="exercices[{{ $i }}][type]" class="form-select form-select-sm select-ex-type" required>
                <option value="qcm"         {{ ($ex['type'] ?? '') === 'qcm'         ? 'selected' : '' }}>📋 QCM</option>
                <option value="texte_libre" {{ ($ex['type'] ?? '') === 'texte_libre' ? 'selected' : '' }}>✍️ Texte libre</option>
            </select>
        </div>

        <div class="col-12 choix-wrap" style="{{ ($ex['type'] ?? 'qcm') !== 'qcm' ? 'display:none' : '' }}">
            <label class="form-label small fw-bold mb-1">Choix (4 options)</label>
            <div class="row g-1">
                @foreach(array_pad($ex['choix'] ?? [], 4, '') as $ci => $choix)
                <div class="col-6 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text fw-bold"
                              style="background:#f0f4ff;color:#1B3A6B;font-size:.7rem">{{ chr(65 + $ci) }}</span>
                        <input type="text" name="exercices[{{ $i }}][choix][]"
                            class="form-control form-control-sm"
                            value="{{ $choix }}" placeholder="Choix {{ $ci + 1 }}">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-5">
            <label class="form-label small fw-bold mb-1">Bonne réponse <span class="text-danger">*</span></label>
            <input type="text" name="exercices[{{ $i }}][reponse]" class="form-control form-control-sm"
                style="border-color:#198754"
                value="{{ $ex['reponse'] ?? '' }}" required>
        </div>
        <div class="col-md-7">
            <label class="form-label small fw-bold mb-1">Explication (après correction)</label>
            <input type="text" name="exercices[{{ $i }}][explication]" class="form-control form-control-sm"
                value="{{ $ex['explication'] ?? '' }}"
                placeholder="Explication affichée après correction de l'étudiant">
        </div>
    </div>
</div>