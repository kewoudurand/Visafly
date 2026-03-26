<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') — VisaFly</title>
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
  @stack('styles')
</head>
<body style="background:#f0f4f8;">

  <!-- Navbar -->
  @include('partials.navbar')

  <div style="display:flex;min-height:calc(100vh - 66px);">

    <!-- Sidebar dashboard -->
    <aside style="width:220px;background:#1B3A6B;flex-shrink:0;padding:1.5rem 0;">

      <!-- Profil -->
      <div style="text-align:center;padding:0 1rem 1.5rem;border-bottom:1px solid rgba(255,255,255,.1);">
        <div style="width:52px;height:52px;border-radius:50%;background:rgba(245,166,35,.2);
                    border:2px solid #F5A623;display:flex;align-items:center;justify-content:center;
                    margin:0 auto 10px;font-size:18px;font-weight:700;color:#F5A623;">
          {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
        </div>
        <div style="font-size:13px;font-weight:600;color:#fff;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
        <div style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;">
          {{ Auth::user()->getRoleNames()->first() ?? 'Membre' }}
        </div>
      </div>

      <!-- Navigation -->
      <nav style="padding:.75rem 0;">

        <a href="{{ route('dashboard.index') }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 18px;font-size:13px;
                  color:{{ request()->routeIs('dashboard') ? '#F5A623' : 'rgba(255,255,255,.7)' }};
                  background:{{ request()->routeIs('dashboard') ? 'rgba(245,166,35,.12)' : 'transparent' }};
                  text-decoration:none;border-left:{{ request()->routeIs('dashboard') ? '3px solid #F5A623' : '3px solid transparent' }};">
          <i class="bi bi-speedometer2"></i> Tableau de bord
        </a>

        @can('pass test')
        <a href="{{ route('tcf.index') }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 18px;font-size:13px;
                  color:{{ request()->routeIs('tcf.*') ? '#F5A623' : 'rgba(255,255,255,.7)' }};
                  background:{{ request()->routeIs('tcf.*') ? 'rgba(245,166,35,.12)' : 'transparent' }};
                  text-decoration:none;border-left:{{ request()->routeIs('tcf.*') ? '3px solid #F5A623' : '3px solid transparent' }};">
          <i class="bi bi-journal-check"></i> Mes épreuves
        </a>
        @endcan

        @can('book consultation')
        <a href="{{ route('consultations.create') }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 18px;font-size:13px;
                  color:rgba(255,255,255,.7);text-decoration:none;border-left:3px solid transparent;">
          <i class="bi bi-calendar-check"></i> Consultations
        </a>
        @endcan

        @can('manage users')
        <div style="font-size:10px;font-weight:600;color:rgba(255,255,255,.3);
                    text-transform:uppercase;letter-spacing:.8px;padding:12px 18px 4px;">
          Administration
        </div>
        <a href="#"
           style="display:flex;align-items:center;gap:10px;padding:10px 18px;font-size:13px;
                  color:rgba(255,255,255,.7);text-decoration:none;border-left:3px solid transparent;">
          <i class="bi bi-people"></i> Utilisateurs
        </a>
        @endcan

        @can('view analytics')
        <a href="#"
           style="display:flex;align-items:center;gap:10px;padding:10px 18px;font-size:13px;
                  color:rgba(255,255,255,.7);text-decoration:none;border-left:3px solid transparent;">
          <i class="bi bi-bar-chart-line"></i> Analytics
        </a>
        @endcan

        <div style="margin-top:1rem;border-top:1px solid rgba(255,255,255,.1);padding-top:.75rem;">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="display:flex;align-items:center;gap:10px;padding:10px 18px;
                           font-size:13px;color:rgba(226,75,74,.8);background:none;
                           border:none;cursor:pointer;width:100%;border-left:3px solid transparent;">
              <i class="bi bi-box-arrow-right"></i> Déconnexion
            </button>
          </form>
        </div>
      </nav>
    </aside>

    <!-- Contenu principal -->
    <main style="flex:1;padding:2rem;overflow-y:auto;">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible rounded-3 mb-3">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-warning alert-dismissible rounded-3 mb-3">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @yield('content')
    </main>

  </div>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  @stack('scripts')
</body>
</html>