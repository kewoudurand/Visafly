{{-- resources/views/service-detail.blade.php --}}
@extends('layouts.app')

@section('title', $service['titre'] . ' — Services VisaFly')
@section('meta_description', $service['meta_description'] ?? 'Découvrez notre service '.$service['titre'].' chez VisaFly International.')

@push('styles')
<style>
/* ══ Hero service ══════════════════════════════════════════ */
.svc-hero {
    background: linear-gradient(135deg, #1B3A6B 0%, #0d2247 100%);
    padding: 60px 24px 100px;
    position: relative;
    overflow: hidden;
}
.svc-hero::before {
    content: '';
    position: absolute;
    top: -100px; right: -100px;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: rgba(245,166,35,.07);
}
.svc-hero::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 250px; height: 250px;
    border-radius: 50%;
    background: rgba(255,255,255,.03);
}
.svc-hero-inner {
    max-width: 860px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}
.svc-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: rgba(255,255,255,.55);
    margin-bottom: 24px;
}
.svc-breadcrumb a { color: rgba(255,255,255,.55); text-decoration: none; }
.svc-breadcrumb a:hover { color: #F5A623; }
.svc-breadcrumb i { font-size: 10px; }

.svc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(245,166,35,.15);
    border: 1px solid rgba(245,166,35,.35);
    color: #F5A623;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 16px;
    letter-spacing: .5px;
}
.svc-hero-title {
    font-size: 2.2rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 14px;
    line-height: 1.2;
}
.svc-hero-desc {
    font-size: 15px;
    color: rgba(255,255,255,.72);
    margin-bottom: 30px;
    line-height: 1.8;
    max-width: 680px;
}
.svc-hero-ctas {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.svc-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 24px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s;
}
.svc-btn-primary { background: #F5A623; color: #1B3A6B; }
.svc-btn-primary:hover { background: #e09412; color: #1B3A6B; transform: translateY(-2px); text-decoration: none; }
.svc-btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,.35); }
.svc-btn-outline:hover { border-color: #F5A623; color: #F5A623; text-decoration: none; }

/* ══ Corps ══════════════════════════════════════════════════ */
.svc-body {
    max-width: 1100px;
    margin: -56px auto 80px;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

/* ══ Stats rapides ══════════════════════════════════════════ */
.svc-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 40px;
}
@media(max-width:768px) { .svc-stats { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:420px) { .svc-stats { grid-template-columns: 1fr 1fr; } }
.svc-stat {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 16px;
    padding: 20px 16px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(27,58,107,.07);
}
.svc-stat-num {
    font-size: 1.8rem;
    font-weight: 900;
    color: #1B3A6B;
    line-height: 1;
    margin-bottom: 4px;
}
.svc-stat-lbl {
    font-size: 11px;
    font-weight: 600;
    color: #999;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* ══ Main card ══════════════════════════════════════════════ */
.svc-main-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 20px;
    padding: 36px 40px;
    box-shadow: 0 4px 24px rgba(27,58,107,.07);
    margin-bottom: 24px;
}
@media(max-width:640px) { .svc-main-card { padding: 24px 20px; } }
.svc-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #1B3A6B;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #F5A623;
    display: inline-block;
}

/* ══ Étapes processus ═══════════════════════════════════════ */
.steps-grid { display: flex; flex-direction: column; gap: 0; }
.step-item {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    padding: 20px 0;
    border-bottom: 1px solid #f5f5f5;
}
.step-item:last-child { border-bottom: none; }
.step-num {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #1B3A6B;
    color: #fff;
    font-size: 16px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}
.step-content h4 {
    font-size: 15px;
    font-weight: 700;
    color: #1B3A6B;
    margin-bottom: 4px;
}
.step-content p {
    font-size: 13px;
    color: #666;
    margin: 0;
    line-height: 1.7;
}
.step-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 8px;
    margin-top: 6px;
}

