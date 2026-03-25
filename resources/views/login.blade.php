<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Connexion — VisaFly</title>
  <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-social/bootstrap-social.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel='shortcut icon' type='image/x-icon' href='{{ asset("assets/img/favicon.ico") }}' />
  <style>
    * { box-sizing: border-box; }

    body {
      background: #f0f4f8;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: system-ui, sans-serif;
    }

    /* ══════════════════════════════
       NAVBAR
    ══════════════════════════════ */
    .vf-navbar {
      background: #fff;
      border-bottom: 2px solid #F5A623;
      box-shadow: 0 2px 16px rgba(27,58,107,.08);
      padding: 0 2rem;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      flex-shrink: 0;
    }

    .vf-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .vf-logo-icon {
      width: 36px; height: 36px;
      background: #1B3A6B;
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .vf-logo-text {
      font-size: 20px;
      font-weight: 800;
      color: #1B3A6B;
      letter-spacing: -0.3px;
      line-height: 1;
    }
    .vf-logo-text span { color: #F5A623; }

    .vf-nav-links {
      display: flex;
      align-items: center;
      gap: 4px;
      list-style: none;
      margin: 0; padding: 0;
    }
    .vf-nav-links li a {
      display: block;
      padding: 6px 12px;
      font-size: 13px;
      font-weight: 500;
      color: #1B3A6B;
      text-decoration: none;
      border-radius: 6px;
      transition: all .2s;
      position: relative;
    }
    .vf-nav-links li a::after {
      content: '';
      position: absolute;
      bottom: 0; left: 50%;
      transform: translateX(-50%);
      width: 0; height: 2px;
      background: #F5A623;
      border-radius: 2px;
      transition: width .3s;
    }
    .vf-nav-links li a:hover {
      color: #F5A623;
      background: rgba(245,166,35,.07);
    }
    .vf-nav-links li a:hover::after { width: 55%; }

    .vf-btn-register {
      padding: 8px 20px;
      background: #F5A623;
      color: #1B3A6B !important;
      border-radius: 20px;
      font-weight: 700 !important;
      font-size: 13px !important;
      transition: all .2s !important;
    }
    .vf-btn-register:hover {
      background: #e09610 !important;
      transform: translateY(-1px);
    }
    .vf-btn-register::after { display: none !important; }

    /* ══════════════════════════════
       PAGE CONTENU
    ══════════════════════════════ */
    .login-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      position: relative;
      overflow: hidden;
    }

    /* Cercles décoratifs en arrière-plan */
    .login-wrapper::before {
      content: '';
      position: absolute;
      top: -100px; right: -100px;
      width: 400px; height: 400px;
      border-radius: 50%;
      background: rgba(27,58,107,.05);
      pointer-events: none;
    }
    .login-wrapper::after {
      content: '';
      position: absolute;
      bottom: -80px; left: -80px;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(245,166,35,.07);
      pointer-events: none;
    }

    /* ══════════════════════════════
       CARTE LOGIN
    ══════════════════════════════ */
    .login-card {
      background: #fff;
      border-radius: 20px;
      border: 1px solid rgba(27,58,107,.1);
      box-shadow: 0 20px 60px rgba(27,58,107,.12);
      width: 100%;
      max-width: 440px;
      overflow: hidden;
      position: relative;
      z-index: 1;
    }

    /* En-tête carte */
    .login-card-header {
      background: linear-gradient(135deg, #1B3A6B 0%, #152d54 100%);
      padding: 28px 32px 24px;
      position: relative;
      overflow: hidden;
    }
    .login-card-header::before {
      content: '';
      position: absolute;
      top: -30px; right: -30px;
      width: 120px; height: 120px;
      border-radius: 50%;
      background: rgba(245,166,35,.15);
    }
    .login-card-header::after {
      content: '';
      position: absolute;
      bottom: -20px; left: 20px;
      width: 80px; height: 80px;
      border-radius: 50%;
      background: rgba(255,255,255,.05);
    }
    .login-card-header h2 {
      font-size: 22px;
      font-weight: 800;
      color: #fff;
      margin: 0 0 4px;
      position: relative; z-index: 1;
    }
    .login-card-header p {
      font-size: 13px;
      color: rgba(255,255,255,.6);
      margin: 0;
      position: relative; z-index: 1;
    }
    .header-icon {
      width: 48px; height: 48px;
      background: rgba(245,166,35,.2);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 14px;
      position: relative; z-index: 1;
    }

    /* Corps carte */
    .login-card-body { padding: 28px 32px; }

    /* Alertes erreur */
    .alert-vf {
      background: rgba(226,75,74,.08);
      border: 1px solid rgba(226,75,74,.3);
      border-radius: 10px;
      padding: 12px 14px;
      font-size: 13px;
      color: #a32d2d;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* Labels */
    .vf-label {
      font-size: 12px;
      font-weight: 600;
      color: #1B3A6B;
      margin-bottom: 6px;
      display: block;
    }

    /* Champs */
    .vf-input {
      width: 100%;
      border: 1.5px solid #e8e8e8 !important;
      border-radius: 10px !important;
      padding: 11px 14px 11px 42px !important;
      font-size: 14px !important;
      color: #333 !important;
      background: #fafafa !important;
      outline: none !important;
      transition: all .2s ease !important;
      height: auto !important;
    }
    .vf-input:focus {
      border-color: #F5A623 !important;
      background: #fff !important;
      box-shadow: 0 0 0 3px rgba(245,166,35,.12) !important;
    }
    .input-icon-wrap {
      position: relative;
      margin-bottom: 16px;
    }
    .input-icon {
      position: absolute;
      left: 13px; top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 15px;
      pointer-events: none;
    }
    .input-icon-wrap:focus-within .input-icon { color: #F5A623; }

    /* Lien mot de passe */
    .forgot-link {
      font-size: 12px;
      color: #1B3A6B;
      text-decoration: none;
      font-weight: 500;
    }
    .forgot-link:hover { color: #F5A623; text-decoration: underline; }

    /* Remember me */
    .remember-label {
      font-size: 13px;
      color: #555;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }
    .remember-label input[type=checkbox] {
      width: 16px; height: 16px;
      accent-color: #1B3A6B;
      cursor: pointer;
    }

    /* Bouton connexion */
    .btn-vf-login {
      width: 100%;
      background: #1B3A6B;
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 13px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      transition: all .25s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 20px;
    }
    .btn-vf-login:hover {
      background: #F5A623;
      color: #1B3A6B;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(245,166,35,.35);
    }

    /* Séparateur social */
    .social-sep {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 20px 0;
    }
    .social-sep::before,
    .social-sep::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #eee;
    }
    .social-sep span {
      font-size: 12px;
      color: #bbb;
      white-space: nowrap;
    }

    /* Boutons sociaux */
    .btn-social-vf {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      border: 1.5px solid #e8e8e8;
      background: #fff;
      color: #333;
      transition: all .2s;
      text-decoration: none;
    }
    .btn-social-vf:hover {
      border-color: #1B3A6B;
      background: rgba(27,58,107,.04);
      color: #1B3A6B;
    }
    .btn-social-vf.facebook { color: #1877f2; }
    .btn-social-vf.facebook:hover { border-color: #1877f2; background: rgba(24,119,242,.05); }
    .btn-social-vf.twitter  { color: #1da1f2; }
    .btn-social-vf.twitter:hover  { border-color: #1da1f2; background: rgba(29,161,242,.05); }

    /* Lien inscription */
    .register-link {
      text-align: center;
      margin-top: 20px;
      font-size: 13px;
      color: #888;
    }
    .register-link a {
      color: #1B3A6B;
      font-weight: 600;
      text-decoration: none;
    }
    .register-link a:hover { color: #F5A623; text-decoration: underline; }

    /* Responsive mobile */
    @media (max-width: 480px) {
      .vf-nav-links { display: none; }
      .login-card-header { padding: 22px 22px 18px; }
      .login-card-body  { padding: 22px; }
    }
  </style>
</head>
<body>

  <div class="loader"></div>

  {{-- ══ NAVBAR ══ --}}
  <nav class="vf-navbar">

    <a href="{{ url('/') }}" class="vf-logo">
      <div class="vf-logo-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="M12 2L2 7l10 5 10-5-10-5z" fill="#F5A623"/>
          <path d="M2 17l10 5 10-5M2 12l10 5 10-5"
                stroke="#F5A623" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </div>
      <span class="vf-logo-text">Visa<span>Fly</span></span>
    </a>

    <ul class="vf-nav-links">
      <li><a href="{{ url('/') }}">Accueil</a></li>
      <li><a href="{{ url('/#about') }}">À propos</a></li>
      <li><a href="{{ url('/#services') }}">Nos services</a></li>
      <li><a href="{{ url('/#langues') }}">Langues</a></li>
      <li><a href="{{ url('/#contact') }}">Contact</a></li>
    </ul>

    <a href="{{ route('register') }}" class="vf-btn-register vf-nav-links">
      <i class="bi bi-person-plus me-1"></i> Créer un compte
    </a>

  </nav>

  {{-- ══ CONTENU LOGIN ══ --}}
  <div class="login-wrapper">
    <div class="login-card">

      {{-- En-tête --}}
      <div class="login-card-header">
        <div class="header-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"
                  stroke="#F5A623" stroke-width="2" stroke-linecap="round"/>
            <circle cx="12" cy="7" r="4" stroke="#F5A623" stroke-width="2"/>
          </svg>
        </div>
        <h2>Bienvenue !</h2>
        <p>Connectez-vous à votre espace VisaFly</p>
      </div>

      {{-- Corps --}}
      <div class="login-card-body">

        {{-- Erreur de connexion --}}
        @if($errors->any())
          <div class="alert-vf">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert-vf">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="needs-validation" novalidate>
          @csrf

          {{-- Email --}}
          <div>
            <label class="vf-label" for="email">Adresse email</label>
            <div class="input-icon-wrap">
              <i class="bi bi-envelope input-icon"></i>
              <input id="email" type="email"
                     class="form-control vf-input"
                     name="email"
                     value="{{ old('email') }}"
                     placeholder="votre@email.com"
                     tabindex="1" required autofocus>
            </div>
          </div>

          {{-- Mot de passe --}}
          <div>
            <div class="d-flex align-items-center justify-content-between mb-1">
              <label class="vf-label mb-0" for="password">Mot de passe</label>
              <a href="#" class="forgot-link">Mot de passe oublié ?</a>
            </div>
            <div class="input-icon-wrap">
              <i class="bi bi-lock input-icon"></i>
              <input id="password" type="password"
                     class="form-control vf-input"
                     name="password"
                     placeholder="••••••••"
                     tabindex="2" required>
            </div>
          </div>

          {{-- Se souvenir --}}
          <label class="remember-label">
            <input type="checkbox" name="remember" tabindex="3">
            Se souvenir de moi
          </label>

          {{-- Bouton connexion --}}
          <button type="submit" class="btn-vf-login" tabindex="4">
            <i class="bi bi-box-arrow-in-right"></i>
            Se connecter
          </button>

        </form>

        {{-- Séparateur social --}}
        <div class="social-sep">
          <span>ou continuer avec</span>
        </div>

        {{-- Boutons sociaux --}}
        <div class="d-flex gap-2">
          <a href="#" class="btn-social-vf facebook">
            <i class="fab fa-facebook"></i> Facebook
          </a>
          <a href="#" class="btn-social-vf twitter">
            <i class="fab fa-twitter"></i> Twitter
          </a>
        </div>

        {{-- Lien inscription --}}
        <div class="register-link">
          Pas encore de compte ?
          <a href="{{ route('register') }}">Créer un compte</a>
        </div>

      </div>
    </div>
  </div>

  <script src="{{ asset('assets/js/app.min.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html>