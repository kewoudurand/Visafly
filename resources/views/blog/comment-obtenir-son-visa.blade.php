{{-- resources/views/blog/comment-obtenir-son-visa.blade.php --}}
@extends('layouts.app')

@section('title', 'Comment obtenir son visa — Guide complet par pays — VisaFly')
@section('meta_description', 'Découvrez comment obtenir un visa pour la France, le Canada, l\'Allemagne, la Belgique, le Portugal et les États-Unis. Guide complet étape par étape par VisaFly International.')

@push('styles')
<style>
/* ══ Hero blog ═══════════════════════════════════════════════ */
.blog-hero {
    background: linear-gradient(135deg, #1B3A6B 0%, #0d2247 100%);
    padding: 64px 24px 110px;
    position: relative;
    overflow: hidden;
    text-align: center;
}
.blog-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: 10%;
    width: 360px; height: 360px;
    border-radius: 50%;
    background: rgba(245,166,35,.06);
}
.blog-hero-inner { max-width: 760px; margin: 0 auto; position: relative; z-index: 1; }
.blog-hero-badge {
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
    margin-bottom: 18px;
    letter-spacing: .5px;
}
.blog-hero-title {
    font-size: 2.4rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 14px;
    line-height: 1.2;
}
@media(max-width:640px) { .blog-hero-title { font-size: 1.8rem; } }
.blog-hero-sub {
    font-size: 15px;
    color: rgba(255,255,255,.7);
    margin-bottom: 30px;
    line-height: 1.8;
}
.blog-hero-chips {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 32px;
}
.blog-hero-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 12px;
    color: rgba(255,255,255,.8);
    font-weight: 500;
}
.blog-breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 12px;
    color: rgba(255,255,255,.5);
    margin-bottom: 20px;
}
.blog-breadcrumb a { color: rgba(255,255,255,.5); text-decoration: none; }
.blog-breadcrumb a:hover { color: #F5A623; }

/* ══ Ancres pays (sticky nav) ══════════════════════════════ */
.pays-nav-wrap {
    background: #fff;
    border-bottom: 2px solid #f0f0f0;
    position: sticky;
    top: 66px;
    z-index: 100;
    box-shadow: 0 2px 12px rgba(27,58,107,.06);
}
.pays-nav {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    gap: 4px;
    overflow-x: auto;
    scrollbar-width: none;
}
.pays-nav::-webkit-scrollbar { display: none; }
.pays-nav-item {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 14px 16px;
    font-size: 13px;
    font-weight: 600;
    color: #888;
    text-decoration: none;
    white-space: nowrap;
    border-bottom: 2.5px solid transparent;
    transition: all .2s;
}
.pays-nav-item:hover { color: #1B3A6B; border-bottom-color: #F5A623; }
.pays-nav-item img { width: 20px; height: 14px; object-fit: cover; border-radius: 3px; }

/* ══ Corps principal ════════════════════════════════════════ */
.blog-body {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px 80px;
}

/* ══ Cards pays ═════════════════════════════════════════════ */
.pays-section {
    margin-top: -40px;
    padding-top: 80px;
}
.pays-card {
    background: #fff;
    border-radius: 24px;
    border: 1.5px solid #eee;
    overflow: hidden;
    box-shadow: 0 6px 30px rgba(27,58,107,.07);
    margin-bottom: 36px;
}
.pays-card-header {
    padding: 28px 36px 24px;
    display: flex;
    align-items: center;
    gap: 18px;
    border-bottom: 1.5px solid #f5f5f5;
}
@media(max-width:640px) { .pays-card-header { padding: 20px; flex-wrap: wrap; } }
.pays-flag-wrap {
    position: relative;
    flex-shrink: 0;
}
.pays-flag-img {
    width: 72px;
    height: 48px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,.15);
}
.pays-card-info { flex: 1; }
.pays-name {
    font-size: 1.6rem;
    font-weight: 900;
    color: #1B3A6B;
    margin-bottom: 4px;
}
.pays-tagline { font-size: 13px; color: #888; margin-bottom: 10px; }
.pays-chips { display: flex; gap: 8px; flex-wrap: wrap; }
.pays-chip {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    background: rgba(27,58,107,.07);
    color: #1B3A6B;
}
.pays-chip.chip-green { background: rgba(28,200,138,.1); color: #0f6e56; }
.pays-chip.chip-orange { background: rgba(245,166,35,.1); color: #633806; }
.pays-chip.chip-red { background: rgba(226,75,74,.1); color: #a32d2d; }

.pays-card-body { padding: 28px 36px; }
@media(max-width:640px) { .pays-card-body { padding: 20px; } }

/* ══ Onglets types visa ═════════════════════════════════════ */
.visa-types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 28px;
}
.visa-type-item {
    background: rgba(27,58,107,.03);
    border: 1px solid #eee;
    border-radius: 14px;
    padding: 16px;
    text-decoration: none;
    transition: all .2s;
}
.visa-type-item:hover {
    border-color: #1B3A6B;
    background: rgba(27,58,107,.06);
    transform: translateY(-2px);
}
.visa-type-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: #1B3A6B;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; color: #F5A623;
    margin-bottom: 10px;
}
.visa-type-name { font-size: 13px; font-weight: 700; color: #1B3A6B; margin-bottom: 3px; }
.visa-type-desc { font-size: 11px; color: #888; line-height: 1.5; }

/* ══ Étapes accordéon ═══════════════════════════════════════ */
.etapes-title {
    font-size: 15px;
    font-weight: 800;
    color: #1B3A6B;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.etapes-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f0f0f0;
}
.etape-row {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    padding: 14px 0;
    border-bottom: 1px solid #f8f8f8;
}
.etape-row:last-child { border-bottom: none; }
.etape-num-badge {
    width: 32px; height: 32px; border-radius: 50%;
    background: #1B3A6B; color: #fff;
    font-size: 13px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.etape-text h5 { font-size: 13px; font-weight: 700; color: #1B3A6B; margin-bottom: 3px; }
.etape-text p { font-size: 12px; color: #666; margin: 0; line-height: 1.7; }

/* Délai pill */
.delai-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 8px;
    background: rgba(28,200,138,.1);
    color: #0f6e56;
    margin-top: 4px;
}

/* ══ Docs requis ════════════════════════════════════════════ */
.docs-section {
    background: rgba(27,58,107,.03);
    border-radius: 14px;
    padding: 18px;
    margin-top: 20px;
}
.docs-section-title {
    font-size: 13px;
    font-weight: 700;
    color: #1B3A6B;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.docs-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}
@media(max-width:600px) { .docs-grid { grid-template-columns: 1fr; } }
.doc-pill {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: #444;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 7px 12px;
}
.doc-pill i { color: #F5A623; font-size: 13px; flex-shrink: 0; }

/* ══ Alerte VisaFly ═════════════════════════════════════════ */
.visafly-aide {
    background: linear-gradient(135deg, rgba(27,58,107,.06), rgba(245,166,35,.05));
    border: 1px solid rgba(27,58,107,.12);
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    margin-top: 20px;
    flex-wrap: wrap;
}
.visafly-aide-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: #1B3A6B;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #F5A623; flex-shrink: 0;
}
.visafly-aide-text { flex: 1; min-width: 160px; }
.visafly-aide-text strong { display: block; font-size: 13px; color: #1B3A6B; margin-bottom: 2px; }
.visafly-aide-text span { font-size: 12px; color: #777; }
.visafly-aide-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    background: #1B3A6B;
    color: #fff;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    transition: all .2s;
    flex-shrink: 0;
}
.visafly-aide-btn:hover { background: #152d54; color: #fff; text-decoration: none; }

/* ══ CTA final ══════════════════════════════════════════════ */
.cta-final {
    background: linear-gradient(135deg, #1B3A6B, #0d2247);
    border-radius: 24px;
    padding: 48px 40px;
    text-align: center;
    margin-top: 48px;
    position: relative;
    overflow: hidden;
}
.cta-final::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(245,166,35,.08);
}
.cta-final-title {
    font-size: 1.8rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 12px;
    position: relative;
}
.cta-final-sub { font-size: 14px; color: rgba(255,255,255,.7); margin-bottom: 28px; position: relative; }
.cta-final-btns { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; position: relative; }
.cta-btn-w { display: inline-flex; align-items: center; gap: 8px; padding: 13px 26px; border-radius: 25px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all .2s; }
.cta-btn-gold { background: #F5A623; color: #1B3A6B; }
.cta-btn-gold:hover { background: #e09412; color: #1B3A6B; transform: translateY(-2px); text-decoration: none; }
.cta-btn-out { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,.4); }
.cta-btn-out:hover { border-color: #F5A623; color: #F5A623; text-decoration: none; }
</style>
@endpush

@section('content')

{{-- ══ HERO ══════════════════════════════════════════════════════ --}}
<div class="blog-hero">
  <div class="blog-hero-inner">
    <div class="blog-breadcrumb">
      <a href="{{ url('/') }}"><i class="bi bi-house-fill"></i> Accueil</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ url('/#services') }}">Services</a>
      <i class="bi bi-chevron-right"></i>
      <span>Guide Visa</span>
    </div>
    <div class="blog-hero-badge">
      <i class="bi bi-book-fill"></i>
      Guide complet 2025
    </div>
    <h1 class="blog-hero-title">
      Comment obtenir<br>son visa étape par étape
    </h1>
    <p class="blog-hero-sub">
      Tout ce qu'il faut savoir pour préparer votre dossier, choisir le bon type de visa
      et maximiser vos chances d'obtention — par pays et par profil.
    </p>
    <div class="blog-hero-chips">
      <div class="blog-hero-chip"><i class="bi bi-globe"></i> 6 pays couverts</div>
      <div class="blog-hero-chip"><i class="bi bi-file-earmark-check"></i> Documents listés</div>
      <div class="blog-hero-chip"><i class="bi bi-clock-history"></i> Délais indiqués</div>
      <div class="blog-hero-chip"><i class="bi bi-patch-check"></i> Mis à jour 2025</div>
    </div>
    <a href="{{ route('consultations.create') }}"
       style="display:inline-flex;align-items:center;gap:8px;padding:13px 28px;
              background:#F5A623;color:#1B3A6B;border-radius:25px;font-size:14px;
              font-weight:700;text-decoration:none;transition:all .2s;"
       onmouseover="this.style.background='#e09412'"
       onmouseout="this.style.background='#F5A623'">
      <i class="bi bi-calendar-check"></i>
      Faire accompagner mon dossier par VisaFly
    </a>
  </div>
</div>

{{-- ══ NAVIGATION PAYS (sticky) ══════════════════════════════════ --}}
<div class="pays-nav-wrap">
  <div class="pays-nav">
    @foreach([
      ['slug' => 'france',     'nom' => 'France',     'flag' => 'fr'],
      ['slug' => 'canada',     'nom' => 'Canada',     'flag' => 'ca'],
      ['slug' => 'allemagne',  'nom' => 'Allemagne',  'flag' => 'de'],
      ['slug' => 'belgique',   'nom' => 'Belgique',   'flag' => 'be'],
      ['slug' => 'portugal',   'nom' => 'Portugal',   'flag' => 'pt'],
      ['slug' => 'usa',        'nom' => 'États-Unis', 'flag' => 'us'],
    ] as $pays)
    <a href="#pays-{{ $pays['slug'] }}" class="pays-nav-item">
      <img src="https://flagcdn.com/w40/{{ $pays['flag'] }}.png" alt="{{ $pays['nom'] }}">
      {{ $pays['nom'] }}
    </a>
    @endforeach
  </div>
</div>

{{-- ══ CORPS ═══════════════════════════════════════════════════════ --}}
<div class="blog-body">

{{-- ════════════════════════════════════
     FRANCE
════════════════════════════════════ --}}
<div class="pays-section" id="pays-france">

  <div class="pays-card" data-aos="fade-up">
    <div class="pays-card-header">
      <div class="pays-flag-wrap">
        <img src="https://flagcdn.com/w80/fr.png" alt="France" class="pays-flag-img">
      </div>
      <div class="pays-card-info">
        <div class="pays-name">🇫🇷 France</div>
        <div class="pays-tagline">Visa Schengen · Long séjour · Études · Travail</div>
        <div class="pays-chips">
          <div class="pays-chip"><i class="bi bi-clock"></i> Délai : 3–8 semaines</div>
          <div class="pays-chip chip-orange"><i class="bi bi-cash"></i> Frais : 80–99 €</div>
          <div class="pays-chip chip-green"><i class="bi bi-check"></i> Ambassade à Yaoundé</div>
        </div>
      </div>
    </div>

    <div class="pays-card-body">

      {{-- Types de visa --}}
      <div class="etapes-title"><i class="bi bi-card-list" style="color:#F5A623;"></i> Types de visa disponibles</div>
      <div class="visa-types-grid">
        @foreach([
          ['icon' => 'bi-person-walking', 'nom' => 'Visa touristique (C)', 'desc' => 'Court séjour 90j max, tourisme et visite familiale'],
          ['icon' => 'bi-mortarboard',    'nom' => 'Visa étudiant (D)',    'desc' => 'Études supérieures, programme Campus France obligatoire'],
          ['icon' => 'bi-briefcase',      'nom' => 'Visa travail (D)',     'desc' => 'Contrat de travail, autorisation de travail requise'],
          ['icon' => 'bi-people',         'nom' => 'Regroupement familial','desc' => 'Rejoindre un conjoint ou parent résidant en France'],
        ] as $type)
        <div class="visa-type-item">
          <div class="visa-type-icon"><i class="bi {{ $type['icon'] }}"></i></div>
          <div class="visa-type-name">{{ $type['nom'] }}</div>
          <div class="visa-type-desc">{{ $type['desc'] }}</div>
        </div>
        @endforeach
      </div>

      {{-- Étapes --}}
      <div class="etapes-title"><i class="bi bi-list-ol" style="color:#F5A623;"></i> Étapes pour obtenir votre visa France</div>
      @foreach([
        ['num' => 1, 'titre' => 'Créer un compte Campus France (si visa études)', 'desc' => 'Inscrivez-vous sur la plateforme Campus France Cameroun et remplissez votre dossier académique avant tout rendez-vous consulaire.', 'delai' => '2–4 semaines'],
        ['num' => 2, 'titre' => 'Constituer le dossier complet', 'desc' => 'Rassemblez tous les documents requis : passeport valide, photos, justificatifs financiers, hébergement, assurance voyage.', 'delai' => '1 semaine'],
        ['num' => 3, 'titre' => 'Prendre rendez-vous au consulat ou VFS Global', 'desc' => 'Réservez votre rendez-vous biométrique sur le site VFS Global ou directement au Consulat de France à Yaoundé.', 'delai' => '1–3 semaines'],
        ['num' => 4, 'titre' => 'Déposer le dossier et les données biométriques', 'desc' => 'Présentez-vous à l\'heure avec votre dossier complet. Les empreintes digitales et photo sont prises sur place.', 'delai' => 'Jour J'],
        ['num' => 5, 'titre' => 'Suivi et réponse du consulat', 'desc' => 'Vous recevez une réponse sous 3 à 15 jours ouvrés. Le passeport vous est restitué avec ou sans visa.', 'delai' => '3–15 jours'],
      ] as $e)
      <div class="etape-row">
        <div class="etape-num-badge">{{ $e['num'] }}</div>
        <div class="etape-text">
          <h5>{{ $e['titre'] }}</h5>
          <p>{{ $e['desc'] }}</p>
          @if(isset($e['delai']))
          <span class="delai-pill"><i class="bi bi-clock"></i> {{ $e['delai'] }}</span>
          @endif
        </div>
      </div>
      @endforeach

      {{-- Documents requis --}}
      <div class="docs-section">
        <div class="docs-section-title">
          <i class="bi bi-folder2-open" style="color:#F5A623;"></i>
          Documents généralement requis
        </div>
        <div class="docs-grid">
          @foreach([
            'Passeport valide 3 mois après la date de retour',
            '2 photos d\'identité récentes (35×45 mm)',
            'Formulaire de demande Schengen signé',
            'Assurance voyage (min. 30 000 €)',
            'Justificatif d\'hébergement (hôtel/invitation)',
            'Relevés bancaires 3 derniers mois',
            'Justificatif de revenus ou bourse',
            'Lettre de motivation (visa études/travail)',
            'Attestation de prise en charge (si invité)',
            'Lettre d\'admission université (si études)',
          ] as $doc)
          <div class="doc-pill"><i class="bi bi-check-circle-fill"></i> {{ $doc }}</div>
          @endforeach
        </div>
      </div>

      <div class="visafly-aide">
        <div class="visafly-aide-icon"><i class="bi bi-headset"></i></div>
        <div class="visafly-aide-text">
          <strong>VisaFly prépare votre dossier France</strong>
          <span>Taux de succès 94% — Accompagnement de A à Z — Délai optimisé</span>
        </div>
        <a href="{{ route('consultations.create') }}" class="visafly-aide-btn">
          <i class="bi bi-arrow-right"></i> Je me fais accompagner
        </a>
      </div>

    </div>
  </div>
</div>

{{-- ════════════════════════════════════
     CANADA
════════════════════════════════════ --}}
<div class="pays-section" id="pays-canada">
  <div class="pays-card" data-aos="fade-up">
    <div class="pays-card-header">
      <img src="https://flagcdn.com/w80/ca.png" alt="Canada" class="pays-flag-img">
      <div class="pays-card-info">
        <div class="pays-name">🇨🇦 Canada</div>
        <div class="pays-tagline">AVE · Permis d'études · Permis de travail · Résidence permanente</div>
        <div class="pays-chips">
          <div class="pays-chip"><i class="bi bi-clock"></i> Délai : 4–16 semaines</div>
          <div class="pays-chip chip-orange"><i class="bi bi-cash"></i> Frais : 100–185 CAD</div>
          <div class="pays-chip chip-green"><i class="bi bi-translate"></i> TCF/TEF requis</div>
        </div>
      </div>
    </div>
    <div class="pays-card-body">
      <div class="etapes-title"><i class="bi bi-card-list" style="color:#F5A623;"></i> Types de visa / permis</div>
      <div class="visa-types-grid">
        @foreach([
          ['icon' => 'bi-mortarboard',    'nom' => 'Permis d\'études',         'desc' => 'Lettre d\'acceptation d\'un établissement canadien désigné requise'],
          ['icon' => 'bi-briefcase',      'nom' => 'Permis de travail',         'desc' => 'EIMT requis (employeur canadien), ou programme mobilité'],
          ['icon' => 'bi-house-heart',    'nom' => 'Résidence permanente',      'desc' => 'Via Entrée Express, PEQ ou parrainage familial'],
          ['icon' => 'bi-airplane',       'nom' => 'Visa visiteur',            'desc' => 'Tourisme, affaires, visite familiale — 6 mois max'],
        ] as $type)
        <div class="visa-type-item">
          <div class="visa-type-icon"><i class="bi {{ $type['icon'] }}"></i></div>
          <div class="visa-type-name">{{ $type['nom'] }}</div>
          <div class="visa-type-desc">{{ $type['desc'] }}</div>
        </div>
        @endforeach
      </div>

      <div class="etapes-title"><i class="bi bi-list-ol" style="color:#F5A623;"></i> Étapes pour le Canada</div>
      @foreach([
        ['titre' => 'Passer le TCF Canada ou IELTS', 'desc' => 'Le Canada exige une preuve de compétence linguistique. TCF pour le français, IELTS pour l\'anglais. Score minimum selon le programme visé.', 'delai' => '3–6 semaines pour résultats'],
        ['titre' => 'Créer un profil IRCC (Immigration Canada)', 'desc' => 'Créez votre compte sur le portail d\'Immigration, Réfugiés et Citoyenneté Canada. Soumettez votre profil Entrée Express si applicable.', 'delai' => '1–2 jours'],
        ['titre' => 'Obtenir une lettre d\'acceptation (études)', 'desc' => 'Pour un permis d\'études, obtenez une lettre d\'un Établissement d\'Enseignement Désigné (EED). VisaFly vous accompagne dans les démarches universitaires.', 'delai' => '2–8 semaines'],
        ['titre' => 'Constituer et soumettre le dossier en ligne', 'desc' => 'Tout se fait sur le portail IRCC. Scans de qualité requis. Données biométriques dans un Centre de Collecte (Yaoundé ou Douala).', 'delai' => '2–3 jours'],
        ['titre' => 'Attendre la décision et préparer l\'arrivée', 'desc' => 'Délai variable selon le programme : 4 semaines (visiteur) à 16 semaines (résidence permanente). Planifiez votre arrivée avec VisaFly.', 'delai' => '4–16 semaines'],
      ] as $i => $e)
      <div class="etape-row">
        <div class="etape-num-badge">{{ $i + 1 }}</div>
        <div class="etape-text">
          <h5>{{ $e['titre'] }}</h5>
          <p>{{ $e['desc'] }}</p>
          <span class="delai-pill"><i class="bi bi-clock"></i> {{ $e['delai'] }}</span>
        </div>
      </div>
      @endforeach

      <div class="docs-section">
        <div class="docs-section-title">
          <i class="bi bi-folder2-open" style="color:#F5A623;"></i> Documents requis
        </div>
        <div class="docs-grid">
          @foreach([
            'Passeport valide',
            'Photo numérique format IRCC',
            'Résultats TCF Canada / IELTS',
            'Lettre d\'acceptation EED (études)',
            'Relevés bancaires (min. 10 000 CAD)',
            'Preuve de liens avec le Cameroun',
            'Casier judiciaire vierge',
            'Formulaire IMM rempli en ligne',
            'Frais de traitement payés',
            'Données biométriques (si requis)',
          ] as $doc)
          <div class="doc-pill"><i class="bi bi-check-circle-fill"></i> {{ $doc }}</div>
          @endforeach
        </div>
      </div>

      <div class="visafly-aide">
        <div class="visafly-aide-icon"><i class="bi bi-headset"></i></div>
        <div class="visafly-aide-text">
          <strong>Préparez votre TCF Canada avec VisaFly</strong>
          <span>Tests d'entraînement TCF, TEF, IELTS disponibles sur la plateforme</span>
        </div>
        <a href="{{ route('langues.series', 'tcf') }}" class="visafly-aide-btn">
          <i class="bi bi-pencil-square"></i> S'entraîner maintenant
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ════════════════════════════════════
     ALLEMAGNE
════════════════════════════════════ --}}
<div class="pays-section" id="pays-allemagne">
  <div class="pays-card" data-aos="fade-up">
    <div class="pays-card-header">
      <img src="https://flagcdn.com/w80/de.png" alt="Allemagne" class="pays-flag-img">
      <div class="pays-card-info">
        <div class="pays-name">🇩🇪 Allemagne</div>
        <div class="pays-tagline">Visa Schengen · Visa national · Chercheur d'emploi · Études</div>
        <div class="pays-chips">
          <div class="pays-chip"><i class="bi bi-clock"></i> Délai : 4–12 semaines</div>
          <div class="pays-chip chip-orange"><i class="bi bi-cash"></i> Frais : 75–80 €</div>
          <div class="pays-chip chip-green"><i class="bi bi-book"></i> Allemand requis (A2-B1)</div>
        </div>
      </div>
    </div>
    <div class="pays-card-body">
      <div class="etapes-title"><i class="bi bi-card-list" style="color:#F5A623;"></i> Types de visa</div>
      <div class="visa-types-grid">
        @foreach([
          ['icon' => 'bi-mortarboard',   'nom' => 'Visa étudiant',         'desc' => 'Lettre d\'admission, preuves financières 8 640 €/an sur compte bloqué'],
          ['icon' => 'bi-briefcase',     'nom' => 'Visa emploi qualifié',  'desc' => 'Contrat ou offre d\'emploi, diplôme reconnu par l\'Allemagne'],
          ['icon' => 'bi-search',        'nom' => 'Visa chercheur emploi', 'desc' => 'Valable 6 mois, diplôme qualifié requis, chercher un emploi sur place'],
          ['icon' => 'bi-award',         'nom' => 'Visa formation/apprentissage', 'desc' => 'Ausbildung — contrat de formation professionnelle avec entreprise allemande'],
        ] as $type)
        <div class="visa-type-item">
          <div class="visa-type-icon"><i class="bi {{ $type['icon'] }}"></i></div>
          <div class="visa-type-name">{{ $type['nom'] }}</div>
          <div class="visa-type-desc">{{ $type['desc'] }}</div>
        </div>
        @endforeach
      </div>

      <div class="etapes-title"><i class="bi bi-list-ol" style="color:#F5A623;"></i> Étapes</div>
      @foreach([
        ['titre' => 'Apprendre l\'allemand (niveau A2 minimum)', 'desc' => 'La plupart des visas allemands exigent une preuve de niveau en langue. Préparez-vous avec nos cours d\'allemand VisaFly (A1 à B2).', 'delai' => '3–6 mois de préparation'],
        ['titre' => 'Faire reconnaître ses diplômes (anabin)', 'desc' => 'Vérifiez si votre diplôme camerounais est reconnu en Allemagne via la base de données anabin. VisaFly vous aide dans cette démarche.', 'delai' => '2–6 semaines'],
        ['titre' => 'Prendre rendez-vous à l\'ambassade d\'Allemagne', 'desc' => 'L\'ambassade d\'Allemagne à Yaoundé traite les demandes de visa national (D). Les rendez-vous sont souvent pris plusieurs semaines à l\'avance.', 'delai' => '2–8 semaines d\'attente'],
        ['titre' => 'Déposer le dossier complet', 'desc' => 'Dossier complet, traduction certifiée des documents, frais consulaires. Empreintes digitales collectées sur place.', 'delai' => 'Jour J'],
        ['titre' => 'Attendre la décision', 'desc' => 'Délai de traitement de 4 à 12 semaines selon le type de visa. En cas d\'acceptation, le visa est collé sur le passeport.', 'delai' => '4–12 semaines'],
      ] as $i => $e)
      <div class="etape-row">
        <div class="etape-num-badge">{{ $i + 1 }}</div>
        <div class="etape-text">
          <h5>{{ $e['titre'] }}</h5>
          <p>{{ $e['desc'] }}</p>
          <span class="delai-pill"><i class="bi bi-clock"></i> {{ $e['delai'] }}</span>
        </div>
      </div>
      @endforeach

      <div class="docs-section">
        <div class="docs-section-title"><i class="bi bi-folder2-open" style="color:#F5A623;"></i> Documents requis</div>
        <div class="docs-grid">
          @foreach([
            'Passeport valide 6 mois après expiration visa',
            'Photos biométriques',
            'Formulaire national de demande signé',
            'Attestation de niveau en allemand',
            'Preuve de ressources financières',
            'Lettre d\'admission ou contrat',
            'Casier judiciaire traduit et légalisé',
            'Assurance maladie valable en Allemagne',
            'Reconnaissance de diplôme (si emploi)',
            'CV et lettre de motivation',
          ] as $doc)
          <div class="doc-pill"><i class="bi bi-check-circle-fill"></i> {{ $doc }}</div>
          @endforeach
        </div>
      </div>

      <div class="visafly-aide">
        <div class="visafly-aide-icon"><i class="bi bi-book"></i></div>
        <div class="visafly-aide-text">
          <strong>Cours d'allemand A1 → B2 sur VisaFly</strong>
          <span>Préparez votre niveau linguistique requis pour le visa allemand</span>
        </div>
        <a href="{{ route('cours.allemand.index') }}" class="visafly-aide-btn">
          <i class="bi bi-arrow-right"></i> Voir les cours
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ════════════════════════════════════
     BELGIQUE
════════════════════════════════════ --}}
<div class="pays-section" id="pays-belgique">
  <div class="pays-card" data-aos="fade-up">
    <div class="pays-card-header">
      <img src="https://flagcdn.com/w80/be.png" alt="Belgique" class="pays-flag-img">
      <div class="pays-card-info">
        <div class="pays-name">🇧🇪 Belgique</div>
        <div class="pays-tagline">Visa Schengen · Long séjour · Regroupement familial</div>
        <div class="pays-chips">
          <div class="pays-chip"><i class="bi bi-clock"></i> Délai : 3–12 semaines</div>
          <div class="pays-chip chip-orange"><i class="bi bi-cash"></i> Frais : 80–180 €</div>
          <div class="pays-chip chip-red"><i class="bi bi-exclamation-triangle"></i> Pas d'ambassade à YDE</div>
        </div>
      </div>
    </div>
    <div class="pays-card-body">
      <div style="background:rgba(226,75,74,.06);border:1px solid rgba(226,75,74,.2);border-radius:12px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#a32d2d;">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Important :</strong> La Belgique n'a pas d'ambassade propre au Cameroun. Les demandes de visa sont traitées par le Consulat de France à Yaoundé (pour les visas Schengen courts) ou l'ambassade de Belgique à Abidjan / Nairobi pour les longs séjours.
      </div>

      <div class="etapes-title"><i class="bi bi-list-ol" style="color:#F5A623;"></i> Étapes</div>
      @foreach([
        ['titre' => 'Identifier le type de visa nécessaire', 'desc' => 'Court séjour (C) via France, long séjour (D) via l\'ambassade belge de la région. VisaFly vous aide à choisir la bonne procédure.'],
        ['titre' => 'Contacter le bon poste consulaire', 'desc' => 'Pour un visa court séjour Schengen : Consulat de France à Yaoundé. Pour un visa national long séjour : ambassade belge à Abidjan ou Nairobi.'],
        ['titre' => 'Constituer le dossier', 'desc' => 'Passeport, photos, formulaire Schengen, justificatifs financiers, hébergement, assurance voyage Schengen et lettre de motivation.'],
        ['titre' => 'Déposer le dossier et payer les frais', 'desc' => 'Dépôt en personne au consulat avec prise des empreintes. Paiement des frais consulaires (80 € court, 180 € long séjour).'],
      ] as $i => $e)
      <div class="etape-row">
        <div class="etape-num-badge">{{ $i + 1 }}</div>
        <div class="etape-text">
          <h5>{{ $e['titre'] }}</h5>
          <p>{{ $e['desc'] }}</p>
        </div>
      </div>
      @endforeach

      <div class="visafly-aide">
        <div class="visafly-aide-icon"><i class="bi bi-headset"></i></div>
        <div class="visafly-aide-text">
          <strong>VisaFly gère les démarches Belgique pour vous</strong>
          <span>Identification du bon consulat, constitution du dossier, suivi</span>
        </div>
        <a href="{{ route('consultations.create') }}" class="visafly-aide-btn">
          <i class="bi bi-calendar-check"></i> Consultation gratuite
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ════════════════════════════════════
     PORTUGAL
════════════════════════════════════ --}}
<div class="pays-section" id="pays-portugal">
  <div class="pays-card" data-aos="fade-up">
    <div class="pays-card-header">
      <img src="https://flagcdn.com/w80/pt.png" alt="Portugal" class="pays-flag-img">
      <div class="pays-card-info">
        <div class="pays-name">🇵🇹 Portugal</div>
        <div class="pays-tagline">Visa Schengen · Visa D7 Passif · Golden Visa · Études</div>
        <div class="pays-chips">
          <div class="pays-chip"><i class="bi bi-clock"></i> Délai : 3–10 semaines</div>
          <div class="pays-chip chip-orange"><i class="bi bi-cash"></i> Frais : 80–150 €</div>
          <div class="pays-chip chip-green"><i class="bi bi-emoji-smile"></i> Procédure accessible</div>
        </div>
      </div>
    </div>
    <div class="pays-card-body">
      <div class="etapes-title"><i class="bi bi-card-list" style="color:#F5A623;"></i> Types de visa</div>
      <div class="visa-types-grid">
        @foreach([
          ['icon' => 'bi-sun',        'nom' => 'Visa D7 (revenu passif)', 'desc' => 'Pour personnes avec revenus stables : pension, loyers, freelance'],
          ['icon' => 'bi-mortarboard','nom' => 'Visa étudiant',           'desc' => 'Lettre d\'admission d\'un établissement portugais reconnu'],
          ['icon' => 'bi-briefcase',  'nom' => 'Visa emploi',             'desc' => 'Contrat de travail signé avec employeur au Portugal'],
          ['icon' => 'bi-geo-alt',    'nom' => 'Visa visiteur',           'desc' => 'Court séjour Schengen, jusqu\'à 90 jours'],
        ] as $type)
        <div class="visa-type-item">
          <div class="visa-type-icon"><i class="bi {{ $type['icon'] }}"></i></div>
          <div class="visa-type-name">{{ $type['nom'] }}</div>
          <div class="visa-type-desc">{{ $type['desc'] }}</div>
        </div>
        @endforeach
      </div>

      <div class="etapes-title"><i class="bi bi-list-ol" style="color:#F5A623;"></i> Étapes</div>
      @foreach([
        ['titre' => 'Choisir le bon type de visa', 'desc' => 'Le Portugal propose plusieurs visas adaptés à votre situation. VisaFly évalue votre profil et vous oriente vers la procédure la plus adaptée.'],
        ['titre' => 'Constituer le dossier', 'desc' => 'Passeport, photos, formulaire national, justificatifs financiers, assurance santé, hébergement, casier judiciaire.'],
        ['titre' => 'Prise de rendez-vous consulaire', 'desc' => 'Ambassade du Portugal à Yaoundé ou via VFS Global. Les délais d\'attente varient de 1 à 4 semaines.'],
        ['titre' => 'Dépôt et collecte biométrique', 'desc' => 'Présentation en personne avec dossier complet. Frais consulaires réglés le jour du dépôt.'],
        ['titre' => 'Réponse et préparation à l\'installation', 'desc' => 'Si le visa est accordé, VisaFly vous accompagne pour trouver un logement et préparer votre installation à Lisbonne ou Porto.'],
      ] as $i => $e)
      <div class="etape-row">
        <div class="etape-num-badge">{{ $i + 1 }}</div>
        <div class="etape-text">
          <h5>{{ $e['titre'] }}</h5>
          <p>{{ $e['desc'] }}</p>
        </div>
      </div>
      @endforeach

      <div class="visafly-aide">
        <div class="visafly-aide-icon"><i class="bi bi-house-heart"></i></div>
        <div class="visafly-aide-text">
          <strong>VisaFly accompagne jusqu'à votre installation</strong>
          <span>Logement, intégration, ouverture de compte bancaire portugais</span>
        </div>
        <a href="{{ route('consultations.create') }}" class="visafly-aide-btn">
          <i class="bi bi-arrow-right"></i> Démarrer mon projet Portugal
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ════════════════════════════════════
     ÉTATS-UNIS
════════════════════════════════════ --}}
<div class="pays-section" id="pays-usa">
  <div class="pays-card" data-aos="fade-up">
    <div class="pays-card-header">
      <img src="https://flagcdn.com/w80/us.png" alt="États-Unis" class="pays-flag-img">
      <div class="pays-card-info">
        <div class="pays-name">🇺🇸 États-Unis</div>
        <div class="pays-tagline">Visa B1/B2 · F1 Études · H1B Travail · J1 Échange</div>
        <div class="pays-chips">
          <div class="pays-chip"><i class="bi bi-clock"></i> Délai : 6–20 semaines</div>
          <div class="pays-chip chip-orange"><i class="bi bi-cash"></i> Frais : 160–190 USD</div>
          <div class="pays-chip chip-red"><i class="bi bi-exclamation-triangle"></i> Entretien obligatoire</div>
        </div>
      </div>
    </div>
    <div class="pays-card-body">
      <div class="etapes-title"><i class="bi bi-card-list" style="color:#F5A623;"></i> Types de visa</div>
      <div class="visa-types-grid">
        @foreach([
          ['icon' => 'bi-airplane',     'nom' => 'Visa B1/B2',      'desc' => 'Tourisme, affaires, visites médicales — 6 mois renouvelables'],
          ['icon' => 'bi-mortarboard',  'nom' => 'Visa F1 (études)', 'desc' => 'Admission I-20 d\'un établissement SEVP requis'],
          ['icon' => 'bi-arrow-left-right','nom' => 'Visa J1 (échange)','desc' => 'Programme d\'échange culturel, stage, recherche'],
          ['icon' => 'bi-briefcase',    'nom' => 'Visa H1B (travail)', 'desc' => 'Sponsorisé par un employeur américain, quota annuel'],
        ] as $type)
        <div class="visa-type-item">
          <div class="visa-type-icon"><i class="bi {{ $type['icon'] }}"></i></div>
          <div class="visa-type-name">{{ $type['nom'] }}</div>
          <div class="visa-type-desc">{{ $type['desc'] }}</div>
        </div>
        @endforeach
      </div>

      <div class="etapes-title"><i class="bi bi-list-ol" style="color:#F5A623;"></i> Étapes</div>
      @foreach([
        ['titre' => 'Remplir le formulaire DS-160 en ligne', 'desc' => 'Formulaire de demande de visa non-immigrant rempli en ligne sur le portail officiel. Conserver le code-barres de confirmation.', 'delai' => '2–3 heures'],
        ['titre' => 'Payer les frais MRV (160 USD)', 'desc' => 'Paiement via les canaux autorisés pour le Cameroun. Conservez votre reçu de paiement.', 'delai' => '1 jour'],
        ['titre' => 'Prendre rendez-vous à l\'ambassade à Yaoundé', 'desc' => 'Les rendez-vous à l\'Ambassade américaine à Yaoundé peuvent être pris plusieurs semaines à l\'avance. Agissez tôt.', 'delai' => '4–12 semaines d\'attente'],
        ['titre' => 'Préparer l\'entretien consulaire', 'desc' => 'L\'entretien est obligatoire pour presque tous les types de visa. VisaFly vous prépare aux questions types et à présenter votre dossier.', 'delai' => '30–60 minutes'],
        ['titre' => 'Attendre la décision et le passeport', 'desc' => 'Le consul décide généralement sur place. En cas d\'approbation, le passeport est retourné sous 5–10 jours ouvrés avec le visa collé.', 'delai' => '5–10 jours'],
      ] as $i => $e)
      <div class="etape-row">
        <div class="etape-num-badge">{{ $i + 1 }}</div>
        <div class="etape-text">
          <h5>{{ $e['titre'] }}</h5>
          <p>{{ $e['desc'] }}</p>
          @if(isset($e['delai']))
          <span class="delai-pill"><i class="bi bi-clock"></i> {{ $e['delai'] }}</span>
          @endif
        </div>
      </div>
      @endforeach

      <div class="docs-section">
        <div class="docs-section-title"><i class="bi bi-folder2-open" style="color:#F5A623;"></i> Documents requis</div>
        <div class="docs-grid">
          @foreach([
            'Passeport valide 6 mois après la fin du séjour',
            'Confirmation DS-160 avec code-barres',
            'Reçu de paiement MRV (160 USD)',
            'Photo format US récente',
            'Relevés bancaires 6 derniers mois',
            'Preuves de liens au Cameroun',
            'Lettre d\'invitation (si applicable)',
            'I-20 ou DS-2019 (études/échange)',
            'Lettre de l\'employeur (visa affaires)',
            'Documents d\'admission universitaire (F1)',
          ] as $doc)
          <div class="doc-pill"><i class="bi bi-check-circle-fill"></i> {{ $doc }}</div>
          @endforeach
        </div>
      </div>

      <div class="visafly-aide">
        <div class="visafly-aide-icon"><i class="bi bi-headset"></i></div>
        <div class="visafly-aide-text">
          <strong>Préparez votre entretien visa US avec VisaFly</strong>
          <span>Simulation d'entretien, optimisation du dossier, taux de succès élevé</span>
        </div>
        <a href="{{ route('consultations.create') }}" class="visafly-aide-btn">
          <i class="bi bi-arrow-right"></i> Préparer mon entretien
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ══ CTA FINAL ════════════════════════════════════════════════ --}}
<div class="cta-final" data-aos="fade-up">
  <div class="cta-final-title">
    Prêt à concrétiser votre projet ?
  </div>
  <div class="cta-final-sub">
    L'équipe VisaFly International vous accompagne de la constitution du dossier
    jusqu'à votre installation à l'étranger.
  </div>
  <div class="cta-final-btns">
    <a href="{{ route('consultations.create') }}" class="cta-btn-w cta-btn-gold">
      <i class="bi bi-calendar-check"></i> Consultation gratuite
    </a>
    <a href="{{ route('abonnement.index') }}" class="cta-btn-w cta-btn-out">
      <i class="bi bi-patch-check"></i> Voir nos formules
    </a>
    <a href="https://wa.me/237651350338" target="_blank" class="cta-btn-w cta-btn-out">
      <i class="bi bi-whatsapp"></i> WhatsApp
    </a>
  </div>
</div>

</div>{{-- /.blog-body --}}

@endsection

@push('scripts')
<script>
// Scroll actif pour la nav pays
const sections = document.querySelectorAll('.pays-section');
const navItems = document.querySelectorAll('.pays-nav-item');

window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(s => {
    if(window.scrollY >= s.offsetTop - 140) {
      current = s.id.replace('pays-', '');
    }
  });
  navItems.forEach(item => {
    item.style.borderBottomColor = 'transparent';
    item.style.color = '#888';
    if(item.getAttribute('href') === '#pays-' + current) {
      item.style.borderBottomColor = '#F5A623';
      item.style.color = '#1B3A6B';
    }
  });
});

// Smooth scroll
document.querySelectorAll('.pays-nav-item').forEach(link => {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if(target) {
      window.scrollTo({ top: target.offsetTop - 130, behavior: 'smooth' });
    }
  });
});
</script>
@endpush