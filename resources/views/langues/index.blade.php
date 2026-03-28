{{-- resources/views/langues/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Choisissez votre examen — VisaFly')

@push('styles')
<style>
.exam-hero{text-align:center;padding:60px 20px 40px;}
.exam-hero h1{font-size:2rem;font-weight:800;color:#1B3A6B;margin-bottom:12px;}
.exam-hero p{font-size:15px;color:#666;max-width:620px;margin:0 auto;line-height:1.7;}

.exam-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;
           max-width:860px;margin:0 auto;padding:0 20px 60px;}
@media(max-width:640px){.exam-grid{grid-template-columns:1fr;}}

.exam-card{border-radius:16px;padding:22px 24px;display:flex;align-items:center;
           gap:18px;cursor:pointer;text-decoration:none;transition:all .25s;
           box-shadow:0 4px 16px rgba(0,0,0,.08);}
.exam-card:hover{transform:translateY(-3px);box-shadow:0 10px 32px rgba(0,0,0,.14);}
.exam-card.orange{background:#F5A623;color:#1B3A6B;}
.exam-card.marine{background:#1B3A6B;color:#fff;}

.exam-card-icon{width:54px;height:54px;border-radius:12px;
                background:rgba(255,255,255,.25);
                display:flex;align-items:center;justify-content:center;
                font-size:22px;flex-shrink:0;}
.exam-card-lang{font-size:10px;font-weight:700;text-transform:uppercase;
                letter-spacing:.8px;opacity:.75;display:flex;align-items:center;gap:5px;}
.exam-card-title{font-size:18px;font-weight:800;margin:3px 0;}
.exam-card-sub{font-size:12px;opacity:.75;}
.exam-card-arrow{margin-left:auto;font-size:20px;opacity:.6;}
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="exam-hero">
  <h1>Commencez votre préparation</h1>
  <p>Chez VisaFly, nous savons qu'un projet d'immigration ne s'improvise pas.
     Chaque parcours est unique, et chaque pays exige une stratégie bien définie
     en passant par les tests de langues.</p>
</section>

{{-- 4 cartes --}}
<div class="exam-grid">

  {{-- TCF --}}
  <a href="{{ route('langues.series', 'tcf') }}" class="exam-card orange">
    <div class="exam-card-icon">
      <i class="bi bi-pencil-square"></i>
    </div>
    <div>
      <div class="exam-card-lang">🇫🇷 Français</div>
      <div class="exam-card-title">Commencer le TCF</div>
      <div class="exam-card-sub">Test de Connaissance du Français</div>
    </div>
    <div class="exam-card-arrow"><i class="bi bi-arrow-right-circle-fill"></i></div>
  </a>

  {{-- TEF --}}
  <a href="{{ route('langues.series', 'tef') }}" class="exam-card orange">
    <div class="exam-card-icon">
      <i class="bi bi-pencil-square"></i>
    </div>
    <div>
      <div class="exam-card-lang">🇨🇦 Français</div>
      <div class="exam-card-title">Commencer le TEF</div>
      <div class="exam-card-sub">Test d'Évaluation du Français</div>
    </div>
    <div class="exam-card-arrow"><i class="bi bi-arrow-right-circle-fill"></i></div>
  </a>

  {{-- IELTS --}}
  <a href="{{ route('langues.series', 'ielts') }}" class="exam-card marine">
    <div class="exam-card-icon">
      <i class="bi bi-translate"></i>
    </div>
    <div>
      <div class="exam-card-lang">🇬🇧 Anglais</div>
      <div class="exam-card-title">Passer l'IELTS</div>
      <div class="exam-card-sub">Test de langue anglaise</div>
    </div>
    <div class="exam-card-arrow"><i class="bi bi-arrow-right-circle-fill"></i></div>
  </a>

  {{-- Goethe --}}
  <a href="{{ route('langues.series', 'goethe') }}" class="exam-card marine">
    <div class="exam-card-icon">
      <i class="bi bi-globe"></i>
    </div>
    <div>
      <div class="exam-card-lang">🇩🇪 Allemand</div>
      <div class="exam-card-title">Passer le Goethe</div>
      <div class="exam-card-sub">Test de langue allemande</div>
    </div>
    <div class="exam-card-arrow"><i class="bi bi-arrow-right-circle-fill"></i></div>
  </a>

</div>
@endsection