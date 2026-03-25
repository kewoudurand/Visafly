{{-- resources/views/tcf/index.blade.php --}}
@extends('layouts.app')

@section('title', 'TCF — Préparation VisaFly')

@section('content')
<div class="container py-4">

  {{-- En-tête --}}
  <div class="text-center mb-4">
    <h2 class="fw-bold" style="color:#1B3A6B;">Commencez votre préparation</h2>
    <p class="text-muted">Choisissez une série, sélectionnez la discipline et commencez à vous exercer.</p>
  </div>

  {{-- Alertes --}}
  @if(session('error'))
    <div class="alert alert-warning d-flex align-items-center gap-2 rounded-3 mb-4">
      <i class="bi bi-exclamation-triangle-fill"></i>
      {{ session('error') }}
    </div>
  @endif

  {{-- Bandeau abonnement si non abonné --}}
  @if(!$aAbonnement)
    <div class="alert mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3"
         style="background:rgba(245,166,35,.1);border:1px solid rgba(245,166,35,.4);">
      <div class="d-flex align-items-center gap-3">
        <div style="width:40px;height:40px;background:#F5A623;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="bi bi-star-fill text-white"></i>
        </div>
        <div>
          <div class="fw-semibold" style="color:#633806;">
            {{ $passagesGratuits >= 2 ? 'Limite gratuite atteinte' : "Vous avez {$passagesGratuits}/2 épreuves gratuites utilisées" }}
          </div>
          <small style="color:#854F0B;">Abonnez-vous pour accéder à toutes les séries — 5 000 XAF / mois</small>
        </div>
      </div>
      <a href="{{ route('tcf.abonnement') }}" class="btn btn-warning fw-semibold px-4"
         style="border-radius:20px;color:#1B3A6B;">
        <i class="bi bi-unlock me-1"></i> S'abonner
      </a>
    </div>
  @else
    <div class="alert d-flex align-items-center gap-2 mb-4 rounded-3"
         style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
      <i class="bi bi-patch-check-fill"></i>
      <span>Abonnement actif — Accès illimité à toutes les séries.</span>
    </div>
  @endif

  {{-- Grille des séries --}}
  <div class="row g-3">
    @foreach($series as $serie)
      @php
        $accessible = $serie->gratuit
          ? ($aAbonnement || $passagesGratuits < 2)
          : $aAbonnement;
      @endphp

      <div class="col-md-6">
        @if($accessible)
          <a href="{{ route('tcf.disciplines', $serie) }}"
             class="d-flex align-items-center justify-content-between text-decoration-none p-4 rounded-3 fw-semibold"
             style="background:#1B3A6B;color:#fff;transition:all .2s;"
             onmouseover="this.style.background='#152d54'"
             onmouseout="this.style.background='#1B3A6B'">
            <span>{{ $serie->nom }}</span>
            <div class="d-flex align-items-center gap-2">
              @if($serie->gratuit && !$aAbonnement)
                <span class="badge" style="background:#F5A623;color:#1B3A6B;font-size:10px;">Gratuit</span>
              @endif
              <i class="bi bi-arrow-right-circle"></i>
            </div>
          </a>
        @else
          <div class="d-flex align-items-center justify-content-between p-4 rounded-3 fw-semibold"
               style="background:#f0f0f0;color:#999;border:1px solid #e0e0e0;cursor:not-allowed;">
            <span>{{ $serie->nom }}</span>
            <div class="d-flex align-items-center gap-2">
              @if($serie->gratuit && $passagesGratuits >= 2)
                <span class="badge bg-secondary" style="font-size:10px;">Limite atteinte</span>
              @endif
              <i class="bi bi-lock-fill"></i>
            </div>
          </div>
        @endif
      </div>
    @endforeach
  </div>

</div>
@endsection
