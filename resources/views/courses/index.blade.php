{{-- resources/views/cours/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Cours d\'Allemand — VisaFly')

@push('styles')
<style>
.cours-hero {
    background: linear-gradient(135deg, #1B3A6B 0%, #0f2548 100%);
    padding: 56px 24px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.cours-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: rgba(245,166,35,.08);
}
.cours-hero::after {
    content: '🇩🇪';
    position: absolute;
    font-size: 120px;
    opacity: .06;
    bottom: -20px; left: -20px;
    transform: rotate(-10deg);
}
.hero-title {
    font-size: 2rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 8px;
    position: relative;
    z-index: 1;
}
.hero-subtitle {
    font-size: 14px;
    color: rgba(255,255,255,.65);
    position: relative;
    z-index: 1;
}

/* Statistiques utilisateur */
.stats-bar {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 28px;
    position: relative;
    z-index: 1;
}
.stat-chip {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 12px;
    padding: 10px 18px;
    text-align: center;
    backdrop-filter: blur(8px);
}
.stat-chip-num {
    font-size: 1.4rem;
    font-weight: 800;
    color: #F5A623;
    line-height: 1;
}
.stat-chip-lbl {
    font-size: 10px;
    color: rgba(255,255,255,.6);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-top: 2px;
}

/* Cards cours */
.cours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    max-width: 1100px;
    margin: -28px auto 0;
    padding: 0 20px 60px;
    position: relative;
    z-index: 2;
}
.cours-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid #eee;
    box-shadow: 0 4px 20px rgba(27,58,107,.07);
    transition: all .25s;
    text-decoration: none;
    display: block;
    color: inherit;
}
.cours-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(27,58,107,.14);
    text-decoration: none;
    color: inherit;
}
.cours-card-header {
    padding: 24px 22px 20px;
    position: relative;
    overflow: hidden;
}
.cours-card-header::after {
    content: '';
    position: absolute;
    bottom: -20px; right: -20px;
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,.1);
}
.cours-niveau-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    background: rgba(255,255,255,.2);
    color: #fff;
    letter-spacing: .5px;
    margin-bottom: 10px;
}
.cours-card-titre {
    font-size: 16px;
    font-weight: 800;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 4px;
}
.cours-card-sub {
    font-size: 12px;
    color: rgba(255,255,255,.7);
}
.cours-card-body { padding: 18px 22px 20px; }
.cours-card-desc {
    font-size: 13px;
    color: #555;
    line-height: 1.6;
    margin-bottom: 14px;
}

/* Barre de progression */
.prog-bar-wrap { margin-bottom: 14px; }
.prog-bar-top {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: #888;
    margin-bottom: 5px;
}
.prog-track {
    height: 6px;
    background: #f0f0f0;
    border-radius: 3px;
    overflow: hidden;
}
.prog-fill {
    height: 100%;
    border-radius: 3px;
    transition: width .6s ease;
}

/* Méta infos */
.cours-meta {
    display: flex;
    gap: 12px;
    font-size: 12px;
    color: #888;
}
.cours-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Bouton */
.btn-cours {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    padding: 11px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all .2s;
    margin-top: 14px;
    text-decoration: none;
}
.btn-cours:hover {
    filter: brightness(1.08);
    transform: translateY(-1px);
    text-decoration: none;
}

/* Badge gratuit */
.gratuit-badge {
    position: absolute;
    top: 12px; right: 12px;
    background: rgba(28,200,138,.9);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    backdrop-filter: blur(4px);
}
</style>
@endpush

@section('content')

{{-- Hero ─────────────────────────────────────────── --}}
<div class="cours-hero">
    <div style="font-size:32px;margin-bottom:12px;position:relative;z-index:1;">🇩🇪</div>
    <h1 class="hero-title">Cours d'Allemand</h1>
    <p class="hero-subtitle">De A1 à C1 — Apprenez l'allemand pour vos études, votre carrière ou votre immigration</p>

    @if($stats)
    <div class="stats-bar">
        <div class="stat-chip">
            <div class="stat-chip-num">{{ $stats['lecons_terminees'] }}</div>
            <div class="stat-chip-lbl">Leçons terminées</div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-num">{{ $stats['points_totaux'] }}</div>
            <div class="stat-chip-lbl">Points gagnés</div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-num">{{ $stats['score_moyen'] }}%</div>
            <div class="stat-chip-lbl">Score moyen</div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-num">{{ $stats['cours_en_cours'] }}</div>
            <div class="stat-chip-lbl">Cours entamés</div>
        </div>
    </div>
    @endif
</div>

{{-- Grille des cours ─────────────────────────────── --}}
<div class="cours-grid">
    @forelse($cours as $c)
    <a href="{{ route('cours.allemand.show', $c->slug) }}" class="cours-card">

        {{-- Header coloré --}}
        <div class="cours-card-header" style="background:{{ $c->couleur }};">
            @if($c->gratuit)
            <div class="gratuit-badge">🆓 Gratuit</div>
            @endif

            <div class="cours-niveau-badge">{{ $c->niveau }}</div>
            <div class="cours-card-titre">{{ $c->titre }}</div>
            <div class="cours-card-sub">{{ $c->sous_titre }}</div>
        </div>

        {{-- Body --}}
        <div class="cours-card-body">
            <p class="cours-card-desc">{{ Str::limit($c->description, 90) }}</p>

            {{-- Progression --}}
            <div class="prog-bar-wrap">
                <div class="prog-bar-top">
                    <span>Progression</span>
                    <span>{{ $c->progression }}%</span>
                </div>
                <div class="prog-track">
                    <div class="prog-fill"
                         style="width:{{ $c->progression }}%;background:{{ $c->couleur }};"></div>
                </div>
            </div>

            {{-- Méta --}}
            <div class="cours-meta">
                <div class="cours-meta-item">
                    <i class="bi bi-book"></i>
                    {{ $c->lecons_count }} leçons
                </div>
                <div class="cours-meta-item">
                    <i class="bi bi-clock"></i>
                    {{ $c->duree_heures }}h
                </div>
                @if($c->progression > 0)
                <div class="cours-meta-item" style="color:#1cc88a;">
                    <i class="bi bi-check-circle"></i>
                    {{ $c->lecons_termines }}/{{ $c->lecons_count }}
                </div>
                @endif
            </div>

            {{-- CTA --}}
            <div class="btn-cours" style="background:{{ $c->couleur }};color:#fff;">
                @if($c->progression > 0 && $c->progression < 100)
                    <i class="bi bi-play-circle"></i> Continuer
                @elseif($c->progression === 100)
                    <i class="bi bi-check-circle"></i> Réviser
                @else
                    <i class="bi bi-lightning-charge"></i> Commencer
                @endif
            </div>
        </div>
    </a>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:#aaa;">
        <i class="bi bi-book" style="font-size:48px;display:block;margin-bottom:12px;opacity:.3;"></i>
        <div style="font-size:15px;">Aucun cours disponible pour le moment.</div>
    </div>
    @endforelse
</div>

@endsection