{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Dashboard Admin — VisaFly')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ══════════════════════════════════════════
   TOKENS
══════════════════════════════════════════ */
:root {
    --navy:   #1B3A6B;
    --navy2:  #16305a;
    --gold:   #F5A623;
    --gold2:  #e09416;
    --red:    #E24B4A;
    --green:  #1CC88A;
    --purple: #7F77DD;
    --bg:     #F4F6FA;
    --card:   #ffffff;
    --border: #EAECF0;
    --txt1:   #1A1F2E;
    --txt2:   #6B7280;
    --txt3:   #9CA3AF;
}

body { font-family: 'Inter', sans-serif; background: var(--bg); }

/* ══════════════════════════════════════════
   HEADER DASHBOARD
══════════════════════════════════════════ */
.db-header {
    background: linear-gradient(135deg, var(--navy) 0%, #24508f 100%);
    padding: 28px 32px 48px;
    position: relative;
    overflow: hidden;
}
.db-header::before {
    content: '';
    position: absolute;
    top: -60px; right: -80px;
    width: 300px; height: 300px;
    background: rgba(245,166,35,.07);
    border-radius: 50%;
}
.db-header::after {
    content: '';
    position: absolute;
    bottom: -40px; left: 40%;
    width: 200px; height: 200px;
    background: rgba(255,255,255,.03);
    border-radius: 50%;
}
.db-header-inner {
    position: relative; z-index: 1;
    display: flex; align-items: flex-start;
    justify-content: space-between; flex-wrap: wrap; gap: 16px;
}
.db-greeting {
    color: rgba(255,255,255,.6);
    font-size: 12px; font-weight: 500; letter-spacing: .5px;
    text-transform: uppercase; margin-bottom: 4px;
}
.db-title {
    color: #fff; font-size: 1.5rem; font-weight: 800; margin: 0;
    display: flex; align-items: center; gap: 10px;
}
.db-title .gold-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--gold); display: inline-block;
    animation: blink 2.5s ease-in-out infinite;
}
@keyframes blink {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: .4; transform: scale(.7); }
}
.db-subtitle {
    color: rgba(255,255,255,.5); font-size: 12px; margin-top: 4px;
}

/* ── Recherche globale ── */
.global-search-wrap {
    position: relative; min-width: 320px; max-width: 420px; flex: 1;
}
.global-search {
    width: 100%;
    background: rgba(255,255,255,.1);
    border: 1.5px solid rgba(255,255,255,.2);
    border-radius: 10px;
    padding: 10px 16px 10px 42px;
    color: #fff; font-size: 13px; font-family: 'Inter', sans-serif;
    outline: none; transition: all .2s;
}
.global-search::placeholder { color: rgba(255,255,255,.45); }
.global-search:focus {
    background: rgba(255,255,255,.15);
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(245,166,35,.2);
}
.global-search-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: rgba(255,255,255,.5); font-size: 14px; pointer-events: none;
}
.search-hint {
    font-size: 10px; color: rgba(255,255,255,.3);
    margin-top: 5px; padding-left: 4px;
    font-family: monospace; letter-spacing: .3px;
}

/* ══════════════════════════════════════════
   CONTENU PRINCIPAL (décalé sous header)
══════════════════════════════════════════ */
.db-content {
    padding: 0 32px 40px;
    margin-top: -24px; /* remonte sous le header */
    position: relative; z-index: 2;
}

/* ══════════════════════════════════════════
   KPI CARDS
══════════════════════════════════════════ */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 24px;
}
.kpi-card {
    background: var(--card);
    border-radius: 14px;
    border: 1px solid var(--border);
    padding: 20px;
    box-shadow: 0 2px 12px rgba(27,58,107,.06);
    position: relative; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.kpi-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(27,58,107,.1); }
