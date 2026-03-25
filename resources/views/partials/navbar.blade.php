{{--
  resources/views/partials/navbar.blade.php
  Inclure avec : @include('partials.navbar')
--}}

{{-- ══ BARRE D'ANNONCE ══ --}}
<div class="vf-announce-bar">
  <span>
    🎯 Nouveau : Préparez dès maintenant votre TCF/TEF sur VisaFly !
    Profitez de nos séries d'entraînement complètes.
  </span>
  <a href="{{ route('tcf.index') }}">Commencer →</a>
</div>

{{-- ══ NAVBAR PRINCIPALE ══ --}}
<header id="vf-header" class="vf-header">
  <div class="vf-header-inner">

    {{-- Logo --}}
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

    {{-- Liens navigation --}}
    <nav class="vf-nav">
      <ul class="vf-nav-list">
        <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Accueil</a></li>
        <li><a href="{{ url('/#about') }}">À propos</a></li>
        <li class="has-dropdown">
          <a href="#">Nos langues <i class="bi bi-chevron-down"></i></a>
          <div class="vf-dropdown">
            <a href="{{ route('tcf.index') }}"><i class="bi bi-book me-2"></i>TCF Canada</a>
            <a href="{{ route('tcf.index') }}"><i class="bi bi-book me-2"></i>TEF Canada</a>
            <a href="#"><i class="bi bi-mic me-2"></i>IELTS</a>
            <a href="#"><i class="bi bi-translate me-2"></i>TestDaF / Goethe</a>
          </div>
        </li>
        <li><a href="{{ url('/#services') }}">Nos services</a></li>
        
        <li><a href="{{ url('/#contact') }}">Contact</a></li>
      </ul>
    </nav>

    {{-- Actions droite --}}
    <div class="vf-actions">

      {{-- Sélecteur langue --}}
      <div class="vf-lang">
        <img src="https://flagcdn.com/w20/fr.png" alt="FR" width="18">
        <span>FR</span>
        <i class="bi bi-chevron-down"></i>
      </div>

      @guest
        {{-- Non connecté --}}
        <a href="{{ route('register') }}" class="vf-btn-register">
          Créer un compte
        </a>
        <a href="{{ route('login') }}" class="vf-btn-login">
          Se connecter
        </a>
      @endguest

      @auth
        {{-- Connecté — icône notification --}}
        <button class="vf-icon-btn" title="Notifications">
          <i class="bi bi-bell"></i>
          <span class="vf-notif-dot"></span>
        </button>

        {{-- Menu profil --}}
        <div class="vf-profile-menu">
          <button class="vf-avatar-btn" id="profileToggle">
            @if(Auth::user()->avatar)
              <img src="{{ asset('storage/'.Auth::user()->avatar) }}"
                   alt="{{ Auth::user()->name }}"
                   class="vf-avatar-img">
            @else
              <div class="vf-avatar-initials">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, strpos(Auth::user()->name, ' ') + 1, 1)) }}
              </div>
            @endif
          </button>

          <div class="vf-profile-dropdown" id="profileDropdown">
            {{-- En-tête profil --}}
            <div class="vf-profile-head">
              <div class="vf-profile-head-avatar">
                @if(Auth::user()->avatar)
                  <img src="{{ asset('storage/'.Auth::user()->avatar) }}" alt="">
                @else
                  <div class="vf-avatar-initials lg">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                  </div>
                @endif
              </div>
              <div>
                <div class="vf-profile-name">{{ Auth::user()->name }}</div>
                <div class="vf-profile-email">{{ Auth::user()->email }}</div>
              </div>
            </div>

            <div class="vf-dropdown-sep"></div>

            <a href="#" class="vf-profile-item">
              <i class="bi bi-person-circle"></i> Mon profil
            </a>
            <a href="{{ route('tcf.index') }}" class="vf-profile-item">
              <i class="bi bi-journal-check"></i> Mes épreuves TCF
            </a>
            <a href="{{ route('consultation') }}" class="vf-profile-item">
              <i class="bi bi-calendar-check"></i> Mes consultations
            </a>
            <a href="#" class="vf-profile-item">
              <i class="bi bi-gear"></i> Paramètres
            </a>

            <div class="vf-dropdown-sep"></div>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="vf-profile-item logout">
                <i class="bi bi-box-arrow-right"></i> Se déconnecter
              </button>
            </form>
          </div>
        </div>
      @endauth

      {{-- Hamburger mobile --}}
      <button class="vf-hamburger d-xl-none" id="mobileToggle">
        <i class="bi bi-list"></i>
      </button>

    </div>
  </div>
</header>

