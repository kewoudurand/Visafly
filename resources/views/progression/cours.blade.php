{{-- resources/views/progression/cours.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Progression — ' . $cours->titre)

@push('styles')
<style>
:root { --c:{{ $cours->couleur ?? '#1B3A6B' }}; --marine:#1B3A6B; --or:#F5A623; }
.pc-hero { background:var(--or);padding:44px 24px 80px;position:relative;overflow:hidden; }
.pc-hero::before { content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(0,0,0,.2),transparent 60%); }
.pc-inner { max-width:860px;margin:0 auto;position:relative;z-index:1; }
.pc-breadcrumb { display:flex;align-items:center;gap:6px;font-size:.75rem;color:rgba(255,255,255,.6);margin-bottom:16px;flex-wrap:wrap; }
.pc-breadcrumb a { color:rgba(255,255,255,.7);text-decoration:none; }
.pc-breadcrumb a:hover { color:#fff; }
.pc-title { font-size:1.8rem;font-weight:900;color:#fff;margin-bottom:6px; }
.pc-sub { color:rgba(255,255,255,.75);font-size:.9rem; }

/* Stats bar */
.pc-stats-bar {
    max-width:860px;margin:-40px auto 28px;padding:0 20px;
    display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;
    position:relative;z-index:10;
}
.pc-stat {
    background:#fff;border-radius:14px;padding:16px;text-align:center;
    box-shadow:0 4px 14px rgba(0,0,0,.08);
    border-top:3px solid var(--or);
}
.pc-stat-val { font-size:1.5rem;font-weight:900;color:var(--marine);line-height:1; }
.pc-stat-lbl { font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#aaa;margin-top:5px; }

/* Body */
.pc-body { max-width:860px;margin:0 auto 80px;padding:0 20px; }

/* Radial progress */
.pc-radial-wrap { display:flex;align-items:center;gap:20px;background:#fff;border-radius:16px;padding:22px;box-shadow:0 4px 14px rgba(0,0,0,.07);margin-bottom:24px; }
.pc-radial { position:relative;width:90px;height:90px;flex-shrink:0; }
.pc-radial svg { transform:rotate(-90deg); }
.pc-radial-pct { position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:900;color:var(--marine); }
.pc-radial-info { flex:1; }
.pc-radial-title { font-size:1rem;font-weight:800;color:var(--marine);margin-bottom:4px; }
.pc-radial-sub   { font-size:.82rem;color:#888; }

/* Leçons list */
.lesson-prog-row {
    display:flex;align-items:center;gap:14px;
    padding:14px 18px;border-bottom:1px solid #f5f5f5;
    text-decoration:none;transition:background .15s;
}
.lesson-prog-row:last-child { border-bottom:none; }
.lesson-prog-row:hover { background:#fafbff;text-decoration:none; }
.lpr-status { width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.82rem;flex-shrink:0; }
.lpr-status.done    { background:var(--or);color:#fff; }
.lpr-status.pending { background:#fff8e8;color:#856404; }
.lpr-status.none    { background:#f5f5f5;color:#bbb; }
.lpr-info { flex:1;min-width:0; }
.lpr-titre { font-size:.88rem;font-weight:700;color:var(--marine);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.lpr-meta  { font-size:.74rem;color:#888;display:flex;gap:10px;flex-wrap:wrap; }
.lpr-right { display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0; }
.lpr-score-badge { font-size:.75rem;font-weight:800;padding:3px 10px;border-radius:10px; }
.lpr-tentatives { font-size:.7rem;color:#bbb; }

@media(max-width:600px) { .pc-stats-bar{grid-template-columns:repeat(2,1fr);} .pc-title{font-size:1.4rem;} }
</style>
@endpush

@section('content')

<div class="pc-hero">
    <div class="pc-inner">
        <div class="pc-breadcrumb">
            <a href="{{ route('progression.index') }}">Mon parcours</a>
            <span>›</span>
            <span style="color:rgba(255,255,255,.85)">{{ $cours->titre }}</span>
        </div>
        <h1 class="pc-title">{{ $cours->titre }}</h1>
        <p class="pc-sub">Détail de votre progression pour ce cours</p>
    </div>
</div>

{{-- Stats --}}
<div class="pc-stats-bar">
    <div class="pc-stat">
        <div class="pc-stat-val">{{ $stats['terminees'] }}/{{ $stats['total'] }}</div>
        <div class="pc-stat-lbl">Leçons terminées</div>
    </div>
    <div class="pc-stat">
        <div class="pc-stat-val">{{ $stats['points_gagnes'] }}</div>
        <div class="pc-stat-lbl">Points gagnés</div>
    </div>
    <div class="pc-stat">
        <div class="pc-stat-val">{{ $stats['points_total'] }}</div>
        <div class="pc-stat-lbl">Points possibles</div>
    </div>
    <div class="pc-stat">
        <div class="pc-stat-val">{{ $stats['score_moyen'] ? round($stats['score_moyen']) : '—' }}%</div>
        <div class="pc-stat-lbl">Score moyen</div>
    </div>
</div>

<div class="pc-body">

    {{-- Cercle progression global --}}
    @php $pct = $coursProgression?->pourcentage ?? 0; @endphp
    <div class="pc-radial-wrap">
        <div class="pc-radial">
            <svg width="90" height="90" viewBox="0 0 90 90">
                <circle cx="45" cy="45" r="36" fill="none" stroke="#f0f0f0" stroke-width="7"/>
                <circle cx="45" cy="45" r="36" fill="none" stroke="{{ $cours->couleur ?? '#1B3A6B' }}" stroke-width="7"
                    stroke-dasharray="{{ round(226.2 * $pct / 100) }} 226.2"
                    stroke-linecap="round"/>
            </svg>
            <div class="pc-radial-pct">{{ $pct }}%</div>
        </div>
        <div class="pc-radial-info">
            <div class="pc-radial-title">
                @if($pct >= 100) 🏆 Cours terminé ! @elseif($pct > 0) 🔥 En progression @else 📚 Pas encore commencé @endif
            </div>
            <div class="pc-radial-sub">
                {{ $stats['terminees'] }} leçon{{ $stats['terminees']>1?'s':'' }} terminée{{ $stats['terminees']>1?'s':'' }}
                sur {{ $stats['total'] }} · {{ $stats['points_gagnes'] }} pts gagnés sur {{ $stats['points_total'] }} possibles
            </div>
            @if($pct < 100)
            <a href="{{ route('cours.allemand.show', $cours->slug) }}"
               style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;background:{{ $cours->couleur ?? '#1B3A6B' }};color:#fff;border-radius:20px;padding:7px 18px;font-size:.8rem;font-weight:700;text-decoration:none">
                <i class="bi bi-play-fill"></i>
                {{ $pct > 0 ? 'Continuer' : 'Commencer' }} le cours
            </a>
            @endif
        </div>
    </div>

    {{-- Liste des leçons avec statut --}}
    <div style="font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--marine);margin-bottom:12px;display:flex;align-items:center;gap:8px">
        <i class="bi bi-list-ol"></i> Programme du cours
        <div style="flex:1;height:1px;background:#eee"></div>
    </div>

    <div style="background:#fff;border-radius:16px;border:1px solid #eee;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.05)">
        @foreach($lecons as $lecon)
        @php
            $lp      = $lessonProgressions[$lecon->id] ?? null;
            $statut  = $lp?->statut;
            $done    = $statut === 'terminee';
            $started = $statut === 'en_cours';
        @endphp
        <a href="{{ $lp ? route('progression.lecon', $lecon) : route('cours.allemand.lecon', [$cours->slug, $lecon->slug]) }}"
           class="lesson-prog-row">

            <div class="lpr-status {{ $done ? 'done' : ($started ? 'pending' : 'none') }}">
                @if($done)     <i class="bi bi-check-lg"></i>
                @elseif($started) <i class="bi bi-hourglass-split"></i>
                @else          {{ $lecon->ordre }}
                @endif
            </div>

            <div class="lpr-info">
                <div class="lpr-titre">{{ $lecon->titre }}</div>
                <div class="lpr-meta">
                    <span><i class="bi {{ $lecon->iconeType() }} me-1"></i>{{ ucfirst($lecon->type) }}</span>
                    @if($lecon->nombreExercices())<span><i class="bi bi-pencil me-1"></i>{{ $lecon->nombreExercices() }} exercices</span>@endif
                    @if($lp && $lp->tentatives > 0)<span><i class="bi bi-arrow-repeat me-1"></i>{{ $lp->tentatives }} tentative{{ $lp->tentatives > 1 ? 's' : '' }}</span>@endif
                </div>
            </div>

            <div class="lpr-right">
                @if($done)
                    <span class="lpr-score-badge"
                        style="background:{{ $lp->score >= 70 ? '#e8f8f0' : ($lp->score >= 50 ? '#fff8e8' : '#fde8e8') }};
                               color:{{ $lp->score >= 70 ? '#198754' : ($lp->score >= 50 ? '#856404' : '#dc3545') }}">
                        {{ $lp->score }}%
                    </span>
                    <span style="font-size:.7rem;color:#1cc88a;font-weight:700">+{{ $lp->points_gagnes }} pts</span>
                @elseif($started)
                    <span class="lpr-score-badge" style="background:#fff8e8;color:#856404">En cours</span>
                @else
                    <span style="font-size:.72rem;color:#ccc">{{ $lecon->points_recompense }} pts</span>
                @endif
                <i class="bi bi-chevron-right" style="color:#ddd;font-size:.75rem"></i>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection