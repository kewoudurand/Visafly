{{--
  resources/views/partials/navbar.blade.php
  @include('partials.navbar')

  ✅  NAVBAR HORIZONTALE UNIQUEMENT
  ✅  La sidebar verticale reste dans layouts/dashboard.blade.php
  ✅  Connecté  → avatar + cloche + dropdown profil
  ✅  Non conn. → boutons Créer un compte + Se connecter
--}}

{{-- ══ BARRE D'ANNONCE ══ --}}
<div class="vf-announce-bar">
  <span>🎯 Nouveau : Préparez dès maintenant votre TCF/TEF sur VisaFly ! Profitez de nos séries d'entraînement complètes.</span>
  <a href="{{ route('langues.index') }}">Commencer →</a>
</div>

{{-- ══ HEADER ══ --}}
<header id="vf-header" class="vf-header">
  <div class="vf-header-inner">

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="vf-logo">
      <div class="vf-logo-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="M12 2L2 7l10 5 10-5-10-5z" fill="#F5A623"/>
          <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke="#F5A623" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </div>
      <span class="vf-logo-text">Visa<span>Fly</span></span>
    </a>

    {{-- ══ LIENS HORIZONTAUX (public) ══ --}}
    <nav class="vf-nav">
      <ul class="vf-nav-list">
        <li><a href="{{ url('/') }}"class="{{ request()->is('/') ? 'active' : '' }}">Accueil</a></li>
        <li><a href="{{ url('/#about') }}">À propos</a></li>
            <li class="has-dropdown">
              <a href="#">Nos langues <i class="bi bi-chevron-down"></i></a>
              <div class="vf-dropdown">
                <a href="{{ route('langues.series', 'tcf') }}">
                  <i class="bi bi-pencil-square me-2"></i>TCF Canada
                </a>
                <a href="{{ route('langues.series', 'tef') }}">
                  <i class="bi bi-pencil-square me-2"></i>TEF Canada
                </a>
                <a href="{{ route('langues.series', 'ielts') }}">
                  <i class="bi bi-translate me-2"></i>IELTS
                </a>
                <a href="{{ route('langues.series', 'goethe') }}">
                  <i class="bi bi-globe me-2"></i>Goethe-Zertifikat
                </a>
              </div>
            </li>
        <li><a href="{{ url('/#services') }}">Nos services</a></li>
        <li><a href="{{ url('/#contact') }}">Contact</a></li>
      </ul>
    </nav>

    {{-- ══ ACTIONS DROITE ══ --}}
    <div class="vf-actions">

      {{-- Langue --}}
      <div class="vf-lang">
        <img src="https://flagcdn.com/w20/fr.png" alt="FR" width="18" style="border-radius:2px;">
        <span>FR</span>
        <i class="bi bi-chevron-down" style="font-size:9px;"></i>
      </div>

      {{-- ── NON CONNECTÉ ── --}}
      @guest
        <a href="{{ route('auth.register.show') }}" class="vf-btn-primary">Créer un compte</a>
        <a href="{{ route('login') }}"    class="vf-btn-outline">Se connecter</a>
      @endguest

      {{-- ── CONNECTÉ ── --}}
      @auth
        {{-- Cloche notification --}}
        <button class="vf-icon-btn" title="Notifications">
          <i class="bi bi-bell" style="font-size:16px;"></i>
          <span class="vf-notif-dot"></span>
        </button>

        {{-- Avatar + dropdown --}}
        <div class="vf-profile-menu">

          <button class="vf-avatar-btn" id="profileToggle" title="{{ Auth::user()->first_name }}">
            @if(Auth::user()->avatar)
              <img src="{{ asset('storage/'.Auth::user()->avatar) }}"
                   alt="{{ Auth::user()->first_name }}" class="vf-avatar-img">
            @else
              {{-- Initiales depuis name (ex: "Admin VisaFly" → "AV") --}}
              <div class="vf-avatar-initials">
                @php
                  $parts = explode(' ', trim(Auth::user()->first_name));
                  echo strtoupper(substr($parts[0], 0, 1));
                  echo isset($parts[1]) ? strtoupper(substr($parts[1], 0, 1)) : '';
                @endphp
              </div>
            @endif
          </button>

          {{-- Dropdown profil --}}
          <div class="vf-profile-dropdown" id="profileDropdown">

            {{-- Tête --}}
            <div class="vf-profile-head">
              <div class="vf-profile-head-avatar">
                @if(Auth::user()->avatar)
                  <img src="{{ asset('storage/'.Auth::user()->avatar) }}" alt="">
                @else
                  <div class="vf-avatar-initials lg">
                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                  </div>
                @endif
              </div>
              <div style="min-width:0;">
                <div class="vf-profile-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                <div class="vf-profile-email">{{ Auth::user()->email }}</div>
                {{-- Badge rôle --}}
                @if(Auth::user()->roles->isNotEmpty())
                  <div style="margin-top:4px;">
                    <span style="font-size:10px;padding:2px 8px;border-radius:10px;
                                 background:rgba(27,58,107,.1);color:#1B3A6B;font-weight:600;">
                      {{ Auth::user()->roles->first()->name }}
                    </span>
                  </div>
                @endif
              </div>
            </div>

            <div class="vf-dd-sep"></div>

          {{-- Dashboard — route dynamique selon le rôle --}}
          <a href="{{ Auth::user()->hasRole(['super-admin', 'admin', 'consultant'])
              ? route('admin.users.index')
              : route('dashboard') }}"
            class="vf-dd-item">
            <i class="bi bi-speedometer2"></i>
            {{ Auth::user()->hasRole(['super-admin', 'admin', 'consultant'])
                ? 'Administration'
                : 'Mon espace' }}
          </a>
            @can('pass test')
            <a href="{{ route('langues.index') }}" class="vf-dd-item">
              <i class="bi bi-journal-check"></i> Mes épreuves TCF
            </a>
            @endcan

            @can('book consultation')
            <a href="{{ route('consultations.create') }}" class="vf-dd-item">
              <i class="bi bi-calendar-check"></i> Mes consultations
            </a>
            @endcan

            <div class="vf-dd-sep"></div>

            <a href="{{route('profil.edit')}}" class="vf-dd-item">
              <i class="bi bi-gear"></i> Paramètres
            </a>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="vf-dd-item vf-dd-logout">
                <i class="bi bi-box-arrow-right"></i> Se déconnecter
              </button>
            </form>

          </div>
        </div>
      @endauth

      {{-- Hamburger mobile --}}
      <button class="vf-hamburger d-xl-none" id="mobileToggle">
        <i class="bi bi-list" style="font-size:20px;"></i>
      </button>

    </div>
  </div>
