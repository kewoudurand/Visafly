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
  @include('partials.navbar')

  {{-- Flash messages --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

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