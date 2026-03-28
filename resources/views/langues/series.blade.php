{{-- resources/views/langues/series.blade.php --}}
@extends('layouts.app')
@section('title', 'Séries — '.$langue->nom)

@push('styles')
<style>
.series-hero{text-align:center;padding:48px 20px 32px;}
.series-hero h2{font-size:1.6rem;font-weight:800;color:#1B3A6B;}
.series-hero p{font-size:14px;color:#666;max-width:540px;margin:10px auto 0;line-height:1.6;}

.series-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;
             max-width:760px;margin:0 auto;padding:0 20px 60px;}
@media(max-width:520px){.series-grid{grid-template-columns:1fr;}}

.serie-btn{border-radius:12px;padding:22px 20px;font-size:16px;font-weight:700;
           display:flex;align-items:center;justify-content:center;
           cursor:pointer;text-decoration:none;transition:all .2s;
           position:relative;gap:10px;}
.serie-btn.libre{background:{{ $langue->couleur }};color:#fff;
                 box-shadow:0 4px 14px rgba(0,0,0,.12);}
.serie-btn.libre:hover{filter:brightness(1.08);transform:translateY(-2px);}
.serie-btn.locked{background:#e8e8e8;color:#aaa;cursor:not-allowed;}
.serie-btn .lock-icon{position:absolute;top:10px;right:12px;font-size:14px;}
.serie-btn .niveau-dot{width:8px;height:8px;border-radius:50%;
                       background:rgba(255,255,255,.5);flex-shrink:0;}
.legend{max-width:760px;margin:0 auto 20px;padding:0 20px;
        display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
.legend-item{display:flex;align-items:center;gap:6px;font-size:12px;color:#666;}
.legend-dot{width:14px;height:14px;border-radius:4px;}
</style>
@endpush

@section('content')

<div class="series-hero">
  <div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:16px;">
    <a href="#" onclick="window.history.back()"
       style="width:34px;height:34px;border-radius:8px;background:#f0f0f0;border:none;
              display:flex;align-items:center;justify-content:center;
              color:#1B3A6B;text-decoration:none;font-size:14px;">
      <i class="bi bi-arrow-left"></i>
    </a>
    <div style="width:38px;height:38px;border-radius:10px;background:{{ $langue->couleur }};
                display:flex;align-items:center;justify-content:center;
                font-size:12px;font-weight:800;color:#fff;">
      {{ strtoupper($langue->code) }}
    </div>
  </div>
  <h2>Commencez votre préparation</h2>
  <p>Vous êtes proche de votre objectif ! Choisissez une série, ensuite sélectionnez
     la discipline et commencez à vous exercer.</p>
</div>

{{-- Légende --}}
<div class="legend">
  <div class="legend-item">
    <div class="legend-dot" style="background:{{ $langue->couleur }};"></div>
    <span>Série disponible</span>
  </div>
  <div class="legend-item">
    <div class="legend-dot" style="background:#e8e8e8;"></div>
    <span><i class="bi bi-lock" style="font-size:10px;"></i> Premium — abonnement requis</span>
  </div>
</div>

{{-- Grille des séries --}}
<div class="series-grid">
  @foreach($series as $serie)
    @php
      $accessible = $serie->gratuite || $aAbonnement;
    @endphp
    @if($accessible)
      <a href="{{ route('langues.disciplines', [$langue->code, $serie->id]) }}"
         class="serie-btn libre">
        {{ $serie->titre }}
      </a>
    @else
      <div class="serie-btn locked"
           onclick="document.getElementById('aboModal').style.display='flex'">
        {{ $serie->titre }}
        <i class="bi bi-lock-fill lock-icon"></i>
      </div>
    @endif
  @endforeach
</div>

{{-- Modal abonnement --}}
<div id="aboModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
            z-index:1000;align-items:center;justify-content:center;padding:1rem;">
  <div style="background:#fff;border-radius:18px;padding:36px;max-width:420px;width:100%;
              text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.2);">
    <div style="width:60px;height:60px;border-radius:50%;background:rgba(245,166,35,.15);
                display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
      <i class="bi bi-lock-fill" style="color:#F5A623;font-size:24px;"></i>
    </div>
    <h3 style="font-size:1.2rem;font-weight:800;color:#1B3A6B;margin-bottom:10px;">
      Série Premium
    </h3>
    <p style="font-size:13px;color:#666;line-height:1.6;margin-bottom:24px;">
      Cette série est réservée aux abonnés. Passez à la version premium pour accéder
      à toutes les séries d'entraînement TCF/TEF.
    </p>
    <div style="display:flex;gap:10px;">
      <button onclick="document.getElementById('aboModal').style.display='none'"
              style="flex:1;padding:12px;border-radius:25px;border:1.5px solid #ddd;
                     background:#fff;color:#666;font-size:13px;font-weight:600;cursor:pointer;">
        Annuler
      </button>
      <a href="{{ route('tcf.abonnement') }}"
         style="flex:1;padding:12px;border-radius:25px;background:#F5A623;
                color:#1B3A6B;font-size:13px;font-weight:700;text-decoration:none;
                display:flex;align-items:center;justify-content:center;">
        <i class="bi bi-lightning-fill me-1"></i>S'abonner
      </a>
    </div>
  </div>
</div>

@endsection