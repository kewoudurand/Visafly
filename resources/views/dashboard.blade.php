@extends('layouts.app')
@section('title', 'Dashboard — VisaFly')

@section('content')
<div class="container py-4">

  <!-- En-tête personnalisé -->
  <div class="mb-4">
    <h2 class="fw-bold" style="color:#1B3A6B;">
      Bonjour, {{ Auth::user()->first_name }} 👋
    </h2>
    <p class="text-muted">Votre espace personnel VisaFly</p>
  </div>

  <div class="row g-4">

    @if(in_array('tests', $widgets))
    <div class="col-md-4">
      <div class="card border-0 rounded-3 p-4 h-100"
           style="background:rgba(27,58,107,.05);border:1px solid rgba(27,58,107,.1)!important;">
        <div class="fw-semibold mb-1" style="color:#1B3A6B;">
          <i class="bi bi-journal-check me-2"></i>Mes épreuves TCF
        </div>
        <div style="font-size:2rem;font-weight:800;color:#1B3A6B;">
          {{ $stats['tests_passes'] ?? 0 }}
        </div>
        <small class="text-muted">épreuves terminées</small>
        <a href="{{ route('tcf.index') }}"
           class="btn btn-sm rounded-pill mt-3"
           style="background:#1B3A6B;color:#fff;">
          Commencer une épreuve
        </a>
      </div>
    </div>
    @endif

    @if(in_array('consultations', $widgets))
    <div class="col-md-4">
      <div class="card border-0 rounded-3 p-4 h-100"
           style="background:rgba(245,166,35,.06);border:1px solid rgba(245,166,35,.2)!important;">
        <div class="fw-semibold mb-1" style="color:#633806;">
          <i class="bi bi-calendar-check me-2"></i>Consultations
        </div>
        <div style="font-size:2rem;font-weight:800;color:#854F0B;">0</div>
        <small class="text-muted">consultations réservées</small>
        <a href="{{ route('consultations.create') }}"
           class="btn btn-sm rounded-pill mt-3"
           style="background:#F5A623;color:#1B3A6B;">
          Réserver une consultation
        </a>
      </div>
    </div>
    @endif

    @if(in_array('analytics', $widgets))
    <div class="col-md-12">
      <div class="card border-0 rounded-3 p-4"
           style="border:1px solid rgba(27,58,107,.1)!important;">
        <div class="fw-semibold mb-3" style="color:#1B3A6B;">
          <i class="bi bi-bar-chart-line me-2"></i>Analytics plateforme
          <span class="badge bg-warning text-dark ms-2">Admin</span>
        </div>
        <p class="text-muted">Statistiques globales de la plateforme...</p>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection