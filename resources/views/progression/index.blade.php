{{-- resources/views/progression/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Mon parcours — VisaFly')

@push('styles')
<style>
:root { --marine:#1B3A6B; --or:#F5A623; }

.prog-hero {
    background:linear-gradient(135deg,#0f2347,#1B3A6B);
    padding:48px 24px 100px; text-align:center; position:relative; overflow:hidden;
}
.prog-hero::before {
    content:''; position:absolute; inset:0;
    background:radial-gradient(ellipse 70% 50% at 50% 110%,rgba(245,166,35,.12) 0%,transparent 70%);
}
.prog-hero h1 { font-size:2rem;font-weight:900;color:#fff;margin-bottom:6px; }
.prog-hero p  { color:rgba(255,255,255,.65);font-size:.95rem; }

/* Stats cards */
.prog-stats {
    max-width:1000px; margin:-50px auto 32px; padding:0 20px;
    display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:14px;
    position:relative; z-index:10;
}
.stat-card {
    background:#fff; border-radius:16px; padding:20px 18px;
    box-shadow:0 6px 20px rgba(0,0,0,.09); text-align:center;
    border-top:4px solid transparent;
}
.stat-card .sc-val { font-size:1.8rem;font-weight:900;color:var(--marine);line-height:1; }
.stat-card .sc-lbl { font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#aaa;margin-top:6px; }
.stat-card .sc-icon { font-size:1.4rem;margin-bottom:8px; }

/* Body */
.prog-body { max-width:1000px;margin:0 auto 80px;padding:0 20px; }

/* Titre section */
.sec-title {
    font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;
    color:var(--marine);margin-bottom:14px;
    display:flex;align-items:center;gap:8px;
}
.sec-title::after { content:'';flex:1;height:1px;background:#eee; }

/* Cours en cours */
.cours-prog-card {
    background:#fff; border-radius:16px; border:1px solid #eee;
    padding:0; overflow:hidden; margin-bottom:12px;
    display:flex; flex-direction:column;
    box-shadow:0 2px 10px rgba(0,0,0,.06);
    transition:box-shadow .2s,transform .2s;
    text-decoration:none;
}
.cours-prog-card:hover { box-shadow:0 6px 20px rgba(0,0,0,.1);transform:translateY(-2px);text-decoration:none; }
.cpc-accent { height:5px; }
.cpc-body { padding:18px 20px; display:flex;align-items:center;gap:16px; }
.cpc-niveau { width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:900;color:#fff;flex-shrink:0; }
.cpc-info { flex:1; min-width:0; }
.cpc-titre { font-size:.92rem;font-weight:800;color:var(--marine);margin-bottom:4px; white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.cpc-meta  { font-size:.75rem;color:#888;display:flex;gap:12px;flex-wrap:wrap; }
.cpc-right { display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0; }
.cpc-pct   { font-size:1.1rem;font-weight:900; }
.cpc-badge { font-size:.68rem;font-weight:700;padding:3px 10px;border-radius:20px; }
.prog-bar-sm { height:5px;background:#f0f0f0;border-radius:10px;overflow:hidden;margin:0 20px 16px; }
.prog-fill-sm { height:100%;border-radius:10px; }

/* Activité récente */
.activite-item {
    display:flex;align-items:center;gap:14px;
    padding:12px 0;border-bottom:1px solid #f5f5f5;
}
.activite-item:last-child { border-bottom:none; }
.act-icon { width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0; }
.act-info { flex:1;min-width:0; }
.act-titre { font-size:.85rem;font-weight:700;color:var(--marine);white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.act-cours { font-size:.75rem;color:#888; }
.act-right { display:flex;flex-direction:column;align-items:flex-end;gap:3px;flex-shrink:0; }
.act-score { font-size:.78rem;font-weight:700; }
.act-date  { font-size:.7rem;color:#bbb; }

/* Empty state */
.empty-state { text-align:center;padding:48px 20px;background:#fff;border-radius:16px;border:1px dashed #ddd; }
.empty-state .es-icon { font-size:2.8rem;margin-bottom:14px; }

@media(max-width:600px) {
    .prog-stats { grid-template-columns:repeat(2,1fr); }
    .prog-hero h1 { font-size:1.5rem; }
}
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="prog-hero">
    <div style="position:relative;z-index:1">
        <p style="font-size:.72rem;font-weight:700;letter-spacing:.25em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:8px">
            MON ESPACE
        </p>
        <h1>Mon parcours d'apprentissage</h1>
        <p>Bonjour {{ auth()->user()->first_name }} — voici votre progression globale</p>
    </div>
</div>

{{-- Stats --}}
<div class="prog-stats">
    <div class="stat-card" style="border-top-color:#1cc88a">
        <div class="sc-icon">✅</div>
        <div class="sc-val">{{ $stats['lecons_terminees'] }}</div>
        <div class="sc-lbl">Leçons terminées</div>
    </div>
    <div class="stat-card" style="border-top-color:var(--or)">
        <div class="sc-icon">⭐</div>
        <div class="sc-val">{{ number_format($stats['points_total']) }}</div>
        <div class="sc-lbl">Points gagnés</div>
    </div>
    <div class="stat-card" style="border-top-color:#0dcaf0">
        <div class="sc-icon">🎯</div>
        <div class="sc-val">{{ $stats['score_moyen'] ? round($stats['score_moyen']) : 0 }}%</div>
        <div class="sc-lbl">Score moyen</div>
    </div>
    <div class="stat-card" style="border-top-color:var(--marine)">
        <div class="sc-icon">📚</div>
        <div class="sc-val">{{ $stats['cours_termines'] }}</div>
        <div class="sc-lbl">Cours terminés</div>
    </div>
    <div class="stat-card" style="border-top-color:#6f42c1">
        <div class="sc-icon">⏱</div>
        <div class="sc-val">
            @if($stats['temps_estime_min'] >= 60)
                {{ floor($stats['temps_estime_min']/60) }}h{{ $stats['temps_estime_min']%60>0?($stats['temps_estime_min']%60).'m':'' }}
            @else
                {{ $stats['temps_estime_min'] }}min
            @endif
        </div>
        <div class="sc-lbl">Temps d'étude</div>
    </div>
    <div class="stat-card" style="border-top-color:#fd7e14">
        <div class="sc-icon">🔥</div>
        <div class="sc-val">{{ $stats['cours_en_cours'] }}</div>
        <div class="sc-lbl">Cours en cours</div>
    </div>
</div>

<div class="prog-body">

    {{-- ── Mes cours ──────────────────────────────────────────── --}}
    <div class="sec-title"><i class="bi bi-journals"></i>Mes cours</div>

    @if($coursProgressions->isEmpty())
    <div class="empty-state mb-4">
        <div class="es-icon">📖</div>
        <h5 style="color:var(--marine);font-weight:800">Vous n'avez encore commencé aucun cours</h5>
        <p class="text-muted mb-3">Explorez nos cours et démarrez votre apprentissage dès aujourd'hui.</p>
        <a href="{{ route('cours.list') }}" class="btn" style="background:var(--marine);color:#fff;border-radius:25px;padding:10px 28px;font-weight:700">
            Voir les cours disponibles
        </a>
    </div>
    @else
    <div class="mb-4">
        @foreach($coursProgressions as $cp)
        @php $c = $cp->cours; if(!$c) continue; @endphp
        <a href="{{ route('progression.cours', $c) }}" class="cours-prog-card">
            <div class="cpc-accent" style="background:{{ $c->couleur ?? '#1B3A6B' }}"></div>
            <div class="cpc-body">
                <div class="cpc-niveau" style="background:{{ $c->couleur ?? '#1B3A6B' }}">{{ $c->niveau }}</div>
                <div class="cpc-info">
                    <div class="cpc-titre">{{ $c->titre }}</div>
                    <div class="cpc-meta">
                        <span><i class="bi bi-collection me-1"></i>{{ $cp->lecons_terminees }}/{{ $cp->total_lecons }} leçons</span>
                        <span><i class="bi bi-trophy me-1"></i>{{ $cp->points_total }} pts</span>
                    </div>
                </div>
                <div class="cpc-right">
                    <span class="cpc-pct" style="color:{{ $c->couleur ?? '#1B3A6B' }}">{{ $cp->pourcentage }}%</span>
                    @if($cp->termine)
                        <span class="cpc-badge" style="background:#e8f8f0;color:#198754">✅ Terminé</span>
                    @else
                        <span class="cpc-badge" style="background:#fff8e8;color:#856404">En cours</span>
                    @endif
                </div>
            </div>
            <div class="prog-bar-sm">
                <div class="prog-fill-sm" style="width:{{ $cp->pourcentage }}%;background:{{ $c->couleur ?? '#1B3A6B' }}"></div>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    <div class="row g-4">

        {{-- ── Activité récente ──────────────────────────────── --}}
        <div class="col-lg-7">
            <div class="sec-title"><i class="bi bi-clock-history"></i>Activité récente</div>
            <div style="background:#fff;border-radius:16px;border:1px solid #eee;padding:6px 20px;">
                @forelse($activiteRecente as $lp)
                @php
                    $l = $lp->lesson;
                    $c = $lp->cours;
                    $bgIcon = match($l?->type) {
                        'vocabulaire'=>'#e8f8f0','dialogue'=>'#e8f0ff',
                        'grammaire'=>'#fff8e8','audio'=>'#e8f9ff',default=>'#f5f5f5'
                    };
                    $clIcon = match($l?->type) {
                        'vocabulaire'=>'#198754','dialogue'=>'#0d6efd',
                        'grammaire'=>'#856404','audio'=>'#0dcaf0',default=>'#888'
                    };
                @endphp
                <div class="activite-item">
                    <div class="act-icon" style="background:{{ $bgIcon }}">
                        <i class="bi {{ $l?->iconeType() ?? 'bi-book' }}" style="color:{{ $clIcon }}"></i>
                    </div>
                    <div class="act-info">
                        <div class="act-titre">{{ $l?->titre ?? '—' }}</div>
                        <div class="act-cours">{{ $c?->titre ?? '—' }}</div>
                    </div>
                    <div class="act-right">
                        <span class="act-score" style="color:{{ $lp->score >= 70 ? '#198754' : ($lp->score >= 50 ? '#F5A623' : '#dc3545') }}">
                            {{ $lp->score }}%
                        </span>
                        <span class="act-date">{{ $lp->terminee_le?->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-4" style="font-size:.85rem">Aucune activité pour l'instant.</p>
                @endforelse
            </div>
        </div>

        {{-- ── Cours suggérés ────────────────────────────────── --}}
        <div class="col-lg-5">
            <div class="sec-title"><i class="bi bi-compass"></i>Cours suggérés</div>
            
            @forelse($coursDisponibles as $c)
                <a href="{{ route('cours.allemand.show', ['cours' => $c->slug]) }}"
                style="display:flex;align-items:center;gap:14px;background:#fff;border-radius:14px;border:1px solid #eee;padding:16px;margin-bottom:10px;text-decoration:none;transition:all .2s"
                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.09)'"
                onmouseout="this.style.transform='';this.style.boxShadow=''">
                    
                    <div style="width:46px;height:46px;border-radius:12px;background:{{ $c->couleur ?? '#1B3A6B' }};display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:900;color:#fff;flex-shrink:0">
                        {{ $c->niveau }}
                    </div>

                    <div style="flex:1;min-width:0">
                        <div style="font-size:.88rem;font-weight:800;color:var(--marine);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $c->titre }}
                        </div>
                        <div style="font-size:.75rem;color:#888">
                            {{ $c->lessons_count ?? $c->lecons()->where('publiee',true)->count() }} leçons 
                            @if($c->duree_estimee_minutes)
                                · {{ ceil($c->duree_estimee_minutes/60) }}h
                            @endif
                        </div>
                    </div>
                    <i class="bi bi-arrow-right" style="color:#ddd;font-size:.85rem"></i>
                </a>
            @empty
                <p class="text-muted text-center" style="font-size:.85rem">Tous les cours ont été commencés 🎉</p>
            @endforelse

            <a href="{{ route('cours.list') }}" style="display:block;text-align:center;font-size:.8rem;color:var(--marine);font-weight:700;padding-top:8px;text-decoration:none">
                Voir tous les cours →
            </a>
        </div>

    </div>
</div>

@endsection