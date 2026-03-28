{{-- resources/views/admin/analytics/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Analytics — VisaFly')

@push('styles')
<style>
.an-card{background:#fff;border-radius:14px;border:1px solid #eee;
         padding:22px;box-shadow:0 2px 12px rgba(27,58,107,.05);margin-bottom:20px;}
.an-title{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:16px;
          display:flex;align-items:center;gap:7px;padding-bottom:12px;
          border-bottom:2px solid rgba(27,58,107,.06);}
.kpi{background:#fff;border-radius:14px;border:1px solid #eee;padding:20px;
     box-shadow:0 2px 8px rgba(27,58,107,.05);}
.kpi-num{font-size:2rem;font-weight:800;line-height:1;margin-bottom:4px;}
.kpi-lbl{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}
.kpi-sub{font-size:11px;color:#aaa;margin-top:4px;}
.trend-up  {color:#1cc88a;font-size:11px;font-weight:600;}
.trend-down{color:#E24B4A;font-size:11px;font-weight:600;}
.bar-row{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.bar-label{font-size:12px;color:#555;min-width:80px;text-align:right;}
.bar-track{flex:1;height:8px;background:#f0f0f0;border-radius:4px;overflow:hidden;}
.bar-fill{height:100%;border-radius:4px;transition:width .6s ease;}
.bar-val{font-size:12px;font-weight:600;color:#1B3A6B;min-width:32px;}
.stat-pill{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;
           border-radius:20px;font-size:12px;font-weight:600;margin:3px;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Analytics</h2>
    <p class="text-muted mb-0" style="font-size:13px;">Vue d'ensemble de la plateforme VisaFly</p>
  </div>
  <div style="font-size:12px;color:#888;">
    <i class="bi bi-clock me-1"></i>Mis à jour : {{ now()->format('d/m/Y H:i') }}
  </div>
</div>

{{-- ══ KPIs principaux ══ --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="kpi">
      <div class="kpi-num" style="color:#1B3A6B;">{{ $users['total'] }}</div>
      <div class="kpi-lbl">Utilisateurs</div>
      <div class="kpi-sub">
        <span class="trend-up">+{{ $users['ce_mois'] }}</span> ce mois
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="kpi">
      <div class="kpi-num" style="color:#F5A623;">{{ $tests['total'] }}</div>
      <div class="kpi-lbl">Épreuves TCF</div>
      <div class="kpi-sub">Score moy. <strong>{{ $tests['score_moyen'] }}%</strong></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="kpi">
      <div class="kpi-num" style="color:#1cc88a;">{{ $consultations['total'] }}</div>
      <div class="kpi-lbl">Consultations</div>
      <div class="kpi-sub">{{ $consultations['approuvees'] }} approuvées</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="kpi" style="background:#1B3A6B;border-color:#1B3A6B;">
      <div class="kpi-num" style="color:#F5A623;">
        {{ number_format($revenus['total'], 0, ',', ' ') }}
      </div>
      <div class="kpi-lbl" style="color:rgba(255,255,255,.6);">Revenus XAF</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:4px;">
        {{ number_format($revenus['ce_mois'], 0, ',', ' ') }} XAF ce mois
      </div>
    </div>
  </div>
</div>

<div class="row g-4">

  {{-- ══ Colonne gauche ══ --}}
  <div class="col-lg-8">

    {{-- Évolution inscriptions + épreuves --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-graph-up" style="color:#F5A623;"></i>
        Évolution sur 6 mois
      </div>
      <canvas id="evolutionChart" height="110"></canvas>
    </div>

    {{-- Répartition consultations --}}
    <div class="row g-3">
      <div class="col-md-6">
        <div class="an-card">
          <div class="an-title">
            <i class="bi bi-calendar-check" style="color:#F5A623;"></i>
            Consultations par statut
          </div>
          @php
            $total_c = max($consultations['total'], 1);
            $statuts = [
              ['En attente', $consultations['en_attente'], '#F5A623'],
              ['Approuvées', $consultations['approuvees'], '#1cc88a'],
              ['Déclinées',  $consultations['declinee'],  '#E24B4A'],
              ['Terminées',  $consultations['terminee'],  '#7F77DD'],
            ];
          @endphp
          @foreach($statuts as [$label, $val, $color])
          <div class="bar-row">
            <span class="bar-label" style="color:#666;">{{ $label }}</span>
            <div class="bar-track">
              <div class="bar-fill"
                   style="width:{{ round($val/$total_c*100) }}%;background:{{ $color }};"></div>
            </div>
            <span class="bar-val">{{ $val }}</span>
          </div>
          @endforeach
        </div>
      </div>

      <div class="col-md-6">
        <div class="an-card">
          <div class="an-title">
            <i class="bi bi-journal-text" style="color:#F5A623;"></i>
            Scores TCF par tranche
          </div>
          @php
            $total_t = max($tests['terminees'], 1);
            $tranches = [
              ['0–20%',   $tests['par_score']['0-20'],  '#E24B4A'],
              ['21–40%',  $tests['par_score']['21-40'], '#F5A623'],
              ['41–60%',  $tests['par_score']['41-60'], '#54a3f3'],
              ['61–80%',  $tests['par_score']['61-80'], '#1cc88a'],
              ['81–100%', $tests['par_score']['81-100'],'#1B3A6B'],
            ];
          @endphp
          @foreach($tranches as [$label, $val, $color])
          <div class="bar-row">
            <span class="bar-label" style="color:#666;">{{ $label }}</span>
            <div class="bar-track">
              <div class="bar-fill"
                   style="width:{{ round($val/$total_t*100) }}%;background:{{ $color }};"></div>
            </div>
            <span class="bar-val">{{ $val }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Revenus par mois --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-currency-dollar" style="color:#F5A623;"></i>
        Revenus abonnements (6 mois)
      </div>
      <canvas id="revenusChart" height="90"></canvas>
    </div>

  </div>

  {{-- ══ Colonne droite ══ --}}
  <div class="col-lg-4">

    {{-- Répartition utilisateurs par rôle --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-people" style="color:#F5A623;"></i>
        Utilisateurs par rôle
      </div>
      @php
        $roleColors = [
          'super-admin' => '#1B3A6B',
          'admin'       => '#54a3f3',
          'consultant'  => '#F5A623',
          'instructor'  => '#7F77DD',
          'student'     => '#1cc88a',
          'partner'     => '#E24B4A',
        ];
        $total_u = max($users['total'], 1);
      @endphp
      <canvas id="rolesChart" height="180"></canvas>
      <div style="margin-top:14px;">
        @foreach($users['par_role'] as $r)
        <div class="bar-row">
          <span class="bar-label" style="color:#666;">{{ $r->role }}</span>
          <div class="bar-track">
            <div class="bar-fill"
                 style="width:{{ round($r->total/$total_u*100) }}%;
                        background:{{ $roleColors[$r->role] ?? '#888' }};"></div>
          </div>
          <span class="bar-val">{{ $r->total }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Abonnements --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-credit-card" style="color:#F5A623;"></i>
        Abonnements actifs
      </div>
      <div style="text-align:center;margin-bottom:14px;">
        <div style="font-size:2.5rem;font-weight:800;color:#1cc88a;">{{ $revenus['actifs'] }}</div>
        <div style="font-size:12px;color:#888;">abonnements actifs</div>
      </div>
      @foreach($revenus['par_forfait'] as $f)
      <div style="display:flex;align-items:center;justify-content:space-between;
                  padding:9px 12px;border-radius:9px;margin-bottom:6px;
                  background:rgba(27,58,107,.04);">
        <div>
          <div style="font-size:13px;font-weight:600;color:#1B3A6B;">{{ ucfirst($f->forfait) }}</div>
          <div style="font-size:11px;color:#888;">{{ $f->total }} abonné(s)</div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:13px;font-weight:700;color:#1B3A6B;">
            {{ number_format($f->revenus, 0, ',', ' ') }}
          </div>
          <div style="font-size:10px;color:#888;">XAF</div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Consultations par type --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-pie-chart" style="color:#F5A623;"></i>
        Consultations par type
      </div>
      @php
        $typeLabels = ['immigration'=>'Immigration','visa'=>'Visa','bourse'=>'Bourse','langue'=>'Langue','autre'=>'Autre'];
        $typeColors = ['immigration'=>'#1B3A6B','visa'=>'#54a3f3','bourse'=>'#1cc88a','langue'=>'#F5A623','autre'=>'#888'];
      @endphp
      {{-- @forelse($consultations['par_type'] as $t)
      <div class="bar-row">
        <span class="bar-label" style="color:#666;">{{ $typeLabels[$t->type] ?? $t->type }}</span>
        <div class="bar-track">
          <div class="bar-fill"
               style="width:{{ round($t->total/$total_c*100) }}%;
                      background:{{ $typeColors[$t->type] ?? '#888' }};"></div>
        </div>
        <span class="bar-val">{{ $t->total }}</span>
      </div>
      @empty
      <p style="font-size:13px;color:#aaa;text-align:center;padding:12px 0;">Aucune donnée</p>
      @endforelse --}}
    </div>

  </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  const marine = '#1B3A6B';
  const or     = '#F5A623';
  const green  = '#1cc88a';
  const font   = { family: 'system-ui, sans-serif', size: 12 };

  // ── Données depuis PHP ──
  const moisLabels = @json($users['par_mois']->pluck('mois'));
  const moisUsers  = @json($users['par_mois']->pluck('total'));
  const moisTests  = @json($tests['par_mois']->pluck('total'));
  const moisRev    = @json($revenus['par_mois']->pluck('total'));
  const rolesData  = @json($users['par_role']);

  // ── Chart 1 : Évolution 6 mois ──
  new Chart(document.getElementById('evolutionChart'), {
    type: 'line',
    data: {
      labels: moisLabels,
      datasets: [
        {
          label: 'Inscriptions',
          data: moisUsers,
          borderColor: marine,
          backgroundColor: 'rgba(27,58,107,.08)',
          tension: .4, fill: true,
          pointBackgroundColor: marine, pointRadius: 4,
        },
        {
          label: 'Épreuves TCF',
          data: moisTests,
          borderColor: or,
          backgroundColor: 'rgba(245,166,35,.06)',
          tension: .4, fill: true,
          pointBackgroundColor: or, pointRadius: 4,
        },
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { labels: { font, color: '#555', boxWidth: 12 } },
      },
      scales: {
        x: { ticks: { font, color: '#888' }, grid: { display: false } },
        y: { ticks: { font, color: '#888' }, grid: { color: '#f5f5f5' }, beginAtZero: true },
      }
    }
  });

  // ── Chart 2 : Revenus ──
  new Chart(document.getElementById('revenusChart'), {
    type: 'bar',
    data: {
      labels: @json($revenus['par_mois']->pluck('mois')),
      datasets: [{
        label: 'Revenus XAF',
        data: moisRev,
        backgroundColor: 'rgba(27,58,107,.7)',
        borderRadius: 6,
        borderSkipped: false,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { font, color: '#888' }, grid: { display: false } },
        y: { ticks: { font, color: '#888' }, grid: { color: '#f5f5f5' }, beginAtZero: true },
      }
    }
  });

  // ── Chart 3 : Rôles (donut) ──
  new Chart(document.getElementById('rolesChart'), {
    type: 'doughnut',
    data: {
      labels: rolesData.map(r => r.role),
      datasets: [{
        data: rolesData.map(r => r.total),
        backgroundColor: ['#1B3A6B','#54a3f3','#F5A623','#7F77DD','#1cc88a','#E24B4A'],
        borderWidth: 2,
        borderColor: '#fff',
      }]
    },
    options: {
      responsive: true,
      cutout: '65%',
      plugins: {
        legend: { position: 'bottom', labels: { font, color: '#555', boxWidth: 10, padding: 12 } },
      }
    }
  });

});
</script>
@endpush

@endsection