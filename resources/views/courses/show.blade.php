{{-- resources/views/courses/show.blade.php --}}
@extends('layouts.app')
@section('title', $cours->titre . ' — VisaFly')

@push('styles')
<style>
:root { --c: {{ $cours->couleur ?? '#1B3A6B' }}; --marine:#1B3A6B; --or:#F5A623; }

/* ── Hero header ───────────────────────────── */
.cs-hero {
    background: var(--c);
    padding: 36px 0 0;
    position: relative; overflow: hidden;
}
.cs-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background: linear-gradient(135deg, rgba(0,0,0,.18) 0%, transparent 60%),
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M0 0h40v40H0zM40 40h40v40H40z'/%3E%3C/g%3E%3C/svg%3E");
}
.cs-hero-inner { max-width:1040px; margin:0 auto; padding:0 24px; position:relative; z-index:1; }

/* Breadcrumb */
.cs-breadcrumb { display:flex; align-items:center; gap:6px; font-size:.75rem; color:rgba(255,255,255,.6); margin-bottom:18px; flex-wrap:wrap; }
.cs-breadcrumb a { color:rgba(255,255,255,.7); text-decoration:none; }
.cs-breadcrumb a:hover { color:#fff; }
.cs-breadcrumb span { opacity:.5; }

/* Niveau badge */
.cs-niveau-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.2); color:#fff; border-radius:8px;
    font-size:.68rem; font-weight:800; letter-spacing:.06em; padding:4px 12px;
    margin-bottom:12px;
}
.cs-hero h1 { font-size:2rem; font-weight:900; color:#fff; margin-bottom:6px; }
.cs-hero-sub { font-size:.95rem; color:rgba(255,255,255,.8); margin-bottom:18px; }

/* Tags */
.cs-tags { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px; }
.cs-tag {
    display:inline-flex; align-items:center; gap:5px;
    background:rgba(255,255,255,.12); color:rgba(255,255,255,.9);
    border-radius:20px; font-size:.75rem; font-weight:600;
    padding:4px 12px; border:1px solid rgba(255,255,255,.2);
}

/* Barre prog hero */
.cs-hero-prog { background:rgba(0,0,0,.2); padding:12px 0; margin-top:8px; }
.cs-hero-prog-inner { max-width:1040px; margin:0 auto; padding:0 24px; display:flex; align-items:center; gap:14px; }
.cs-hero-prog-label { font-size:.75rem; color:rgba(255,255,255,.7); font-weight:600; white-space:nowrap; }
.cs-hero-prog-bar { flex:1; height:7px; background:rgba(255,255,255,.2); border-radius:10px; overflow:hidden; }
.cs-hero-prog-fill { height:100%; border-radius:10px; background:#fff; }
.cs-hero-prog-pct { font-size:.8rem; font-weight:800; color:#fff; white-space:nowrap; }

/* ── Layout body ───────────────────────────── */
.cs-body { max-width:1040px; margin:28px auto 80px; padding:0 24px; display:grid; grid-template-columns:1fr 280px; gap:24px; }

/* ── Stats bar ─────────────────────────────── */
.cs-stats-bar {
    display:grid; grid-template-columns:repeat(4,1fr) auto;
    gap:12px; background:#fff; border-radius:16px;
    box-shadow:0 4px 16px rgba(0,0,0,.07);
    padding:20px 22px; margin-bottom:20px; align-items:center;
}
.cs-stat-item { text-align:center; }
.cs-stat-val { font-size:1.6rem; font-weight:900; color:var(--marine); line-height:1; }
.cs-stat-lbl { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#aaa; margin-top:4px; }
.cs-prog-circle { position:relative; width:70px; height:70px; flex-shrink:0; }
.cs-prog-circle svg { transform:rotate(-90deg); }
.cs-prog-circle-pct { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:900; color:var(--marine); }

/* ── Section cards ─────────────────────────── */
.cs-card { background:#fff; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,.07); margin-bottom:20px; overflow:hidden; }
.cs-card-head { padding:16px 20px; border-bottom:1px solid #f5f5f5; display:flex; align-items:center; gap:8px; }
.cs-card-head-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }
.cs-card-title { font-size:.82rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#333; }
.cs-card-body { padding:18px 20px; }

/* Description */
.cs-desc-text { font-size:.88rem; color:#555; line-height:1.75; }

/* Objectifs */
.cs-objectifs { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:8px; }
.cs-objectifs li { display:flex; align-items:flex-start; gap:10px; font-size:.85rem; color:#444; line-height:1.5; }
.cs-objectifs li::before { content:'✓'; width:20px; height:20px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.65rem; font-weight:900; flex-shrink:0; margin-top:1px; background:var(--c); color:#fff; }

/* ── Liste leçons ──────────────────────────── */
.lesson-row {
    display:flex; align-items:center; gap:14px;
    padding:14px 20px; border-bottom:1px solid #f5f5f5;
    text-decoration:none; transition:background .15s;
}
.lesson-row:last-child { border-bottom:none; }
.lesson-row:hover { background:#fafbff; text-decoration:none; }
.lesson-row.locked { opacity:.5; pointer-events:none; }

.lr-num {
    width:32px; height:32px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:.72rem; font-weight:800;
}
.lr-num.done    { background:var(--c); color:#fff; }
.lr-num.pending { background:#f0f0f0; color:#888; }
.lr-num.locked  { background:#f0f0f0; color:#bbb; }

.lr-info { flex:1; min-width:0; }
.lr-titre { font-size:.88rem; font-weight:700; color:#1B3A6B; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:4px; }
.lr-chips { display:flex; flex-wrap:wrap; gap:6px; }
.lr-chip { font-size:.7rem; color:#888; background:#f5f5f5; border-radius:5px; padding:2px 7px; display:inline-flex; align-items:center; gap:3px; }
.lr-gratuit { font-size:.68rem; font-weight:700; background:#e8f8f0; color:#198754; border-radius:5px; padding:2px 7px; }
.lr-pts { font-size:.7rem; color:#888; flex-shrink:0; }
.lr-arrow { color:#ddd; font-size:.85rem; flex-shrink:0; }
.lesson-row:hover .lr-arrow { color:var(--c); }

/* Score badge */
.lr-score { font-size:.7rem; font-weight:700; background:#e8f8f0; color:#198754; border-radius:6px; padding:2px 8px; flex-shrink:0; }

/* ── Sidebar ───────────────────────────────── */
.cs-sidebar {}
.cs-sidebar-card { background:#fff; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,.07); margin-bottom:16px; overflow:hidden; }
.cs-sidebar-head { padding:14px 18px; border-bottom:1px solid #f5f5f5; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#555; }
.cs-sidebar-body { padding:14px 18px; }
.cs-detail-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f9f9f9; font-size:.8rem; }
.cs-detail-row:last-child { border-bottom:none; }
.cs-detail-label { color:#888; font-weight:600; }
.cs-detail-val { color:#333; font-weight:700; text-align:right; }

/* Autres cours */
.autre-cours { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid #f5f5f5; text-decoration:none; }
.autre-cours:last-child { border-bottom:none; }
.autre-cours:hover { text-decoration:none; }
.autre-cours-badge { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:900; color:#fff; flex-shrink:0; }
.autre-cours-info {}
.autre-cours-titre { font-size:.82rem; font-weight:700; color:#1B3A6B; margin-bottom:2px; }
.autre-cours-meta { font-size:.72rem; color:#888; }

/* CTA commencer */
.cs-cta-btn {
    display:flex; align-items:center; justify-content:center; gap:8px;
    width:100%; padding:14px; border-radius:12px; border:none; cursor:pointer;
    font-size:.9rem; font-weight:800; color:#fff;
    text-decoration:none; background:var(--c); transition:filter .2s,transform .2s;
    margin-bottom:10px;
}
.cs-cta-btn:hover { filter:brightness(1.08); transform:translateY(-1px); color:#fff; }
.cs-cta-test {
    display:flex; align-items:center; justify-content:center; gap:8px;
    width:100%; padding:12px; border-radius:12px; cursor:pointer;
    font-size:.85rem; font-weight:700; color:var(--c);
    text-decoration:none; background:#f0f4ff; border:1.5px solid var(--c)1a; transition:all .2s;
}
.cs-cta-test:hover { background:var(--c)15; color:var(--c); text-decoration:none; }

/* ── Guest CTA ─────────────────────────────── */
.cs-guest-cta { background:linear-gradient(135deg,#1B3A6B,#243f70); border-radius:16px; padding:28px; color:#fff; text-align:center; margin-top:16px; }

@media(max-width:768px) {
    .cs-body { grid-template-columns:1fr; }
    .cs-stats-bar { grid-template-columns:repeat(2,1fr); }
    .cs-hero h1 { font-size:1.5rem; }
}
</style>
@endpush

@section('content')

{{-- ── Hero ──────────────────────────────────────────────────── --}}
<div class="cs-hero">
    <div class="cs-hero-inner">

        {{-- Breadcrumb --}}
        <div class="cs-breadcrumb">
            <a href="{{ route('cours.list') }}">Cours d'Allemand</a>
            <span>›</span>
            <a href="#">{{ $cours->niveau }}</a>
            <span>›</span>
            <span style="color:rgba(255,255,255,.85)">{{ $cours->titre }}</span>
        </div>

        <div class="cs-niveau-badge">
            <i class="bi {{ $cours->icone ?? 'bi-book' }}"></i>
            Niveau {{ $cours->niveau }}
        </div>

        <h1>{{ $cours->titre }}</h1>
        @if($cours->sous_titre)
            <p class="cs-hero-sub">{{ $cours->sous_titre }}</p>
        @endif

        {{-- Tags --}}
        <div class="cs-tags">
            <span class="cs-tag"><i class="bi bi-translate"></i>Français → Allemand</span>
            <span class="cs-tag"><i class="bi bi-bar-chart-steps"></i>Niveau CECRL {{ $cours->niveau }}</span>
            @if($cours->instructeur)
            <span class="cs-tag"><i class="bi bi-person-badge"></i>{{ $cours->instructeur->name }}</span>
            @endif
            @if(! $cours->gratuit)
            <span class="cs-tag"><i class="bi bi-lock"></i>Abonnement requis</span>
            @else
            <span class="cs-tag"><i class="bi bi-unlock-fill"></i>Accès gratuit</span>
            @endif
        </div>
    </div>

    {{-- Barre de progression --}}
    @auth
    <div class="cs-hero-prog">
        <div class="cs-hero-prog-inner">
            <span class="cs-hero-prog-label">Votre progression :</span>
            <div class="cs-hero-prog-bar">
                <div class="cs-hero-prog-fill" style="width:{{ $pourcentage }}%"></div>
            </div>
            <span class="cs-hero-prog-pct">{{ $pourcentage }}%</span>
        </div>
    </div>
    @endauth
</div>

{{-- ── Body ──────────────────────────────────────────────────── --}}
<div class="cs-body">

    {{-- ════ COLONNE PRINCIPALE ════ --}}
    <div>

        {{-- Stats bar --}}
        @php
            $totalLecons    = $lecons->count();
            $leconesTerm    = count(array_filter($progressions, fn($s)=>$s==='terminee'));
            $totalPts       = $lecons->sum('points_recompense');
            $totalDureeMin  = $lecons->sum('duree_estimee_minutes');
            $dureeFormatee  = $totalDureeMin >= 60 ? floor($totalDureeMin/60).'h'.($totalDureeMin%60>0?($totalDureeMin%60).'min':'') : $totalDureeMin.'min';
        @endphp
        <div class="cs-stats-bar">
            <div class="cs-stat-item">
                <div class="cs-stat-val">{{ $totalLecons }}</div>
                <div class="cs-stat-lbl">Leçons</div>
            </div>
            <div class="cs-stat-item">
                <div class="cs-stat-val">{{ $leconesTerm }}</div>
                <div class="cs-stat-lbl">Terminées</div>
            </div>
            <div class="cs-stat-item">
                <div class="cs-stat-val">{{ $dureeFormatee }}</div>
                <div class="cs-stat-lbl">Durée totale</div>
            </div>
            <div class="cs-stat-item">
                <div class="cs-stat-val">{{ $totalPts }}</div>
                <div class="cs-stat-lbl">Points possibles</div>
            </div>
            {{-- Cercle progression --}}
            @php $pctSvg = $pourcentage; @endphp
            <div class="cs-prog-circle">
                <svg width="70" height="70" viewBox="0 0 70 70">
                    <circle cx="35" cy="35" r="28" fill="none" stroke="#f0f0f0" stroke-width="6"/>
                    <circle cx="35" cy="35" r="28" fill="none" stroke="{{ $cours->couleur ?? '#1B3A6B' }}" stroke-width="6"
                        stroke-dasharray="{{ round(175.9 * $pctSvg / 100) }} 175.9"
                        stroke-linecap="round"/>
                </svg>
                <div class="cs-prog-circle-pct">{{ $pctSvg }}%</div>
            </div>
        </div>

        {{-- À propos --}}
        @if($cours->description)
        <div class="cs-card">
            <div class="cs-card-head">
                <div class="cs-card-head-icon" style="background:{{ $cours->couleur ?? '#1B3A6B' }}20">
                    <i class="bi bi-info-circle" style="color:{{ $cours->couleur ?? '#1B3A6B' }}"></i>
                </div>
                <span class="cs-card-title">À propos de ce cours</span>
            </div>
            <div class="cs-card-body">
                <p class="cs-desc-text">{{ $cours->description }}</p>
            </div>
        </div>
        @endif

        {{-- Ce que vous allez apprendre --}}
        @php
            $objectifs = $lecons->take(6)->map(fn($l) => match($l->type) {
                'vocabulaire' => 'Maîtriser le vocabulaire : '.$l->titre,
                'grammaire'   => 'Comprendre la grammaire : '.$l->titre,
                'dialogue'    => 'Pratiquer les dialogues : '.$l->titre,
                'audio'       => 'Comprendre les échanges audio : '.$l->titre,
                default       => 'Explorer : '.$l->titre,
            })->toArray();
        @endphp
        @if(count($objectifs))
        <div class="cs-card">
            <div class="cs-card-head">
                <div class="cs-card-head-icon" style="background:{{ $cours->couleur ?? '#1B3A6B' }}20">
                    <i class="bi bi-check2-all" style="color:{{ $cours->couleur ?? '#1B3A6B' }}"></i>
                </div>
                <span class="cs-card-title">Ce que vous allez apprendre</span>
            </div>
            <div class="cs-card-body">
                <ul class="cs-objectifs">
                    @foreach($objectifs as $obj)
                    <li>{{ $obj }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Programme --}}
        <div class="cs-card">
            <div class="cs-card-head">
                <div class="cs-card-head-icon" style="background:{{ $cours->couleur ?? '#1B3A6B' }}20">
                    <i class="bi bi-list-ol" style="color:{{ $cours->couleur ?? '#1B3A6B' }}"></i>
                </div>
                <span class="cs-card-title">Programme du cours — {{ $totalLecons }} leçon{{ $totalLecons>1?'s':'' }}</span>
            </div>

            @if($lecons->isEmpty())
            <div class="cs-card-body text-center text-muted py-4">
                <i class="bi bi-journals fs-2 d-block mb-2"></i>
                Aucune leçon disponible.
            </div>
            @else
            @foreach($lecons as $lecon)
            @php
                $statut       = $progressions[$lecon->id] ?? null;
                $estTerminee  = $statut === 'terminee';
                $estAccessible = $lecon->gratuite || auth()->check();
            @endphp

            @if($estAccessible)
            <a href="{{ route('cours.allemand.lecon', [$cours->slug, $lecon->slug]) }}" class="lesson-row">
            @else
            <div class="lesson-row locked">
            @endif

                <div class="lr-num {{ $estTerminee ? 'done' : ($estAccessible ? 'pending' : 'locked') }}">
                    @if($estTerminee)
                        <i class="bi bi-check-lg"></i>
                    @elseif(! $estAccessible)
                        <i class="bi bi-lock-fill"></i>
                    @else
                        {{ $lecon->ordre }}
                    @endif
                </div>

                <div class="lr-info">
                    <div class="lr-titre">{{ $lecon->titre }}</div>
                    <div class="lr-chips">
                        <span class="lr-chip"><i class="bi {{ $lecon->iconeType() }}"></i>{{ ucfirst($lecon->type) }}</span>
                        @if($lecon->duree_estimee_minutes)
                        <span class="lr-chip"><i class="bi bi-clock"></i>{{ $lecon->duree_estimee_minutes }} min</span>
                        @endif
                        @if($lecon->nombreMots())
                        <span class="lr-chip"><i class="bi bi-alphabet"></i>{{ $lecon->nombreMots() }} mots</span>
                        @endif
                        @if($lecon->nombreExercices())
                        <span class="lr-chip"><i class="bi bi-pencil"></i>{{ $lecon->nombreExercices() }} exercices</span>
                        @endif
                        @if($lecon->gratuite)
                        <span class="lr-gratuit">Gratuite</span>
                        @endif
                    </div>
                </div>

                @if($estTerminee)
                <span class="lr-score"><i class="bi bi-trophy-fill me-1"></i>+{{ $lecon->points_recompense }}</span>
                @else
                <span class="lr-pts">{{ $lecon->points_recompense }} pts</span>
                @endif

                <i class="bi bi-chevron-right lr-arrow"></i>

            @if($estAccessible)
            </a>
            @else
            </div>
            @endif
            @endforeach
            @endif
        </div>

    </div>

    {{-- ════ SIDEBAR ════ --}}
    <div class="cs-sidebar">

        {{-- CTA --}}
        @php
            $firstLecon = $lecons->first();
            $inProgressLecon = $lecons->first(fn($l) => ($progressions[$l->id] ?? null) === 'en_cours')
                ?? $lecons->first(fn($l) => !isset($progressions[$l->id]))
                ?? $firstLecon;
        @endphp
        @if($inProgressLecon)
        <div class="cs-sidebar-card p-3">
            <a href="{{ route('cours.allemand.lecon', [$cours->slug, $inProgressLecon->slug]) }}"
               class="cs-cta-btn">
                <i class="bi bi-play-fill"></i>
                {{ $pourcentage > 0 ? 'Continuer le cours' : 'Commencer le cours' }}
            </a>
            <a href="{{ route('langues.series', $cours->langue_code ?? 'goethe') }}"
               class="cs-cta-test">
                <i class="bi bi-patch-check"></i>
                Passer un test
            </a>
        </div>
        @endif

        {{-- Détails --}}
        <div class="cs-sidebar-card">
            <div class="cs-sidebar-head">Détails du cours</div>
            <div class="cs-sidebar-body p-0">
                @foreach([
                    ['Langue','Français → Allemand'],
                    ['Niveau CECRL',$cours->niveau],
                    ['Leçons',$totalLecons.' leçons'],
                    ['Durée estimée',$dureeFormatee],
                    ['Points à gagner',$totalPts.' points'],
                    ['Accès',$cours->gratuit?'Gratuit':'Abonnement'],
                ] as [$lbl,$val])
                <div class="cs-detail-row" style="padding:8px 18px">
                    <span class="cs-detail-label">{{ $lbl }}</span>
                    <span class="cs-detail-val">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Autres cours --}}
        @php
            $autresCours = \App\Models\Course::where('publie',true)
                ->where('id','!=',$cours->id)
                ->orderBy('ordre')->take(3)->get();
        @endphp
        @if($autresCours->count())
        <div class="cs-sidebar-card">
            <div class="cs-sidebar-head">Autres cours disponibles</div>
            <div class="cs-sidebar-body">
                @foreach($autresCours as $ac)
                <a href="{{ route('cours.allemand.show', $ac->slug) }}" class="autre-cours">
                    <div class="autre-cours-badge" style="background:{{ $ac->couleur ?? '#1B3A6B' }}">{{ $ac->niveau }}</div>
                    <div class="autre-cours-info">
                        <div class="autre-cours-titre">{{ Str::limit($ac->titre, 30) }}</div>
                        <div class="autre-cours-meta">
                            {{ $ac->lecons()->where('publiee',true)->count() }} leçons
                            @if($ac->duree_estimee_minutes) · {{ ceil($ac->duree_estimee_minutes/60) }}h @endif
                        </div>
                    </div>
                </a>
                @endforeach
                <a href="{{ route('cours.list') }}" style="font-size:.78rem;color:var(--c);font-weight:700;display:block;text-align:center;padding-top:10px;text-decoration:none">
                    Voir tous les cours →
                </a>
            </div>
        </div>
        @endif

        {{-- Guest CTA --}}
        @guest
        <div class="cs-guest-cta">
            <h6 class="fw-800 mb-2" style="font-weight:800">Commencez gratuitement</h6>
            <p style="font-size:.82rem;opacity:.8;margin-bottom:16px">Créez un compte pour suivre votre progression et accéder à toutes les leçons.</p>
            <a href="{{ route('register') }}" style="display:block;background:#F5A623;color:#000;font-weight:800;padding:10px;border-radius:10px;text-decoration:none;font-size:.85rem;text-align:center">
                S'inscrire — C'est gratuit
            </a>
        </div>
        @endguest

    </div>
</div>

@endsection