/* ══ Avantages ══════════════════════════════════════════════ */
.avantages-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}
@media(max-width:600px) { .avantages-grid { grid-template-columns: 1fr; } }
.avantage-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    background: rgba(27,58,107,.03);
    border-radius: 14px;
    padding: 16px;
}
.avantage-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: rgba(27,58,107,.08);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #1B3A6B;
    flex-shrink: 0;
}
.avantage-icon.gold { background: rgba(245,166,35,.12); color: #F5A623; }
.avantage-icon.green { background: rgba(28,200,138,.1); color: #1cc88a; }
.avantage-content h5 {
    font-size: 13px;
    font-weight: 700;
    color: #1B3A6B;
    margin-bottom: 4px;
}
.avantage-content p {
    font-size: 12px;
    color: #777;
    margin: 0;
    line-height: 1.6;
}

/* ══ Documents requis ══════════════════════════════════════ */
.docs-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}
@media(max-width:600px) { .docs-list { grid-template-columns: 1fr; } }
.doc-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #f9f9f9;
    border-radius: 10px;
    border: 1px solid #eee;
    font-size: 13px;
    color: #444;
}
.doc-item i { color: #F5A623; font-size: 14px; flex-shrink: 0; }

/* ══ Sidebar ════════════════════════════════════════════════ */
.svc-sidebar-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 4px 20px rgba(27,58,107,.07);
    margin-bottom: 18px;
    position: sticky;
    top: 90px;
}
.sidebar-cta-title {
    font-size: 15px;
    font-weight: 800;
    color: #1B3A6B;
    margin-bottom: 6px;
}
.sidebar-cta-sub {
    font-size: 12px;
    color: #888;
    line-height: 1.6;
    margin-bottom: 18px;
}
.svc-cta-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px;
    border-radius: 22px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s;
    margin-bottom: 10px;
}
.svc-cta-primary { background: #1B3A6B; color: #fff; }
.svc-cta-primary:hover { background: #152d54; transform: translateY(-2px); color: #fff; text-decoration: none; }
.svc-cta-gold { background: #F5A623; color: #1B3A6B; }
.svc-cta-gold:hover { background: #e09412; transform: translateY(-2px); color: #1B3A6B; text-decoration: none; }
.svc-cta-outline { background: transparent; color: #1B3A6B; border: 1.5px solid #ddd; }
.svc-cta-outline:hover { border-color: #1B3A6B; }

/* Délai estimé */
.svc-delai-box {
    background: rgba(28,200,138,.06);
    border: 1px solid rgba(28,200,138,.2);
    border-radius: 12px;
    padding: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}
.svc-delai-icon { font-size: 24px; color: #1cc88a; }
.svc-delai-info strong { display: block; font-size: 13px; color: #0f6e56; font-weight: 700; }
.svc-delai-info span { font-size: 11px; color: #888; }

/* Témoignage rapide */
.svc-temoignage {
    background: rgba(27,58,107,.03);
    border-left: 3px solid #F5A623;
    border-radius: 0 12px 12px 0;
    padding: 14px 16px;
    margin-bottom: 16px;
}
.temo-texte { font-size: 13px; color: #555; font-style: italic; line-height: 1.7; margin-bottom: 8px; }
.temo-auteur { display: flex; align-items: center; gap: 8px; }
.temo-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: #1B3A6B; color: #F5A623;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800;
}
.temo-name { font-size: 12px; font-weight: 700; color: #1B3A6B; }
.temo-dest { font-size: 11px; color: #888; }

/* Services liés */
.svc-related-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f5f5f5;
    text-decoration: none;
    transition: all .15s;
}
.svc-related-item:hover { padding-left: 6px; }
.svc-related-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: rgba(27,58,107,.07);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; color: #1B3A6B; flex-shrink: 0;
}
.svc-related-name { font-size: 13px; font-weight: 600; color: #1B3A6B; }
</style>
@endpush

@section('content')

{{-- ══ HERO ══════════════════════════════════════════════════════ --}}
<div class="svc-hero">
  <div class="svc-hero-inner">

    <div class="svc-breadcrumb">
      <a href="{{ url('/') }}"><i class="bi bi-house-fill"></i> Accueil</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ url('/#services') }}">Nos services</a>
      <i class="bi bi-chevron-right"></i>
      <span>{{ $service['titre'] }}</span>
    </div>

    <div class="svc-badge">
      <i class="bi {{ $service['icon'] }}"></i>
      Service VisaFly
    </div>

    <h1 class="svc-hero-title">{{ $service['titre'] }}</h1>
    <p class="svc-hero-desc">{{ $service['description_longue'] }}</p>

    <div class="svc-hero-ctas">
      <a href="{{ route('consultations.create') }}" class="svc-btn svc-btn-primary">
        <i class="bi bi-calendar-check"></i> Prendre rendez-vous
      </a>
      <a href="{{ url('/#contact') }}" class="svc-btn svc-btn-outline">
        <i class="bi bi-chat-dots"></i> Nous contacter
      </a>
    </div>

  </div>
</div>

{{-- ══ CORPS ═══════════════════════════════════════════════════════ --}}
<div class="svc-body">

  {{-- Stats --}}
  <div class="svc-stats">
    @foreach($service['stats'] as $stat)
    <div class="svc-stat">
      <div class="svc-stat-num">{{ $stat['valeur'] }}</div>
      <div class="svc-stat-lbl">{{ $stat['label'] }}</div>
    </div>
    @endforeach
  </div>

  <div class="row g-4">

    {{-- ── Colonne principale ── --}}
    <div class="col-lg-8">

      {{-- Présentation --}}
      <div class="svc-main-card">
        <div class="svc-card-title">À propos de ce service</div>
        {!! $service['contenu_html'] !!}
      </div>

      {{-- Processus / Étapes --}}
      <div class="svc-main-card">
        <div class="svc-card-title">Comment ça marche ?</div>
        <div class="steps-grid">
          @foreach($service['etapes'] as $i => $etape)
          <div class="step-item">
            <div class="step-num">{{ $i + 1 }}</div>
            <div class="step-content">
              <h4>{{ $etape['titre'] }}</h4>
              <p>{{ $etape['description'] }}</p>
              @if(isset($etape['badge']))
              <span class="step-badge"
                    style="background:{{ $etape['badge_bg'] ?? 'rgba(27,58,107,.08)' }};
                           color:{{ $etape['badge_color'] ?? '#1B3A6B' }};">
                <i class="bi {{ $etape['badge_icon'] ?? 'bi-clock' }}"></i>
                {{ $etape['badge'] }}
              </span>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Avantages --}}
      <div class="svc-main-card">
        <div class="svc-card-title">Pourquoi choisir VisaFly ?</div>
        <div class="avantages-grid">
          @foreach($service['avantages'] as $av)
          <div class="avantage-item">
            <div class="avantage-icon {{ $av['icon_class'] ?? '' }}">
              <i class="bi {{ $av['icon'] }}"></i>
            </div>
            <div class="avantage-content">
              <h5>{{ $av['titre'] }}</h5>
              <p>{{ $av['texte'] }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Documents requis --}}
      @if(isset($service['documents']) && count($service['documents']))
      <div class="svc-main-card">
        <div class="svc-card-title">Documents généralement requis</div>
        <p style="font-size:13px;color:#888;margin-bottom:16px;">
          Cette liste est indicative. Votre conseiller VisaFly vous fournira la liste
          exacte adaptée à votre situation et votre destination.
        </p>
        <div class="docs-list">
          @foreach($service['documents'] as $doc)
          <div class="doc-item">
            <i class="bi bi-file-earmark-check"></i>
            {{ $doc }}
          </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Lien vers le blog visa --}}
      <div style="background:linear-gradient(135deg,#1B3A6B,#0d2247);border-radius:20px;
                  padding:28px 32px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;
                  margin-bottom:24px;">
        <div style="flex:1;min-width:200px;">
          <div style="font-size:11px;font-weight:700;color:rgba(245,166,35,1);letter-spacing:.5px;
                      text-transform:uppercase;margin-bottom:6px;">
            Guide complet
          </div>
          <div style="font-size:18px;font-weight:800;color:#fff;margin-bottom:6px;">
            Comment obtenir votre visa ?
          </div>
          <div style="font-size:13px;color:rgba(255,255,255,.7);">
            Consultez notre guide pays par pays avec toutes les démarches détaillées.
          </div>
        </div>
        <a href="{{ route('blog.visa-guide') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;
                  background:#F5A623;color:#1B3A6B;border-radius:25px;font-size:13px;
                  font-weight:700;text-decoration:none;white-space:nowrap;flex-shrink:0;
                  transition:all .2s;">
          <i class="bi bi-book-fill"></i> Lire le guide
        </a>
      </div>

    </div>

    {{-- ── Sidebar ── --}}
    <div class="col-lg-4">

      {{-- Délai estimé --}}
      <div class="svc-sidebar-card">
        <div class="svc-delai-box">
          <i class="bi bi-clock-history svc-delai-icon"></i>
          <div class="svc-delai-info">
            <strong>Délai moyen : {{ $service['delai'] ?? '4 à 8 semaines' }}</strong>
            <span>selon votre destination et dossier</span>
          </div>
        </div>
        <div class="sidebar-cta-title">Prêt à commencer ?</div>
        <div class="sidebar-cta-sub">
          Notre équipe est disponible du lundi au samedi de 8h à 18h pour répondre à toutes vos questions.
        </div>
        <a href="{{ route('consultations.create') }}" class="svc-cta-btn svc-cta-primary">
          <i class="bi bi-calendar-check"></i> Consultation gratuite
        </a>
        <a href="https://wa.me/237651350338" target="_blank" class="svc-cta-btn svc-cta-gold">
          <i class="bi bi-whatsapp"></i> WhatsApp : +237 651 350 338
        </a>
        <a href="{{ route('abonnement.index') }}" class="svc-cta-btn svc-cta-outline">
          <i class="bi bi-patch-check"></i> Voir nos tarifs
        </a>
      </div>

      {{-- Témoignage --}}
      @if(isset($service['temoignage']))
      <div class="svc-sidebar-card" style="position:static;">
        <div style="font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:14px;">
          <i class="bi bi-chat-quote me-2" style="color:#F5A623;"></i>
          Témoignage client
        </div>
        <div class="svc-temoignage">
          <div class="temo-texte">"{{ $service['temoignage']['texte'] }}"</div>
          <div class="temo-auteur">
            <div class="temo-avatar">
              {{ strtoupper(substr($service['temoignage']['prenom'], 0, 1)) }}
            </div>
            <div>
              <div class="temo-name">{{ $service['temoignage']['prenom'] }} {{ $service['temoignage']['nom_initial'] }}.</div>
              <div class="temo-dest">
                <i class="bi bi-geo-alt-fill" style="color:#F5A623;font-size:10px;"></i>
                {{ $service['temoignage']['destination'] }}
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif

      {{-- Services liés --}}
      <div class="svc-sidebar-card" style="position:static;">
        <div style="font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:14px;
                    padding-bottom:10px;border-bottom:1px solid #f5f5f5;">
          <i class="bi bi-grid me-2" style="color:#F5A623;"></i>
          Autres services
        </div>
        @foreach([
          ['icon' => 'bi-globe',          'nom' => 'Visa & Immigration',     'slug' => 'visa-immigration'],
          ['icon' => 'bi-mortarboard',    'nom' => 'Études à l\'étranger',   'slug' => 'etudes-etranger'],
          ['icon' => 'bi-briefcase',      'nom' => 'Emploi international',   'slug' => 'emploi-international'],
          ['icon' => 'bi-heart-pulse',    'nom' => 'Assurance voyage',       'slug' => 'assurance-voyage'],
          ['icon' => 'bi-airplane',       'nom' => 'Billets d\'avion',       'slug' => 'billets-avion'],
          ['icon' => 'bi-house-door',     'nom' => 'Hébergement',            'slug' => 'hebergement'],
          ['icon' => 'bi-box-seam',       'nom' => 'Import — Export',        'slug' => 'import-export'],
          ['icon' => 'bi-person-walking', 'nom' => 'Accompagnement',         'slug' => 'accompagnement'],
        ] as $svc)
          @if($svc['slug'] !== ($service['slug'] ?? ''))
          <a href="{{ route('services.show', $svc['slug']) }}" class="svc-related-item">
            <div class="svc-related-icon"><i class="bi {{ $svc['icon'] }}"></i></div>
            <div class="svc-related-name">{{ $svc['nom'] }}</div>
            <i class="bi bi-chevron-right ms-auto" style="color:#ccc;font-size:11px;"></i>
          </a>
          @endif
        @endforeach
      </div>

    </div>
  </div>
</div>

@endsection