.kpi-card::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
    background: var(--accent, var(--navy));
}
.kpi-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; margin-bottom: 14px;
    background: var(--icon-bg, rgba(27,58,107,.08));
    color: var(--accent, var(--navy));
}
.kpi-num {
    font-size: 1.8rem; font-weight: 800; color: var(--txt1); line-height: 1;
    margin-bottom: 4px;
}
.kpi-label {
    font-size: 11px; font-weight: 600; color: var(--txt2);
    text-transform: uppercase; letter-spacing: .6px;
}
.kpi-sub {
    font-size: 11px; color: var(--txt3);
    margin-top: 10px; display: flex; align-items: center; gap: 4px;
}
.kpi-sub .up   { color: var(--green); font-weight: 600; }
.kpi-sub .down { color: var(--red);   font-weight: 600; }
.kpi-bg-icon {
    position: absolute; right: -10px; bottom: -8px;
    font-size: 56px; opacity: .04; color: var(--accent, var(--navy));
}

/* ══════════════════════════════════════════
   GRILLE PRINCIPALE (graphique + alertes)
══════════════════════════════════════════ */
.main-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 20px; margin-bottom: 24px;
}

/* ── Carte générique ── */
.db-card {
    background: var(--card);
    border-radius: 14px;
    border: 1px solid var(--border);
    box-shadow: 0 2px 12px rgba(27,58,107,.05);
    overflow: hidden;
}
.db-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.db-card-title {
    font-size: 12px; font-weight: 700;
    color: var(--navy); text-transform: uppercase; letter-spacing: .7px;
    display: flex; align-items: center; gap: 7px; margin: 0;
}
.db-card-title i { color: var(--gold); }
.db-card-body { padding: 20px; }
.db-card-link {
    font-size: 11px; font-weight: 600; color: var(--navy);
    text-decoration: none; opacity: .6; transition: opacity .15s;
    display: flex; align-items: center; gap: 3px;
}
.db-card-link:hover { opacity: 1; color: var(--navy); }

/* ══════════════════════════════════════════
   GRAPHIQUE STATUTS (barres CSS pures)
══════════════════════════════════════════ */
.statut-bars { display: flex; flex-direction: column; gap: 12px; }
.sbar-row { display: flex; flex-direction: column; gap: 5px; }
.sbar-meta {
    display: flex; justify-content: space-between; align-items: center;
}
.sbar-label { font-size: 12px; font-weight: 600; color: var(--txt1); }
.sbar-count { font-size: 12px; font-weight: 700; }
.sbar-track {
    height: 8px; border-radius: 4px;
    background: #F0F2F5; overflow: hidden;
}
.sbar-fill {
    height: 100%; border-radius: 4px;
    transition: width 1s cubic-bezier(.4,0,.2,1);
}

/* Donut chart (SVG) */
.donut-wrap {
    display: flex; align-items: center; gap: 24px;
    margin-bottom: 20px;
}
.donut-legend { display: flex; flex-direction: column; gap: 8px; flex: 1; }
.legend-item {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; color: var(--txt2);
}
.legend-dot {
    width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0;
}
.legend-val { margin-left: auto; font-weight: 700; color: var(--txt1); }

