{{-- resources/views/cours/show.blade.php --}}
@extends('layouts.app')
@section('title', $cours->titre.' — Cours d\'Allemand VisaFly')

@push('styles')
<style>
/* ══ Hero du cours ══════════════════════════════════════ */
.cours-show-hero {
    background: {{ $cours->couleur }};
    padding: 52px 24px 80px;
    position: relative;
    overflow: hidden;
}
.cours-show-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.cours-show-hero::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(0,0,0,.06);
}
.hero-inner {
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}
.hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: rgba(255,255,255,.65);
    margin-bottom: 20px;
}
.hero-breadcrumb a {
    color: rgba(255,255,255,.65);
    text-decoration: none;
    transition: color .2s;
}
.hero-breadcrumb a:hover { color: #fff; }
.hero-breadcrumb i { font-size: 10px; }

.niveau-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .5px;
    margin-bottom: 14px;
    backdrop-filter: blur(6px);
}
.hero-titre {
    font-size: 2rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 6px;
    line-height: 1.2;
}
.hero-sous-titre {
    font-size: 15px;
    color: rgba(255,255,255,.75);
    margin-bottom: 24px;
}

/* Chips méta */
.hero-chips {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}
.hero-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(0,0,0,.15);
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,.9);
    backdrop-filter: blur(4px);
}

/* Barre de progression hero */
.hero-prog-wrap {
    max-width: 420px;
}
.hero-prog-label {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: rgba(255,255,255,.7);
    margin-bottom: 6px;
}
.hero-prog-track {
    height: 8px;
    background: rgba(0,0,0,.2);
    border-radius: 4px;
    overflow: hidden;
}
.hero-prog-fill {
    height: 100%;
    border-radius: 4px;
    background: #fff;
    transition: width .6s ease;
}

/* ══ Contenu principal ══════════════════════════════════ */
.cours-show-body {
    max-width: 900px;
    margin: -44px auto 60px;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

/* ══ Card description ═══════════════════════════════════ */
.desc-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #eee;
    padding: 22px 26px;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(27,58,107,.07);
}
.desc-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #1B3A6B;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.desc-text {
    font-size: 14px;
    color: #555;
    line-height: 1.8;
}

/* ══ Stats cards ════════════════════════════════════════ */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
@media(max-width:640px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
.stat-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #eee;
    padding: 18px 16px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(27,58,107,.05);
}
.stat-card-num {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 4px;
}
.stat-card-lbl {
    font-size: 11px;
    font-weight: 600;
    color: #888;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* ══ Leçons ═════════════════════════════════════════════ */
.lecons-section-title {
    font-size: 16px;
    font-weight: 800;
    color: #1B3A6B;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.lecons-section-title span {
    font-size: 12px;
    font-weight: 500;
    color: #888;
}

.lecon-row {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #eee;
    padding: 16px 20px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(27,58,107,.04);
    transition: all .2s;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}
.lecon-row:hover {
    border-color: {{ $cours->couleur }};
    box-shadow: 0 6px 24px rgba(27,58,107,.1);
    transform: translateY(-2px);
    text-decoration: none;
    color: inherit;
}
.lecon-row.termine {
    border-left: 4px solid #1cc88a;
}
.lecon-row.locked {
    opacity: .65;
    cursor: not-allowed;
}
.lecon-row.locked:hover {
    transform: none;
    box-shadow: 0 2px 8px rgba(27,58,107,.04);
}

/* Numéro de leçon */
.lecon-num {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 800;
    flex-shrink: 0;
    border: 2px solid;
}
.lecon-num.done {
    background: rgba(28,200,138,.1);
    border-color: #1cc88a;
    color: #0f6e56;
}
.lecon-num.current {
    background: {{ $cours->couleur }};
    border-color: {{ $cours->couleur }};
    color: #fff;
}
.lecon-num.pending {
    background: #f8f9fb;
    border-color: #e8e8e8;
    color: #aaa;
}

/* Icône type */
.lecon-type-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}

/* Contenu texte */
.lecon-info { flex: 1; min-width: 0; }
.lecon-titre {
    font-size: 14px;
    font-weight: 700;
    color: #1B3A6B;
    margin-bottom: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.lecon-meta-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.lecon-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #888;
}
.lecon-meta-item i { font-size: 11px; }

/* Badges */
.badge-gratuite {
    padding: 2px 9px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 700;
    background: rgba(28,200,138,.1);
    color: #0f6e56;
}
.badge-premium {
    padding: 2px 9px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 700;
    background: rgba(245,166,35,.1);
    color: #633806;
}
.badge-type {
    padding: 2px 9px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 600;
    background: rgba(27,58,107,.07);
    color: #1B3A6B;
}

/* Statut droite */
.lecon-status { flex-shrink: 0; }
.status-done {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 700;
    color: #1cc88a;
}
.status-lock {
    color: #ccc;
    font-size: 16px;
}
.status-arrow {
    color: {{ $cours->couleur }};
    font-size: 16px;
}

/* ══ Sidebar / CTA ══════════════════════════════════════ */
.sidebar-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #eee;
    padding: 22px;
    box-shadow: 0 4px 20px rgba(27,58,107,.07);
    margin-bottom: 16px;
    position: sticky;
    top: 90px;
}
.btn-commencer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 14px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    margin-bottom: 10px;
}
.btn-commencer:hover {
    filter: brightness(1.08);
    transform: translateY(-2px);
    text-decoration: none;
}
.btn-commencer-sec {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    border: 1.5px solid #e8e8e8;
    background: transparent;
    color: #666;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
}
.btn-commencer-sec:hover {
    border-color: #1B3A6B;
    color: #1B3A6B;
    text-decoration: none;
}

