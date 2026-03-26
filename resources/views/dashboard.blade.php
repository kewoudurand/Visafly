@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('content')

<!-- En-tête -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;">
      Bonjour, {{ $user->first_name }} 👋
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">
      {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
    </p>
  </div>
  <div style="display:flex;align-items:center;gap:6px;">
    @foreach(Auth::user()->getRoleNames() as $role)
      <span class="badge rounded-pill"
            style="background:rgba(27,58,107,.1);color:#1B3A6B;font-size:11px;">
        {{ $role }}
      </span>
    @endforeach
  </div>
</div>

<!-- ══ WIDGETS STUDENT — Tests ══ -->
@if(in_array('tests', $widgets))
<div class="row g-3 mb-4">

  <div class="col-md-3">
    <div class="p-4 rounded-3 h-100"
         style="background:#fff;border:1px solid rgba(27,58,107,.1);
                box-shadow:0 2px 12px rgba(27,58,107,.06);">
      <div style="font-size:11px;font-weight:600;color:#888;
                  text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px;">
        Épreuves terminées
      </div>
      <div style="font-size:2.4rem;font-weight:800;color:#1B3A6B;line-height:1;">
        {{ $stats['tests_passes'] ?? 0 }}
      </div>
      <div style="font-size:12px;color:#888;margin-top:6px;">sur toutes les séries</div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="p-4 rounded-3 h-100"
         style="background:#fff;border:1px solid rgba(27,58,107,.1);
                box-shadow:0 2px 12px rgba(27,58,107,.06);">
      <div style="font-size:11px;font-weight:600;color:#888;
                  text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px;">
        Score moyen
      </div>
      <div style="font-size:2.4rem;font-weight:800;color:#1cc88a;line-height:1;">
        {{ $stats['score_moyen'] ?? 0 }}<small style="font-size:1rem;">%</small>
      </div>
      <div style="font-size:12px;color:#888;margin-top:6px;">sur toutes les épreuves</div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="p-4 rounded-3 h-100"
         style="background:#fff;border:1px solid rgba(245,166,35,.2);
                box-shadow:0 2px 12px rgba(245,166,35,.08);">
      <div style="font-size:11px;font-weight:600;color:#888;
                  text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px;">
        En cours
      </div>
      <div style="font-size:2.4rem;font-weight:800;color:#F5A623;line-height:1;">
        {{ $stats['en_cours'] ?? 0 }}
      </div>
      <div style="font-size:12px;color:#888;margin-top:6px;">épreuve(s) non terminée(s)</div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="p-4 rounded-3 h-100 d-flex flex-column justify-content-between"
         style="background:#1B3A6B;box-shadow:0 4px 20px rgba(27,58,107,.25);">
      <div style="font-size:13px;font-weight:600;color:rgba(255,255,255,.8);margin-bottom:12px;">
        Commencer une épreuve
      </div>
      <a href="{{ route('tcf.index') }}"
         class="btn btn-sm rounded-pill fw-semibold"
         style="background:#F5A623;color:#1B3A6B;border:none;align-self:flex-start;">
        Accéder au TCF →
      </a>
    </div>
  </div>

</div>
@endif

<!-- ══ WIDGETS ADMIN — Analytics ══ -->
@if(in_array('analytics', $widgets))
<div class="row g-3 mb-4">
  <div class="col-12">
    <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                letter-spacing:.8px;margin-bottom:10px;">
      Administration plateforme
    </div>
  </div>

  <div class="col-md-3">
    <div class="p-4 rounded-3" style="background:#fff;border:1px solid rgba(27,58,107,.1);">
      <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                  letter-spacing:.6px;margin-bottom:8px;">Utilisateurs</div>
      <div style="font-size:2rem;font-weight:800;color:#1B3A6B;">
        {{ $stats['total_users'] ?? 0 }}
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="p-4 rounded-3"
         style="background:#fff;border:1px solid rgba(27,58,107,.1);">
      <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                  letter-spacing:.6px;margin-bottom:8px;">Épreuves totales</div>
      <div style="font-size:2rem;font-weight:800;color:#1B3A6B;">
        {{ $stats['total_passages'] ?? 0 }}
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="p-4 rounded-3 d-flex gap-3"
         style="background:#fff;border:1px solid rgba(27,58,107,.1);">
      @can('manage users')
      <a href="#" class="btn rounded-pill btn-sm"
         style="background:rgba(27,58,107,.08);color:#1B3A6B;font-size:12px;">
        <i class="bi bi-people me-1"></i>Gérer utilisateurs
      </a>
      @endcan
      @can('create test')
      <a href="#" class="btn rounded-pill btn-sm"
         style="background:rgba(245,166,35,.12);color:#854F0B;font-size:12px;">
        <i class="bi bi-plus-circle me-1"></i>Créer un test
      </a>
      @endcan
    </div>
  </div>
</div>
@endif

<!-- ══ WIDGET Consultation ══ -->
@if(in_array('consultations', $widgets))
  <div class="p-4 rounded-3 mb-4"
      style="background:#fff;border:1px solid rgba(245,166,35,.2);">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <div style="font-size:14px;font-weight:600;color:#1B3A6B;">
          <i class="bi bi-calendar-check me-2"></i>Mes consultations
        </div>
        <div style="font-size:12px;color:#888;margin-top:4px;">
          Aucune consultation réservée pour le moment.
        </div>
      </div>
      <a href="{{ route('consultations.create') }}"
        class="btn rounded-pill btn-sm fw-semibold"
        style="background:#F5A623;color:#1B3A6B;border:none;">
        Réserver →
      </a>
    </div>
  </div>
@endif

@endsection