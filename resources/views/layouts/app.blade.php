<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'VisaFly')</title>

  {{-- Bootstrap --}}
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">

  {{-- CSS custom --}}
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

  @stack('styles')
</head>
<body>

  {{-- Navbar VisaFly --}}
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{ url('/') }}" class="logo d-flex align-items-center gap-2" style="text-decoration:none;">
        <div style="width:34px;height:34px;background:#1B3A6B;border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M12 2L2 7l10 5 10-5-10-5z" fill="#F5A623"/>
            <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke="#F5A623" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </div>
        <h5 class="sitename mb-0" style="font-size:19px;font-weight:800;color:#1B3A6B;letter-spacing:-.3px;">
          Visa<span style="color:#F5A623;">Fly</span>
        </h5>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ url('/') }}">Accueil</a></li>
          <li><a href="{{ url('/#about') }}">À propos</a></li>
          <li><a href="{{ url('/#services') }}">Nos services</a></li>
          <li><a href="{{ url('/#contact') }}">Contact</a></li>
          <li><a href="{{ route('login') }}" class="btn-nav-login">Se connecter</a></li>
          <li>
            <a href="{{ route('consultation') }}" class="btn-nav-consult">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
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

  {{-- Contenu de la page --}}
  <main style="padding-top:80px; min-height:calc(100vh - 80px);">
    @yield('content')
  </main>

  {{-- Footer minimal --}}
  <footer style="background:#0f2548;padding:16px 0;text-align:center;">
    <p style="color:rgba(255,255,255,.4);font-size:12px;margin:0;">
      © {{ date('Y') }} <strong style="color:rgba(255,255,255,.6);">VisaFly International</strong> — Tous droits réservés
    </p>
  </footer>

  {{-- Scripts --}}
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script>AOS.init();</script>

  @stack('scripts')

</body>
</html>