/* ══════════════════════════════════════════
   ALERTES URGENTES
══════════════════════════════════════════ */
.alert-list { display: flex; flex-direction: column; gap: 0; }
.alert-item {
    padding: 13px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 12px;
    transition: background .15s; cursor: pointer; text-decoration: none;
}
.alert-item:last-child { border-bottom: none; }
.alert-item:hover { background: rgba(27,58,107,.02); }
.alert-avatar {
    width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
    background: var(--navy); color: var(--gold);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800;
}
.alert-info { flex: 1; min-width: 0; }
.alert-name {
    font-size: 13px; font-weight: 600; color: var(--txt1);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.alert-meta { font-size: 11px; color: var(--txt3); margin-top: 2px; }
.alert-badge {
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 700; white-space: nowrap;
    flex-shrink: 0;
}
.ab-urgent { background: rgba(226,75,74,.1); color: #a32d2d; }
.ab-attente { background: rgba(245,166,35,.12); color: #7a4500; }
.ab-cours   { background: rgba(27,58,107,.1); color: var(--navy); }
.ab-new     { background: rgba(28,200,138,.1); color: #0f6e56; }
.urgent-flag {
    width: 6px; height: 36px; border-radius: 3px;
    background: var(--red); flex-shrink: 0;
}

/* ══════════════════════════════════════════
   ACTIVITÉ RÉCENTE (tableau 3 colonnes)
══════════════════════════════════════════ */
.activity-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px; margin-bottom: 24px;
}
.act-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 0; border-bottom: 1px solid var(--border);
}
.act-item:last-child { border-bottom: none; }
.act-icon {
    width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
}
.act-label { font-size: 12px; font-weight: 600; color: var(--txt1); }
.act-sub   { font-size: 11px; color: var(--txt3); margin-top: 1px; }
.act-right { margin-left: auto; text-align: right; }
.act-amount { font-size: 12px; font-weight: 700; color: var(--navy); }
.act-date   { font-size: 10px; color: var(--txt3); }

/* ══════════════════════════════════════════
   MODULES RACCOURCIS (4 piliers)
══════════════════════════════════════════ */
.modules-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
.module-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px;
    text-decoration: none;
    display: flex; flex-direction: column; gap: 12px;
    transition: all .2s;
    box-shadow: 0 2px 8px rgba(27,58,107,.04);
    position: relative; overflow: hidden;
}
.module-card::after {
    content: ''; position: absolute;
    bottom: 0; left: 0; right: 0; height: 3px;
    background: var(--mod-color, var(--navy)); opacity: 0;
    transition: opacity .2s;
}
.module-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(27,58,107,.12); }
.module-card:hover::after { opacity: 1; }
.module-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    background: var(--mod-bg, rgba(27,58,107,.07));
    color: var(--mod-color, var(--navy));
}
.module-title { font-size: 14px; font-weight: 700; color: var(--txt1); }
.module-desc  { font-size: 11px; color: var(--txt3); line-height: 1.4; margin-top: -6px; }
.module-links { display: flex; flex-direction: column; gap: 3px; margin-top: auto; }
.module-link {
    font-size: 11px; color: var(--txt2); text-decoration: none;
    display: flex; align-items: center; gap: 5px; padding: 2px 0;
    transition: color .15s;
}
.module-link:hover { color: var(--mod-color, var(--navy)); }
.module-link i { font-size: 10px; opacity: .6; }

/* ══════════════════════════════════════════
   SECTION LABEL
══════════════════════════════════════════ */
.section-label {
    font-size: 10px; font-weight: 700; color: var(--txt3);
    text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
}
.section-label::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

