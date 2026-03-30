{{-- resources/views/tcf/abonnement.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Abonnement TCF — VisaFly</title>
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
<body style="background:#f8f9fb;">

  {{-- Navbar --}}
  @include('partials.navbar')

  {{-- Contenu --}}
  <main style="padding-top:90px;padding-bottom:60px;min-height:100vh;">
    <div class="container py-4">

      {{-- En-tête --}}
      <div class="text-center mb-5">
        <div class="mb-3">
          <div style="width:64px;height:64px;background:rgba(245,166,35,.12);border-radius:16px;
                      display:flex;align-items:center;justify-content:center;margin:0 auto;">
            <i class="bi bi-stars" style="font-size:28px;color:#F5A623;"></i>
          </div>
        </div>
        <h2 class="fw-bold" style="color:#1B3A6B;">Choisissez votre forfait</h2>
        <p class="text-muted">Accédez à toutes les séries TCF et TEF sans limitation</p>
      </div>

      {{-- Cartes forfaits --}}
      <div class="row g-4 justify-content-center mb-5">
        @foreach($forfaits as $f)
        <div class="col-md-4">
          <div class="card border-0 rounded-4 h-100 text-center p-4 position-relative"
               style="{{ isset($f['populaire'])
                 ? 'border:2px solid #1B3A6B !important;box-shadow:0 12px 40px rgba(27,58,107,.15);'
                 : 'border:1px solid #eee !important;' }}">

            @if(isset($f['populaire']))
              <div class="position-absolute top-0 start-50 translate-middle">
                <span class="badge px-3 py-2"
                      style="background:#F5A623;color:#1B3A6B;border-radius:20px;font-size:11px;font-weight:700;">
                  ⭐ Plus populaire
                </span>
              </div>
            @endif

            <h4 class="fw-bold mt-3" style="color:#1B3A6B;">{{ $f['nom'] }}</h4>

            <div class="my-3">
              <span style="font-size:36px;font-weight:800;color:#1B3A6B;">
                {{ number_format($f['prix'], 0, ',', ' ') }}
              </span>
              <span class="text-muted" style="font-size:13px;">
                {{ $f['devise'] }} / {{ $f['duree'] }}
              </span>
            </div>

            {{-- Séparateur doré --}}
            <div style="width:40px;height:2px;background:#F5A623;border-radius:2px;margin:0 auto 20px;"></div>

            <ul class="list-unstyled text-start mb-4">
              @foreach($f['avantages'] as $av)
                <li class="mb-2 d-flex align-items-center gap-2" style="font-size:13px;color:#444;">
                  <i class="bi bi-check-circle-fill" style="color:#1cc88a;flex-shrink:0;"></i>
                  {{ $av }}
                </li>
              @endforeach
            </ul>

            <a href="#"
               class="btn w-100 rounded-pill fw-semibold py-2"
               style="{{ isset($f['populaire'])
                 ? 'background:#1B3A6B;color:#fff;border:none;'
                 : 'border:1.5px solid #1B3A6B;color:#1B3A6B;background:transparent;' }}">
              Choisir ce forfait
            </a>

          </div>
        </div>
        @endforeach
      </div>

      {{-- Garanties --}}
      <div class="row g-3 justify-content-center mb-4">
        <div class="col-md-3 col-6 text-center">
          <i class="bi bi-shield-check" style="font-size:24px;color:#1cc88a;"></i>
          <p style="font-size:12px;color:#666;margin-top:6px;">Paiement sécurisé</p>
        </div>
        <div class="col-md-3 col-6 text-center">
          <i class="bi bi-arrow-repeat" style="font-size:24px;color:#1B3A6B;"></i>
          <p style="font-size:12px;color:#666;margin-top:6px;">Résiliable à tout moment</p>
        </div>
        <div class="col-md-3 col-6 text-center">
          <i class="bi bi-headset" style="font-size:24px;color:#F5A623;"></i>
          <p style="font-size:12px;color:#666;margin-top:6px;">Support 6j/7</p>
        </div>
      </div>

      {{-- Retour --}}
      <div class="text-center">
        <a href="{{ url('/') }}"
           class="btn btn-outline-secondary rounded-pill px-4">
          <i class="bi bi-arrow-left me-1"></i> Retour aux séries
        </a>
      </div>

    </div>
  </main>

  {{-- Footer --}}
  <footer style="background:#0f2548;padding:16px 0;text-align:center;">
    <p style="color:rgba(255,255,255,.4);font-size:12px;margin:0;">
      © {{ date('Y') }} <strong style="color:rgba(255,255,255,.6);">VisaFly International</strong>
      — Tous droits réservés
    </p>
  </footer>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>