</header>

<style>
/* ─── Announce bar ─── */
.vf-announce-bar{background:#1B3A6B;color:rgba(255,255,255,.9);text-align:center;padding:8px 20px;font-size:13px;display:flex;align-items:center;justify-content:center;gap:12px;flex-wrap:wrap;}
.vf-announce-bar a{color:#F5A623;font-weight:700;text-decoration:none;white-space:nowrap;}
.vf-announce-bar a:hover{text-decoration:underline;}

/* ─── Header ─── */
.vf-header{background:#fff;border-bottom:2px solid #F5A623;box-shadow:0 2px 16px rgba(27,58,107,.07);position:sticky;top:0;z-index:999;width:100%;}
.vf-header-inner{max-width:1280px;margin:0 auto;padding:0 24px;height:66px;display:flex;align-items:center;gap:16px;}

/* ─── Logo ─── */
.vf-logo{display:flex;align-items:center;gap:8px;text-decoration:none;flex-shrink:0;}
.vf-logo-icon{width:36px;height:36px;background:#1B3A6B;border-radius:9px;display:flex;align-items:center;justify-content:center;}
.vf-logo-text{font-size:20px;font-weight:800;color:#1B3A6B;letter-spacing:-.3px;}
.vf-logo-text span{color:#F5A623;}

/* ─── Nav horizontale ─── */
.vf-nav{flex:1;}
.vf-nav-list{display:flex;align-items:center;gap:2px;list-style:none;margin:0;padding:0;}
.vf-nav-list li{position:relative;}
.vf-nav-list li a{display:flex;align-items:center;gap:4px;padding:6px 12px;font-size:13.5px;font-weight:500;color:#1B3A6B;text-decoration:none;border-radius:6px;transition:all .2s;position:relative;white-space:nowrap;}
.vf-nav-list li a::after{content:'';position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:0;height:2px;background:#F5A623;border-radius:2px;transition:width .3s;}
.vf-nav-list li a:hover,.vf-nav-list li a.active{color:#F5A623;background:rgba(245,166,35,.07);}
.vf-nav-list li a:hover::after,.vf-nav-list li a.active::after{width:55%;}

/* Dropdown nav */
.has-dropdown:hover .vf-dropdown{display:flex;}
.vf-dropdown{display:none;flex-direction:column;position:absolute;top:calc(100% + 8px);left:0;background:#fff;border:1px solid rgba(27,58,107,.1);border-radius:12px;box-shadow:0 12px 36px rgba(27,58,107,.12);padding:8px;min-width:200px;z-index:100;}
.vf-dropdown a{display:flex!important;align-items:center;padding:9px 12px!important;font-size:13px!important;color:#333!important;border-radius:8px!important;text-decoration:none;transition:all .15s;white-space:nowrap;}
.vf-dropdown a::after{display:none!important;}
.vf-dropdown a:hover{background:rgba(27,58,107,.05)!important;color:#1B3A6B!important;}

/* ─── Actions droite ─── */
.vf-actions{display:flex;align-items:center;gap:8px;flex-shrink:0;}

.vf-lang{display:flex;align-items:center;gap:5px;padding:6px 11px;border:1px solid rgba(27,58,107,.18);border-radius:8px;cursor:pointer;font-size:12px;font-weight:600;color:#1B3A6B;transition:all .2s;user-select:none;}
.vf-lang:hover{border-color:#F5A623;}

.vf-btn-primary{padding:8px 18px;background:#1B3A6B;color:#fff!important;border-radius:20px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;white-space:nowrap;}
.vf-btn-primary:hover{background:#152d54;transform:translateY(-1px);}
.vf-btn-outline{padding:8px 18px;background:transparent;color:#1B3A6B!important;border:1.5px solid #1B3A6B;border-radius:20px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;white-space:nowrap;}
.vf-btn-outline:hover{background:#1B3A6B;color:#fff!important;}

/* ─── Cloche ─── */
.vf-icon-btn{width:38px;height:38px;border-radius:50%;background:rgba(27,58,107,.06);border:1px solid rgba(27,58,107,.1);display:flex;align-items:center;justify-content:center;color:#1B3A6B;cursor:pointer;position:relative;transition:all .2s;}
.vf-icon-btn:hover{background:rgba(27,58,107,.12);}
.vf-notif-dot{position:absolute;top:6px;right:6px;width:8px;height:8px;border-radius:50%;background:#E24B4A;border:2px solid #fff;}

/* ─── Avatar ─── */
.vf-profile-menu{position:relative;}
.vf-avatar-btn{width:38px;height:38px;border-radius:50%;border:2px solid #F5A623;padding:0;overflow:hidden;cursor:pointer;background:#1B3A6B;transition:all .2s;}
.vf-avatar-btn:hover{box-shadow:0 0 0 3px rgba(245,166,35,.28);}
.vf-avatar-img{width:100%;height:100%;object-fit:cover;border-radius:50%;}
.vf-avatar-initials{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#F5A623;}
.vf-avatar-initials.lg{font-size:18px;}

/* ─── Dropdown profil ─── */
.vf-profile-dropdown{display:none;position:absolute;top:calc(100% + 10px);right:0;background:#fff;border:1px solid rgba(27,58,107,.1);border-radius:14px;box-shadow:0 16px 48px rgba(27,58,107,.15);padding:8px;min-width:240px;z-index:200;}
.vf-profile-dropdown.open{display:block;}

.vf-profile-head{display:flex;align-items:center;gap:10px;padding:10px 12px 12px;}
.vf-profile-head-avatar{width:42px;height:42px;border-radius:50%;overflow:hidden;background:#1B3A6B;flex-shrink:0;display:flex;align-items:center;justify-content:center;}
.vf-profile-name{font-size:14px;font-weight:700;color:#1B3A6B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;}
.vf-profile-email{font-size:11px;color:#888;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;}
.vf-dd-sep{height:1px;background:#f0f0f0;margin:4px 0;}

.vf-dd-item{display:flex;align-items:center;gap:10px;width:100%;padding:9px 12px;font-size:13px;color:#333;font-weight:500;border-radius:8px;text-decoration:none;background:none;border:none;cursor:pointer;transition:all .15s;text-align:left;}
.vf-dd-item:hover{background:rgba(27,58,107,.05);color:#1B3A6B;}
.vf-dd-item i{font-size:15px;color:#888;width:18px;flex-shrink:0;}
.vf-dd-item:hover i{color:#1B3A6B;}
.vf-dd-logout{color:#a32d2d!important;}
.vf-dd-logout i{color:#a32d2d!important;}
.vf-dd-logout:hover{background:rgba(226,75,74,.06)!important;color:#a32d2d!important;}

/* ─── Hamburger ─── */
.vf-hamburger{width:38px;height:38px;border-radius:8px;background:rgba(27,58,107,.06);border:none;cursor:pointer;color:#1B3A6B;display:none;align-items:center;justify-content:center;}

/* ─── Responsive ─── */
@media(max-width:1199px){
  .vf-nav{display:none;}
  .vf-hamburger{display:flex;}
}
@media(max-width:640px){
  .vf-btn-primary{display:none;}
  .vf-announce-bar{font-size:11px;}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ── dropdown profil ── */
  const toggle   = document.getElementById('profileToggle');
  const dropdown = document.getElementById('profileDropdown');
  if (toggle && dropdown) {
    toggle.addEventListener('click', e => { e.stopPropagation(); dropdown.classList.toggle('open'); });
    document.addEventListener('click', () => dropdown.classList.remove('open'));
    dropdown.addEventListener('click', e => e.stopPropagation());
  }

  /* ── hamburger mobile ── */
  const burger = document.getElementById('mobileToggle');
  const navEl  = document.querySelector('.vf-nav');
  if (burger && navEl) {
    burger.addEventListener('click', function () {
      const open = navEl.classList.toggle('vf-nav-open');
      if (open) {
        Object.assign(navEl.style, {
          display:'block', position:'absolute', top:'66px',
          left:'0', right:'0', background:'#fff',
          borderTop:'2px solid #F5A623', padding:'12px',
          boxShadow:'0 8px 24px rgba(27,58,107,.1)', zIndex:'998'
        });
      } else {
        navEl.removeAttribute('style');
      }
    });
  }

});
</script>