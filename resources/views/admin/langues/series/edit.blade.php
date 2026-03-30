{{-- resources/views/admin/langues/series/edit.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Modifier — '.$serie->titre)

@push('styles')
<style>
.vf-card{background:#fff;border-radius:14px;border:1px solid #eee;
         padding:24px;margin-bottom:18px;box-shadow:0 2px 8px rgba(27,58,107,.04);}
.vf-card-title{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:18px;
               display:flex;align-items:center;gap:7px;padding-bottom:12px;
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
.niveau-select{display:flex;gap:10px;}
.niv-option{flex:1;padding:12px 10px;border:1.5px solid #e8e8e8;border-radius:10px;
            cursor:pointer;text-align:center;transition:all .2s;background:#fafafa;}
.niv-option:has(input:checked){border-color:#1B3A6B;background:rgba(27,58,107,.05);}
.niv-option input{display:none;}
.niv-option-dot{width:10px;height:10px;border-radius:50%;margin:0 auto 6px;}
.niv-option-label{font-size:12px;font-weight:700;color:#1B3A6B;}
.niv-option-sub{font-size:10px;color:#888;margin-top:2px;}
.toggle-wrap{display:flex;align-items:center;gap:10px;}
.toggle-track{position:relative;width:42px;height:24px;border-radius:12px;
              background:#e8e8e8;cursor:pointer;transition:background .2s;flex-shrink:0;}
.toggle-track:has(input:checked){background:#1B3A6B;}
.toggle-track input{opacity:0;width:0;height:0;position:absolute;}
.toggle-thumb{position:absolute;top:3px;left:3px;width:18px;height:18px;
              border-radius:50%;background:#fff;transition:transform .2s;
              box-shadow:0 1px 4px rgba(0,0,0,.2);}
.toggle-track:has(input:checked) .toggle-thumb{transform:translateX(18px);}
.btn-save{background:#1B3A6B;color:#fff;border:none;border-radius:25px;
          padding:12px 30px;font-size:13px;font-weight:700;cursor:pointer;
          transition:all .2s;display:inline-flex;align-items:center;gap:8px;}
.btn-save:hover{background:#152d54;transform:translateY(-1px);}
</style>
@endpush

@section('content')

@php
  $disc   = $serie->discipline;
  $langue = $disc->langue;
@endphp

{{-- Fil d'Ariane --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.langues.series.show', $serie) }}"
       style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;
              color:#1B3A6B;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:11px;color:#888;margin-bottom:2px;">
            <span style="color:{{ $langue->couleur }};font-weight:700;">{{ $langue->nom }}</span>
            <span style="margin:0 6px;">·</span>{{ $disc->nom }}
        </div>
        <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.2rem;">
            Modifier la série — {{ $serie->titre }}
        </h2>
    </div>
</div>

@if(session('success'))
<div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

<div class="row g-4">
<div class="col-lg-8">

<form method="POST" action="{{ route('admin.langues.series.update', $serie) }}">
    @csrf @method('PUT')

    {{-- Informations --}}
    <div class="vf-card">
        <div class="vf-card-title">
            <i class="bi bi-collection" style="color:#F5A623;"></i>Informations de la série
        </div>

        <div class="mb-3">
            <label class="vf-label">Titre *</label>
            <input type="text" name="titre"
                   class="vf-input @error('titre') is-invalid @enderror"
                   value="{{ old('titre', $serie->titre) }}"
                   placeholder="ex: Série 100, Practice Test 1...">
            @error('titre')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="vf-label">Description</label>
            <textarea name="description" class="vf-input" rows="3"
                      placeholder="Description de la série...">{{ old('description', $serie->description) }}</textarea>
        </div>

        <div>
            <label class="vf-label">Durée (minutes) *</label>
            <input type="number" name="duree_minutes"
                   class="vf-input @error('duree_minutes') is-invalid @enderror"
                   value="{{ old('duree_minutes', $serie->duree_minutes) }}"
                   min="5" max="300" style="max-width:160px;">
            @error('duree_minutes')<div class="error-msg">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Niveau --}}
    <div class="vf-card">
        <div class="vf-card-title">
            <i class="bi bi-bar-chart-steps" style="color:#F5A623;"></i>Niveau
        </div>
        <div class="niveau-select">
            @foreach([1=>'Débutant|A1-A2|#1cc88a', 2=>'Intermédiaire|B1-B2|#F5A623', 3=>'Avancé|C1-C2|#E24B4A'] as $niv => $info)
            @php [$label, $sub, $color] = explode('|', $info); @endphp
            <label class="niv-option">
                <input type="radio" name="niveau" value="{{ $niv }}"
                       {{ old('niveau', $serie->niveau) == $niv ? 'checked' : '' }}>
                <div class="niv-option-dot" style="background:{{ $color }};"></div>
                <div class="niv-option-label">{{ $label }}</div>
                <div class="niv-option-sub">{{ $sub }}</div>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Options --}}
    <div class="vf-card">
        <div class="vf-card-title">
            <i class="bi bi-toggles" style="color:#F5A623;"></i>Options
        </div>
        <div class="d-flex flex-column gap-3">
            <div class="toggle-wrap">
                <label class="toggle-track">
                    <input type="checkbox" name="gratuite" value="1"
                           {{ old('gratuite', $serie->gratuite) ? 'checked' : '' }}>
                    <div class="toggle-thumb"></div>
                </label>
                <div>
                    <div style="font-size:13px;font-weight:500;color:#333;">Série gratuite</div>
                    <div style="font-size:11px;color:#888;">Accessible sans abonnement</div>
                </div>
            </div>
            <div class="toggle-wrap">
                <label class="toggle-track">
                    <input type="checkbox" name="active" value="1"
                           {{ old('active', $serie->active) ? 'checked' : '' }}>
                    <div class="toggle-thumb"></div>
                </label>
                <div>
                    <div style="font-size:13px;font-weight:500;color:#333;">Série active</div>
                    <div style="font-size:11px;color:#888;">Désactivée = non visible par les étudiants</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn-save">
            <i class="bi bi-check-circle-fill"></i>Enregistrer les modifications
        </button>
        <a href="{{ route('admin.langues.series.show', $serie) }}"
           style="padding:12px 20px;border:1.5px solid #ddd;color:#666;border-radius:25px;
                  font-size:13px;text-decoration:none;">
            Annuler
        </a>
    </div>

</form>
</div>

{{-- Infos rapides --}}
<div class="col-lg-4">
    <div class="vf-card" style="background:rgba(27,58,107,.03);border-color:rgba(27,58,107,.08);">
        <div class="vf-card-title" style="border-bottom-color:rgba(27,58,107,.08);">
            <i class="bi bi-info-circle" style="color:#F5A623;"></i>Informations actuelles
        </div>
        @foreach([
            ['bi-collection','Discipline',$disc->nom],
            ['bi-translate','Examen',$langue->nom],
            ['bi-question-circle','Questions',$serie->nombre_questions.' question(s)'],
            ['bi-clock','Durée',$serie->duree_minutes.' min'],
            ['bi-calendar','Créée le',$serie->created_at->format('d/m/Y')],
        ] as [$icon, $lbl, $val])
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
            <i class="bi {{ $icon }}" style="color:#F5A623;width:16px;"></i>
            <div>
                <div style="font-size:10px;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">{{ $lbl }}</div>
                <div style="font-size:13px;color:#1B3A6B;font-weight:600;">{{ $val }}</div>
            </div>
        </div>
        @endforeach

        {{-- Lien rapide questions --}}
        <div style="margin-top:4px;padding-top:12px;border-top:1px solid rgba(27,58,107,.08);">
            <a href="{{ route('admin.langues.questions.create', $serie) }}"
               style="display:flex;align-items:center;gap:6px;font-size:12px;
                      color:#1B3A6B;text-decoration:none;font-weight:600;">
                <i class="bi bi-plus-circle" style="color:#F5A623;"></i>
                Ajouter une question
            </a>
        </div>
    </div>
</div>
</div>

@endsection