/* Responsive */
@media (max-width: 1100px) {
    .kpi-grid     { grid-template-columns: repeat(2, 1fr); }
    .main-grid    { grid-template-columns: 1fr; }
    .activity-grid{ grid-template-columns: 1fr; }
    .modules-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
    .db-header   { padding: 20px 16px 40px; }
    .db-content  { padding: 0 16px 30px; }
    .kpi-grid    { grid-template-columns: 1fr 1fr; }
    .modules-grid{ grid-template-columns: 1fr; }
    .global-search-wrap { min-width: 100%; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════
     HEADER
══════════════════════════════════════════════════════ --}}
<div class="db-header">
    <div class="db-header-inner">
        <div>
            <div class="db-greeting">Tableau de bord · {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</div>
            <h1 class="db-title">
                <span class="gold-dot"></span>
                Bonjour, {{ Auth::user()->full_name }}
            </h1>
            <p class="db-subtitle">Voici l'état de votre plateforme en temps réel.</p>
        </div>

        {{-- Recherche globale --}}
        <div class="global-search-wrap">
            <i class="bi bi-search global-search-icon"></i>
            <input type="text"
                   class="global-search"
                   placeholder="Rechercher un dossier, un client…"
                   id="globalSearch"
                   autocomplete="off">
            <div class="search-hint">Format dossier : VF-{{ now()->format('Y') }}-{{ now()->format('m') }}-ID</div>

            {{-- Résultats (JS) --}}
            <div id="searchResults" style="display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;
                 background:#fff;border-radius:10px;border:1px solid var(--border);
                 box-shadow:0 12px 40px rgba(0,0,0,.15);z-index:999;overflow:hidden;"></div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     CONTENU
══════════════════════════════════════════════════════ --}}
<div class="db-content">

    {{-- ── KPIs ──────────────────────────────────────── --}}
    <div class="section-label">Indicateurs clés du mois</div>
    <div class="kpi-grid">

        {{-- CA Mensuel --}}
        <div class="kpi-card" style="--accent:var(--gold);--icon-bg:rgba(245,166,35,.1);">
            <div class="kpi-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="kpi-num">
                @php $caMois = $stats['ca_mois'] ?? 0; @endphp
                {{ number_format($caMois, 0, ',', ' ') }}
                <span style="font-size:14px;font-weight:600;color:var(--txt3);"> XAF</span>
            </div>
            <div class="kpi-label">CA ce mois</div>
            <div class="kpi-sub">
                <i class="bi bi-graph-up-arrow up"></i>
                <span class="up">{{ $stats['nouveaux_abonnes'] ?? 0 }} nouveaux abonnés</span>
            </div>
            <i class="bi bi-cash-coin kpi-bg-icon"></i>
        </div>

        {{-- Consultations en attente --}}
        <div class="kpi-card" style="--accent:var(--red);--icon-bg:rgba(226,75,74,.08);">
            <div class="kpi-icon"><i class="bi bi-folder-exclamation"></i></div>
            <div class="kpi-num">{{ $stats['consultations_attente'] ?? 0 }}</div>
            <div class="kpi-label">Dossiers en attente</div>
            <div class="kpi-sub">
                <i class="bi bi-check2-circle" style="color:var(--green);"></i>
                <span style="color:var(--green);font-weight:600;">{{ $stats['consultations_terminees_mois'] ?? 0 }}</span>
                <span>terminées ce mois</span>
            </div>
            <i class="bi bi-folder-exclamation kpi-bg-icon"></i>
        </div>

        {{-- Étudiants cours --}}
        <div class="kpi-card" style="--accent:var(--purple);--icon-bg:rgba(127,119,221,.1);">
            <div class="kpi-icon"><i class="bi bi-mortarboard"></i></div>
            <div class="kpi-num">{{ $stats['total_etudiants'] ?? 0 }}</div>
            <div class="kpi-label">Étudiants inscrits</div>
            <div class="kpi-sub">
                <i class="bi bi-plus-circle" style="color:var(--purple);"></i>
                <span style="color:var(--purple);font-weight:600;">{{ $stats['nouveaux_etudiants_mois'] ?? 0 }}</span>
                <span>ce mois</span>
            </div>
            <i class="bi bi-mortarboard kpi-bg-icon"></i>
        </div>

        {{-- Utilisateurs total --}}
        <div class="kpi-card" style="--accent:var(--green);--icon-bg:rgba(28,200,138,.08);">
            <div class="kpi-icon"><i class="bi bi-people"></i></div>
            <div class="kpi-num">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="kpi-label">Utilisateurs inscrits</div>
            <div class="kpi-sub">
                <i class="bi bi-person-plus" style="color:var(--green);"></i>
                <span style="color:var(--green);font-weight:600;">+{{ $stats['nouveaux_users_mois'] ?? 0 }}</span>
                <span>ce mois</span>
            </div>
            <i class="bi bi-people kpi-bg-icon"></i>
        </div>

    </div>

    {{-- ── Graphique statuts + Alertes ──────────────── --}}
    <div class="main-grid">

        {{-- Répartition statuts consultations --}}
        <div class="db-card">
            <div class="db-card-header">
                <h6 class="db-card-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    Répartition des dossiers
                </h6>
                <a href="{{ route('admin.consultations.index') }}" class="db-card-link">
                    Voir tous <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="db-card-body">
                @php
                    $statutsData = [
                        ['label' => 'En attente',  'count' => $stats['s_attente'] ?? 0,  'color' => '#F5A623', 'cls' => 'ab-attente'],
                        ['label' => 'En cours',    'count' => $stats['s_en_cours'] ?? 0, 'color' => '#1B3A6B', 'cls' => 'ab-cours'],
                        ['label' => 'Approuvés',   'count' => $stats['s_approuve'] ?? 0, 'color' => '#1CC88A', 'cls' => 'ab-new'],
                        ['label' => 'Déclinés',    'count' => $stats['s_decline'] ?? 0,  'color' => '#E24B4A', 'cls' => 'ab-urgent'],
                        ['label' => 'Terminés',    'count' => $stats['s_termine'] ?? 0,  'color' => '#7F77DD', 'cls' => ''],
                    ];
                    $totalDossiers = max(1, array_sum(array_column($statutsData, 'count')));
                @endphp

                {{-- Donut SVG --}}
                <div class="donut-wrap">
                    @php
                        $cx = 60; $cy = 60; $r = 48; $circ = 2 * M_PI * $r;
                        $offset = 0; $svgParts = '';
                        foreach($statutsData as $s) {
                            $pct = $s['count'] / $totalDossiers;
                            $dash = $pct * $circ;
                            $gap  = $circ - $dash;
                            $svgParts .= sprintf(
                                '<circle cx="%d" cy="%d" r="%d" fill="none" stroke="%s" stroke-width="16" stroke-dasharray="%.2f %.2f" stroke-dashoffset="%.2f" style="transform:rotate(-90deg);transform-origin:center;"/>',
                                $cx, $cy, $r, $s['color'], $dash, $gap, -$offset * $circ
                            );
                            $offset += $pct;
                        }
                    @endphp
                    <svg width="120" height="120" viewBox="0 0 120 120" style="flex-shrink:0;">
                        <circle cx="60" cy="60" r="48" fill="none" stroke="#F0F2F5" stroke-width="16"/>
                        {!! $svgParts !!}
                        <text x="60" y="56" text-anchor="middle" font-size="16" font-weight="800"
                              fill="#1A1F2E" font-family="Inter,sans-serif">{{ $totalDossiers }}</text>
                        <text x="60" y="70" text-anchor="middle" font-size="9" fill="#9CA3AF"
                              font-family="Inter,sans-serif">DOSSIERS</text>
                    </svg>
                    <div class="donut-legend">
                        @foreach($statutsData as $s)
                        <div class="legend-item">
                            <div class="legend-dot" style="background:{{ $s['color'] }};"></div>
                            <span>{{ $s['label'] }}</span>
                            <span class="legend-val">{{ $s['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Barres de progression --}}
                <div class="statut-bars">
                    @foreach($statutsData as $s)
                    @php $pct = round($s['count'] / $totalDossiers * 100); @endphp
                    <div class="sbar-row">
                        <div class="sbar-meta">
                            <span class="sbar-label">{{ $s['label'] }}</span>
                            <span class="sbar-count" style="color:{{ $s['color'] }};">{{ $s['count'] }} <span style="color:var(--txt3);font-weight:400;font-size:10px;">{{ $pct }}%</span></span>
                        </div>
                        <div class="sbar-track">
                            <div class="sbar-fill" style="width:{{ $pct }}%;background:{{ $s['color'] }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Alertes / dossiers urgents --}}
        <div class="db-card">
            <div class="db-card-header">
                <h6 class="db-card-title">
                    <i class="bi bi-lightning-charge-fill"></i>
                    Action requise
                </h6>
                <a href="{{ route('admin.consultations.index', ['statut'=>'en_attente']) }}" class="db-card-link">
                    Tous <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="alert-list">
                @forelse($consultationsUrgentes ?? [] as $c)
                <a href="{{ route('admin.consultations.show', $c->id) }}" class="alert-item">
                    @if($c->urgent)<div class="urgent-flag"></div>@endif
                    <div class="alert-avatar">{{ strtoupper(substr($c->client_name ?? '?', 0, 2)) }}</div>
                    <div class="alert-info">
                        <div class="alert-name">{{ $c->client_name }}</div>
                        <div class="alert-meta">
                            {{ $c->destination_country ?? '—' }} · {{ $c->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <span class="alert-badge {{ $c->urgent ? 'ab-urgent' : 'ab-attente' }}">
                        {{ $c->urgent ? '⚡ Urgent' : 'En attente' }}
                    </span>
                </a>
                @empty
                <div style="padding:32px;text-align:center;color:var(--txt3);">
                    <i class="bi bi-check2-all" style="font-size:28px;display:block;margin-bottom:8px;color:var(--green);"></i>
                    <div style="font-size:13px;">Aucun dossier urgent</div>
                </div>
                @endforelse
            </div>

            {{-- Séparateur --}}
            <div style="padding:12px 20px;border-top:1px solid var(--border);
                        background:rgba(27,58,107,.02);">
                <div style="font-size:10px;font-weight:700;color:var(--txt3);
                            text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px;">
                    Consultants actifs
                </div>
                <div class="d-flex flex-column gap-2">
                @foreach($consultantsStats ?? [] as $cons)
                <div class="d-flex align-items-center gap-2">
                    <div style="width:28px;height:28px;border-radius:50%;background:var(--navy);
                                color:var(--gold);display:flex;align-items:center;justify-content:center;
                                font-size:10px;font-weight:800;flex-shrink:0;">
                        {{ strtoupper(substr($cons->name, 0, 2)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:12px;font-weight:600;color:var(--txt1);">{{ $cons->name }}</div>
                    </div>
                    <span style="font-size:11px;font-weight:700;background:rgba(27,58,107,.08);
                                 color:var(--navy);padding:2px 8px;border-radius:8px;">
                        {{ $cons->consultations_count }} dossiers
                    </span>
                </div>
                @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ── Activité récente ──────────────────────────── --}}
    <div class="section-label">Activité récente</div>
    <div class="activity-grid">

        {{-- Nouveaux abonnés --}}
        <div class="db-card">
            <div class="db-card-header">
                <h6 class="db-card-title"><i class="bi bi-star-fill"></i>Derniers abonnés</h6>
                <a href="{{ route('admin.abonnements.index') ?? '#' }}" class="db-card-link">Tous <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="db-card-body" style="padding:12px 20px;">
                @forelse($derniersAbonnes ?? [] as $ab)
                <div class="act-item">
                    <div class="act-icon" style="background:rgba(245,166,35,.1);color:var(--gold);">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div>
                        <div class="act-label">{{ $ab->name }}</div>
                        <div class="act-sub">{{ $ab->plan ?? 'Plan inconnu' }}</div>
                    </div>
                    <div class="act-right">
                        <div class="act-amount">{{ number_format($ab->montant ?? 0, 0, ',', ' ') }} XAF</div>
                        <div class="act-date">{{ optional($ab->subscribed_at)->diffForHumans() ?? '—' }}</div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:24px;color:var(--txt3);font-size:12px;">
                    Aucun abonnement récent
                </div>
                @endforelse
            </div>
        </div>

        {{-- Inscriptions cours --}}
        <div class="db-card">
            <div class="db-card-header">
                <h6 class="db-card-title"><i class="bi bi-book-fill"></i>Inscriptions cours</h6>
                <a href="{{ route('admin.cours.index') ?? '#' }}" class="db-card-link">Tous <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="db-card-body" style="padding:12px 20px;">
                @forelse($dernieresInscriptions ?? [] as $ins)
                <div class="act-item">
                    <div class="act-icon" style="background:rgba(127,119,221,.1);color:var(--purple);">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div style="min-width:0;flex:1;">
                        <div class="act-label" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $ins->user?->name ?? '—' }}
                        </div>
                        <div class="act-sub" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $ins->cours?->titre ?? '—' }}
                        </div>
                    </div>
                    <div class="act-right">
                        <div class="act-date">{{ $ins->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:24px;color:var(--txt3);font-size:12px;">
                    Aucune inscription récente
                </div>
                @endforelse
            </div>
        </div>

        {{-- Derniers paiements --}}
        <div class="db-card">
            <div class="db-card-header">
                <h6 class="db-card-title"><i class="bi bi-receipt-cutoff"></i>Paiements reçus</h6>
                <span class="db-card-link" style="cursor:default;">Ce mois</span>
            </div>
            <div class="db-card-body" style="padding:12px 20px;">
                @forelse($derniersPaiements ?? [] as $p)
                <div class="act-item">
                    <div class="act-icon" style="background:rgba(28,200,138,.08);color:var(--green);">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div style="min-width:0;flex:1;">
                        <div class="act-label">{{ $p->consultation?->client_name ?? '—' }}</div>
                        <div class="act-sub">{{ $p->modeLabel() }} · {{ $p->statutLabel() }}</div>
                    </div>
                    <div class="act-right">
                        <div class="act-amount" style="color:var(--green);">
                            +{{ number_format($p->montant, 0, ',', ' ') }} {{ $p->devise }}
                        </div>
                        <div class="act-date">{{ $p->date_paiement->format('d/m/Y') }}</div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:24px;color:var(--txt3);font-size:12px;">
                    Aucun paiement ce mois
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ── Modules (4 piliers) ───────────────────────── --}}
    <div class="section-label">Modules de gestion</div>
    <div class="modules-grid">

        {{-- Abonnements --}}
        <a href="#" class="module-card" style="--mod-color:var(--gold);--mod-bg:rgba(245,166,35,.08);">
            <div class="module-icon"><i class="bi bi-star"></i></div>
            <div>
                <div class="module-title">Abonnements</div>
                <div class="module-desc">Plans, tarifs, durées et suivi des renouvellements.</div>
            </div>
            <div class="module-links">
                <a href="#" class="module-link" style="color:var(--gold);"><i class="bi bi-arrow-right"></i> Gérer les plans</a>
                <a href="#" class="module-link"><i class="bi bi-arrow-right"></i> Voir les abonnés actifs</a>
                <a href="#" class="module-link"><i class="bi bi-arrow-right"></i> Annulations &amp; remboursements</a>
            </div>
        </a>

        {{-- Cours --}}
        <a href="{{ route('admin.cours.index') ?? '#' }}" class="module-card" style="--mod-color:var(--purple);--mod-bg:rgba(127,119,221,.08);">
            <div class="module-icon"><i class="bi bi-book"></i></div>
            <div>
                <div class="module-title">Cours &amp; Contenu</div>
                <div class="module-desc">Création, chapitres, accès et suivi de progression.</div>
            </div>
            <div class="module-links">
                <a href="{{ route('admin.cours.create') ?? '#' }}" class="module-link" style="color:var(--purple);"><i class="bi bi-plus-circle"></i> Nouveau cours</a>
                <a href="{{ route('admin.cours.index') ?? '#' }}" class="module-link"><i class="bi bi-arrow-right"></i> Tous les cours</a>
                <a href="#" class="module-link"><i class="bi bi-arrow-right"></i> Gestion des instructeurs</a>
            </div>
        </a>

        {{-- Consultations --}}
        <a href="{{ route('admin.consultations.index') }}" class="module-card" style="--mod-color:var(--navy);--mod-bg:rgba(27,58,107,.07);">
            <div class="module-icon"><i class="bi bi-briefcase"></i></div>
            <div>
                <div class="module-title">Consultations</div>
                <div class="module-desc">Dossiers, pipeline, documents et assignation consultants.</div>
            </div>
            <div class="module-links">
                <a href="{{ route('admin.consultations.index', ['statut'=>'en_attente']) }}" class="module-link" style="color:var(--navy);"><i class="bi bi-arrow-right"></i> Dossiers en attente</a>
                <a href="{{ route('admin.consultations.index') }}" class="module-link"><i class="bi bi-arrow-right"></i> Tous les dossiers</a>
                <a href="{{ route('admin.consultations.export') }}" class="module-link"><i class="bi bi-download"></i> Exporter CSV</a>
            </div>
        </a>

        {{-- Utilisateurs --}}
        <a href="{{ route('admin.users.index') ?? '#' }}" class="module-card" style="--mod-color:var(--green);--mod-bg:rgba(28,200,138,.07);">
            <div class="module-icon"><i class="bi bi-people"></i></div>
            <div>
                <div class="module-title">Utilisateurs</div>
                <div class="module-desc">Rôles, accès, blocage et support client.</div>
            </div>
            <div class="module-links">
                <a href="{{ route('admin.users.index') ?? '#' }}" class="module-link" style="color:var(--green);"><i class="bi bi-arrow-right"></i> Tous les utilisateurs</a>
                <a href="#" class="module-link"><i class="bi bi-arrow-right"></i> Gérer les rôles</a>
                <a href="#" class="module-link"><i class="bi bi-arrow-right"></i> Utilisateurs bloqués</a>
            </div>
        </a>

    </div>

</div>

@push('scripts')
<script>
// ── Recherche globale AJAX ───────────────────────────────
const searchInput = document.getElementById('globalSearch');
const searchResults = document.getElementById('searchResults');
let searchTimeout;

searchInput.addEventListener('input', function () {
    clearTimeout(searchTimeout);
    const q = this.value.trim();

    if (q.length < 2) { searchResults.style.display = 'none'; return; }

    searchTimeout = setTimeout(() => {
        fetch(`{{ route('admin.consultations.index') }}?search=${encodeURIComponent(q)}&format=json`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.ok ? r.json() : null)
        .then(data => {
            if (!data || !data.results?.length) {
                searchResults.innerHTML = `<div style="padding:16px;text-align:center;
                    font-size:12px;color:#9CA3AF;">Aucun résultat pour "<strong>${q}</strong>"</div>`;
            } else {
                searchResults.innerHTML = data.results.map(c => `
                    <a href="/admin/consultations/${c.id}"
                       style="display:flex;align-items:center;gap:10px;padding:10px 14px;
                              text-decoration:none;border-bottom:1px solid #F0F2F5;
                              transition:background .1s;"
                       onmouseover="this.style.background='#F8F9FB'"
                       onmouseout="this.style.background=''"
                    >
                        <div style="width:32px;height:32px;border-radius:50%;background:#1B3A6B;
                                    color:#F5A623;display:flex;align-items:center;justify-content:center;
                                    font-size:11px;font-weight:800;flex-shrink:0;">
                            ${c.client_name.substring(0,2).toUpperCase()}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:13px;font-weight:600;color:#1A1F2E;">${c.client_name}</div>
                            <div style="font-size:11px;color:#9CA3AF;">${c.destination_country || '—'} · ${c.statut}</div>
                        </div>
                        <span style="font-size:10px;font-family:monospace;color:#9CA3AF;">VF-${c.created_at_year}-${c.created_at_month}-${c.id}</span>
                    </a>
                `).join('');
            }
            searchResults.style.display = 'block';
        })
        .catch(() => { searchResults.style.display = 'none'; });
    }, 300);
});

document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// ── Animer les barres au chargement ─────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const fills = document.querySelectorAll('.sbar-fill');
    fills.forEach(el => {
        const w = el.style.width;
        el.style.width = '0%';
        setTimeout(() => { el.style.width = w; }, 200);
    });
});
</script>
@endpush

@endsection