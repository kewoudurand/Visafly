{{-- resources/views/student/courses/progress.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Ma progression')

@push('styles')
<style>
.tab-btn{padding:8px 18px;border-radius:20px;border:1.5px solid #e8e8e8;
         background:#fff;font-size:13px;font-weight:600;color:#666;cursor:pointer;transition:all .2s;}
.tab-btn.active{background:#1B3A6B;color:#fff;border-color:#1B3A6B;}

.prog-card{background:#fff;border-radius:14px;border:1px solid #eee;
           padding:16px 18px;margin-bottom:10px;display:flex;align-items:center;
           gap:14px;transition:all .2s;text-decoration:none;color:inherit;}
.prog-card:hover{border-color:#ddd;box-shadow:0 4px 14px rgba(27,58,107,.08);transform:translateY(-1px);}

.lang-badge{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;
            justify-content:center;font-size:13px;font-weight:900;color:#fff;flex-shrink:0;}

.progress-ring-wrap{position:relative;width:48px;height:48px;flex-shrink:0;}
.progress-ring-wrap svg{transform:rotate(-90deg);}
.progress-pct{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
              font-size:10px;font-weight:800;color:#1B3A6B;}

.score-pill{padding:3px 9px;border-radius:10px;font-size:11px;font-weight:700;}
.score-good{background:rgba(28,200,138,.1);color:#0f6e56;}
.score-mid{background:rgba(245,166,35,.12);color:#633806;}
.score-bad{background:rgba(226,75,74,.08);color:#a32d2d;}
.score-nc{background:#f0f0f0;color:#888;}

.kpi-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;}
@media(max-width:480px){.kpi-row{grid-template-columns:repeat(3,1fr);gap:8px;}}
.kpi-box{background:#fff;border-radius:12px;border:1px solid #eee;padding:14px 16px;text-align:center;
         box-shadow:0 2px 8px rgba(27,58,107,.05);}
.kpi-n{font-size:1.8rem;font-weight:800;line-height:1;margin-bottom:3px;}
.kpi-l{font-size:10px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.5px;}

.empty-state{text-align:center;padding:48px 20px;background:#f8f9fb;border-radius:14px;
             border:1.5px dashed #ddd;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">
      <i class="bi bi-graph-up-arrow me-2" style="color:#F5A623;"></i>Ma progression
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">Tous vos cours commencés et terminés</p>
  </div>
  <a href="{{ route('langues.index') }}"
     style="display:inline-flex;align-items:center;gap:6px;padding:9px 20px;
            background:#1B3A6B;color:#fff;border-radius:20px;font-size:12px;
            font-weight:700;text-decoration:none;">
    <i class="bi bi-play-fill"></i> Commencer un cours
  </a>
</div>

{{-- ── KPIs ── --}}
<div class="kpi-row">
  <div class="kpi-box">
    <div class="kpi-n" style="color:#F5A623;">{{ $stats['total_cours_commences'] ?? 0 }}</div>
    <div class="kpi-l">Commencés</div>
  </div>
  <div class="kpi-box">
    <div class="kpi-n" style="color:#1cc88a;">{{ $stats['total_cours_termines'] ?? 0 }}</div>
    <div class="kpi-l">Terminés</div>
  </div>
  <div class="kpi-box">
    <div class="kpi-n" style="color:#1B3A6B;">{{ $stats['score_moyen'] ?? 0 }}%</div>
    <div class="kpi-l">Score moy.</div>
  </div>
</div>

{{-- ── Onglets Commencés / Terminés ── --}}
<div style="display:flex;gap:8px;margin-bottom:20px;">
  <button class="tab-btn active" id="tab-en-cours" onclick="showTab('en-cours')">
    <i class="bi bi-play-circle me-1"></i>En cours
    @if(isset($enCours) && $enCours->isNotEmpty())
    <span style="background:#F5A623;color:#1B3A6B;border-radius:20px;
                 padding:1px 7px;font-size:10px;font-weight:800;margin-left:4px;">
      {{ $enCours->count() }}
    </span>
    @endif
  </button>
  <button class="tab-btn" id="tab-termines" onclick="showTab('termines')">
    <i class="bi bi-check-circle me-1"></i>Terminés
    @if(isset($termines) && $termines->isNotEmpty())
    <span style="background:rgba(28,200,138,.15);color:#0f6e56;border-radius:20px;
                 padding:1px 7px;font-size:10px;font-weight:800;margin-left:4px;">
      {{ $termines->count() }}
    </span>
    @endif
  </button>
</div>

{{-- ── EN COURS ── --}}
<div id="panel-en-cours">
  @forelse($enCours ?? [] as $passage)
  @php $lang = $passage->langue; @endphp
  <div class="prog-card">
    {{-- Badge langue --}}
    @if($lang)
    <div class="lang-badge" style="background:{{ $lang->couleur }};">
      {{ strtoupper($lang->code) }}
    </div>
    @endif

    {{-- Info --}}
    <div style="flex:1;min-width:0;">
      <div style="font-size:14px;font-weight:700;color:#1B3A6B;
                  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
        {{ $passage->serie?->titre ?? 'Série' }}
      </div>
      <div style="font-size:11px;color:#888;margin-top:2px;">
        @if($passage->discipline) {{ $passage->discipline->nom }} · @endif
        {{ $lang?->nom ?? '' }} ·
        Commencé {{ $passage->created_at->diffForHumans() }}
      </div>
    </div>

    {{-- Actions --}}
    <div style="display:flex;align-items:center;gap:8px;">
      <span class="score-pill score-nc">En cours</span>
      @if($lang && $passage->serie && $passage->discipline)
      <a href="{{ route('langues.epreuve', [$lang->code, $passage->serie->id, $passage->discipline->id]) }}"
         style="display:inline-flex;align-items:center;gap:4px;padding:7px 14px;
                background:{{ $lang->couleur }};color:#fff;border-radius:20px;
                font-size:12px;font-weight:600;text-decoration:none;white-space:nowrap;">
        <i class="bi bi-play-fill"></i> Continuer
      </a>
      @endif
    </div>
  </div>
  @empty
  <div class="empty-state">
    <i class="bi bi-journal-text" style="font-size:36px;color:#ccc;display:block;margin-bottom:10px;"></i>
    <div style="font-size:13px;color:#888;margin-bottom:14px;">Aucun cours en cours.</div>
    <a href="{{ route('langues.index') }}"
       style="padding:9px 20px;background:#1B3A6B;color:#fff;border-radius:20px;
              font-size:12px;font-weight:700;text-decoration:none;">
      Commencer un cours
    </a>
  </div>
  @endforelse
</div>

{{-- ── TERMINÉS ── --}}
<div id="panel-termines" style="display:none;">
  @forelse($termines ?? [] as $passage)
  @php
    $lang  = $passage->langue;
    $sc    = $passage->score ?? null;
    $cls   = $sc >= 60 ? 'score-good' : ($sc >= 40 ? 'score-mid' : ($sc !== null ? 'score-bad' : 'score-nc'));
    $color = $sc >= 60 ? '#1cc88a' : ($sc >= 40 ? '#F5A623' : ($sc !== null ? '#E24B4A' : '#888'));
    $pct   = $sc ?? 0;
  @endphp
  <div class="prog-card">
    @if($lang)
    <div class="lang-badge" style="background:{{ $lang->couleur }};">
      {{ strtoupper($lang->code) }}
    </div>
    @endif

    <div style="flex:1;min-width:0;">
      <div style="font-size:14px;font-weight:700;color:#1B3A6B;
                  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
        {{ $passage->serie?->titre ?? 'Série' }}
      </div>
      <div style="font-size:11px;color:#888;margin-top:2px;">
        @if($passage->discipline) {{ $passage->discipline->nom }} · @endif
        Terminé {{ $passage->fin_at ? $passage->fin_at->format('d/m/Y') : $passage->created_at->format('d/m/Y') }}
        @if($passage->duree_secondes)
          · {{ floor($passage->duree_secondes/60) }} min
        @endif
      </div>
    </div>

    {{-- Anneau progression --}}
    <div class="progress-ring-wrap">
      <svg width="48" height="48" viewBox="0 0 48 48">
        <circle cx="24" cy="24" r="20" fill="none" stroke="#f0f0f0" stroke-width="4"/>
        <circle cx="24" cy="24" r="20" fill="none" stroke="{{ $color }}" stroke-width="4"
                stroke-dasharray="{{ round(($pct/100)*125.6) }} 125.6"
                stroke-linecap="round"/>
      </svg>
      <div class="progress-pct">{{ $sc !== null ? $sc.'%' : '—' }}</div>
    </div>

    <span class="score-pill {{ $cls }}">{{ $sc !== null ? $sc.'%' : '—' }}</span>
  </div>
  @empty
  <div class="empty-state">
    <i class="bi bi-check-circle" style="font-size:36px;color:#ccc;display:block;margin-bottom:10px;"></i>
    <div style="font-size:13px;color:#888;">Vous n'avez encore terminé aucun cours.</div>
  </div>
  @endforelse
</div>

@push('scripts')
<script>
function showTab(name) {
  ['en-cours','termines'].forEach(n => {
    document.getElementById('panel-'+n).style.display = n === name ? 'block' : 'none';
    const btn = document.getElementById('tab-'+n);
    if (n === name) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });
}
</script>
@endpush

@endsection