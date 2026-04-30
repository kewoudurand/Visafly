{{-- resources/views/admin/courses/student-progress.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Progression des étudiants')

@push('styles')
<style>
  /* ════════════════════════════════════════════════════════════
    ADMIN — TABLEAU SUIVI ÉTUDIANTS
  ════════════════════════════════════════════════════════════ */

  /* KPIs */
  .kpi-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
  @media(max-width:900px){.kpi-row{grid-template-columns:repeat(2,1fr);}}
  @media(max-width:480px){.kpi-row{grid-template-columns:repeat(2,1fr);}}

  .kpi{background:#fff;border-radius:14px;border:1px solid #eee;padding:18px 20px;
      position:relative;overflow:hidden;box-shadow:0 2px 8px rgba(27,58,107,.05);}
  .kpi::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
              background:var(--c,#1B3A6B);}
  .kpi-n{font-size:2rem;font-weight:800;color:var(--c,#1B3A6B);line-height:1;margin-bottom:4px;}
  .kpi-l{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}

  /* Tableau */
  .prog-table th{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                letter-spacing:.6px;border:none;padding:12px 16px;background:#f8f9fb;
                white-space:nowrap;}
  .prog-table td{padding:14px 16px;vertical-align:middle;border-bottom:1px solid #f5f5f5;
                font-size:13px;}
  .prog-table tr:hover td{background:rgba(27,58,107,.015);}

  /* Avatar initiales */
  .user-av{width:36px;height:36px;border-radius:50%;background:#1B3A6B;
          display:flex;align-items:center;justify-content:center;
          font-size:12px;font-weight:700;color:#F5A623;flex-shrink:0;}

  /* Mini barre de progression */
  .mini-bar{height:6px;border-radius:3px;background:#f0f0f0;overflow:hidden;min-width:80px;}
  .mini-bar-fill{height:100%;border-radius:3px;}

  /* Score pill */
  .sp{padding:3px 9px;border-radius:10px;font-size:11px;font-weight:700;}
  .sp-good{background:rgba(28,200,138,.1);color:#0f6e56;}
  .sp-mid {background:rgba(245,166,35,.12);color:#633806;}
  .sp-bad {background:rgba(226,75,74,.08);color:#a32d2d;}
  .sp-none{background:#f0f0f0;color:#888;}

  /* Abonnement */
  .abo-dot{width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:5px;}

  /* Top étudiant card */
  .top-card{background:#fff;border-radius:12px;border:1px solid #eee;
            padding:14px;display:flex;align-items:center;gap:12px;
            box-shadow:0 2px 6px rgba(27,58,107,.04);transition:all .2s;}
  .top-card:hover{box-shadow:0 4px 16px rgba(27,58,107,.08);}
  .rank-badge{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;
              justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;}

  /* Filtre search */
  .vf-search{border:1.5px solid #e8e8e8;border-radius:10px;padding:9px 14px 9px 38px;
            font-size:13px;width:100%;max-width:300px;outline:none;
            background:#fafafa url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%23aaa' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.656a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z'/%3E%3C/svg%3E") no-repeat 12px center;
            transition:border-color .2s;}
  .vf-search:focus{border-color:#F5A623;background-color:#fff;}

  @media(max-width:768px){
    .hide-mobile{display:none!important;}
    .prog-table td,.prog-table th{padding:10px 10px;}
  }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">
      Suivi des étudiants
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">
      Progression et niveau de cours de chaque utilisateur
    </p>
  </div>
  <div style="font-size:12px;color:#888;">
    <i class="bi bi-clock me-1"></i>{{ now()->format('d/m/Y H:i') }}
  </div>
</div>

{{-- ── KPIs Globaux ── --}}
<div class="kpi-row">
  <div class="kpi" style="--c:#1B3A6B;">
    <div class="kpi-n">{{ $statsGlobales['total_passages'] }}</div>
    <div class="kpi-l">Passages totaux</div>
  </div>
  <div class="kpi" style="--c:#1cc88a;">
    <div class="kpi-n">{{ $statsGlobales['passages_termines'] }}</div>
    <div class="kpi-l">Cours terminés</div>
  </div>
  <div class="kpi" style="--c:#F5A623;">
    <div class="kpi-n">{{ $statsGlobales['score_moyen'] }}<span style="font-size:1rem;">%</span></div>
    <div class="kpi-l">Score moyen</div>
  </div>
  <div class="kpi" style="--c:#7F77DD;">
    <div class="kpi-n">{{ $statsGlobales['users_actifs'] }}</div>
    <div class="kpi-l">Actifs (7 jours)</div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-12">

    {{-- ── Filtre ── --}}
    <form method="GET" class="mb-3 d-flex align-items-center gap-2">
      <input type="text" name="search" class="vf-search"
             value="{{ request('search') }}"
             placeholder="Rechercher un étudiant…">
      @if(request('search'))
      <a href="{{ route('admin.student-progress.index') }}"
         style="font-size:12px;color:#888;text-decoration:none;padding:6px;">✕ Effacer</a>
      @endif
    </form>

    {{-- ── Tableau étudiants ── --}}
    <div class="rounded-3 table-responsive"
         style="background:#fff;border:1px solid #eee;box-shadow:0 2px 8px rgba(27,58,107,.05);">
      <table class="table prog-table mb-0">
        <thead>
          <tr>
            <th>Étudiant</th>
            <th class="hide-mobile">Progression</th>
            <th>Score moy.</th>
            <th class="hide-mobile">Passages</th>
            <th class="hide-mobile">Abonnement</th>
            <th class="hide-mobile">Dernière activité</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($etudiants as $etudiant)
          @php
            $pStats  = $passagesStats[$etudiant->id] ?? null;
            $total   = $pStats?->total ?? 0;
            $termines= $pStats?->termines ?? 0;
            $score   = $pStats ? (int)($pStats->score_moyen ?? 0) : null;
            $scoreClass = $score >= 60 ? 'sp-good' : ($score >= 40 ? 'sp-mid' : ($score !== null && $total > 0 ? 'sp-bad' : 'sp-none'));
            $pct     = $total > 0 ? min(100, (int)round(($termines / $total) * 100)) : 0;
            $progColor = $pct >= 60 ? '#1cc88a' : ($pct >= 30 ? '#F5A623' : '#E24B4A');
            $aAbo    = $etudiant->a_abonnement_actif > 0;
          @endphp
          <tr>
            {{-- Étudiant --}}
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="user-av">
                  {{ strtoupper(substr($etudiant->first_name ?? '?', 0, 2)) }}
                </div>
                <div>
                  <div style="font-weight:600;color:#1B3A6B;">
                    {{ $etudiant->first_name }} {{ $etudiant->last_name }}
                  </div>
                  <div style="font-size:11px;color:#888;">{{ $etudiant->email }}</div>
                </div>
              </div>
            </td>

            {{-- Progression --}}
            <td class="hide-mobile">
              <div style="display:flex;align-items:center;gap:8px;">
                <div class="mini-bar" style="flex:1;">
                  <div class="mini-bar-fill"
                       style="width:{{ $pct }}%;background:{{ $progColor }};"></div>
                </div>
                <span style="font-size:11px;color:#888;min-width:28px;">{{ $pct }}%</span>
              </div>
              <div style="font-size:10px;color:#aaa;margin-top:2px;">
                {{ $termines }}/{{ $total }} terminés
              </div>
            </td>

            {{-- Score --}}
            <td>
              @if($total > 0)
                <span class="sp {{ $scoreClass }}">{{ $score ?? 0 }}%</span>
              @else
                <span class="sp sp-none">—</span>
              @endif
            </td>

            {{-- Passages --}}
            <td class="hide-mobile" style="color:#555;">{{ $total ?: '—' }}</td>

            {{-- Abonnement --}}
            <td class="hide-mobile">
              <span style="font-size:12px;">
                <span class="abo-dot" style="background:{{ $aAbo ? '#1cc88a' : '#ddd' }};"></span>
                {{ $aAbo ? 'Actif' : 'Aucun' }}
              </span>
            </td>

            {{-- Dernière activité --}}
            <td class="hide-mobile" style="font-size:12px;color:#888;">
              @if($pStats?->derniere_activite)
                {{ \Carbon\Carbon::parse($pStats->derniere_activite)->diffForHumans() }}
              @else
                Jamais
              @endif
            </td>

            {{-- Voir détail --}}
            <td>
              <a href="{{ route('admin.student-progress.show', $etudiant) }}"
                 style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;
                        border:1px solid #e8e8e8;border-radius:8px;background:#fff;
                        font-size:12px;color:#1B3A6B;text-decoration:none;
                        transition:all .15s;"
                 onmouseover="this.style.borderColor='#1B3A6B'"
                 onmouseout="this.style.borderColor='#e8e8e8'">
                <i class="bi bi-eye"></i>
                <span class="hide-mobile">Voir</span>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5" style="color:#aaa;">
              <i class="bi bi-people" style="font-size:32px;display:block;margin-bottom:8px;opacity:.3;"></i>
              Aucun étudiant trouvé
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($etudiants->hasPages())
    <div class="mt-3 d-flex justify-content-center">
      {{ $etudiants->links() }}
    </div>
    @endif

  </div>

  {{-- ══ Top étudiants (colonne droite) ══ --}}
  <div class="col-lg-4">

    <div style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:14px;
                display:flex;align-items:center;gap:8px;">
      <i class="bi bi-trophy-fill" style="color:#F5A623;"></i>Top étudiants
    </div>

    @forelse($topEtudiants as $i => $top)
    @php
      $rankColors = ['#F5A623','#aaa','#cd7f32'];
      $rc = $rankColors[$i] ?? '#1B3A6B';
      $sc = (int)($top->score_moyen ?? 0);
      $scClass = $sc >= 60 ? 'sp-good' : ($sc >= 40 ? 'sp-mid' : 'sp-bad');
    @endphp
    <div class="top-card mb-2">
      <div class="rank-badge" style="background:{{ $rc }}22;color:{{ $rc }};">
        {{ $i + 1 }}
      </div>
      <div class="user-av" style="width:34px;height:34px;font-size:11px;flex-shrink:0;">
        {{ strtoupper(substr($top->user?->first_name ?? '?', 0, 2)) }}
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:13px;font-weight:600;color:#1B3A6B;
                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
          {{ $top->user?->first_name ?? '—' }}
        </div>
        <div style="font-size:11px;color:#888;">{{ $top->nb_passages }} passage(s)</div>
      </div>
      <span class="sp {{ $scClass }}">{{ $sc }}%</span>
    </div>
    @empty
    <div style="text-align:center;padding:32px;background:#f8f9fb;border-radius:12px;border:1.5px dashed #ddd;">
      <div style="font-size:13px;color:#aaa;">Aucune donnée disponible</div>
    </div>
    @endforelse

  </div>
</div>

@endsection