<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') — VisaFly</title>
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <style>
  *{box-sizing:border-box;}
  body{background:#f0f4f8;}
  .dash-wrapper{display:flex;min-height:calc(100vh - 66px);}

  /* ── Sidebar ── */
  .dash-sidebar{width:230px;background:#1B3A6B;flex-shrink:0;display:flex;flex-direction:column;
                position:sticky;top:66px;height:calc(100vh - 66px);overflow-y:auto;
                transition:transform .3s cubic-bezier(.4,0,.2,1);z-index:500;}

  .sidebar-profile{text-align:center;padding:1.2rem 1rem;border-bottom:1px solid rgba(255,255,255,.1);}
  .sidebar-avatar{width:52px;height:52px;border-radius:50%;background:rgba(245,166,35,.2);
                  border:2px solid #F5A623;display:flex;align-items:center;justify-content:center;
                  margin:0 auto 8px;overflow:hidden;font-size:18px;font-weight:700;color:#F5A623;}
  .sidebar-avatar img{width:100%;height:100%;object-fit:cover;}
  .sidebar-name{font-size:13px;font-weight:600;color:#fff;}
  .sidebar-role{font-size:10px;color:rgba(255,255,255,.45);margin-top:2px;}

  .sidebar-nav{padding:.6rem 0;flex:1;}
  .sidebar-section{font-size:10px;font-weight:600;color:rgba(255,255,255,.28);text-transform:uppercase;
                   letter-spacing:.8px;padding:14px 18px 5px;display:block;}
  .sidebar-link{display:flex;align-items:center;gap:10px;padding:9px 18px;font-size:13px;
                color:rgba(255,255,255,.7);text-decoration:none;border-left:3px solid transparent;
                transition:all .18s;white-space:nowrap;background:none;border-right:none;
                border-top:none;border-bottom:none;width:100%;cursor:pointer;text-align:left;}
  .sidebar-link:hover,.sidebar-link.active{color:#F5A623;background:rgba(245,166,35,.1);border-left-color:#F5A623;}
  .sidebar-link i{font-size:15px;width:18px;flex-shrink:0;}

  .sidebar-logout{border-top:1px solid rgba(255,255,255,.1);padding:.6rem 0;}
  .sidebar-close-btn{display:none;position:absolute;top:12px;right:12px;width:30px;height:30px;
                     border-radius:7px;background:rgba(255,255,255,.1);border:none;cursor:pointer;
                     color:#fff;font-size:15px;align-items:center;justify-content:center;}

  /* Overlay */
  .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
                   z-index:1040;backdrop-filter:blur(2px);}
  .sidebar-overlay.active{display:block;}

  /* Main */
  .dash-main{flex:1;padding:2rem;overflow-y:auto;min-width:0;}

  /* Mobile topbar */
  .dash-mobile-topbar{display:none;align-items:center;gap:12px;margin-bottom:16px;
                      padding-bottom:14px;border-bottom:1px solid #eee;}
  .dash-mobile-topbar h2{margin:0;font-size:1.1rem;font-weight:800;color:#1B3A6B;}
  .dash-mobile-toggle{width:38px;height:38px;border-radius:8px;border:1px solid rgba(27,58,107,.15);
                      background:#fff;color:#1B3A6B;display:flex;align-items:center;
                      justify-content:center;cursor:pointer;font-size:18px;flex-shrink:0;}

  @media(max-width:991px){
    .dash-sidebar{position:fixed;top:0;left:0;bottom:0;height:100vh;width:260px;
                  transform:translateX(-100%);z-index:1050;padding-top:1rem;}
    .dash-sidebar.sidebar-open{transform:translateX(0);box-shadow:4px 0 24px rgba(0,0,0,.25);}
    .sidebar-close-btn{display:flex;}
    .dash-mobile-topbar{display:flex;}
  }
  @media(max-width:640px){.dash-main{padding:1rem;}}
  </style>
  @stack('styles')
</head>
<body>
  @include('partials.navbar')
  <div class="sidebar-overlay" id="sidebarOverlay"></div>
  <div class="dash-wrapper">

    <aside class="dash-sidebar" id="dashSidebar">
      <button class="sidebar-close-btn" id="sidebarCloseBtn"><i class="bi bi-x-lg"></i></button>

      <div class="sidebar-profile mt-3">
        <div class="sidebar-avatar">
          @if(Auth::user()->avatar)
            <img src="{{ asset('storage/'.Auth::user()->avatar) }}" alt="">
          @else
            {{ strtoupper(substr(Auth::user()->first_name ?? '?', 0, 1)) }}
          @endif
        </div>
        <div class="sidebar-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
        <div class="sidebar-role">{{ Auth::user()->getRoleNames()->first() ?? 'Membre' }}</div>
      </div>

      <nav class="sidebar-nav">

        <a href="{{ route('dashboard.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i> Tableau de bord
        </a>

        {{-- ── ÉTUDIANT / USER ── --}}
        @if(Auth::user()->hasAnyRole(['student','user']))
          <span class="sidebar-section">Mon espace</span>
          <a href="{{ route('langues.index') }}"
             class="sidebar-link {{ request()->routeIs('langues.*') ? 'active' : '' }}">
            <i class="bi bi-journal-check"></i> Mes épreuves
          </a>
          <a href="{{ route('student.courses.progress') }}"
             class="sidebar-link {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i> Ma progression
          </a>
          @can('book consultation')
          <a href="{{ route('consultations.create') }}"
             class="sidebar-link {{ request()->routeIs('consultation*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Consultations
          </a>
          @endcan
          <a href="{{ route('abonnement.index') }}"
             class="sidebar-link {{ request()->routeIs('abonnement*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Mon abonnement
          </a>
          <a href="{{ route('profil.edit') }}"
             class="sidebar-link {{ request()->routeIs('profil*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Mon profil
          </a>
        @endif

        {{-- ── INSTRUCTEUR ── --}}
        @if(Auth::user()->hasAnyRole(['instructor']) && !Auth::user()->hasAnyRole(['admin','super-admin']))
          <span class="sidebar-section">Mes cours</span>
          <a href="{{ route('instructor.dashboard') }}"
             class="sidebar-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
            <i class="bi bi-book"></i> Mes cours
          </a>
          <a href="{{ route('instructor.courses.create') }}"
             class="sidebar-link {{ request()->routeIs('instructor.courses.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Créer un cours
          </a>
          <a href="{{ route('langues.index') }}"
             class="sidebar-link {{ request()->routeIs('langues.*') ? 'active' : '' }}">
            <i class="bi bi-translate"></i> Examens langues
          </a>
          <a href="{{ route('profil.edit') }}"
             class="sidebar-link {{ request()->routeIs('profil*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Mon profil
          </a>
        @endif

        {{-- ── CONSULTANT ── --}}
        @if(Auth::user()->hasAnyRole(['consultant']) && !Auth::user()->hasAnyRole(['admin','super-admin']))
          <span class="sidebar-section">Consultations</span>
          <a href="{{ route('admin.consultations.index') }}"
             class="sidebar-link {{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Mes consultations
          </a>
          <a href="{{ route('profil.edit') }}"
             class="sidebar-link {{ request()->routeIs('profil*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Mon profil
          </a>
        @endif

        {{-- ── ADMIN / SUPER-ADMIN ── --}}
        @if(Auth::user()->hasAnyRole(['admin','super-admin']))
          <span class="sidebar-section">Administration</span>
          <a href="{{ route('admin.users.index') }}"
             class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Utilisateurs
          </a>
          <a href="{{ route('admin.student-progress.index') }}"
             class="sidebar-link {{ request()->routeIs('admin.student-progress.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i> Suivi étudiants
          </a>
          <a href="{{ route('admin.langues.index') }}"
             class="sidebar-link {{ request()->routeIs('admin.langues.*','admin.series.*') ? 'active' : '' }}">
            <i class="bi bi-translate"></i> Gestion langues
          </a>
          <a href="{{ route('admin.roles.index') }}"
             class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> Rôles & Permissions
          </a>
          <a href="{{ route('admin.abonnements.plans.index') }}"
             class="sidebar-link {{ request()->routeIs('admin.abonnements.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Plans abonnement
          </a>
          <a href="{{ route('admin.analytics.index') }}"
             class="sidebar-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Analytics
          </a>
          <a href="{{ route('admin.consultations.index') }}"
             class="sidebar-link {{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Consultations
          </a>
          <a href="{{ route('instructor.dashboard') }}"
             class="sidebar-link {{ request()->routeIs('instructor.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i> Gestion cours
          </a>
        @endif

      </nav>

      <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="sidebar-link" style="color:rgba(226,75,74,.8);">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
          </button>
        </form>
      </div>
    </aside>

    <main class="dash-main">
      <div class="dash-mobile-topbar">
        <button class="dash-mobile-toggle" id="sidebarOpenBtn"><i class="bi bi-list"></i></button>
        <h2>@yield('title', 'Dashboard')</h2>
      </div>

      @if(session('success'))
      <div class="alert alert-success alert-dismissible rounded-3 mb-3" style="font-size:13px;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      @endif
      @if(session('error'))
      <div class="alert alert-warning alert-dismissible rounded-3 mb-3" style="font-size:13px;">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      @endif

      @yield('content')
    </main>
  </div>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script>
  (function(){
    const sidebar  = document.getElementById('dashSidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const openBtn  = document.getElementById('sidebarOpenBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');
    function open()  { sidebar.classList.add('sidebar-open'); overlay.classList.add('active'); document.body.style.overflow='hidden'; }
    function close() { sidebar.classList.remove('sidebar-open'); overlay.classList.remove('active'); document.body.style.overflow=''; }
    openBtn?.addEventListener('click', open);
    closeBtn?.addEventListener('click', close);
    overlay?.addEventListener('click', close);
    document.addEventListener('keydown', e => e.key==='Escape' && close());
    window.addEventListener('resize', () => window.innerWidth > 991 && close());
  })();
  </script>
  @stack('scripts')
</body>
</html>