<style>
/* ════════════════════════════════════════
   BARRE D'ANNONCE
════════════════════════════════════════ */
.vf-announce-bar {
  background: #1B3A6B;
  color: rgba(255,255,255,.9);
  text-align: center;
  padding: 8px 20px;
  font-size: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  flex-wrap: wrap;
}
.vf-announce-bar a {
  color: #F5A623;
  font-weight: 700;
  text-decoration: none;
  white-space: nowrap;
}
.vf-announce-bar a:hover { text-decoration: underline; }

/* ════════════════════════════════════════
   HEADER
════════════════════════════════════════ */
.vf-header {
  background: #fff;
  border-bottom: 2px solid #F5A623;
  box-shadow: 0 2px 16px rgba(27,58,107,.07);
  position: sticky;
  top: 0;
  z-index: 999;
  width: 100%;
}
.vf-header-inner {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 24px;
  height: 66px;
  display: flex;
  align-items: center;
  gap: 16px;
}

/* ════════════════════════════════════════
   LOGO
════════════════════════════════════════ */
.vf-logo {
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  flex-shrink: 0;
}
.vf-logo-icon {
  width: 36px; height: 36px;
  background: #1B3A6B;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
}
.vf-logo-text {
  font-size: 20px; font-weight: 800;
  color: #1B3A6B; letter-spacing: -.3px;
}
.vf-logo-text span { color: #F5A623; }

/* ════════════════════════════════════════
   NAV LIENS
════════════════════════════════════════ */
.vf-nav { flex: 1; }
.vf-nav-list {
  display: flex; align-items: center;
  gap: 2px; list-style: none; margin: 0; padding: 0;
}
.vf-nav-list li { position: relative; }
.vf-nav-list li a {
  display: flex; align-items: center; gap: 4px;
  padding: 6px 11px;
  font-size: 13.5px; font-weight: 500;
  color: #1B3A6B; text-decoration: none;
  border-radius: 6px;
  transition: all .2s;
  position: relative;
  white-space: nowrap;
}
.vf-nav-list li a::after {
  content: '';
  position: absolute;
  bottom: 0; left: 50%;
  transform: translateX(-50%);
  width: 0; height: 2px;
  background: #F5A623; border-radius: 2px;
  transition: width .3s;
}
.vf-nav-list li a:hover,
.vf-nav-list li a.active {
  color: #F5A623;
  background: rgba(245,166,35,.07);
}
.vf-nav-list li a:hover::after,
.vf-nav-list li a.active::after { width: 55%; }
.vf-nav-list li a .bi-chevron-down {
  font-size: 10px; margin-top: 1px;
}

/* Dropdown nav */
.has-dropdown:hover .vf-dropdown { display: flex; }
.vf-dropdown {
  display: none;
  flex-direction: column;
  position: absolute;
  top: calc(100% + 8px); left: 0;
  background: #fff;
  border: 1px solid rgba(27,58,107,.1);
  border-radius: 12px;
  box-shadow: 0 12px 36px rgba(27,58,107,.12);
  padding: 8px;
  min-width: 200px;
  z-index: 100;
}
.vf-dropdown a {
  display: flex !important;
  align-items: center;
  padding: 9px 12px !important;
  font-size: 13px !important;
  color: #333 !important;
  border-radius: 8px !important;
  text-decoration: none;
  transition: all .15s;
  white-space: nowrap;
}
.vf-dropdown a::after { display: none !important; }
.vf-dropdown a:hover {
  background: rgba(27,58,107,.05) !important;
  color: #1B3A6B !important;
}

/* ════════════════════════════════════════
   ACTIONS DROITE
════════════════════════════════════════ */
.vf-actions {
  display: flex; align-items: center; gap: 8px;
  flex-shrink: 0;
}

/* Sélecteur langue */
.vf-lang {
  display: flex; align-items: center; gap: 4px;
  padding: 5px 10px;
  border: 1px solid rgba(27,58,107,.15);
  border-radius: 8px; cursor: pointer;
  font-size: 12px; font-weight: 600; color: #1B3A6B;
  transition: all .2s;
}
.vf-lang:hover { border-color: #F5A623; }
.vf-lang .bi-chevron-down { font-size: 9px; }

/* Boutons auth */
.vf-btn-register {
  padding: 8px 18px;
  background: #1B3A6B;
  color: #fff !important;
  border-radius: 20px;
  font-size: 13px; font-weight: 600;
  text-decoration: none;
  transition: all .2s;
  white-space: nowrap;
}
.vf-btn-register:hover {
  background: #152d54;
  transform: translateY(-1px);
}
.vf-btn-login {
  padding: 8px 18px;
  background: transparent;
  color: #1B3A6B !important;
  border: 1.5px solid #1B3A6B;
  border-radius: 20px;
  font-size: 13px; font-weight: 600;
  text-decoration: none;
  transition: all .2s;
  white-space: nowrap;
}
.vf-btn-login:hover {
  background: #1B3A6B;
  color: #fff !important;
}

/* ════════════════════════════════════════
   ICÔNE NOTIFICATION
════════════════════════════════════════ */
.vf-icon-btn {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: rgba(27,58,107,.06);
  border: 1px solid rgba(27,58,107,.1);
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; color: #1B3A6B; cursor: pointer;
  position: relative;
  transition: all .2s;
}
.vf-icon-btn:hover { background: rgba(27,58,107,.12); }
.vf-notif-dot {
  position: absolute;
  top: 6px; right: 6px;
  width: 8px; height: 8px;
  border-radius: 50%;
  background: #E24B4A;
  border: 2px solid #fff;
}

/* ════════════════════════════════════════
   MENU PROFIL
════════════════════════════════════════ */
.vf-profile-menu { position: relative; }

.vf-avatar-btn {
  width: 38px; height: 38px;
  border-radius: 50%;
  border: 2px solid #F5A623;
  padding: 0; overflow: hidden;
  cursor: pointer;
  background: #1B3A6B;
  transition: all .2s;
}
.vf-avatar-btn:hover { box-shadow: 0 0 0 3px rgba(245,166,35,.25); }

.vf-avatar-img {
  width: 100%; height: 100%;
  object-fit: cover; border-radius: 50%;
}
.vf-avatar-initials {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 700; color: #F5A623;
}
.vf-avatar-initials.lg { font-size: 18px; }

/* Dropdown profil */
.vf-profile-dropdown {
  display: none;
  position: absolute;
  top: calc(100% + 10px); right: 0;
  background: #fff;
  border: 1px solid rgba(27,58,107,.1);
  border-radius: 14px;
  box-shadow: 0 16px 48px rgba(27,58,107,.15);
  padding: 8px;
  min-width: 230px;
  z-index: 200;
}
.vf-profile-dropdown.open { display: block; }

/* En-tête dropdown profil */
.vf-profile-head {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px 12px;
}
.vf-profile-head-avatar {
  width: 40px; height: 40px;
  border-radius: 50%; overflow: hidden;
  background: #1B3A6B; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
}
.vf-profile-name {
  font-size: 14px; font-weight: 700; color: #1B3A6B;
}
.vf-profile-email {
  font-size: 11px; color: #888; margin-top: 1px;
}

.vf-dropdown-sep {
  height: 1px; background: #f0f0f0;
  margin: 4px 0;
}

.vf-profile-item {
  display: flex; align-items: center; gap: 10px;
  width: 100%; padding: 9px 12px;
  font-size: 13px; color: #333; font-weight: 500;
  border-radius: 8px; text-decoration: none;
  background: none; border: none; cursor: pointer;
  transition: all .15s; text-align: left;
}
.vf-profile-item:hover {
  background: rgba(27,58,107,.05);
  color: #1B3A6B;
}
.vf-profile-item i { font-size: 15px; color: #888; width: 18px; }
.vf-profile-item:hover i { color: #1B3A6B; }
.vf-profile-item.logout { color: #a32d2d; }
.vf-profile-item.logout i { color: #a32d2d; }
.vf-profile-item.logout:hover { background: rgba(226,75,74,.06); color: #a32d2d; }

/* ════════════════════════════════════════
   HAMBURGER MOBILE
════════════════════════════════════════ */
.vf-hamburger {
  width: 38px; height: 38px;
  border-radius: 8px;
  background: rgba(27,58,107,.06);
  border: none; cursor: pointer;
  font-size: 20px; color: #1B3A6B;
  display: none;
}

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width: 1199px) {
  .vf-nav { display: none; }
  .vf-hamburger { display: flex; align-items: center; justify-content: center; }
}
@media (max-width: 480px) {
  .vf-btn-register { display: none; }
  .vf-announce-bar { font-size: 11px; }
}
</style>

<script>
// ── Toggle menu profil ──
document.addEventListener('DOMContentLoaded', function () {
  const toggle   = document.getElementById('profileToggle');
  const dropdown = document.getElementById('profileDropdown');

  if (toggle && dropdown) {
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('open');
    });
    document.addEventListener('click', function () {
      dropdown.classList.remove('open');
    });
    dropdown.addEventListener('click', function (e) {
      e.stopPropagation();
    });
  }

  // ── Hamburger mobile ──
  const hamburger = document.getElementById('mobileToggle');
  const nav       = document.querySelector('.vf-nav');
  if (hamburger && nav) {
    hamburger.addEventListener('click', function () {
      nav.style.display = nav.style.display === 'block' ? 'none' : 'block';
      nav.style.position = 'absolute';
      nav.style.top = '66px';
      nav.style.left = '0'; nav.style.right = '0';
      nav.style.background = '#fff';
      nav.style.borderTop = '2px solid #F5A623';
      nav.style.padding = '12px';
      nav.style.boxShadow = '0 8px 24px rgba(27,58,107,.1)';
      nav.style.zIndex = '998';
    });
  }
});
</script>