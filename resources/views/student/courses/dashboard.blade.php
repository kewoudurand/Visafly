{{-- resources/views/student/courses/dashboard.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Mes cours — VisaFly')

@push('styles')
<style>
/* ════════════════════════════════════════════════════════════
   DASHBOARD ÉTUDIANT — SUIVI DES COURS
   Police : system-ui (cohérence avec le reste du projet)
   Couleurs : palette VisaFly existante (#1B3A6B, #F5A623, etc.)
════════════════════════════════════════════════════════════ */

/* ─── KPI Cards ─── */
.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
@media(max-width:900px){.kpi-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.kpi-grid{grid-template-columns:repeat(2,1fr);gap:10px;}}

.kpi-card{background:#fff;border-radius:14px;border:1px solid #eee;
          padding:18px 20px;position:relative;overflow:hidden;
          box-shadow:0 2px 10px rgba(27,58,107,.06);transition:transform .2s;}
.kpi-card:hover{transform:translateY(-2px);}
.kpi-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;
                  background:var(--accent,#F5A623);}
.kpi-num{font-size:2rem;font-weight:800;line-height:1;color:var(--accent,#1B3A6B);margin-bottom:4px;}
.kpi-lbl{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}
.kpi-sub{font-size:11px;color:#aaa;margin-top:4px;}
.kpi-icon{position:absolute;top:16px;right:16px;font-size:22px;
          color:var(--accent,#1B3A6B);opacity:.15;}

/* ─── Streak badge ─── */
.streak-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;
              border-radius:20px;background:rgba(245,166,35,.12);color:#633806;
              font-size:12px;font-weight:700;border:1px solid rgba(245,166,35,.3);}

/* ─── Section titres ─── */
.section-title{font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:14px;
               display:flex;align-items:center;gap:8px;}
.section-title i{color:#F5A623;}

/* ─── Carte langue/progression ─── */
.lang-card{background:#fff;border-radius:14px;border:1px solid #eee;
           overflow:hidden;box-shadow:0 2px 10px rgba(27,58,107,.05);
           transition:all .2s;}
.lang-card:hover{box-shadow:0 6px 24px rgba(27,58,107,.1);transform:translateY(-2px);}

.lang-header{padding:16px 18px;display:flex;align-items:center;gap:12px;color:#fff;}
.lang-code-badge{width:46px;height:46px;border-radius:11px;background:rgba(255,255,255,.2);
                 border:1.5px solid rgba(255,255,255,.3);display:flex;align-items:center;
                 justify-content:center;font-size:13px;font-weight:900;flex-shrink:0;}
.lang-header-info{flex:1;}
.lang-header-info h3{font-size:15px;font-weight:800;margin:0 0 2px;}
.lang-header-info small{font-size:11px;opacity:.7;}

.lang-body{padding:16px 18px;}

/* Barre de progression */
.prog-bar-wrap{margin-bottom:12px;}
.prog-bar-label{display:flex;justify-content:space-between;font-size:11px;
                color:#888;margin-bottom:5px;}
.prog-bar{height:8px;border-radius:4px;background:#f0f0f0;overflow:hidden;}
.prog-bar-fill{height:100%;border-radius:4px;transition:width .8s cubic-bezier(.4,0,.2,1);}

/* Stat chips */
.lang-stats{display:flex;gap:10px;flex-wrap:wrap;}
.stat-chip{display:flex;align-items:center;gap:4px;padding:4px 10px;border-radius:8px;
           background:#f8f9fb;border:1px solid #eee;font-size:11px;font-weight:600;color:#555;}
.stat-chip i{font-size:11px;}

/* ─── Historique activité ─── */
.activity-item{display:flex;align-items:center;gap:12px;padding:13px 16px;
               background:#f8f9fb;border-radius:10px;border:1px solid #eee;
               margin-bottom:8px;transition:all .15s;}
.activity-item:hover{background:#fff;border-color:#ddd;box-shadow:0 2px 8px rgba(27,58,107,.06);}

.activity-badge{width:38px;height:38px;border-radius:9px;flex-shrink:0;
                display:flex;align-items:center;justify-content:center;
                font-size:11px;font-weight:900;color:#fff;}
.activity-info{flex:1;min-width:0;}
.activity-title{font-size:13px;font-weight:600;color:#1B3A6B;
                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.activity-sub{font-size:11px;color:#888;margin-top:1px;}

.score-pill{display:inline-flex;align-items:center;gap:3px;padding:3px 9px;
            border-radius:10px;font-size:11px;font-weight:700;}
.score-good {background:rgba(28,200,138,.1);color:#0f6e56;}
.score-mid  {background:rgba(245,166,35,.12);color:#633806;}
.score-bad  {background:rgba(226,75,74,.08);color:#a32d2d;}
.score-none {background:#f0f0f0;color:#888;}

/* ─── Recommandations ─── */
.reco-card{background:#fff;border-radius:12px;border:1.5px dashed #e8e8e8;
           padding:14px 16px;margin-bottom:8px;display:flex;align-items:center;
           gap:12px;transition:all .2s;text-decoration:none;}
.reco-card:hover{border-color:#1B3A6B;background:rgba(27,58,107,.02);}
.reco-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;
           justify-content:center;font-size:15px;font-weight:900;color:#fff;flex-shrink:0;}
.reco-info{flex:1;}
.reco-title{font-size:13px;font-weight:600;color:#1B3A6B;}
.reco-sub{font-size:11px;color:#888;margin-top:1px;}

/* ─── Empty state ─── */
.empty-state{text-align:center;padding:40px 20px;background:#f8f9fb;
             border-radius:12px;border:1.5px dashed #ddd;}

/* ─── Responsive : cacher colonnes sur mobile ─── */
@media(max-width:768px){
  .hide-mobile{display:none!important;}
  .kpi-num{font-size:1.6rem;}
}
</style>
@endpush

@section('content')

{{-- ── En-tête ── --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">
      Mes cours & progression
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">
      Suivez votre progression sur toutes vos séries d'entraînement
    </p>
  </div>
  @if(($stats['streak_jours'] ?? 0) > 0)
  <div class="streak-badge">
    🔥 {{ $stats['streak_jours'] }} jour(s) de suite
  </div>
  @endif
</div>

{{-- ── KPIs ── --}}
<div class="kpi-grid mb-4">
  <div class="kpi-card" style="--accent:#1B3A6B;">
    <i class="bi bi-journals kpi-icon"></i>
    <div class="kpi-num">{{ $stats['total_cours_commences'] ?? 0 }}</div>
    <div class="kpi-lbl">Séries commencées</div>
    <div class="kpi-sub">{{ $stats['total_cours_termines'] ?? 0 }} terminées</div>
  </div>
  <div class="kpi-card" style="--accent:#1cc88a;">
    <i class="bi bi-graph-up-arrow kpi-icon"></i>
    <div class="kpi-num">{{ $stats['score_moyen'] ?? 0 }}<span style="font-size:1rem;">%</span></div>
    <div class="kpi-lbl">Score moyen</div>
    <div class="kpi-sub">Meilleur : {{ $stats['meilleur_score'] ?? 0 }}%</div>
  </div>
  <div class="kpi-card" style="--accent:#F5A623;">
    <i class="bi bi-clock-history kpi-icon"></i>
    <div class="kpi-num">{{ $stats['temps_total_minutes'] ?? 0 }}<span style="font-size:1rem;">min</span></div>
    <div class="kpi-lbl">Temps pratiqué</div>
    <div class="kpi-sub">Temps total cumulé</div>
  </div>
  <div class="kpi-card" style="--accent:#7F77DD;">
    <i class="bi bi-star-fill kpi-icon"></i>
    <div class="kpi-num" style="font-size:1.1rem;line-height:1.3;">
      {{ Str::limit($stats['serie_favorite'] ?? 'Aucune', 12) ?: '—' }}
    </div>
    <div class="kpi-lbl">Série favorite</div>
    <div class="kpi-sub">La plus pratiquée</div>
  </div>
</div>

<div class="row g-4">

  {{-- ══ COLONNE GAUCHE : Progression par langue ══ --}}
  <div class="col-lg-7">

    <div class="section-title">
      <i class="bi bi-translate"></i>Progression par examen
    </div>

    @forelse($progressionParLangue as $item)
    @php $l = $item['langue']; @endphp
    <div class="lang-card mb-3">
      <div class="lang-header" style="background:{{ $l->couleur }};">
        <div class="lang-code-badge">{{ strtoupper($l->code) }}</div>
        <div class="lang-header-info">
          <h3>{{ $l->nom }}</h3>
          <small>{{ $l->organisme ?? '' }}</small>
        </div>
        <div style="text-align:right;">
          <div style="font-size:1.4rem;font-weight:800;">{{ $item['score_moyen'] }}<span style="font-size:.8rem;opacity:.8;">%</span></div>
          <div style="font-size:10px;opacity:.7;">score moy.</div>
        </div>
      </div>

      <div class="lang-body">
        {{-- Barre de progression --}}
        <div class="prog-bar-wrap">
          <div class="prog-bar-label">
            <span>Progression</span>
            <span>{{ $item['termines'] }} / {{ max($item['total'], 1) }} séries</span>
          </div>
          <div class="prog-bar">
            <div class="prog-bar-fill"
                 style="width:{{ $item['progression'] }}%;background:{{ $l->couleur }};"></div>
          </div>
        </div>

        {{-- Chips stats --}}
        <div class="lang-stats">
          <span class="stat-chip">
            <i class="bi bi-play-circle" style="color:#1B3A6B;"></i>
            {{ $item['total'] }} passage(s)
          </span>
          <span class="stat-chip">
            <i class="bi bi-check-circle" style="color:#1cc88a;"></i>
            {{ $item['termines'] }} terminé(s)
          </span>
          @if($item['derniere_activite'])
          <span class="stat-chip">
            <i class="bi bi-clock" style="color:#F5A623;"></i>
            {{ \Carbon\Carbon::parse($item['derniere_activite'])->diffForHumans() }}
          </span>
          @endif
        </div>

        {{-- Lien vers les séries --}}
        <div style="margin-top:12px;">
          <a href="{{ route('langues.series', $l->code) }}"
             style="display:inline-flex;align-items:center;gap:5px;padding:7px 16px;
                    background:{{ $l->couleur }};color:#fff;border-radius:20px;
                    font-size:12px;font-weight:600;text-decoration:none;">
            <i class="bi bi-play-fill"></i>Continuer
          </a>
        </div>
      </div>
    </div>
    @empty
    <div class="empty-state">
      <i class="bi bi-translate" style="font-size:36px;color:#ccc;display:block;margin-bottom:10px;"></i>
      <div style="font-size:13px;color:#888;margin-bottom:14px;">
        Vous n'avez encore commencé aucun cours.
      </div>
      <a href="{{ route('langues.index') }}"
         style="padding:9px 20px;background:#1B3A6B;color:#fff;border-radius:20px;
                font-size:12px;font-weight:700;text-decoration:none;">
        Commencer maintenant
      </a>
    </div>
    @endforelse

  </div>

  {{-- ══ COLONNE DROITE ══ --}}
  <div class="col-lg-5">

    {{-- Activité récente --}}
    <div class="section-title"><i class="bi bi-clock-history"></i>Activité récente</div>

    @forelse($activiteRecente as $passage)
    @php
      $sc    = $passage->score ?? null;
      $cls   = $sc >= 60 ? 'score-good' : ($sc >= 40 ? 'score-mid' : ($sc !== null ? 'score-bad' : 'score-none'));
      $label = $sc !== null ? $sc.'%' : 'En cours';
      $lang  = $passage->langue;
    @endphp
    <div class="activity-item">
      @if($lang)
      <div class="activity-badge" style="background:{{ $lang->couleur }};">
        {{ strtoupper($lang->code) }}
      </div>
      @else
      <div class="activity-badge" style="background:#888;">–</div>
      @endif

      <div class="activity-info">
        <div class="activity-title">
          {{ $passage->serie?->titre ?? 'Série' }}
          @if($passage->discipline)
            — {{ $passage->discipline->nom_court ?? $passage->discipline->nom }}
          @endif
        </div>
        <div class="activity-sub">{{ $passage->created_at->diffForHumans() }}</div>
      </div>

      <span class="score-pill {{ $cls }}">{{ $label }}</span>
    </div>
    @empty
    <div class="empty-state" style="padding:24px;">
      <i class="bi bi-journal-x" style="font-size:28px;color:#ccc;display:block;margin-bottom:8px;"></i>
      <span style="font-size:12px;color:#888;">Aucune activité récente</span>
    </div>
    @endforelse

    {{-- Recommandations ──────────────────── --}}
    @if($recommandations->isNotEmpty())
    <div class="section-title mt-4"><i class="bi bi-lightning-fill"></i>À essayer</div>

    @foreach($recommandations as $reco)
    @php $lang = $reco->discipline?->langue; @endphp
    <a href="{{ route('langues.disciplines', [$lang?->code ?? 'tcf', $reco->id]) }}"
       class="reco-card d-block">
      <div class="reco-icon" style="background:{{ $lang?->couleur ?? '#1B3A6B' }};">
        {{ strtoupper($lang?->code ?? '?') }}
      </div>
      <div class="reco-info">
        <div class="reco-title">{{ $reco->titre }}</div>
        <div class="reco-sub">
          {{ $lang?->nom ?? '' }}
          @if($reco->gratuite) · <span style="color:#1cc88a;font-weight:600;">Gratuit</span>@endif
        </div>
      </div>
      <i class="bi bi-arrow-right-circle" style="color:#ccc;font-size:18px;flex-shrink:0;"></i>
    </a>
    @endforeach
    @endif

  </div>
</div>

@endsection