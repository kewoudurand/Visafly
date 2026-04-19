{{-- resources/views/admin/lessons/_exercice_row.blade.php --}}
{{-- NOTE : copier dans un fichier séparé : _exercice_row.blade.php --}}
<div class="card-exercice">
    <button type="button" class="btn btn-danger btn-sm btn-remove-row" title="Supprimer">
        <i class="bi bi-x"></i>
    </button>
    <div class="row g-2">
        <div class="col-md-8">
            <label class="form-label small mb-1 fw-bold">Question *</label>
            <input type="text" name="exercices[{{ $i }}][question]"
                class="form-control form-control-sm"
                value="{{ $ex['question'] ?? '' }}" placeholder="Que signifie ..." required>
        </div>
        <div class="col-md-4">
            <label class="form-label small mb-1 fw-bold">Type *</label>
            <select name="exercices[{{ $i }}][type]" class="form-select form-select-sm select-ex-type" required>
                <option value="qcm"         {{ ($ex['type'] ?? '') === 'qcm'         ? 'selected' : '' }}>QCM</option>
                <option value="texte_libre" {{ ($ex['type'] ?? '') === 'texte_libre' ? 'selected' : '' }}>Texte libre</option>
            </select>
        </div>

        {{-- Choix QCM --}}
        <div class="col-12 choix-wrap" style="{{ ($ex['type'] ?? 'qcm') !== 'qcm' ? 'display:none' : '' }}">
            <label class="form-label small mb-1 fw-bold">Choix (un par ligne = un champ)</label>
            <div class="row g-1">
                @foreach(array_pad($ex['choix'] ?? [], 4, '') as $ci => $choix)
                <div class="col-md-3">
                    <input type="text" name="exercices[{{ $i }}][choix][]"
                        class="form-control form-control-sm"
                        value="{{ $choix }}" placeholder="Choix {{ $ci + 1 }}">
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label small mb-1 fw-bold">Bonne réponse *</label>
            <input type="text" name="exercices[{{ $i }}][reponse]"
                class="form-control form-control-sm"
                value="{{ $ex['reponse'] ?? '' }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label small mb-1 fw-bold">Explication (après correction)</label>
            <input type="text" name="exercices[{{ $i }}][explication]"
                class="form-control form-control-sm"
                value="{{ $ex['explication'] ?? '' }}" placeholder="Explication affichée après correction">
        </div>
    </div>
</div>