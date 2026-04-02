{{-- resources/views/admin/analytics/user_detail.blade.php - CORRIGÉE --}}
@extends('layouts.dashboard')
@section('title', 'Analyse — '.$user->name)

@push('styles')
<style>
.an-card{background:#fff;border-radius:14px;border:1px solid #eee;
         padding:22px;box-shadow:0 2px 8px rgba(27,58,107,.04);margin-bottom:18px;}
.an-title{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:16px;
          display:flex;align-items:center;gap:7px;padding-bottom:12px;
          border-bottom:1.5px solid rgba(27,58,107,.06);}
.kpi-sm{background:#f8f9fb;border-radius:12px;padding:16px;text-align:center;}
.kpi-sm-num{font-size:1.8rem;font-weight:800;line-height:1;margin-bottom:4px;}
.kpi-sm-lbl{font-size:10px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.5px;}

/* Onglets langue */
.lang-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;}
.lang-tab{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;
          border-radius:10px;border:1.5px solid #e8e8e8;background:#fff;
          font-size:12px;font-weight:700;cursor:pointer;transition:all .2s;color:#666;}
.lang-tab:hover{border-color:#ddd;}
.lang-tab.active{color:#fff;border-color:transparent;}

/* Tableau passages */
.p-table th{font-size:10px;font-weight:700;color:#888;text-transform:uppercase;
            letter-spacing:.6px;border:none;padding:10px 14px;background:#f8f9fb;}
.p-table td{padding:12px 14px;vertical-align:middle;border-bottom:1px solid #f5f5f5;font-size:13px;}
.score-bar{height:8px;border-radius:4px;background:#f0f0f0;overflow:hidden;}
.score-bar-fill{height:100%;border-radius:4px;transition:width .5s;}
.niveau-tag{padding:2px 9px;border-radius:8px;font-size:10px;font-weight:700;}
.disc-chip{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;
           border-radius:8px;font-size:11px;font-weight:600;
           background:rgba(27,58,107,.07);color:#1B3A6B;}
</style>
@endpush

@section('content')

{{-- Retour --}}
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="{{ route('admin.analytics.index') }}"
     style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;
            display:flex;align-items:center;justify-content:center;
            color:#1B3A6B;text-decoration:none;">
    <i class="bi bi-arrow-left"></i>
  </a>
  <div>
    <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.3rem;">
      Analyse — {{ $user->first_name }}
    </h2>
    <p class="text-muted mb-0" style="font-size:12px;">{{ $user->email }}</p>
  </div>
  {{-- Lien vers profil admin --}}
  <a href="{{ route('admin.users.show', $user) }}"
     style="margin-left:auto;display:inline-flex;align-items:center;gap:5px;padding:8px 16px;
            border:1.5px solid #1B3A6B;color:#1B3A6B;border-radius:20px;
            font-size:12px;font-weight:600;text-decoration:none;">
    <i class="bi bi-person-circle"></i>Voir le profil
  </a>
</div>

<div class="row g-4">
<div class="col-lg-4">

  {{-- Carte utilisateur --}}
  <div class="an-card" style="background:#1B3A6B;color:#fff;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
      <div style="width:52px;height:52px;border-radius:50%;background:rgba(245,166,35,.2);
                  border:2px solid #F5A623;display:flex;align-items:center;justify-content:center;
                  font-size:18px;font-weight:800;color:#F5A623;flex-shrink:0;">
        {{ strtoupper(substr($user->first_name,0,2)) }}
      </div>
      <div>
        <div style="font-size:16px;font-weight:800;">{{ $user->first_name }}</div>
        <div style="font-size:11px;color:rgba(255,255,255,.6);">{{ $user->email }}</div>
        <div style="margin-top:4px;">
          @foreach($user->roles as $r)
          <span style="font-size:10px;padding:2px 9px;border-radius:8px;
                       background:rgba(245,166,35,.2);color:#F5A623;font-weight:700;">
            {{ $r->name }}
          </span>
          @endforeach
        </div>
      </div>
    </div>
    <div style="font-size:11px;color:rgba(255,255,255,.5);">
      Membre depuis {{ $user->created_at->format('d/m/Y') }}
    </div>
  </div>

  {{-- Stats globales --}}
  <div class="an-card">
    <div class="an-title"><i class="bi bi-bar-chart" style="color:#F5A623;"></i>Performances globales</div>
    <div class="row g-2">
      <div class="col-6">
        <div class="kpi-sm">
          <div class="kpi-sm-num" style="color:#1B3A6B;">{{ $statsUser['total'] ?? 0 }}</div>
          <div class="kpi-sm-lbl">Passages</div>
        </div>
      </div>
      <div class="col-6">
        <div class="kpi-sm">
          <div class="kpi-sm-num" style="color:#1cc88a;">{{ $statsUser['termines'] ?? 0 }}</div>
          <div class="kpi-sm-lbl">Terminés</div>
        </div>
      </div>
      <div class="col-6">
        <div class="kpi-sm">
          <div class="kpi-sm-num" style="color:#F5A623;">{{ $statsUser['score_moyen'] ?? 0 }}%</div>
          <div class="kpi-sm-lbl">Score moy.</div>
        </div>
      </div>
      <div class="col-6">
        <div class="kpi-sm">
          <div class="kpi-sm-num" style="color:#1B3A6B;">{{ $statsUser['meilleur'] ?? 0 }}%</div>
          <div class="kpi-sm-lbl">Meilleur</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Progression dans le temps --}}
  @if($progression && $progression->isNotEmpty())
  <div class="an-card">
    <div class="an-title"><i class="bi bi-graph-up" style="color:#F5A623;"></i>Progression</div>
    <canvas id="progressionChart" height="150"></canvas>
  </div>
  @else
  <div class="an-card" style="text-align:center;color:#aaa;padding:40px 20px;">
    <i class="bi bi-graph-up" style="font-size:28px;opacity:.3;display:block;margin-bottom:8px;"></i>
    <small>Pas assez de données</small>
  </div>
  @endif

</div>

<div class="col-lg-8">

  {{-- Onglets par langue --}}
  <div class="lang-tabs" id="langTabs">
    @php $first = true; @endphp
    @foreach($langues as $langue)
    @php 
      $passages = $passagesParLangue[$langue->code] ?? collect();
      $nbP = $passages->count(); 
    @endphp
    @if($nbP > 0)
    <button class="lang-tab {{ $first ? 'active' : '' }}"
            id="tab-{{ $langue->code }}"
            onclick="switchLang('{{ $langue->code }}', this)"
            style="{{ $first ? 'background:'.$langue->couleur.';border-color:'.$langue->couleur.';' : '' }}">
      {{ strtoupper($langue->code) }}
      <span style="font-size:10px;padding:1px 6px;border-radius:6px;
                   background:rgba(255,255,255,.25);{{ !$first ? 'background:rgba(27,58,107,.1);color:#1B3A6B;' : '' }}">
        {{ $nbP }}
      </span>
    </button>
    @php $first = false; @endphp
    @endif
    @endforeach
  </div>

  @if(collect($passagesParLangue)->flatten()->isEmpty())
  <div class="an-card" style="text-align:center;padding:60px 20px;background:#f8f9fb;border:2px dashed #ddd;">
    <i class="bi bi-journal-x" style="font-size:32px;color:#ccc;display:block;margin-bottom:12px;"></i>
    <div style="font-size:13px;color:#888;font-weight:500;">
      Aucun passage enregistré pour cet utilisateur
    </div>
  </div>
  @else

  {{-- Panneaux par langue --}}
  @php $firstPanel = true; @endphp
  @foreach($langues as $langue)
  @php
    $passages = $passagesParLangue[$langue->code] ?? collect();
    $isVisible = $firstPanel;
  @endphp
  <div id="panel-{{ $langue->code }}" style="{{ !$isVisible ? 'display:none;' : '' }}">

    @if($passages->isEmpty())
    <div style="text-align:center;padding:40px;background:#f8f9fb;border-radius:14px;
                border:1.5px dashed #ddd;">
      <div style="font-size:28px;color:#ccc;margin-bottom:8px;">
        <i class="bi bi-journal-x"></i>
      </div>
      <div style="font-size:13px;color:#888;">
        Aucun passage {{ $langue->nom }} pour cet utilisateur.
      </div>
    </div>
    @else

    {{-- Résumé pour cette langue --}}
    @php
      $scoreMoyLangue = (int) round($passages->avg('score') ?? 0);
      $meilleurScore  = $passages->max('score') ?? 0;
      $nbTermines     = $passages->where('statut','termine')->count();
    @endphp
    <div class="row g-2 mb-3">
      <div class="col-4">
        <div class="kpi-sm">
          <div class="kpi-sm-num" style="color:{{ $langue->couleur }};">{{ $passages->count() }}</div>
          <div class="kpi-sm-lbl">Passages</div>
        </div>
      </div>
      <div class="col-4">
        <div class="kpi-sm">
          <div class="kpi-sm-num" style="color:#F5A623;">{{ $scoreMoyLangue }}%</div>
          <div class="kpi-sm-lbl">Score moy.</div>
        </div>
      </div>
      <div class="col-4">
        <div class="kpi-sm">
          <div class="kpi-sm-num" style="color:#1cc88a;">{{ $meilleurScore }}%</div>
          <div class="kpi-sm-lbl">Meilleur</div>
        </div>
      </div>
    </div>

    {{-- Tableau des passages --}}
    <div class="an-card" style="padding:0;overflow:hidden;">
      <table class="table p-table mb-0">
        <thead>
          <tr>
            <th>Série</th>
            <th>Discipline</th>
            <th>Score</th>
            <th>Résultats</th>
            <th>Niveau</th>
            <th>Durée</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($passages as $p)
          @php
            $sc = $p->score ?? 0;
            $fillColor = $sc >= 80 ? '#1B3A6B' : ($sc >= 60 ? '#1cc88a' : ($sc >= 40 ? '#F5A623' : '#E24B4A'));
          @endphp
          <tr>
            <td>
              <div style="font-size:13px;font-weight:600;color:#1B3A6B;">
                {{ $p->serie?->titre ?? $p->serie?->nom ?? '—' }}
              </div>
            </td>
            <td>
              <span class="disc-chip">{{ $p->discipline?->code ?? $p->discipline?->nom ?? '—' }}</span>
            </td>
            <td>
              <div style="font-size:14px;font-weight:800;color:{{ $fillColor }};">
                {{ $p->score !== null ? $p->score.'%' : '—' }}
              </div>
              <div class="score-bar mt-1" style="width:70px;">
                <div class="score-bar-fill"
                     style="width:{{ $sc }}%;background:{{ $fillColor }};"></div>
              </div>
            </td>
            <td style="font-size:11px;color:#666;line-height:1.6;">
              <span style="color:#1cc88a;font-weight:600;">✓ {{ $p->bonnes_reponses ?? 0 }}</span>
              /
              <span style="color:#E24B4A;">✗ {{ $p->mauvaises_reponses ?? 0 }}</span>
              /
              <span style="color:#aaa;">– {{ $p->non_repondues ?? 0 }}</span>
            </td>
            <td>
              <span class="niveau-tag"
                    style="background:rgba(27,58,107,.08);color:#1B3A6B;">
                {{ $p->niveauAtteint() }}
              </span>
            </td>
            <td style="font-size:12px;color:#888;">{{ $p->dureeFormatee() }}</td>
            <td style="font-size:12px;color:#888;white-space:nowrap;">
              {{ $p->created_at->format('d/m/Y') }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-3" style="color:#aaa;font-size:13px;">
              Aucun passage terminé
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @endif
  </div>
  @php $firstPanel = false; @endphp
  @endforeach

  @endif

</div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Onglets langue
const langColors = @json($langues->pluck('couleur','code'));
function switchLang(code, btn) {
  document.querySelectorAll('[id^="panel-"]').forEach(p => p.style.display = 'none');
  const panel = document.getElementById('panel-' + code);
  if (panel) panel.style.display = 'block';
  
  document.querySelectorAll('.lang-tab').forEach(b => {
    b.classList.remove('active');
    b.style.background = '#fff'; 
    b.style.color = '#666'; 
    b.style.borderColor = '#e8e8e8';
  });
  btn.classList.add('active');
  btn.style.background = langColors[code] || '#1B3A6B';
  btn.style.color = '#fff';
  btn.style.borderColor = langColors[code] || '#1B3A6B';
}

// Graphique progression
@if($progression && $progression->isNotEmpty())
const progressionData = @json($progression);
if (progressionData && progressionData.length > 0) {
  const ctx = document.getElementById('progressionChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: progressionData.map(d => d.mois),
        datasets: [{
          label: 'Score moyen',
          data: progressionData.map(d => d.score_moyen),
          borderColor: '#F5A623',
          backgroundColor: 'rgba(245,166,35,.08)',
          tension: .4,
          fill: true,
          pointBackgroundColor: '#F5A623',
          pointRadius: 4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { 
            ticks: { font: { size: 11 }, color: '#888' }, 
            grid: { display: false } 
          },
          y: { 
            min: 0, 
            max: 100, 
            ticks: { font: { size: 11 }, color: '#888' }, 
            grid: { color: '#f5f5f5' } 
          },
        }
      }
    });
  }
}
@endif
</script>
@endpush

@endsection