<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>VisaFly</title>
  <meta name="description" content="@yield('meta_description', 'Agence VisaFly spécialisée en immigration Canada depuis le Cameroun. Accompagnement complet visa et études.')">

  <meta name="keywords" content="visa canada cameroun, immigration canada douala, agence visa cameroun">

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

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <!-- Logo -->
      <a href="index.html" class="logo d-flex align-items-center gap-2" style="text-decoration:none;">
        <div style="width:38px;height:38px;background:#1B3A6B;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <img src="assets/img/logo.png" alt="VisaFly" style="width:24px;height:24px;object-fit:contain;filter:brightness(0) invert(1);">
        </div>
        <h5 class="sitename mb-0" style="font-size:20px;font-weight:800;color:#1B3A6B;letter-spacing:-0.3px;">
          Visa<span style="color:#F5A623;">Fly</span>
        </h5>
      </a>

      <!-- Navigation -->
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Accueil</a></li>
          <li><a href="#about">À propos</a></li>
          <li><a href="#langues">Les Langues</a></li>
          <li><a href="#services">Nos services</a></li>
          <li><a href="#portfolio">Réalisations</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="#contact">Contact</a></li>
          <li>
            <a href="{{ route('login') }}" class="btn-nav-login">Se connecter</a>
          </li>
          <li>
            <a href="{{ route('consultation') }}" class="btn-nav-consult">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" style="flex-shrink:0;">
                <path d="M22 2L11 13" stroke="#1B3A6B" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M22 2L15 22l-4-9-9-4 20-7z" stroke="#1B3A6B" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Se consulter
            </a>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

        <div class="main-content" style="flex:1; overflow-y:auto; padding:2rem; background:#F8F9FA;">
          @yield('space-work')
        </div>

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