/* Points / objectifs */
.objectif-line {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 13px;
    color: #444;
    margin-bottom: 8px;
    line-height: 1.5;
}
.objectif-line i { color: #1cc88a; font-size: 14px; margin-top: 1px; flex-shrink: 0; }

/* Alerte premium */
.premium-alert {
    background: rgba(245,166,35,.08);
    border: 1px solid rgba(245,166,35,.3);
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 13px;
    color: #633806;
    line-height: 1.6;
    margin-bottom: 16px;
}
.premium-alert strong { display: block; margin-bottom: 4px; }

/* Responsive */
@media(max-width:768px) {
    .cours-show-hero { padding: 36px 16px 72px; }
    .hero-titre { font-size: 1.5rem; }
}
</style>
@endpush

@section('content')

{{-- ══ HERO ═══════════════════════════════════════════════════ --}}
<div class="cours-show-hero">
  <div class="hero-inner">

    {{-- Fil d'Ariane --}}
    <div class="hero-breadcrumb">
      <a href="{{ route('cours.allemand.index') }}">
        <i class="bi bi-house-fill"></i> Cours d'allemand
      </a>
      <i class="bi bi-chevron-right"></i>
      <span>{{ $cours->niveau }} — {{ $cours->titre }}</span>
    </div>

    {{-- Badge niveau --}}
    <div class="niveau-pill">
      <i class="bi bi-award-fill"></i>
      Niveau {{ $cours->niveau }}
    </div>

    <h1 class="hero-titre">{{ $cours->titre }}</h1>
    <p class="hero-sous-titre">{{ $cours->sous_titre }}</p>

    {{-- Chips infos --}}
    <div class="hero-chips">
      <div class="hero-chip">
        <i class="bi bi-book"></i>
        {{ $cours->lecons->count() }} leçons
      </div>
      <div class="hero-chip">
        <i class="bi bi-clock"></i>
        {{ $cours->duree_heures }}h de contenu
      </div>
      <div class="hero-chip">
        <i class="bi bi-translate"></i>
        Français → Allemand
      </div>
      @if($cours->gratuit)
      <div class="hero-chip">
        <i class="bi bi-unlock-fill"></i>
        Accès gratuit
      </div>
      @else
      <div class="hero-chip">
        <i class="bi bi-patch-check-fill"></i>
        Abonnement requis
      </div>
      @endif
    </div>

    {{-- Barre de progression --}}
    @auth
    <div class="hero-prog-wrap">
      <div class="hero-prog-label">
        <span>Votre progression</span>
        <span>{{ $progression }}%</span>
      </div>
      <div class="hero-prog-track">
        <div class="hero-prog-fill" style="width:{{ $progression }}%;"></div>
      </div>
    </div>
    @endauth

  </div>
</div>

{{-- ══ CORPS ═══════════════════════════════════════════════════ --}}
<div class="cours-show-body">
  <div class="row g-4">

    {{-- ── Colonne principale ── --}}
    <div class="col-lg-8">

      {{-- Stats --}}
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-card-num" style="color:{{ $cours->couleur }};">
            {{ $cours->lecons->count() }}
          </div>
          <div class="stat-card-lbl">Leçons</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-num" style="color:#1cc88a;">
            {{ count($leconsTerminees) }}
          </div>
          <div class="stat-card-lbl">Terminées</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-num" style="color:#F5A623;">
            {{ $cours->duree_heures }}h
          </div>
          <div class="stat-card-lbl">Durée totale</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-num" style="color:#1B3A6B;">
            {{ $cours->lecons->sum('points_recompense') }}
          </div>
          <div class="stat-card-lbl">Points possibles</div>
        </div>
      </div>

      {{-- Description --}}
      @if($cours->description)
      <div class="desc-card">
        <div class="desc-card-title">
          <i class="bi bi-info-circle" style="color:#F5A623;font-size:14px;"></i>
          À propos de ce cours
        </div>
        <p class="desc-text">{{ $cours->description }}</p>
      </div>
      @endif

      {{-- Objectifs d'apprentissage --}}
      <div class="desc-card">
        <div class="desc-card-title">
          <i class="bi bi-bullseye" style="color:#F5A623;font-size:14px;"></i>
          Ce que vous allez apprendre
        </div>

        @php
          $objectifs = match($cours->niveau) {
            'A1' => [
              'Se présenter et saluer en allemand',
              'Compter de 1 à 1000',
              'Utiliser les articles der, die, das',
              'Décrire les couleurs et les objets',
              'Poser des questions simples',
              'Comprendre des conversations basiques',
            ],
            'A2' => [
              'Commander au restaurant et dans les commerces',
              'Utiliser les transports en Allemagne',
              'Parler de sa famille et de son travail',
              'Comprendre des textes courts',
              'Exprimer ses goûts et préférences',
              'Donner et comprendre des directions',
            ],
            'B1' => [
              'Utiliser le Präteritum et le Perfekt',
              'Parler de l\'avenir avec le Futur I',
              'Employer les cas : Nominatif, Accusatif, Datif',
              'Rédiger des lettres et emails formels',
              'Participer à des conversations courantes',
              'Comprendre des textes journalistiques simples',
            ],
            'B2' => [
              'Maîtriser tous les temps et modes',
              'Utiliser le Konjunktiv II',
              'Argumenter et débattre en allemand',
              'Comprendre des textes complexes',
              'Rédiger des analyses et rapports',
              'Communiquer de façon spontanée et fluide',
            ],
            default => [
              'Vocabulaire essentiel',
              'Grammaire adaptée au niveau',
              'Exercices pratiques',
              'Dialogues authentiques',
            ],
          };
        @endphp

        @foreach($objectifs as $obj)
        <div class="objectif-line">
          <i class="bi bi-check-circle-fill"></i>
          {{ $obj }}
        </div>
        @endforeach
      </div>

      {{-- ══ Liste des leçons ══ --}}
      <div class="lecons-section-title">
        <i class="bi bi-list-check" style="color:#F5A623;font-size:16px;"></i>
        Programme du cours
        <span>{{ $cours->lecons->count() }} leçons</span>
      </div>

      @forelse($cours->lecons as $idx => $lecon)
      @php
        $estTerminee  = in_array($lecon->id, $leconsTerminees);
        $estAccessible = $lecon->gratuite || auth()->check();
        $estActive     = !$estTerminee && $estAccessible;

        // Définir la couleur du type
        $typeColors = [
          'vocabulaire'   => ['#E1F5EE', '#0f6e56'],
          'grammaire'     => ['#E6F1FB', '#185FA5'],
          'dialogue'      => ['#FAEEDA', '#633806'],
          'exercice'      => ['#EEF4FF', '#534AB7'],
          'culture'       => ['#FBEAF0', '#993556'],
          'prononciation' => ['rgba(226,75,74,.08)', '#a32d2d'],
        ];
        [$typeBg, $typeColor] = $typeColors[$lecon->type] ?? ['#f0f0f0', '#888'];
      @endphp

      @if($estAccessible)
      <a href="{{ route('cours.allemand.lecon', [$cours->slug, $lecon->slug]) }}"
         class="lecon-row {{ $estTerminee ? 'termine' : '' }}">
      @else
      <div class="lecon-row locked">
      @endif

        {{-- Numéro --}}
        <div class="lecon-num {{ $estTerminee ? 'done' : ($idx === 0 ? 'current' : 'pending') }}">
          @if($estTerminee)
            <i class="bi bi-check-lg"></i>
          @else
            {{ $idx + 1 }}
          @endif
        </div>

        {{-- Icône type --}}
        <div class="lecon-type-icon"
             style="background:{{ $typeBg }};color:{{ $typeColor }};">
          <i class="bi {{ $lecon->typeIcon() }}"></i>
        </div>

        {{-- Infos --}}
        <div class="lecon-info">
          <div class="lecon-titre">{{ $lecon->titre }}</div>
          <div class="lecon-meta-row">
            <span class="badge-type">{{ $lecon->typeLabel() }}</span>
            <div class="lecon-meta-item">
              <i class="bi bi-clock"></i>
              {{ $lecon->duree_minutes }} min
            </div>
            @if($lecon->mots && count($lecon->mots))
            <div class="lecon-meta-item">
              <i class="bi bi-alphabet"></i>
              {{ count($lecon->mots) }} mots
            </div>
            @endif
            @if($lecon->exercices && count($lecon->exercices))
            <div class="lecon-meta-item">
              <i class="bi bi-check2-circle"></i>
              {{ count($lecon->exercices) }} exercices
            </div>
            @endif
            <div class="lecon-meta-item">
              <i class="bi bi-star"></i>
              {{ $lecon->points_recompense }} pts
            </div>
            @if($lecon->gratuite)
              <span class="badge-gratuite">Gratuite</span>
            @else
              <span class="badge-premium">Premium</span>
            @endif
          </div>
        </div>

        {{-- Statut --}}
        <div class="lecon-status">
          @if(!$estAccessible)
            <i class="bi bi-lock-fill status-lock"></i>
          @elseif($estTerminee)
            <div class="status-done">
              <i class="bi bi-check-circle-fill"></i>
              Terminée
            </div>
          @else
            <i class="bi bi-chevron-right status-arrow"></i>
          @endif
        </div>

      @if($estAccessible)
      </a>
      @else
      </div>
      @endif

      @empty
      <div style="text-align:center;padding:60px 20px;background:#f8f9fb;
                  border-radius:14px;border:1.5px dashed #ddd;">
        <i class="bi bi-collection"
           style="font-size:36px;color:#ccc;display:block;margin-bottom:12px;"></i>
        <div style="font-size:14px;color:#888;">
          Les leçons de ce cours seront disponibles prochainement.
        </div>
      </div>
      @endforelse

    </div>

    {{-- ── Sidebar ── --}}
    <div class="col-lg-4">

      {{-- Card d'action principale --}}
      <div class="sidebar-card">

        {{-- Progression circulaire (simple) --}}
        <div style="text-align:center;margin-bottom:20px;">
          <div style="position:relative;width:90px;height:90px;margin:0 auto 10px;">
            <svg viewBox="0 0 90 90" width="90" height="90">
              <circle cx="45" cy="45" r="38"
                      fill="none" stroke="#f0f0f0" stroke-width="8"/>
              <circle cx="45" cy="45" r="38"
                      fill="none"
                      stroke="{{ $cours->couleur }}"
                      stroke-width="8"
                      stroke-linecap="round"
                      stroke-dasharray="{{ round(2 * 3.14159 * 38) }}"
                      stroke-dashoffset="{{ round(2 * 3.14159 * 38 * (1 - $progression / 100)) }}"
                      transform="rotate(-90 45 45)"/>
            </svg>
            <div style="position:absolute;inset:0;display:flex;flex-direction:column;
                        align-items:center;justify-content:center;">
              <div style="font-size:1.3rem;font-weight:900;color:#1B3A6B;line-height:1;">
                {{ $progression }}%
              </div>
              <div style="font-size:10px;color:#888;font-weight:600;text-transform:uppercase;
                          letter-spacing:.4px;">
                progression
              </div>
            </div>
          </div>
          <div style="font-size:13px;color:#555;">
            {{ count($leconsTerminees) }} / {{ $cours->lecons->count() }} leçons terminées
          </div>
        </div>

        @guest
        {{-- Visiteur non connecté --}}
        <div class="premium-alert">
          <strong>🔐 Connectez-vous pour commencer</strong>
          Certaines leçons sont gratuites et accessibles sans compte.
        </div>
        <a href="{{ route('auth.register.show') }}" class="btn-commencer"
           style="background:{{ $cours->couleur }};color:#fff;">
          <i class="bi bi-person-plus"></i> Créer un compte gratuit
        </a>
        <a href="{{ route('login') }}" class="btn-commencer-sec">
          <i class="bi bi-box-arrow-in-right"></i> Se connecter
        </a>

        @elseauth

          @php
            // Trouver la première leçon non terminée
            $prochaineLecon = $cours->lecons
              ->first(fn($l) => !in_array($l->id, $leconsTerminees));
            $premiereLecon  = $cours->lecons->first();
          @endphp

          @if($progression === 100)
          {{-- Cours terminé ✅ --}}
          <div style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);
                      border-radius:12px;padding:14px;text-align:center;margin-bottom:14px;">
            <i class="bi bi-patch-check-fill"
               style="font-size:28px;color:#1cc88a;display:block;margin-bottom:6px;"></i>
            <div style="font-size:14px;font-weight:700;color:#0f6e56;margin-bottom:2px;">
              Cours terminé !
            </div>
            <div style="font-size:12px;color:#888;">Félicitations 🎉</div>
          </div>
          <a href="{{ route('cours.allemand.lecon', [$cours->slug, $premiereLecon->slug]) }}"
             class="btn-commencer-sec">
            <i class="bi bi-arrow-repeat"></i> Réviser le cours
          </a>

          @elseif($progression > 0 && $prochaineLecon)
          {{-- En cours --}}
          <a href="{{ route('cours.allemand.lecon', [$cours->slug, $prochaineLecon->slug]) }}"
             class="btn-commencer"
             style="background:{{ $cours->couleur }};color:#fff;">
            <i class="bi bi-play-circle-fill"></i> Continuer — Leçon {{ $prochaineLecon->ordre + 1 }}
          </a>
          <a href="{{ route('cours.allemand.lecon', [$cours->slug, $premiereLecon->slug]) }}"
             class="btn-commencer-sec">
            <i class="bi bi-arrow-counterclockwise"></i> Recommencer depuis le début
          </a>

          @elseif($premiereLecon)
          {{-- Pas encore commencé --}}
          <a href="{{ route('cours.allemand.lecon', [$cours->slug, $premiereLecon->slug]) }}"
             class="btn-commencer"
             style="background:{{ $cours->couleur }};color:#fff;">
            <i class="bi bi-lightning-charge-fill"></i> Commencer ce cours
          </a>

          @endif

          {{-- Lien abonnement si pas abonné --}}
          @php
            $aAbonnement = auth()->user()->abonnementActif()->exists();
          @endphp
          @if(!$aAbonnement && !$cours->gratuit)
          <div style="border-top:1px solid #f5f5f5;margin-top:14px;padding-top:14px;">
            <div style="font-size:12px;color:#888;margin-bottom:10px;text-align:center;">
              Certaines leçons nécessitent un abonnement
            </div>
            <a href="{{ route('abonnement.index') }}"
               style="display:flex;align-items:center;justify-content:center;gap:6px;
                      padding:10px;border-radius:20px;border:1.5px solid #F5A623;
                      color:#633806;font-size:12px;font-weight:700;text-decoration:none;
                      background:rgba(245,166,35,.06);transition:all .2s;">
              <i class="bi bi-unlock-fill"></i> Débloquer tout le cours
            </a>
          </div>
          @endif

        @endguest

      </div>

      {{-- Détails du cours --}}
      <div class="sidebar-card" style="position:static;">
        <div style="font-size:13px;font-weight:700;color:#1B3A6B;
                    margin-bottom:14px;padding-bottom:10px;
                    border-bottom:1px solid #f5f5f5;">
          <i class="bi bi-info-circle me-2" style="color:#F5A623;"></i>
          Détails du cours
        </div>

        @foreach([
          ['bi-translate',       'Langue',         'Français → Allemand'],
          ['bi-bar-chart-steps', 'Niveau CECR',    $cours->niveau],
          ['bi-book',            'Leçons',         $cours->lecons->count().' leçons'],
          ['bi-clock',           'Durée estimée',  $cours->duree_heures.'h de contenu'],
          ['bi-star',            'Points',         $cours->lecons->sum('points_recompense').' points à gagner'],
          ['bi-phone',           'Mobile-Ready',   'Disponible sur app mobile'],
          ['bi-infinity',        'Accès',          $cours->gratuit ? 'Gratuit' : 'Abonnement requis'],
        ] as [$icon, $lbl, $val])
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:8px 0;border-bottom:1px solid #f8f8f8;font-size:13px;">
          <div style="display:flex;align-items:center;gap:8px;color:#888;">
            <i class="bi {{ $icon }}" style="color:#F5A623;width:16px;font-size:13px;"></i>
            {{ $lbl }}
          </div>
          <div style="font-weight:600;color:#1B3A6B;">{{ $val }}</div>
        </div>
        @endforeach

      </div>

      {{-- Autres cours --}}
      @php
        $autresCours = \App\Models\Course::actifs()
          ->where('id', '!=', $cours->id)
          ->withCount('lecons')
          ->limit(3)
          ->get();
      @endphp

      @if($autresCours->count())
      <div class="sidebar-card" style="position:static;">
        <div style="font-size:13px;font-weight:700;color:#1B3A6B;
                    margin-bottom:14px;padding-bottom:10px;
                    border-bottom:1px solid #f5f5f5;">
          <i class="bi bi-grid me-2" style="color:#F5A623;"></i>
          Autres cours disponibles
        </div>
        @foreach($autresCours as $autre)
        <a href="{{ route('cours.allemand.show', $autre->slug) }}"
           style="display:flex;align-items:center;gap:12px;padding:10px 0;
                  border-bottom:1px solid #f8f8f8;text-decoration:none;
                  transition:all .15s;"
           onmouseover="this.style.paddingLeft='6px'"
           onmouseout="this.style.paddingLeft='0'">
          <div style="width:36px;height:36px;border-radius:10px;
                      background:{{ $autre->couleur }};
                      display:flex;align-items:center;justify-content:center;
                      font-size:11px;font-weight:800;color:#fff;flex-shrink:0;">
            {{ $autre->niveau }}
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:13px;font-weight:600;color:#1B3A6B;
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              {{ $autre->titre }}
            </div>
            <div style="font-size:11px;color:#888;margin-top:1px;">
              {{ $autre->lecons_count }} leçons · {{ $autre->duree_heures }}h
            </div>
          </div>
          <i class="bi bi-chevron-right" style="color:#ccc;font-size:12px;flex-shrink:0;"></i>
        </a>
        @endforeach
        <a href="{{ route('cours.allemand.index') }}"
           style="display:block;text-align:center;padding:10px;margin-top:8px;
                  font-size:12px;font-weight:600;color:#1B3A6B;text-decoration:none;">
          Voir tous les cours →
        </a>
      </div>
      @endif

    </div>
  </div>
</div>

@endsection