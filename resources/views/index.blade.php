<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>VisaFly</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{asset('assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
  <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet">
  <style>
    .flags-wrapper {
      overflow: hidden;
      width: 100%;
      position: relative;
      padding: 10px 0;
    }

    /* Fondu sur les bords */
    .flags-wrapper::before,
    .flags-wrapper::after {
      content: '';
      position: absolute;
      top: 0;
      width: 80px;
      height: 100%;
      z-index: 2;
      pointer-events: none;
    }
    .flags-wrapper::before {
      left: 0;
      background: linear-gradient(to right, var(--bs-body-bg, #fff), transparent);
    }
    .flags-wrapper::after {
      right: 0;
      background: linear-gradient(to left, var(--bs-body-bg, #fff), transparent);
    }

    .flags-track {
      display: flex;
      gap: 2rem;
      width: max-content;
      animation: scrollFlags 18s linear infinite;
    }

    .flags-track:hover {
      animation-play-state: paused;
    }

    .flag-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      min-width: 80px;
      transition: transform 0.3s ease;
    }

    .flag-item:hover {
      transform: scale(1.15);
    }

    .flag-item img {
      width: 70px;
      height: 47px;
      object-fit: cover;
      border-radius: 6px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }

    .flag-item span {
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--bs-secondary-color, #555);
      text-align: center;
    }

    @keyframes scrollFlags {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
  </style>

</head>

<body class="index-page">

 @include('partials.navbar')

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content">
              <div class="hero-badge" data-aos="fade-up" data-aos-delay="150">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L2 7l10 5 10-5-10-5z" fill="#F5A623"/>
              </svg>
              Agence de mobilité internationale
            </div>

              <h1 data-aos="fade-up" data-aos-delay="200">
                Visafly International<br>
                <span class="accent">Votre Visa</span> vers le succès mondial
              </h1>

              <p data-aos="fade-up" data-aos-delay="300">Avec Visafly, concrétisez vos rêves d’études, d’emploi, de voyage ou d’affaires à l’étranger. Notre équipe vous accompagne pas à pas dans toutes vos démarches de visa, placement et mobilité internationale. Fiable, rapide et professionnelle, Visafly transforme vos ambitions en réalité.</p>

              <div class="hero-cta" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('consultation') }}" class="btn-primary">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M22 2L11 13" stroke="#1B3A6B" stroke-width="2.2" stroke-linecap="round"/>
                    <path d="M22 2L15 22l-4-9-9-4 20-7z" stroke="#1B3A6B" stroke-width="2.2"
                          stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Se faire consulter
                </a>
              </div>
              <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
                <div class="stat-item">
                  <div class="stat-number">100+</div>
                  <div class="stat-label">Visa obtenu</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number">98%</div>
                  <div class="stat-label">Client Satisfait</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number">6+</div>
                  <div class="stat-label">annee d'experience</div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-image" data-aos="fade-left" data-aos-delay="300">
              <img src="assets/img/about/about-square-10.webp" alt="Business Success" class="img-fluid">
              <div class="floating-card" data-aos="zoom-in" data-aos-delay="600">
                <div class="card-icon">
                  <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="card-content">
                  <h5>Croissance de taux de réussite</h5>
                  <div class="growth-percentage">+75%</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">About</span>
        <h2>À propos de nous</h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
            <div class="content">
              <h2>Visafly International</h2>
              <p class="lead">Visafly International est une agence de mobilité mondiale spécialisée dans l’accompagnement administratif, professionnel et stratégique pour l’obtention de visas d’études, de travail, de business et de tourisme.</p>

              <p>Nous croyons que chaque projet international mérite un suivi rigoureux, humain et professionnel. Notre mission est de faciliter l’accès aux opportunités à l’étranger pour les étudiants, travailleurs, investisseurs et voyageurs africains souhaitant bâtir un avenir meilleur.</p>

              <div class="stats-row">
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="6" data-purecounter-duration="1" data-purecounter-suffix="+"></div>
                  <div class="stat-label">Années d'expérience</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="100" data-purecounter-duration="1" data-purecounter-suffix="+"></div>
                  <div class="stat-label">Projets terminés</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="240" data-purecounter-duration="1" data-purecounter-suffix="+"></div>
                  <div class="stat-label">Clients satisfaits</div>
                </div>
              </div>

              <div class="cta-section">
                <a href="#team" team="" class="btn-outline">Découvrez notre équipe</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="image-wrapper">
              <img src="assets/img/about/about-square-12.webp" alt="About us" class="img-fluid">
              <div class="floating-card" data-aos="zoom-in" data-aos-delay="500">
                <div class="card-content">
                  <div class="icon">
                    <i class="bi bi-award"></i>
                  </div>
                  <div class="text">
                    <h4>Le meilleur</h4>
                    <p>Reconnus pour notre excellence dans notre secteur d'activité</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Langues Section -->
    <section id="langues" class="services section">

      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Les langues</span>
        <h2>Nos tests de langues</h2>
        <p>Chez VisaFly, nous savons qu'un projet d'immigration ne s'improvise pas. Chaque parcours est unique, et chaque pays exige une stratégie bien définie en passant par les tests de langues.</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4 justify-content-center">

          <!-- TCF -->
          <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="150">
            <a href="{{ route('tcf.index') }}" class="btn-lang btn-lang-or">
              <div class="btn-lang-icon btn-lang-icon-or">
                <i class="bi bi-pencil-square"></i>
              </div>
              <div class="btn-lang-text">
                <div class="btn-lang-flag">
                  <img src="https://flagcdn.com/w40/fr.png" alt="France">
                  <span>Français</span>
                </div>
                <strong>Commencer le TCF</strong>
                <small>Test de Connaissance du Français</small>
              </div>
            </a>
          </div>

          <!-- TEF -->
          <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <a href="tef.html" class="btn-lang btn-lang-or">
              <div class="btn-lang-icon btn-lang-icon-or">
                <i class="bi bi-pencil-square"></i>
              </div>
              <div class="btn-lang-text">
                <div class="btn-lang-flag">
                  <img src="https://flagcdn.com/w40/ca.png" alt="Canada">
                  <span>Français</span>
                </div>
                <strong>Commencer le TEF</strong>
                <small>Test d'Évaluation du Français</small>
              </div>
            </a>
          </div>

          <!-- IELTS / Anglais -->
          <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="250">
            <a href="ielts.html" class="btn-lang btn-lang-marine">
              <div class="btn-lang-icon btn-lang-icon-marine">
                <i class="bi bi-translate"></i>
              </div>
              <div class="btn-lang-text">
                <div class="btn-lang-flag">
                  <img src="https://flagcdn.com/w40/gb.png" alt="UK">
                  <span>Anglais</span>
                </div>
                <strong>Passer l'IELTS / TOEFL</strong>
                <small>Test de langue anglaise</small>
              </div>
            </a>
          </div>

          <!-- TestDaF / Allemand -->
          <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <a href="testdaf.html" class="btn-lang btn-lang-marine">
              <div class="btn-lang-icon btn-lang-icon-marine">
                <i class="bi bi-globe-europe-africa"></i>
              </div>
              <div class="btn-lang-text">
                <div class="btn-lang-flag">
                  <img src="https://flagcdn.com/w40/de.png" alt="Allemagne">
                  <span>Allemand</span>
                </div>
                <strong>Passer le TestDaF / Goethe</strong>
                <small>Test de langue allemande</small>
              </div>
            </a>
          </div>

        </div>
      </div>

    </section><!-- /Services Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Services</span>
        <h2>Ce que nous proposons</h2>
        <p>Chez VisaFly, nous savons qu’un projet d’immigration ne s’improvise pas. Chaque parcours est unique, et chaque pays exige une stratégie bien définie</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item" data-num="01">
              <div class="service-icon">
                <i class="bi bi-graph-up-arrow"></i>
              </div>
              <h3>Conseil stratégique(Consulting)</h3>
              <p>Notre service de Conseil Stratégique vous accompagne pas à pas afin de maximiser vos chances de réussite et de construire un projet solide, réaliste et conforme aux exigences des autorités d’immigration.</p>
              <a href="service-details.html" class="service-link">
                Learn More <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item" data-num="02">
              <div class="service-icon">
                <i class="bi bi-palette"></i>
              </div>
              <h3>VISA</h3>
              <p>Accompagnement complet dans la préparation, la vérification et le dépôt de votre demande de visa selon votre destination et votre projet.</p>
              <a href="service-details.html" class="service-link">
                Learn More <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item" data-num="03">
              <div class="service-icon">
                <i class="bi bi-code-slash"></i>
              </div>
              <h3>FORMATION & LANGUE</h3>
              <p>Orientation vers les meilleures écoles et formations à l’étranger, avec appui pour l’apprentissage ou la certification en langue (IELTS, TEF, etc.).</p>
              <a href="service-details.html" class="service-link">
                Learn More <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item" data-num="04">
              <div class="service-icon">
                <i class="bi bi-megaphone"></i>
              </div>
              <h3>Assurances voyage et sante</h3>
              <p>Souscription rapide à des assurances couvrant vos soins médicaux, urgences et imprévus durant le séjour à l’étranger.</p>
              <a href="service-details.html" class="service-link">
                Learn More <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item" data-num="05">
              <div class="service-icon">
                <i class="bi bi-people"></i>
              </div>
              <h3>Achats de billets d'avion</h3>
              <p>Assistance pour trouver et réserver les meilleurs vols au tarif le plus avantageux selon votre destination et vos dates de départ.</p>
              <a href="service-details.html" class="service-link">
                Learn More <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item" data-num="06">
              <div class="service-icon">
                <i class="bi bi-bar-chart"></i>
              </div>
              <h3>Reservation d'hotel ou hebergement</h3>
              <p>Aide à la recherche et à la réservation d’un logement sûr et adapté à votre budget et à votre durée de séjour.</p>
              <a href="service-details.html" class="service-link">
                Learn More <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item" data-num="07">
              <div class="service-icon">
                <i class="bi bi-bar-chart"></i>
              </div>
              <h3>Orientation et accompagnement a jusqu'a l'installation </h3>
              <p>Accueil, conseils pratiques et suivi personnalisé pour faciliter votre intégration dans le nouveau pays.</p>
              <a href="service-details.html" class="service-link">
                Learn More <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item" data-num="08">
              <div class="service-icon">
                <i class="bi bi-bar-chart"></i>
              </div>
              <h3>L'import - Export</h3>
              <p>Appui aux particuliers et entreprises dans leurs opérations commerciales internationales : sourcing, transport, et formalités douanières.</p>
              <a href="service-details.html" class="service-link">
                Learn More <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Pourquoi nous -->
    <section id="why-us" class="why-us section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Pourquoi nous</span>
        <h2>Pourquoi nous choisir ?</h2>
        <p>Choisir VisaFly, c’est opter pour une équipe experte, fiable et dévouée, qui vous accompagne à chaque étape de votre projet d’immigration avec professionnalisme et transparence.</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="features-grid" data-aos="fade-up" data-aos-delay="400">
          <div class="row g-5">

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div class="feature-item" data-num="01">
                <div class="icon-wrapper">
                  <i class="bi bi-lightbulb"></i>
                </div>
                <div class="feature-content">
                  <h3> Transparence et fiabilité dans toutes nos démarches.</h3>
                  <p>VisaFly agit avec honnêteté et clarté à chaque étape, vous garantissant des informations vérifiées et un service sans surprise.</p>
                </div>
              </div>
            </div><!-- End Feature Item -->

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div class="feature-item" data-num="02">
                <div class="icon-wrapper">
                  <i class="bi bi-award"></i>
                </div>
                <div class="feature-content">
                  <h3> Accompagnement personnalisé adapté à votre profil et vos ambitions.</h3>
                  <p>Chaque client bénéficie d’un suivi sur mesure, conçu selon son parcours, ses objectifs et son projet d’immigration.</p>
                </div>
              </div>
            </div><!-- End Feature Item -->

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
              <div class="feature-item" data-num="03">
                <div class="icon-wrapper">
                  <i class="bi bi-headset"></i>
                </div>
                <div class="feature-content">
                  <h3>Réseau de partenaires internationaux solides en Europe, en Asie et en Afrique.</h3>
                  <p>Nos collaborations avec des institutions reconnues facilitent vos démarches et renforcent vos chances de réussite.</p>
                </div>
              </div>
            </div><!-- End Feature Item -->

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
              <div class="feature-item" data-num="04">
                <div class="icon-wrapper">
                  <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="feature-content">
                  <h3>Suivi complet du dossier jusqu’à votre arrivée et intégration à destination</h3>
                  <p>VisaFly reste à vos côtés avant, pendant et après votre départ pour assurer une installation fluide et sécurisée.</p>
                </div>
              </div>
            </div><!-- End Feature Item -->

          </div>
        </div>

      </div>

    </section><!-- /Why Us Section -->

    <!-- Nos realisation -->
    <section id="portfolio" class="portfolio section">

      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Nos réalisations</span>
        <h2>Ce que nous avons accompli</h2>
        <p>Au fil des années, VisaFly a accompagné avec succès de nombreux candidats dans la concrétisation de leurs projets d'immigration, d'études et de voyage à l'étranger.</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <!-- Filtres -->
        <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="200">
          <li data-filter="*" class="filter-active">Tous</li>
          <li data-filter=".filter-visa">Visa obtenu</li>
          <li data-filter=".filter-etudes">Études à l'étranger</li>
          <li data-filter=".filter-emploi">Emploi international</li>
          <li data-filter=".filter-installation">Accompagnement</li>
        </ul>

        <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="300">

          <!-- Allemagne — Visa travail -->
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-visa filter-emploi">
            <div class="pf-card">
              <div class="pf-img">
                <img src="assets/img/portfolio/Berlin.jpg" alt="Berlin" loading="lazy">
                <div class="pf-overlay">
                  <a href="assets/img/portfolio/Berlin.jpg" class="glightbox pf-btn" title="Agrandir">
                    <i class="bi bi-eye"></i>
                  </a>
                </div>
                <span class="pf-badge badge-visa">Visa obtenu</span>
              </div>
              <div class="pf-body">
                <div class="pf-country">
                  <img src="https://flagcdn.com/w40/de.png" alt="DE">
                  <span>Allemagne — Berlin</span>
                </div>
                <h3>Visa de travail obtenu en 6 semaines</h3>
                <p>Ingénieur informatique, dossier complet et déposé avec succès auprès du consulat allemand.</p>
                <div class="pf-tags">
                  <span class="pf-tag tag-visa">Visa travail</span>
                  <span class="pf-tag tag-emploi">Emploi</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Canada — Études -->
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-etudes filter-visa">
            <div class="pf-card">
              <div class="pf-img">
                <img src="assets/img/portfolio/Ottawa.jpg" alt="Ottawa" loading="lazy">
                <div class="pf-overlay">
                  <a href="assets/img/portfolio/Ottawa.jpg" class="glightbox pf-btn" title="Agrandir">
                    <i class="bi bi-eye"></i>
                  </a>
                </div>
                <span class="pf-badge badge-etudes">Études</span>
              </div>
              <div class="pf-body">
                <div class="pf-country">
                  <img src="https://flagcdn.com/w40/ca.png" alt="CA">
                  <span>Canada — Ottawa</span>
                </div>
                <h3>Admission universitaire + visa étudiant</h3>
                <p>Accompagnement complet pour l'admission à l'Université d'Ottawa et obtention du permis d'études.</p>
                <div class="pf-tags">
                  <span class="pf-tag tag-etudes">Études</span>
                  <span class="pf-tag tag-visa">Visa étudiant</span>
                </div>
              </div>
            </div>
          </div>

          <!-- France — Visa famille -->
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-visa filter-installation">
            <div class="pf-card">
              <div class="pf-img">
                <img src="assets/img/portfolio/france.jpg" alt="Paris" loading="lazy">
                <div class="pf-overlay">
                  <a href="assets/img/portfolio/france.jpg" class="glightbox pf-btn" title="Agrandir">
                    <i class="bi bi-eye"></i>
                  </a>
                </div>
                <span class="pf-badge badge-visa">Visa obtenu</span>
              </div>
              <div class="pf-body">
                <div class="pf-country">
                  <img src="https://flagcdn.com/w40/fr.png" alt="FR">
                  <span>France — Paris</span>
                </div>
                <h3>Visa long séjour — Regroupement familial</h3>
                <p>Dossier de regroupement familial accepté en première présentation avec accompagnement VisaFly.</p>
                <div class="pf-tags">
                  <span class="pf-tag tag-visa">Visa famille</span>
                  <span class="pf-tag tag-accomp">Accompagnement</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Belgique — Emploi -->
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-emploi filter-visa">
            <div class="pf-card">
              <div class="pf-img">
                <img src="assets/img/portfolio/belgique.jpg" alt="Bruxelles" loading="lazy">
                <div class="pf-overlay">
                  <a href="assets/img/portfolio/belgique.jpg" class="glightbox pf-btn" title="Agrandir">
                    <i class="bi bi-eye"></i>
                  </a>
                </div>
                <span class="pf-badge badge-emploi">Emploi</span>
              </div>
              <div class="pf-body">
                <div class="pf-country">
                  <img src="https://flagcdn.com/w40/be.png" alt="BE">
                  <span>Belgique — Bruxelles</span>
                </div>
                <h3>Placement professionnel CDI — Bruxelles</h3>
                <p>Mise en relation avec un employeur belge et accompagnement pour le permis de travail.</p>
                <div class="pf-tags">
                  <span class="pf-tag tag-emploi">Emploi</span>
                  <span class="pf-tag tag-visa">Permis travail</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Portugal — Installation -->
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-installation filter-visa">
            <div class="pf-card">
              <div class="pf-img">
                <img src="assets/img/portfolio/Portugal.jpg" alt="Lisbonne" loading="lazy">
                <div class="pf-overlay">
                  <a href="assets/img/portfolio/Portugal.jpg" class="glightbox pf-btn" title="Agrandir">
                    <i class="bi bi-eye"></i>
                  </a>
                </div>
                <span class="pf-badge badge-install">Installation</span>
              </div>
              <div class="pf-body">
                <div class="pf-country">
                  <img src="https://flagcdn.com/w40/pt.png" alt="PT">
                  <span>Portugal — Lisbonne</span>
                </div>
                <h3>Accompagnement jusqu'à l'installation</h3>
                <p>Suivi complet depuis la demande de visa jusqu'à la recherche de logement et l'intégration.</p>
                <div class="pf-tags">
                  <span class="pf-tag tag-accomp">Installation</span>
                  <span class="pf-tag tag-visa">Visa</span>
                </div>
              </div>
            </div>
          </div>

          <!-- USA — Business -->
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-visa filter-emploi">
            <div class="pf-card">
              <div class="pf-img">
                <img src="assets/img/portfolio/newyork.jpg" alt="New York" loading="lazy">
                <div class="pf-overlay">
                  <a href="assets/img/portfolio/newyork.jpg" class="glightbox pf-btn" title="Agrandir">
                    <i class="bi bi-eye"></i>
                  </a>
                </div>
                <span class="pf-badge badge-business">Business</span>
              </div>
              <div class="pf-body">
                <div class="pf-country">
                  <img src="https://flagcdn.com/w40/us.png" alt="US">
                  <span>États-Unis — New York</span>
                </div>
                <h3>Visa business B1/B2 — Mission commerciale</h3>
                <p>Visa d'affaires obtenu pour une délégation d'entrepreneurs camerounais à New York.</p>
                <div class="pf-tags">
                  <span class="pf-tag tag-visa">Visa business</span>
                  <span class="pf-tag tag-emploi">Commerce</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section><!-- /Portfolio Section -->

    <!-- Team Section -->
    <section id="team" class="team section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Équipe</span>
        <h2>Découvrez notre équipe</h2>
        <p>L’équipe VisaFly est composée de consultants expérimentés en immigration, formation et voyage, passionnés par l’accompagnement personnalisé. Chaque membre met son expertise au service de votre projet pour garantir des conseils fiables, un suivi rigoureux et une assistance à chaque étape de votre parcours à l’international</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/person/person-f-8.webp" class="img-fluid" alt="Modi DARRYL">
                <span class="team-role-badge">CEO</span>
              </div>
              <div class="member-info">
                <h4>Modi DARRYL</h4>
                <span>CEO Visafly</span>
                <p>Visionnaire et fondateur de l’entreprise, le premier CEO a posé les bases de notre réussite. Son leadership audacieux et sa capacité à anticiper les besoins du marché ont permis de transformer une idée en une organisation solide et respectée. Il reste une source d’inspiration pour notre culture d’entreprise. </p>
                <div class="social">
                  <a href="#"><i class="bi bi-twitter-x"></i></a>
                  <a href="#"><i class="bi bi-linkedin"></i></a>
                  <a href="#"><i class="bi bi-instagram"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="250">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/person/person-f-8.webp" class="img-fluid" alt="NDJOCK ROGER">
                <span class="team-role-badge">CEO</span>
              </div>
              <div class="member-info">
                <h4>NDJOCK ROGER</h4>
                <span>CEO VISAFLY</span>
                <p> Le second CEO a poursuivi cette dynamique en consolidant nos acquis et en ouvrant de nouvelles perspectives. Grâce à son sens stratégique et son approche orientée vers la croissance durable, il a su renforcer notre position sur le marché tout en mettant l’accent sur l’innovation et la satisfaction client.</p>
                <div class="social">
                  <a href="#"><i class="bi bi-twitter-x"></i></a>
                  <a href="#"><i class="bi bi-linkedin"></i></a>
                  <a href="#"><i class="bi bi-github"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/person/person-f-8.webp" class="img-fluid" alt="KEWOU CHRISTIAN">
                <span class="team-role-badge">Tech</span>
              </div>
              <div class="member-info">
                <h4>KEWOU CHRISTIAN</h4>
                <span>Dircteur  departement informatique</span>
                <p>Le département informatique est le moteur technologique de notre organisation. Composé d’experts passionnés, il assure la sécurité, la maintenance et le développement de nos systèmes. Leur mission est de garantir des solutions fiables et innovantes qui soutiennent nos activités et anticipent les évolutions numériques.</p>
                <div class="social">
                  <a href="#"><i class="bi bi-twitter-x"></i></a>
                  <a href="#"><i class="bi bi-linkedin"></i></a>
                  <a href="#"><i class="bi bi-dribbble"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="350">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/person/person-f-8.webp" class="img-fluid" alt="TANEYO ALIANCE">
                <span class="team-role-badge">Admin</span>
              </div>
              <div class="member-info">
                <h4>TANEYO ALIANCE</h4>
                <span>Secretaire general</span>
                <p>La secrétaire est le pilier administratif de l’entreprise. Avec professionnalisme et sens de l’organisation, elle assure la coordination des agendas, la gestion des communications et le suivi des dossiers. Sa disponibilité et son efficacité contribuent à la fluidité de nos opérations. </p>
                <div class="social">
                  <a href="#"><i class="bi bi-twitter-x"></i></a>
                  <a href="#"><i class="bi bi-linkedin"></i></a>
                  <a href="#"><i class="bi bi-facebook"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- /Team Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Nos locaux</span>
        <h2>Nos locaux</h2>
        <p>Nos locaux reflètent notre engagement envers le professionnalisme. Situés au cœur de Yaoundé, ils offrent un cadre moderne et convivial, propice à l'accompagnement de vos projets internationaux.</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <!-- Stats locaux -->
        <div class="locaux-stats-bar" data-aos="fade-up" data-aos-delay="150">
          <div class="lstat-item">
            <div class="lstat-icon"><i class="bi bi-building"></i></div>
            <div class="lstat-info">
              <span class="lstat-num">1<sup>er</sup></span>
              <span class="lstat-label">Agence à Jouvence</span>
            </div>
          </div>
          <div class="lstat-item">
            <div class="lstat-icon"><i class="bi bi-people"></i></div>
            <div class="lstat-info">
              <span class="lstat-num">5<sup>+</sup></span>
              <span class="lstat-label">Conseillers dédiés</span>
            </div>
          </div>
          <div class="lstat-item">
            <div class="lstat-icon"><i class="bi bi-clock"></i></div>
            <div class="lstat-info">
              <span class="lstat-num">6j/7</span>
              <span class="lstat-label">Disponibilité</span>
            </div>
          </div>
          <div class="lstat-item">
            <div class="lstat-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="lstat-info">
              <span class="lstat-num">RDV</span>
              <span class="lstat-label">Sur rendez-vous</span>
            </div>
          </div>
        </div>

        <!-- Carrousel avec badge flottant -->
        <div class="locaux-carousel-wrap" data-aos="fade-up" data-aos-delay="200">

          <!-- Badge "Agence ouverte" -->
          <div class="locaux-open-badge">
            <span class="open-dot"></span>
            <div>
              <strong>Agence ouverte</strong>
              <span>Jouvence, Yaoundé</span>
            </div>
          </div>

          <div id="locauxCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">

              <div class="carousel-item active">
                <div class="row g-2">
                  <div class="col-4 position-relative">
                    <img src="assets/img/locaux/locaux1.jpeg" class="d-block w-100 locaux-img" alt="Salle d'accueil">
                    <span class="img-room-label">Salle d'accueil</span>
                  </div>
                  <div class="col-4 position-relative">
                    <img src="assets/img/locaux/locaux2.jpeg" class="d-block w-100 locaux-img" alt="Bureau conseil">
                    <span class="img-room-label">Bureau conseil</span>
                  </div>
                  <div class="col-4 position-relative">
                    <img src="assets/img/locaux/locaux3.jpeg" class="d-block w-100 locaux-img" alt="Espace client">
                    <span class="img-room-label">Espace client</span>
                  </div>
                </div>
              </div>

              <div class="carousel-item">
                <div class="row g-2">
                  <div class="col-4 position-relative">
                    <img src="assets/img/locaux/locaux4.jpeg" class="d-block w-100 locaux-img" alt="Salle de réunion">
                    <span class="img-room-label">Salle de réunion</span>
                  </div>
                  <div class="col-4 position-relative">
                    <img src="assets/img/locaux/locaux5.jpeg" class="d-block w-100 locaux-img" alt="Espace détente">
                    <span class="img-room-label">Espace détente</span>
                  </div>
                  <div class="col-4 position-relative">
                    <img src="assets/img/locaux/locaux6.jpeg" class="d-block w-100 locaux-img" alt="Entrée agence">
                    <span class="img-room-label">Entrée agence</span>
                  </div>
                </div>
              </div>

            </div>

            <!-- Nav personnalisée -->
            <button class="locaux-btn locaux-btn-prev" type="button"
                    data-bs-target="#locauxCarousel" data-bs-slide="prev">
              <i class="bi bi-chevron-left"></i>
            </button>
            <button class="locaux-btn locaux-btn-next" type="button"
                    data-bs-target="#locauxCarousel" data-bs-slide="next">
              <i class="bi bi-chevron-right"></i>
            </button>

            <!-- Indicateurs -->
            <div class="locaux-indicators">
              <button type="button" data-bs-target="#locauxCarousel"
                      data-bs-slide-to="0" class="locaux-dot active"></button>
              <button type="button" data-bs-target="#locauxCarousel"
                      data-bs-slide-to="1" class="locaux-dot"></button>
            </div>
          </div>
        </div>

        <!-- Barre adresse -->
        <div class="locaux-address-bar" data-aos="fade-up" data-aos-delay="300">
          <div class="addr-item">
            <div class="addr-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div><strong>Adresse</strong><span>Jouvence, Yaoundé, Cameroun</span></div>
          </div>
          <div class="addr-sep"></div>
          <div class="addr-item">
            <div class="addr-icon"><i class="bi bi-telephone-fill"></i></div>
            <div><strong>Téléphone</strong><span>+237 651 350 338</span></div>
          </div>
          <div class="addr-sep"></div>
          <div class="addr-item">
            <div class="addr-icon"><i class="bi bi-envelope-fill"></i></div>
            <div><strong>Email</strong><span>visaflypro@gmail.com</span></div>
          </div>
          <div class="addr-sep"></div>
          <div class="addr-item">
            <div class="addr-icon"><i class="bi bi-clock-fill"></i></div>
            <div><strong>Horaires</strong><span>Lun–Sam : 8h–18h</span></div>
          </div>
        </div>

      </div>
    </section><!-- /Testimonials Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Contact</span>
        <h2>Contactez-nous</h2>
        <p>Notre équipe est à votre écoute pour répondre à toutes vos questions et vous accompagner dans votre projet d'immigration ou de voyage.</p>
      </div>

      <div class="container">
        <div class="row gy-4 align-items-start">

          <!-- ── Colonne gauche ── -->
          <div class="col-lg-4">

            <!-- Carte bleue principale -->
            <div class="contact-info-card">
              <h3>Connectons-nous</h3>
              <div class="contact-gold-bar"></div>
              <p>Nous sommes ici pour discuter de votre projet international et vous accompagner vers la réussite.</p>
            </div>

            <!-- Items contact -->
            <div class="contact-items-list">
              <div class="contact-ci">
                <div class="contact-ci-icon"><i class="bi bi-envelope-fill"></i></div>
                <div>
                  <span class="contact-ci-label">Email</span>
                  <span class="contact-ci-value">visaflypro@gmail.com</span>
                </div>
              </div>
              <div class="contact-ci">
                <div class="contact-ci-icon"><i class="bi bi-telephone-fill"></i></div>
                <div>
                  <span class="contact-ci-label">Téléphone</span>
                  <span class="contact-ci-value">+237 651 350 338</span>
                </div>
              </div>
              <div class="contact-ci">
                <div class="contact-ci-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                  <span class="contact-ci-label">Adresse</span>
                  <span class="contact-ci-value">Jouvence, Yaoundé, Cameroun</span>
                </div>
              </div>
              <div class="contact-ci">
                <div class="contact-ci-icon"><i class="bi bi-clock-fill"></i></div>
                <div>
                  <span class="contact-ci-label">Horaires</span>
                  <span class="contact-ci-value">Lun–Sam : 8h00 – 18h00</span>
                </div>
              </div>
            </div>

            <!-- Réseaux sociaux -->
            <div class="contact-socials">
              <a href="#" class="contact-soc"><i class="bi bi-facebook"></i></a>
              <a href="#" class="contact-soc"><i class="bi bi-instagram"></i></a>
              <a href="#" class="contact-soc"><i class="bi bi-linkedin"></i></a>
              <a href="#" class="contact-soc contact-soc-wa"><i class="bi bi-whatsapp"></i></a>
            </div>

          </div>

          <!-- ── Colonne droite — Formulaire ── -->
          <div class="col-lg-8">
            <div class="contact-form-card">
              <h3>Envoyez-nous un message</h3>
              <p class="contact-form-sub">Nous vous répondrons dans les 24 heures.</p>

              <!-- Badge WhatsApp -->
              <div class="contact-wa-badge">
                <span class="contact-wa-dot"></span>
                <div>
                  <strong>Réponse rapide sur WhatsApp</strong>
                  <span>+237 651 350 338 — disponible 6j/7</span>
                </div>
              </div>

              <form action="forms/contact.php" method="post" class="php-email-form">

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <div class="contact-fg">
                      <label>Nom complet *</label>
                      <input type="text" name="name" placeholder="Jean Dupont" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="contact-fg">
                      <label>Email *</label>
                      <input type="email" name="email" placeholder="jean@email.com" required>
                    </div>
                  </div>
                </div>

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <div class="contact-fg">
                      <label>Destination</label>
                      <select name="destination">
                        <option value="">-- Choisir un pays --</option>
                        <option>France</option>
                        <option>Canada</option>
                        <option>Allemagne</option>
                        <option>Royaume-Uni</option>
                        <option>États-Unis</option>
                        <option>Belgique</option>
                        <option>Portugal</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="contact-fg">
                      <label>Type de demande</label>
                      <select name="type">
                        <option value="">-- Sélectionner --</option>
                        <option>Visa & Immigration</option>
                        <option>Études à l'étranger</option>
                        <option>Emploi international</option>
                        <option>Assurance voyage</option>
                        <option>Autre</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="contact-fg mb-3">
                  <label>Objet *</label>
                  <input type="text" name="subject" placeholder="Ex: Demande de consultation visa France" required>
                </div>

                <div class="contact-fg mb-3">
                  <label>Message *</label>
                  <textarea name="message" rows="5" placeholder="Décrivez votre projet et vos besoins..." required></textarea>
                </div>

                <div class="my-3">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Votre message a été envoyé. Merci !</div>
                </div>

                <button type="submit" class="contact-btn-send">
                  Envoyer le message
                  <i class="bi bi-send-fill"></i>
                </button>

              </form>
            </div>
          </div>

        </div>
      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    <!-- Top bar logo + newsletter -->
    <div class="footer-top-bar">
      <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">

        <div class="footer-logo-wrap d-flex align-items-center gap-2">
          <div class="footer-logo-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M12 2L2 7l10 5 10-5-10-5z" fill="#F5A623"/>
              <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke="#F5A623" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </div>
          <div>
            <div class="footer-brand">Visa<span>Fly</span></div>
            <div class="footer-tagline">Votre partenaire mobilité internationale</div>
          </div>
        </div>

        <div class="footer-newsletter">
          <input type="email" placeholder="Votre email pour nos actualités">
          <button type="button">S'abonner</button>
        </div>

      </div>
    </div>

    <!-- Grille principale -->
    <div class="container footer-top">
      <div class="row gy-4">

        <!-- À propos -->
        <div class="col-lg-4 col-md-12 footer-about">
          <p>VisaFly International est votre agence de mobilité mondiale. Nous accompagnons les talents africains vers leurs opportunités d'études, d'emploi et d'affaires à l'étranger.</p>
          <p class="footer-quote">« Nous ne vous vendons pas un rêve,<br>nous construisons votre avenir. »</p>
          <div class="social-links d-flex mt-3 gap-2">
            <a href="#" class="footer-social-btn">
              <i class="bi bi-facebook"></i>
            </a>
            <a href="#" class="footer-social-btn">
              <i class="bi bi-instagram"></i>
            </a>
            <a href="#" class="footer-social-btn">
              <i class="bi bi-linkedin"></i>
            </a>
            <a href="#" class="footer-social-btn footer-social-wa">
              <i class="bi bi-whatsapp"></i>
            </a>
          </div>
        </div>

        <!-- Liens utiles -->
        <div class="col-lg-2 col-6 footer-links">
          <h4>Liens utiles</h4>
          <ul>
            <li><a href="#hero">Accueil</a></li>
            <li><a href="#about">À propos</a></li>
            <li><a href="#services">Nos services</a></li>
            <li><a href="#portfolio">Réalisations</a></li>
            <li><a href="#team">Notre équipe</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>

        <!-- Services -->
        <div class="col-lg-2 col-6 footer-links">
          <h4>Nos services</h4>
          <ul>
            <li><a href="#">Visa & Immigration</a></li>
            <li><a href="#">Études à l'étranger</a></li>
            <li><a href="#">Emploi international</a></li>
            <li><a href="#">Assurance voyage</a></li>
            <li><a href="#">Billets d'avion</a></li>
            <li><a href="#">Import — Export</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="col-lg-4 col-md-12 footer-contact-col">
          <h4>Nous contacter</h4>
          <div class="footer-contact-items">
            <div class="footer-ci">
              <div class="footer-ci-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div>
                <span class="footer-ci-label">Adresse</span>
                <span class="footer-ci-val">Jouvence, Yaoundé, Cameroun</span>
              </div>
            </div>
            <div class="footer-ci">
              <div class="footer-ci-icon"><i class="bi bi-telephone-fill"></i></div>
              <div>
                <span class="footer-ci-label">Téléphone</span>
                <span class="footer-ci-val">+237 651 350 338</span>
              </div>
            </div>
            <div class="footer-ci">
              <div class="footer-ci-icon"><i class="bi bi-envelope-fill"></i></div>
              <div>
                <span class="footer-ci-label">Email</span>
                <span class="footer-ci-val">visaflypro@gmail.com</span>
              </div>
            </div>
            <div class="footer-ci">
              <div class="footer-ci-icon"><i class="bi bi-clock-fill"></i></div>
              <div>
                <span class="footer-ci-label">Horaires</span>
                <span class="footer-ci-val">Lun–Sam : 8h–18h</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Bottom bar -->
    <div class="container footer-bottom">
      <div class="footer-copy">
        © 2025 <strong>VisaFly International</strong> — Tous droits réservés
      </div>
      <div class="footer-dest-flags">
        <span>Destinations :</span>
        <img src="https://flagcdn.com/w40/fr.png" alt="France" title="France">
        <img src="https://flagcdn.com/w40/ca.png" alt="Canada" title="Canada">
        <img src="https://flagcdn.com/w40/de.png" alt="Allemagne" title="Allemagne">
        <img src="https://flagcdn.com/w40/gb.png" alt="Royaume-Uni" title="Royaume-Uni">
        <img src="https://flagcdn.com/w40/us.png" alt="États-Unis" title="États-Unis">
        <img src="https://flagcdn.com/w40/be.png" alt="Belgique" title="Belgique">
        <img src="https://flagcdn.com/w40/pt.png" alt="Portugal" title="Portugal">
      </div>
      <div class="footer-legal">
        <a href="#">Politique de confidentialité</a>
        <span>·</span>
        <a href="#">CGU</a>
      </div>
    </div>

  </footer>
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
  <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
  <script src="{{asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
  <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>

</body>

</html>