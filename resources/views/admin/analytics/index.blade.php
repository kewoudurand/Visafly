{{-- resources/views/admin/analytics/index.blade.php - CORRIGÉE --}}
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
.trend-up{color:#1cc88a;font-size:11px;font-weight:600;}
.bar-row{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.bar-label{font-size:12px;color:#555;min-width:80px;text-align:right;}
.bar-track{flex:1;height:8px;background:#f0f0f0;border-radius:4px;overflow:hidden;}
.bar-fill{height:100%;border-radius:4px;transition:width .6s ease;}
.bar-val{font-size:12px;font-weight:600;color:#1B3A6B;min-width:32px;}

/* Tableau passages */
.table-an th{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
             letter-spacing:.6px;border:none;padding:11px 14px;background:#f8f9fb;}
.table-an td{padding:12px 14px;vertical-align:middle;border-bottom:1px solid #f5f5f5;font-size:13px;}
.table-an tr:hover td{background:rgba(27,58,107,.02);}
.exam-badge{display:inline-flex;align-items:center;justify-content:center;
            width:34px;height:22px;border-radius:5px;font-size:9px;font-weight:900;color:#fff;}
.score-pill{padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.s-green{background:rgba(28,200,138,.1);color:#0f6e56;}
.s-orange{background:rgba(245,166,35,.1);color:#633806;}
.s-red{background:rgba(226,75,74,.08);color:#a32d2d;}
.s-blue{background:rgba(27,58,107,.1);color:#1B3A6B;}
.user-link{color:#1B3A6B;font-weight:600;text-decoration:none;}
.user-link:hover{text-decoration:underline;color:#F5A623;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Analytics</h2>
    <p class="text-muted mb-0" style="font-size:13px;">Vue d'ensemble de la plateforme VisaFly</p>
  </div>
  <div style="font-size:12px;color:#888;">
    <i class="bi bi-clock me-1"></i>{{ now()->format('d/m/Y H:i') }}
  </div>
</div>

{{-- ══ KPIs ══ --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="kpi">
      <div class="kpi-num" style="color:#1B3A6B;">{{ $users['total'] ?? 0 }}</div>
      <div class="kpi-lbl">Utilisateurs</div>
      <div class="kpi-sub"><span class="trend-up">+{{ $users['ce_mois'] ?? 0 }}</span> ce mois</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="kpi">
      <div class="kpi-num" style="color:#F5A623;">{{ $tests['total'] ?? 0 }}</div>
      <div class="kpi-lbl">Passages langue</div>
      <div class="kpi-sub">Score moy. <strong>{{ $tests['score_moyen'] ?? 0 }}%</strong></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="kpi">
      <div class="kpi-num" style="color:#1cc88a;">{{ $consultations['total'] ?? 0 }}</div>
      <div class="kpi-lbl">Consultations</div>
      <div class="kpi-sub">{{ $consultations['approuvees'] ?? 0 }} approuvées</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="kpi" style="background:#1B3A6B;border-color:#1B3A6B;">
      <div class="kpi-num" style="color:#F5A623;">{{ number_format((int)($revenus['total'] ?? 0),0,',',' ') }}</div>
      <div class="kpi-lbl" style="color:rgba(255,255,255,.6);">Revenus XAF</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:4px;">
        {{ number_format((int)($revenus['ce_mois'] ?? 0),0,',',' ') }} XAF ce mois
      </div>
    </div>
  </div>
</div>

<div class="row g-4">

  {{-- ══ Colonne gauche ══ --}}
  <div class="col-lg-8">

    {{-- Graphe évolution --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-graph-up" style="color:#F5A623;"></i>Évolution sur 6 mois
      </div>
      <canvas id="evolutionChart" height="110"></canvas>
    </div>

    {{-- ══ TABLEAU PASSAGES LANGUE ══ --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-journal-check" style="color:#F5A623;"></i>
        Derniers passages aux examens
        <a href="#"
           style="margin-left:auto;font-size:11px;color:#F5A623;font-weight:600;text-decoration:none;">
          Voir tout →
        </a>
      </div>
      <div style="overflow-x:auto;">
      <table class="table table-an mb-0">
        <thead>
          <tr>
            <th>Étudiant</th>
            <th>Examen</th>
            <th>Série / Discipline</th>
            <th>Score</th>
            <th>Date</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse(($tests['derniers_passages'] ?? collect()) as $p)
          @php
            $sc = $p->score ?? 0;
            $scClass = $sc >= 80 ? 's-blue' : ($sc >= 60 ? 's-green' : ($sc >= 40 ? 's-orange' : 's-red'));
            $lang = $p->langue;
          @endphp
          <tr>
            <td>
              <a href="{{ route('admin.analytics.user', $p->user_id) }}" class="user-link">
                {{ $p->user?->first_name ?? '—' }}
              </a>
              <div style="font-size:11px;color:#888;">{{ $p->user?->email ?? '' }}</div>
            </td>
            <td>
              @if($lang)
              <span class="exam-badge" style="background:{{ $lang->couleur ?? '#999' }};">
                {{ strtoupper($lang->code ?? 'N/A') }}
              </span>
              @else
              <span style="color:#aaa;font-size:11px;">—</span>
              @endif
            </td>
            <td>
              <div style="font-size:12px;font-weight:600;color:#333;">{{ $p->serie?->titre ?? $p->serie?->nom ?? '—' }}</div>
              <div style="font-size:11px;color:#888;">{{ $p->discipline?->nom ?? '' }}</div>
            </td>
            <td>
              @if($sc !== null)
              <span class="score-pill {{ $scClass }}">{{ (int)$sc }}%</span>
              @else<span style="color:#aaa;font-size:12px;">—</span>@endif
            </td>
            <td style="font-size:12px;color:#888;">{{ $p->created_at->format('d/m/Y') }}</td>
            <td>
              <a href="{{ route('admin.analytics.user', $p->user_id) }}"
                 style="font-size:11px;color:#1B3A6B;text-decoration:none;font-weight:600;">
                Profil →
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-3" style="color:#aaa;font-size:13px;">
              Aucun passage enregistré
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
      </div>
    </div>

    {{-- Stats consultations --}}
    <div class="row g-3">
      <div class="col-md-6">
        <div class="an-card">
          <div class="an-title">
            <i class="bi bi-calendar-check" style="color:#F5A623;"></i>Consultations par statut
          </div>
          @php
            $total_c = max((int)($consultations['total'] ?? 0), 1);
            $statuts = [
              ['En attente', $consultations['en_attente'] ?? 0, '#F5A623'],
              ['Approuvées', $consultations['approuvees'] ?? 0, '#1cc88a'],
              ['Déclinées',  $consultations['declinee'] ?? 0,  '#E24B4A'],
              ['Terminées',  $consultations['terminee'] ?? 0,  '#7F77DD'],
            ];
          @endphp
          @foreach($statuts as [$label, $val, $color])
          <div class="bar-row">
            <span class="bar-label">{{ $label }}</span>
            <div class="bar-track">
              <div class="bar-fill" style="width:{{ round(($val/$total_c)*100) }}%;background:{{ $color }};"></div>
            </div>
            <span class="bar-val">{{ $val }}</span>
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-md-6">
        <div class="an-card">
          <div class="an-title">
            <i class="bi bi-bar-chart" style="color:#F5A623;"></i>Scores par tranche
          </div>
          @php
            $total_t = max((int)(($tests['terminees'] ?? 0)), 1);
            $tranches = [
              ['81-100%', $tests['par_score']['81-100'] ?? 0,'#1B3A6B'],
              ['61-80%',  $tests['par_score']['61-80'] ?? 0, '#1cc88a'],
              ['41-60%',  $tests['par_score']['41-60'] ?? 0, '#F5A623'],
              ['21-40%',  $tests['par_score']['21-40'] ?? 0, '#E24B4A'],
              ['0-20%',   $tests['par_score']['0-20'] ?? 0,  '#aaa'],
            ];
          @endphp
          @foreach($tranches as [$label, $val, $color])
          <div class="bar-row">
            <span class="bar-label">{{ $label }}</span>
            <div class="bar-track">
              <div class="bar-fill" style="width:{{ round(($val/$total_t)*100) }}%;background:{{ $color }};"></div>
            </div>
            <span class="bar-val">{{ $val }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Revenus --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-currency-dollar" style="color:#F5A623;"></i>Revenus abonnements (6 mois)
      </div>
      <canvas id="revenusChart" height="90"></canvas>
    </div>

  </div>

  {{-- ══ Colonne droite ══ --}}
  <div class="col-lg-4">

    {{-- Passages par examen --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-translate" style="color:#F5A623;"></i>Passages par examen
      </div>
      @php $totalPassages = max((int)($tests['total'] ?? 0), 1); @endphp
      @forelse(($tests['par_langue'] ?? collect()) as $pl)
      @php $pct = round(($pl->total / $totalPassages) * 100); @endphp
      <div class="bar-row">
        <span style="display:inline-flex;align-items:center;justify-content:center;
                     width:34px;height:22px;border-radius:5px;
                     background:{{ $pl->couleur ?? '#999' }};color:#fff;
                     font-size:9px;font-weight:900;flex-shrink:0;">
          {{ strtoupper($pl->code ?? 'N/A') }}
        </span>
        <div class="bar-track">
          <div class="bar-fill"
               style="width:{{ $pct }}%;background:{{ $pl->couleur ?? '#999' }};"></div>
        </div>
        <span class="bar-val">{{ $pl->total }}</span>
      </div>
      @empty
      <p style="font-size:13px;color:#aaa;text-align:center;padding:12px 0;">Aucun passage</p>
      @endforelse
    </div>

    {{-- Rôles --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-people" style="color:#F5A623;"></i>Utilisateurs par rôle
      </div>
      <canvas id="rolesChart" height="180"></canvas>
      <div style="margin-top:14px;">
        @forelse(($users['par_role'] ?? collect()) as $r)
        @php $roleColors = ['super-admin'=>'#1B3A6B','admin'=>'#54a3f3','consultant'=>'#F5A623','instructor'=>'#7F77DD','student'=>'#1cc88a','partner'=>'#E24B4A']; @endphp
        <div class="bar-row">
          <span class="bar-label">{{ ucfirst($r->role) }}</span>
          <div class="bar-track">
            <div class="bar-fill"
                 style="width:{{ round(($r->total/max((int)($users['total'] ?? 0),1))*100) }}%;
                        background:{{ $roleColors[$r->role] ?? '#888' }};"></div>
          </div>
          <span class="bar-val">{{ $r->total }}</span>
        </div>
        @empty
        <p style="font-size:12px;color:#aaa;">Aucun utilisateur</p>
        @endforelse
      </div>
    </div>

    {{-- Abonnements ✅ CORRIGÉ --}}
    <div class="an-card">
      <div class="an-title">
        <i class="bi bi-credit-card" style="color:#F5A623;"></i>Abonnements actifs
      </div>
      <div style="text-align:center;margin-bottom:14px;">
        <div style="font-size:2.5rem;font-weight:800;color:#1cc88a;">{{ $revenus['actifs'] ?? 0 }}</div>
        <div style="font-size:12px;color:#888;">abonnements actifs</div>
      </div>
      @forelse(($revenus['par_plan'] ?? collect()) as $p)
      <div style="display:flex;align-items:center;justify-content:space-between;
                  padding:9px 12px;border-radius:9px;margin-bottom:6px;
                  background:rgba(27,58,107,.04);">
        <div>
          <div style="font-size:13px;font-weight:600;color:#1B3A6B;">{{ ucfirst($p->nom ?? 'Plan') }}</div>
          <div style="font-size:11px;color:#888;">{{ $p->total ?? 0 }} abonné(s)</div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:13px;font-weight:700;color:#1B3A6B;">{{ number_format((int)($p->revenus ?? 0),0,',',' ') }}</div>
          <div style="font-size:10px;color:#888;">XAF</div>
        </div>
      </div>
      @empty
      <p style="font-size:12px;color:#aaa;text-align:center;padding:12px 0;">Aucun abonnement</p>
      @endforelse
    </div>

  </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const font = { family: 'system-ui, sans-serif', size: 12 };

  // Evolution chart
  const usersMois = @json($users['par_mois'] ?? collect());
  const testsMois = @json($tests['par_mois'] ?? collect());
  
  if (usersMois.length > 0 || testsMois.length > 0) {
    new Chart(document.getElementById('evolutionChart'), {
      type: 'line',
      data: {
        labels: usersMois.map(d => d.mois),
        datasets: [
          {
            label: 'Inscriptions',
            data: usersMois.map(d => d.total),
            borderColor: '#1B3A6B', backgroundColor: 'rgba(27,58,107,.08)',
            tension: .4, fill: true, pointBackgroundColor: '#1B3A6B', pointRadius: 4,
          },
          {
            label: 'Passages langue',
            data: testsMois.map(d => d.total),
            borderColor: '#F5A623', backgroundColor: 'rgba(245,166,35,.06)',
            tension: .4, fill: true, pointBackgroundColor: '#F5A623', pointRadius: 4,
          },
        ]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { font, color: '#555', boxWidth: 12 } } },
        scales: {
          x: { ticks: { font, color: '#888' }, grid: { display: false } },
          y: { ticks: { font, color: '#888' }, grid: { color: '#f5f5f5' }, beginAtZero: true },
        }
      }
    });
  }

  // Revenus chart
  const revenusMois = @json($revenus['par_mois'] ?? collect());
  if (revenusMois.length > 0) {
    new Chart(document.getElementById('revenusChart'), {
      type: 'bar',
      data: {
        labels: revenusMois.map(d => d.mois),
        datasets: [{ 
          label:'Revenus XAF', 
          data: revenusMois.map(d => d.total),
          backgroundColor:'rgba(27,58,107,.7)', 
          borderRadius:6, 
          borderSkipped:false 
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
  }

  // Roles chart
  const rolesData = @json($users['par_role'] ?? collect());
  if (rolesData.length > 0) {
    new Chart(document.getElementById('rolesChart'), {
      type: 'doughnut',
      data: {
        labels: rolesData.map(r => r.role),
        datasets: [{ 
          data: rolesData.map(r => r.total),
          backgroundColor:['#1B3A6B','#54a3f3','#F5A623','#7F77DD','#1cc88a','#E24B4A'],
          borderWidth:2, 
          borderColor:'#fff' 
        }]
      },
      options: {
        responsive: true, 
        cutout:'65%',
        plugins: { legend: { position:'bottom', labels:{ font, color:'#555', boxWidth:10, padding:12 } } }
      }
    });
  }
});
</script>
@endpush

@endsection