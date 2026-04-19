{{-- resources/views/admin/abonnements/plans/create.blade.php --}}
{{-- Réutilisée pour edit : $plan optionnel --}}
@extends('layouts.dashboard')
@section('title', isset($plan) ? 'Modifier le plan' : 'Nouveau plan d\'abonnement')

@push('styles')
<style>
.vf-card{background:#fff;border-radius:14px;border:1px solid #eee;
         padding:22px 24px;margin-bottom:16px;box-shadow:0 2px 8px rgba(27,58,107,.04);}
.vf-card-title{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:16px;
               display:flex;align-items:center;gap:7px;padding-bottom:11px;
               border-bottom:1.5px solid rgba(27,58,107,.06);}
.vf-label{font-size:11px;font-weight:700;color:#1B3A6B;display:block;
          margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px;}
.vf-input{border:1.5px solid #e8e8e8;border-radius:10px;padding:11px 14px;
          font-size:13px;width:100%;outline:none;transition:all .2s;background:#fafafa;}
.vf-input:focus{border-color:#F5A623;background:#fff;box-shadow:0 0 0 3px rgba(245,166,35,.08);}
.error-msg{font-size:11px;color:#E24B4A;margin-top:4px;}

/* Grille icones */
.icone-group-title{font-size:10px;font-weight:700;color:#888;text-transform:uppercase;
                   letter-spacing:.6px;margin:10px 0 6px;}
.icone-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:6px;margin-bottom:8px;}
.icone-opt{padding:10px 6px;border:1.5px solid #e8e8e8;border-radius:9px;
           cursor:pointer;text-align:center;transition:all .2s;background:#fafafa;}
.icone-opt:has(input:checked){border-color:#1B3A6B;background:rgba(27,58,107,.06);}
.icone-opt input{display:none;}
.icone-opt i{font-size:18px;color:#1B3A6B;display:block;margin-bottom:3px;}
.icone-opt span{font-size:9px;color:#888;line-height:1.2;display:block;}

/* Points / avantages */
.point-row{display:flex;align-items:center;gap:8px;padding:10px 12px;
           background:#f8f9fb;border-radius:10px;border:1px solid #eee;margin-bottom:8px;}
.point-icone-sel{width:32px;height:32px;border-radius:8px;background:#fff;border:1px solid #ddd;
                 display:flex;align-items:center;justify-content:center;
                 cursor:pointer;font-size:15px;flex-shrink:0;position:relative;}
.point-color-btn{width:24px;height:24px;border-radius:6px;cursor:pointer;flex-shrink:0;
                 border:1px solid rgba(0,0,0,.1);}
.point-text-input{border:none;background:transparent;font-size:13px;flex:1;
                  outline:none;color:#333;padding:2px 0;}
.point-text-input::placeholder{color:#ccc;}
.point-del{width:26px;height:26px;border-radius:7px;border:1px solid #eee;
           background:#fff;color:#E24B4A;font-size:11px;cursor:pointer;
           display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.point-del:hover{background:#E24B4A;color:#fff;}
.btn-add-point{width:100%;padding:9px;border:1.5px dashed #ddd;border-radius:10px;
               background:none;font-size:12px;color:#888;cursor:pointer;
               display:flex;align-items:center;justify-content:center;gap:6px;transition:all .2s;}
.btn-add-point:hover{border-color:#1B3A6B;color:#1B3A6B;}

/* Toggle */
.toggle-track{position:relative;width:42px;height:24px;border-radius:12px;
              background:#e8e8e8;cursor:pointer;transition:background .2s;flex-shrink:0;display:inline-block;}
.toggle-track:has(input:checked){background:#1B3A6B;}
.toggle-track input{opacity:0;width:0;height:0;position:absolute;}
.toggle-thumb{position:absolute;top:3px;left:3px;width:18px;height:18px;
              border-radius:50%;background:#fff;transition:transform .2s;
              box-shadow:0 1px 4px rgba(0,0,0,.2);}
.toggle-track:has(input:checked) .toggle-thumb{transform:translateX(18px);}

/* Preview carte */
.plan-preview-card{border-radius:16px;padding:24px;position:relative;overflow:hidden;
                   transition:all .3s;}
.plan-preview-card.popular::before{content:'Populaire';position:absolute;top:12px;right:-28px;
    background:#F5A623;color:#1B3A6B;font-size:10px;font-weight:800;padding:4px 36px;
    transform:rotate(45deg);letter-spacing:.5px;}
.preview-icon{font-size:28px;margin-bottom:8px;}
.preview-name{font-size:20px;font-weight:800;}
.preview-price{font-size:2rem;font-weight:900;margin:10px 0 4px;}
.preview-period{font-size:12px;opacity:.7;}
.preview-point{display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:8px;}

.btn-save{background:#1B3A6B;color:#fff;border:none;border-radius:25px;
          padding:12px 30px;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s;}
.btn-save:hover{background:#152d54;}

/* Popup icone picker */
.icone-picker-popup{display:none;position:absolute;top:calc(100% + 4px);left:0;
                    background:#fff;border:1px solid #eee;border-radius:12px;
                    box-shadow:0 8px 32px rgba(27,58,107,.15);
                    padding:12px;z-index:100;width:260px;max-height:320px;overflow-y:auto;}
.icone-picker-popup.open{display:block;}
.picker-row{display:grid;grid-template-columns:repeat(6,1fr);gap:4px;}
.picker-item{width:36px;height:36px;border-radius:7px;display:flex;align-items:center;
             justify-content:center;cursor:pointer;font-size:16px;color:#1B3A6B;
             transition:all .15s;}
.picker-item:hover{background:rgba(27,58,107,.08);}
</style>
@endpush

@section('content')

@php
  $isEdit = isset($plan);
  $action = $isEdit
      ? route('admin.abonnements.plans.update', $plan)
      : route('admin.abonnements.store');
  $points = $isEdit ? ($plan->points ?? []) : [
      ['icone'=>'bi-check-circle-fill','couleur'=>'#1cc88a','texte'=>''],
      ['icone'=>'bi-check-circle-fill','couleur'=>'#1cc88a','texte'=>''],
      ['icone'=>'bi-check-circle-fill','couleur'=>'#1cc88a','texte'=>''],
  ];
@endphp

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.abonnements.plans.index') }}"
       style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;color:#1B3A6B;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.2rem;">
            {{ $isEdit ? 'Modifier le plan — '.$plan->nom : 'Nouveau plan d\'abonnement' }}
        </h2>
        <p class="text-muted mb-0" style="font-size:12px;">Définissez les points et l'icône de chaque avantage</p>
    </div>
</div>

@if(session('success'))
<div class="alert rounded-3 mb-3" style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

<div class="row g-4">
<div class="col-lg-7">
<form method="POST" action="{{ $action }}" id="planForm">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- ── Infos générales ── --}}
    <div class="vf-card">
        <div class="vf-card-title"><i class="bi bi-info-circle" style="color:#F5A623;"></i>Informations</div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="vf-label">Nom du plan *</label>
                <input type="text" name="nom" class="vf-input"
                       value="{{ old('nom', $isEdit ? $plan->nom : '') }}"
                       placeholder="ex: Mensuel, Premium..."
                       oninput="updatePreview()">
            </div>
            <div class="col-md-6">
                <label class="vf-label">Code unique *</label>
                <input type="text" name="code" class="vf-input"
                       value="{{ old('code', $isEdit ? $plan->code : '') }}"
                       placeholder="mensuel"
                       {{ $isEdit ? 'readonly' : '' }}>
            </div>
            <div class="col-12">
                <label class="vf-label">Description courte</label>
                <input type="text" name="description" class="vf-input"
                       value="{{ old('description', $isEdit ? $plan->description : '') }}"
                       placeholder="Idéal pour une préparation intensive"
                       oninput="updatePreview()">
            </div>
        </div>
    </div>

    {{-- ── Prix & durée ── --}}
    <div class="vf-card">
        <div class="vf-card-title"><i class="bi bi-currency-exchange" style="color:#F5A623;"></i>Prix & Durée</div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="vf-label">Prix *</label>
                <input type="number" name="prix" class="vf-input"
                       value="{{ old('prix', $isEdit ? $plan->prix : '') }}"
                       placeholder="5000" min="0"
                       oninput="updatePreview()">
            </div>
            <div class="col-md-3">
                <label class="vf-label">Devise *</label>
                <select name="devise" class="vf-input" onchange="updatePreview()">
                    @foreach(['XAF','EUR','USD','CAD'] as $dev)
                    <option value="{{ $dev }}"
                        {{ old('devise', $isEdit ? $plan->devise : 'XAF') == $dev ? 'selected' : '' }}>
                        {{ $dev }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="vf-label">Durée (jours) *</label>
                <input type="number" name="duree_jours" class="vf-input"
                       value="{{ old('duree_jours', $isEdit ? $plan->duree_jours : 30) }}"
                       min="1" oninput="updatePreview()">
            </div>
        </div>
    </div>

    {{-- ── Apparence ── --}}
    <div class="vf-card">
        <div class="vf-card-title"><i class="bi bi-palette" style="color:#F5A623;"></i>Apparence</div>
        <div class="row g-3 align-items-end mb-3">
            <div class="col-md-4">
                <label class="vf-label">Couleur principale</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="color" name="couleur"
                           value="{{ old('couleur', $isEdit ? $plan->couleur : '#1B3A6B') }}"
                           style="width:44px;height:44px;border-radius:10px;border:1.5px solid #e8e8e8;
                                  padding:2px;cursor:pointer;background:#fafafa;"
                           oninput="updatePreview()">
                    <input type="text"
                           value="{{ old('couleur', $isEdit ? $plan->couleur : '#1B3A6B') }}"
                           class="vf-input" style="max-width:100px;"
                           oninput="document.querySelector('[name=couleur]').value=this.value;updatePreview()">
                </div>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <label class="vf-label">Plan populaire</label>
                        <label class="toggle-track" style="margin-top:4px;">
                            <input type="checkbox" name="populaire" value="1"
                                   {{ old('populaire', $isEdit && $plan->populaire) ? 'checked' : '' }}
                                   onchange="updatePreview()">
                            <div class="toggle-thumb"></div>
                        </label>
                    </div>
                    @if($isEdit)
                    <div>
                        <label class="vf-label">Plan actif</label>
                        <label class="toggle-track" style="margin-top:4px;">
                            <input type="checkbox" name="actif" value="1"
                                   {{ old('actif', $plan->actif) ? 'checked' : '' }}>
                            <div class="toggle-thumb"></div>
                        </label>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Icone principale du plan --}}
        <label class="vf-label">Icône du plan</label>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
            <div style="width:52px;height:52px;border-radius:12px;border:2px solid #e8e8e8;
                        display:flex;align-items:center;justify-content:center;
                        font-size:22px;color:#1B3A6B;background:#fafafa;"
                 id="selectedIconPreview">
                <i class="bi {{ old('icone', $isEdit ? $plan->icone : 'bi-star') }}"></i>
            </div>
            <input type="hidden" name="icone" id="selectedIconInput"
                   value="{{ old('icone', $isEdit ? $plan->icone : 'bi-star') }}">
            <div style="flex:1;">
                <div style="font-size:12px;color:#666;margin-bottom:6px;">Choisissez une icône :</div>
                <div style="display:flex;flex-wrap:wrap;gap:5px;">
                    @foreach(collect($icones)->flatten(1) as $ico)
                    <button type="button"
                            onclick="selectPlanIcon('{{ $ico['class'] }}')"
                            style="width:34px;height:34px;border-radius:8px;border:1.5px solid #e8e8e8;
                                   background:#fafafa;cursor:pointer;transition:all .15s;
                                   display:flex;align-items:center;justify-content:center;"
                            title="{{ $ico['label'] ?? '' }}"
                            id="planIconBtn-{{ $ico['class'] }}">
                        <i class="bi {{ $ico['class'] }}" style="font-size:15px;color:#1B3A6B;"></i>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ── Points / Avantages ── --}}
    <div class="vf-card">
        <div class="vf-card-title">
            <i class="bi bi-list-check" style="color:#F5A623;"></i>
            Avantages du plan
            <span style="font-size:11px;font-weight:400;color:#888;margin-left:4px;">
                — choisissez l'icône et la couleur de chaque point
            </span>
        </div>

        <div id="pointsList">
            @foreach($points as $i => $pt)
            <div class="point-row" id="point-{{ $i }}">
                {{-- Picker icone --}}
                <div style="position:relative;">
                    <div class="point-icone-sel" id="iconeSel-{{ $i }}"
                         onclick="togglePicker({{ $i }})">
                        <i class="bi {{ $pt['icone'] ?? 'bi-check-circle-fill' }}"
                           id="iconePreview-{{ $i }}"
                           style="color:{{ $pt['couleur'] ?? '#1cc88a' }};"></i>
                    </div>
                    <input type="hidden" name="points[{{ $i }}][icone]"
                           id="iconeInput-{{ $i }}"
                           value="{{ $pt['icone'] ?? 'bi-check-circle-fill' }}">
                    {{-- Popup icones --}}
                    <div class="icone-picker-popup" id="picker-{{ $i }}">
                        @foreach($icones as $grp => $liste)
                        <div class="icone-group-title">{{ $grp }}</div>
                        <div class="picker-row">
                            @foreach($liste as $ico)
                            <div class="picker-item"
                                 onclick="choosePointIcon({{ $i }},'{{ $ico['class'] }}')"
                                 title="{{ $ico['label'] ?? '' }}">
                                <i class="bi {{ $ico['class'] }}"></i>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                {{-- Couleur --}}
                <input type="color" name="points[{{ $i }}][couleur]"
                       id="couleurInput-{{ $i }}"
                       value="{{ $pt['couleur'] ?? '#1cc88a' }}"
                       class="point-color-btn"
                       title="Couleur de l'icône"
                       onchange="document.getElementById('iconePreview-{{ $i }}').style.color=this.value;updatePreview()">
                {{-- Texte --}}
                <input type="text" name="points[{{ $i }}][texte]"
                       class="point-text-input"
                       value="{{ $pt['texte'] ?? '' }}"
                       placeholder="Décrivez cet avantage..."
                       oninput="updatePreview()">
                {{-- Supprimer --}}
                <button type="button" class="point-del" onclick="removePoint({{ $i }})">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn-add-point" onclick="addPoint()">
            <i class="bi bi-plus-circle"></i>Ajouter un avantage
        </button>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn-save">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ $isEdit ? 'Enregistrer' : 'Créer le plan' }}
        </button>
        <a href="{{ route('admin.abonnements.plans.index') }}"
           style="padding:12px 20px;border:1.5px solid #ddd;color:#666;border-radius:25px;
                  font-size:13px;text-decoration:none;">Annuler</a>
    </div>

</form>
</div>

{{-- ── Prévisualisation live ── --}}
<div class="col-lg-5">
<div style="position:sticky;top:80px;">
    <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;
                letter-spacing:.6px;margin-bottom:12px;">Prévisualisation</div>
    <div class="plan-preview-card" id="previewCard"
         style="background:#1B3A6B;color:#fff;">
        <div class="preview-icon">
            <i class="bi bi-star" id="prevIcon"></i>
        </div>
        <div class="preview-name" id="prevName">Nom du plan</div>
        <div style="font-size:12px;opacity:.7;margin-top:4px;" id="prevDesc"></div>
        <div class="preview-price">
            <span id="prevPrix">0</span>
            <span style="font-size:14px;font-weight:400;opacity:.8;" id="prevDevise"> XAF</span>
        </div>
        <div class="preview-period" id="prevPeriod">/ 30 jours</div>
        <div style="height:1px;background:rgba(255,255,255,.2);margin:16px 0;"></div>
        <div id="prevPoints"></div>
    </div>
</div>
</div>
</div>

@push('scripts')
<script>
let pointCount = {{ count($points) }};
const allIcones = @json(collect($icones)->flatten(1)->pluck('class')->toArray());

// ── Preview live ──
function updatePreview() {
    const color    = document.querySelector('[name=couleur]')?.value || '#1B3A6B';
    const nom      = document.querySelector('[name=nom]')?.value     || 'Nom du plan';
    const desc     = document.querySelector('[name=description]')?.value || '';
    const prix     = document.querySelector('[name=prix]')?.value    || '0';
    const devise   = document.querySelector('[name=devise]')?.value  || 'XAF';
    const duree    = document.querySelector('[name=duree_jours]')?.value || '30';
    const populaire= document.querySelector('[name=populaire]')?.checked;
    const icone    = document.getElementById('selectedIconInput')?.value || 'bi-star';

    const card = document.getElementById('previewCard');
    card.style.background = color;
    card.className = 'plan-preview-card' + (populaire ? ' popular' : '');

    document.getElementById('prevIcon').className = 'bi ' + icone;
    document.getElementById('prevName').textContent = nom;
    document.getElementById('prevDesc').textContent = desc;
    document.getElementById('prevPrix').textContent = Number(prix).toLocaleString('fr-FR');
    document.getElementById('prevDevise').textContent = ' ' + devise;
    document.getElementById('prevPeriod').textContent = '/ ' + duree + ' jours';

    // Points
    const inputs = document.querySelectorAll('.point-text-input');
    const couleurs = document.querySelectorAll('[id^="couleurInput-"]');
    const icones = document.querySelectorAll('[id^="iconeInput-"]');
    let html = '';
    inputs.forEach((inp, i) => {
        if (!inp.value.trim()) return;
        const c = couleurs[i]?.value || '#1cc88a';
        const ic = icones[i]?.value || 'bi-check-circle-fill';
        html += `<div class="preview-point">
            <i class="bi ${ic}" style="color:${c};font-size:14px;flex-shrink:0;"></i>
            <span style="font-size:13px;">${inp.value}</span>
        </div>`;
    });
    document.getElementById('prevPoints').innerHTML = html;
}

// ── Icone principale ──
function selectPlanIcon(cls) {
    document.getElementById('selectedIconInput').value = cls;
    document.getElementById('selectedIconPreview').innerHTML = `<i class="bi ${cls}"></i>`;
    document.querySelectorAll('[id^="planIconBtn-"]').forEach(b => b.style.borderColor = '#e8e8e8');
    const btn = document.getElementById('planIconBtn-' + cls);
    if (btn) btn.style.borderColor = '#1B3A6B';
    updatePreview();
}

// ── Picker point ──
function togglePicker(i) {
    document.querySelectorAll('.icone-picker-popup').forEach((p, idx) => {
        if (idx !== i) p.classList.remove('open');
    });
    document.getElementById('picker-' + i)?.classList.toggle('open');
}
function choosePointIcon(i, cls) {
    document.getElementById('iconeInput-' + i).value = cls;
    const c = document.getElementById('couleurInput-' + i)?.value || '#1cc88a';
    document.getElementById('iconePreview-' + i).className = 'bi ' + cls;
    document.getElementById('iconePreview-' + i).style.color = c;
    document.getElementById('picker-' + i)?.classList.remove('open');
    updatePreview();
}

// ── Ajouter un point ──
function addPoint() {
    const i   = pointCount++;
    const div = document.createElement('div');
    div.className = 'point-row';
    div.id = 'point-' + i;
    const pickerHtml = allIcones.map(ic => `
        <div class="picker-item" onclick="choosePointIcon(${i},'${ic}')">
            <i class="bi ${ic}"></i>
        </div>`).join('');
    div.innerHTML = `
        <div style="position:relative;">
            <div class="point-icone-sel" onclick="togglePicker(${i})">
                <i class="bi bi-check-circle-fill" id="iconePreview-${i}" style="color:#1cc88a;"></i>
            </div>
            <input type="hidden" name="points[${i}][icone]" id="iconeInput-${i}" value="bi-check-circle-fill">
            <div class="icone-picker-popup" id="picker-${i}">
                <div class="picker-row">${pickerHtml}</div>
            </div>
        </div>
        <input type="color" name="points[${i}][couleur]" id="couleurInput-${i}"
               value="#1cc88a" class="point-color-btn"
               onchange="document.getElementById('iconePreview-${i}').style.color=this.value;updatePreview()">
        <input type="text" name="points[${i}][texte]" class="point-text-input"
               placeholder="Décrivez cet avantage..." oninput="updatePreview()">
        <button type="button" class="point-del" onclick="removePoint(${i})">
            <i class="bi bi-x"></i>
        </button>`;
    document.getElementById('pointsList').appendChild(div);
}

function removePoint(i) {
    const items = document.querySelectorAll('.point-row');
    if (items.length <= 1) return;
    document.getElementById('point-' + i)?.remove();
    updatePreview();
}

// Fermer pickers au clic dehors
document.addEventListener('click', e => {
    if (!e.target.closest('.point-icone-sel') && !e.target.closest('.icone-picker-popup')) {
        document.querySelectorAll('.icone-picker-popup').forEach(p => p.classList.remove('open'));
    }
});

updatePreview();
</script>
@endpush

@endsection