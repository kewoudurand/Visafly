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

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <h1 class="sitename">VisaFly</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Acceuil</a></li>
          <li><a href="#about">À propos</a></li>
          <li><a href="#langues">Les Langues</a></li>
          <li><a href="#services">Nos services</a></li>
          <li><a href="#portfolio">Nos realisation</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="{{ route('login') }}"  class="btn btn-outline-primary px-3 py-2 m-2">Se connecter</a></li>
          <li><a href="{{ route('consultation') }}" class="btn btn-primary text-white px-3 py-2">Se consulter</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content">
              <h1 data-aos="fade-up" data-aos-delay="200">Visafly International Votre Visa vers le succès mondial</h1>
              <p data-aos="fade-up" data-aos-delay="300">Avec Visafly, concrétisez vos rêves d’études, d’emploi, de voyage ou d’affaires à l’étranger. Notre équipe vous accompagne pas à pas dans toutes vos démarches de visa, placement et mobilité internationale. Fiable, rapide et professionnelle, Visafly transforme vos ambitions en réalité.</p>
              <div class="hero-cta" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('consultation') }}" class="btn-primary">Se faire consulter</a>
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
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="6" data-purecounter-duration="1"></div>
                  <div class="stat-label">Années d'expérience</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="100" data-purecounter-duration="1"></div>
                  <div class="stat-label">Projets terminés</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="240" data-purecounter-duration="1"></div>
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

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Les langues</span>
        <h2>Ce que nous proposons</h2>
        <p>Chez VisaFly, nous savons qu’un projet d’immigration ne s’improvise pas. Chaque parcours est unique, et chaque pays exige une stratégie bien définie en passant par les Tests de langues</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

          <div class="row gy-5 justify-content-center">

              <div class="col-lg-5 col-md-6 d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
                  <a href="tcf.html" class="btn btn-warning btn-lg px-5 py-4">
                      <i class="bi bi-pencil-square me-2"></i>
                      Commencer le TCF
                  </a>
              </div>

              <div class="col-lg-5 col-md-6 d-flex justify-content-center" data-aos="fade-up" data-aos-delay="300">
                  <a href="tef.html" class="btn btn-warning btn-lg px-5 py-4">
                      <i class="bi bi-pencil-square me-2"></i>
                      Commencer le TEF
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
            <div class="service-item">
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
            <div class="service-item">
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
            <div class="service-item">
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
            <div class="service-item">
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
            <div class="service-item">
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
            <div class="service-item">
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
            <div class="service-item">
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
            <div class="service-item">
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

        <!-- <div class="row">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
            <div class="content">
              <h2>Why Partner With Us</h2>
              <p>We deliver exceptional results through proven expertise, cutting-edge innovation, and unwavering commitment to your success. Our comprehensive approach ensures sustainable growth and competitive advantage.</p>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="image-wrapper">
              <img src="assets/img/about/about-8.webp" alt="Professional team collaboration" class="img-fluid">
            </div>
          </div>
        </div> -->

        <div class="features-grid" data-aos="fade-up" data-aos-delay="400">
          <div class="row g-5">

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div class="feature-item">
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
              <div class="feature-item">
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
              <div class="feature-item">
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
              <div class="feature-item">
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

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Nos realisation</span>
        <h2>Ce que nous avons realise</h2>
        <p>Au fil des années, VisaFly a accompagné avec succès de nombreux candidats dans la concrétisation de leurs projets d’immigration, d’études et de voyage à l’étranger. Nos réalisations témoignent de notre engagement, de notre expertise et de la confiance que nos clients nous accordent à chaque étape de leur parcours.</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
          <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="200">
            <li data-filter="*" class="filter-active">Tous</li>
            <li data-filter=".filter-visa">Visa Obtenu</li>
            <li data-filter=".filter-development">Salle de cour</li>
            <li data-filter=".filter-strategy">Strategy</li>
            <li data-filter=".filter-consulting">Consulting</li>
          </ul><!-- End Portfolio Filters -->

          <div class="row gy-5 isotope-container" data-aos="fade-up" data-aos-delay="300">

            <div class="col-lg-12 portfolio-item isotope-item filter-visa">
              <article class="portfolio-card">
                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="project-visual">
                      <img src="assets/img/portfolio/Berlin.jpg" alt="Enterprise Digital Platform" class="img-fluid" loading="lazy">
                      <div class="project-overlay">
                        <div class="overlay-content">
                          <a href="assets/img/portfolio/Ottawa.jpg" class="view-project glightbox" aria-label="View project image">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="#" class="project-link" aria-label="View project details">
                            <i class="bi bi-arrow-up-right"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="project-details">
                      <div class="project-header">
                        <span class="project-category">Digital Design</span>
                        <time class="project-year">2024</time>
                      </div>
                      <h3 class="project-title">Enterprise Digital Platform</h3>
                      <p class="project-description">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam.</p>
                      <div class="project-meta">
                        <span class="client-name">Fortune 500 Company</span>
                        <div class="project-scope">
                          <span class="scope-item">UX Design</span>
                          <span class="scope-item">Development</span>
                          <span class="scope-item">Strategy</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            </div>

            <div class="col-lg-12 portfolio-item isotope-item filter-development">
              <article class="portfolio-card">
                <div class="row g-4">
                  <div class="col-md-6 order-md-2">
                    <div class="project-visual">
                      <img src="assets/img/portfolio/Ottawa.jpg" alt="SaaS Product Suite" class="img-fluid" loading="lazy">
                      <div class="project-overlay">
                        <div class="overlay-content">
                          <a href="assets/img/portfolio/portfolio-3.webp" class="view-project glightbox" aria-label="View project image">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="#" class="project-link" aria-label="View project details">
                            <i class="bi bi-arrow-up-right"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 order-md-1">
                    <div class="project-details">
                      <div class="project-header">
                        <span class="project-category">Development</span>
                        <time class="project-year">2024</time>
                      </div>
                      <h3 class="project-title">SaaS Product Suite</h3>
                      <p class="project-description">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti.</p>
                      <div class="project-meta">
                        <span class="client-name">Tech Startup</span>
                        <div class="project-scope">
                          <span class="scope-item">Full Stack</span>
                          <span class="scope-item">Cloud Architecture</span>
                          <span class="scope-item">DevOps</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            </div>

            <div class="col-lg-12 portfolio-item isotope-item filter-strategy">
              <article class="portfolio-card">
                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="project-visual">
                      <img src="assets/img/portfolio/italie.jpg" alt="Brand Transformation" class="img-fluid" loading="lazy">
                      <div class="project-overlay">
                        <div class="overlay-content">
                          <a href="assets/img/portfolio/Portugal.jpg" class="view-project glightbox" aria-label="View project image">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="#" class="project-link" aria-label="View project details">
                            <i class="bi bi-arrow-up-right"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="project-details">
                      <div class="project-header">
                        <span class="project-category">Strategy</span>
                        <time class="project-year">2023</time>
                      </div>
                      <h3 class="project-title">Brand Transformation</h3>
                      <p class="project-description">Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      <div class="project-meta">
                        <span class="client-name">Global Corporation</span>
                        <div class="project-scope">
                          <span class="scope-item">Brand Strategy</span>
                          <span class="scope-item">Visual Identity</span>
                          <span class="scope-item">Guidelines</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            </div>

            <div class="col-lg-12 portfolio-item isotope-item filter-consulting">
              <article class="portfolio-card">
                <div class="row g-4">
                  <div class="col-md-6 order-md-2">
                    <div class="project-visual">
                      <img src="assets/img/portfolio/france.jpg" alt="Digital Transformation" class="img-fluid" loading="lazy">
                      <div class="project-overlay">
                        <div class="overlay-content">
                          <a href="assets/img/portfolio/belgique.jpg" class="view-project glightbox" aria-label="View project image">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="#" class="project-link" aria-label="View project details">
                            <i class="bi bi-arrow-up-right"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 order-md-1">
                    <div class="project-details">
                      <div class="project-header">
                        <span class="project-category">Consulting</span>
                        <time class="project-year">2024</time>
                      </div>
                      <h3 class="project-title">Digital Transformation</h3>
                      <p class="project-description">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                      <div class="project-meta">
                        <span class="client-name">Healthcare Provider</span>
                        <div class="project-scope">
                          <span class="scope-item">Process Optimization</span>
                          <span class="scope-item">Technology Audit</span>
                          <span class="scope-item">Implementation</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            </div>

            <div class="col-lg-12 portfolio-item isotope-item filter-design">
              <article class="portfolio-card">
                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="project-visual">
                      <img src="assets/img/portfolio/belgique.jpg" alt="E-commerce Experience" class="img-fluid" loading="lazy">
                      <div class="project-overlay">
                        <div class="overlay-content">
                          <a href="assets/img/portfolio/belgique.jpg" class="view-project glightbox" aria-label="View project image">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="#" class="project-link" aria-label="View project details">
                            <i class="bi bi-arrow-up-right"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="project-details">
                      <div class="project-header">
                        <span class="project-category">Digital Design</span>
                        <time class="project-year">2024</time>
                      </div>
                      <h3 class="project-title">E-commerce Experience</h3>
                      <p class="project-description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur excepteur sint.</p>
                      <div class="project-meta">
                        <span class="client-name">Retail Brand</span>
                        <div class="project-scope">
                          <span class="scope-item">User Experience</span>
                          <span class="scope-item">Interface Design</span>
                          <span class="scope-item">Conversion Optimization</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            </div>

            <div class="col-lg-12 portfolio-item isotope-item filter-development">
              <article class="portfolio-card">
                <div class="row g-4">
                  <div class="col-md-6 order-md-2">
                    <div class="project-visual">
                      <img src="assets/img/portfolio/newyork.jpg" alt="Mobile Application" class="img-fluid" loading="lazy">
                      <div class="project-overlay">
                        <div class="overlay-content">
                          <a href="assets/img/portfolio/newyork.jpg" class="view-project glightbox" aria-label="View project image">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="#" class="project-link" aria-label="View project details">
                            <i class="bi bi-arrow-up-right"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 order-md-1">
                    <div class="project-details">
                      <div class="project-header">
                        <span class="project-category">Development</span>
                        <time class="project-year">2023</time>
                      </div>
                      <h3 class="project-title">Mobile Application</h3>
                      <p class="project-description">Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat duis aute.</p>
                      <div class="project-meta">
                        <span class="client-name">Financial Services</span>
                        <div class="project-scope">
                          <span class="scope-item">iOS Development</span>
                          <span class="scope-item">Android Development</span>
                          <span class="scope-item">API Integration</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            </div>

          </div><!-- End Portfolio Items Container -->

        </div>

        <div class="portfolio-conclusion" data-aos="fade-up" data-aos-delay="400">
          <div class="conclusion-content">
            <h4>Nos Destinations</h4>
            <p>Faire de Visafly International la référence africaine en matière de mobilité, d'emploi et d'études à l'étranger, en connectant les talents africains aux meilleures opportunités mondiales.</p>

            <!-- Défilement des drapeaux -->
            <div class="flags-wrapper my-4">
              <div class="flags-track">
                <!-- Drapeaux originaux -->
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/fr.png" alt="France" title="France">
                  <span>France</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/ca.png" alt="Canada" title="Canada">
                  <span>Canada</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/de.png" alt="Allemagne" title="Allemagne">
                  <span>Allemagne</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/gb.png" alt="Royaume-Uni" title="Royaume-Uni">
                  <span>Royaume-Uni</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/us.png" alt="États-Unis" title="États-Unis">
                  <span>États-Unis</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/be.png" alt="Belgique" title="Belgique">
                  <span>Belgique</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/pt.png" alt="Portugal" title="Portugal">
                  <span>Portugal</span>
                </div>
                <!-- Duplication pour boucle infinie -->
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/fr.png" alt="France" title="France">
                  <span>France</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/ca.png" alt="Canada" title="Canada">
                  <span>Canada</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/de.png" alt="Allemagne" title="Allemagne">
                  <span>Allemagne</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/gb.png" alt="Royaume-Uni" title="Royaume-Uni">
                  <span>Royaume-Uni</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/us.png" alt="États-Unis" title="États-Unis">
                  <span>États-Unis</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/be.png" alt="Belgique" title="Belgique">
                  <span>Belgique</span>
                </div>
                <div class="flag-item">
                  <img src="https://flagcdn.com/w80/pt.png" alt="Portugal" title="Portugal">
                  <span>Portugal</span>
                </div>
              </div>
            </div>

            <div class="conclusion-actions">
              <a href="#contact" class="primary-action">
                Démarrer une conversation
                <i class="bi bi-arrow-right"></i>
              </a>
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
                <img src="assets/img/person/person-f-8.webp" class="img-fluid" alt="Sarah Johnson" loading="lazy">
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
                <img src="assets/img/person/person-m-12.webp" class="img-fluid" alt="Michael Chen" loading="lazy">
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
                <img src="assets/img/person/person-f-3.webp" class="img-fluid" alt="Emily Rodriguez" loading="lazy">
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
                <img src="assets/img/person/person-m-7.webp" class="img-fluid" alt="David Thompson" loading="lazy">
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

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Nos locaux</span>
        <h2>Nos locaux</h2>
        <p>Nos locaux reflètent notre engagement envers l’innovation et le professionnalisme. Situés au cœur de la ville, ils offrent un cadre moderne et convivial, propice à la créativité et à la collaboration. Les espaces de travail lumineux, les salles de réunion équipées et les zones de détente témoignent de notre volonté de créer un environnement où chaque membre de l’équipe peut s’épanouir. </p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">

            <!-- Slide 1 : images 1, 2, 3 -->
            <div class="carousel-item active">
              <div class="row g-2">
                <div class="col-4">
                  <img src="assets/img/locaux/locaux1.jpeg" class="d-block w-100 rounded" alt="Local 1" style="height: 350px; object-fit: cover;">
                </div>
                <div class="col-4">
                  <img src="assets/img/locaux/locaux2.jpeg" class="d-block w-100 rounded" alt="Local 2" style="height: 350px; object-fit: cover;">
                </div>
                <div class="col-4">
                  <img src="assets/img/locaux/locaux3.jpeg" class="d-block w-100 rounded" alt="Local 3" style="height: 350px; object-fit: cover;">
                </div>
              </div>
            </div>

            <div class="carousel-item">
              <div class="row g-2">
                <div class="col-4">
                  <img src="assets/img/locaux/locaux4.jpeg" class="d-block w-100 rounded" alt="Local 4" style="height: 350px; object-fit: cover;">
                </div>
                <div class="col-4">
                  <img src="assets/img/locaux/locaux5.jpeg" class="d-block w-100 rounded" alt="Local 5" style="height: 350px; object-fit: cover;">
                </div>
                <div class="col-4">
                  <img src="assets/img/locaux/locaux6.jpeg" class="d-block w-100 rounded" alt="Local 6" style="height: 350px; object-fit: cover;">
                </div>
              </div>
            </div>

          </div>

          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>

        </div>

      </div>

    </section><!-- /Testimonials Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Contact</span>
        <h2>Let's Connect</h2>
        <p>Notre équipe est à votre écoute pour répondre à toutes vos questions et vous accompagner dans votre projet d’immigration ou de voyage.Contactez VisaFly dès aujourd’hui pour obtenir des conseils personnalisés et débuter votre démarche en toute confiance.</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-5">

            <div class="info-item">
              <div class="info-icon">
                <i class="bi bi-chat-dots"></i>
              </div>
              <div class="info-content">
                <h4>Connectons-nous</h4>
                <p>Nous sommes ici pour discuter de votre vision et explorer comment nous pouvons la concrétiser ensemble.</p>
              </div>
            </div>

            <div class="contact-details">

              <div class="detail-item">
                <div class="detail-icon">
                  <i class="bi bi-envelope-open"></i>
                </div>
                <div class="detail-content">
                  <span class="detail-label">Envoyez-nous un e-mail</span>
                  <span class="detail-value">visaflypro@gmail.com</span>
                </div>
              </div>

              <div class="detail-item">
                <div class="detail-icon">
                  <i class="bi bi-telephone-outbound"></i>
                </div>
                <div class="detail-content">
                  <span class="detail-label">Appelez-nous</span>
                  <span class="detail-value">+237 651 350 338</span>
                </div>
              </div>

              <div class="detail-item">
                <div class="detail-icon">
                  <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div class="detail-content">
                  <span class="detail-label">Visitez nous</span>
                  <span class="detail-value">Jouvence<br>Yaounde ,Cameroun</span>
                </div>
              </div>

            </div>

          </div>

          <div class="col-lg-7">
            <div class="form-wrapper">
              <div class="form-header">
                <h3>Envoyez-nous un message</h3>
              </div>

              <form action="forms/contact.php" method="post" class="php-email-form">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nom</label>
                      <input type="text" name="name" required="">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Address Email</label>
                      <input type="email" name="email" required="">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Objet</label>
                  <input type="text" name="subject" required="">
                </div>

                <div class="form-group">
                  <label for="projectMessage">Message</label>
                  <textarea name="message" id="projectMessage" rows="5" required=""></textarea>
                </div>

                <div class="my-3">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>
                </div>

                <button type="submit" class="submit-btn">
                  <span>Envoyer</span>
                  <i class="bi bi-arrow-right"></i>
                </button>

              </form>

            </div>

          </div>

        </div>
      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">VisaFly</span>
          </a>
          <p> Nous ne vous vendons pas un rêve, nous construisons votre avenir.</p>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Liens utiles</h4>
          <ul>
            <li><a href="#">Acceuil</a></li>
            <li><a href="#">À propos de nous</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Conditions d'utilisation</a></li>
            <li><a href="#">Politique de confidentialité</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Nos services</h4>
          <ul>
            <li><a href="#">Web Design</a></li>
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Product Management</a></li>
            <li><a href="#">Marketing</a></li>
            <li><a href="#">Graphic Design</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
          <h4>Contact Us</h4>
          <p>A108 Adam Street</p>
          <p>New York, NY 535022</p>
          <p>United States</p>
          <p class="mt-4"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
          <p><strong>Email:</strong> <span>info@example.com</span></p>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">VisaFly</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
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