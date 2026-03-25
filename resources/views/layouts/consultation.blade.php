<!DOCTYPE html>
<html lang="en">


<!-- form-wizard.html  21 Nov 2019 03:55:16 GMT -->

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>VisaFly</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset('assets/css/app.min.css')}}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">


</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1" style="display:flex; min-height:100vh;">
      <div class="navbar-bg"></div>

      <div class="main-sidebar sidebar-style-2" style="background:#1B3A6B; width:250px; flex-shrink:0; min-height:100vh; position:sticky; top:0; height:100vh; overflow-y:auto; z-index:100;">
        <aside id="sidebar-wrapper" class="d-flex flex-column align-items-center text-center p-3" style="gap:1.5rem; padding-top:1.5rem !important;">
          <!--        ZONE ANNONCES              -->
          <!-- ══════════════════════════════════ -->
          <div class="sidebar-annonces w-100">
            <p class="section-label"> Annonces</p>
            <div class="annonces-scroll">

              <div class="annonce-card">
                <span class="annonce-badge">Bourse</span>
                <p> Bourse d'excellence 2025 — Canada · Clôture : 30 Avril</p>
              </div>
              <div class="annonce-card">
                <span class="annonce-badge">Emploi</span>
                <p> Offre CDI en Allemagne — Ingénieur informatique</p>
              </div>
              <div class="annonce-card">
                <span class="annonce-badge">Études</span>
                <p> Admissions ouvertes — Universités françaises 2025/2026</p>
              </div>
              <div class="annonce-card">
                <span class="annonce-badge">Bourse</span>
                <p> Programme Erasmus+ — Dépôt de dossiers en cours</p>
              </div>
              <div class="annonce-card">
                <span class="annonce-badge">Visa</span>
                <p> Nouveau service : accompagnement visa Portugal express</p>
              </div>
              <!-- Duplication pour boucle infinie -->
              <div class="annonce-card">
                <span class="annonce-badge">Bourse</span>
                <p> Bourse d'excellence 2025 — Canada · Clôture : 30 Avril</p>
              </div>
              <div class="annonce-card">
                <span class="annonce-badge">Emploi</span>
                <p> Offre CDI en Allemagne — Ingénieur informatique</p>
              </div>
              <div class="annonce-card">
                <span class="annonce-badge">Études</span>
                <p> Admissions ouvertes — Universités françaises 2025/2026</p>
              </div>
              <div class="annonce-card">
                <span class="annonce-badge">Bourse</span>
                <p> Programme Erasmus+ — Dépôt de dossiers en cours</p>
              </div>
              <div class="annonce-card">
                <span class="annonce-badge">Visa</span>
                <p> Nouveau service : accompagnement visa Portugal express</p>
              </div>

            </div>
          </div>

          <!-- Séparateur doré -->
          <div class="gold-sep"></div>

          <!-- ══════════════════════════════════ -->
          <!--     CARROUSEL PHOTOS SIDEBAR      -->
          <!-- ══════════════════════════════════ -->
          <div class="sidebar-carousel w-100">
            <p class="section-label">📸 Nos locaux & supports</p>

            <div id="sidebarCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
              <div class="carousel-inner rounded overflow-hidden" style="height: 180px;">
                <div class="carousel-item active">
                  <img src="assets/img/locaux/locaux1.jpeg" class="d-block w-100 h-100" alt="Locaux 1"
                      style="object-fit: cover;">
                </div>
                <div class="carousel-item">
                  <img src="assets/img/locaux/locaux2.jpeg" class="d-block w-100 h-100" alt="Locaux 2"
                      style="object-fit: cover;">
                </div>
                <div class="carousel-item">
                  <img src="assets/img/locaux/locaux3.jpeg" class="d-block w-100 h-100" alt="Locaux 3"
                      style="object-fit: cover;">
                </div>
              </div>

              <!-- Indicateurs -->
              <ol class="carousel-indicators" style="bottom: -22px;">
                <li data-target="#sidebarCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#sidebarCarousel" data-slide-to="1"></li>
                <li data-target="#sidebarCarousel" data-slide-to="2"></li>
              </ol>

              <!-- Précédent -->
              <a class="carousel-control-prev" href="#sidebarCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Précédent</span>
              </a>

              <!-- Suivant -->
              <a class="carousel-control-next" href="#sidebarCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Suivant</span>
              </a>
            </div>
          </div>
          <!-- Séparateur doré -->
          <div class="gold-sep" style="margin-top: 1.2rem;"></div>

          <!-- ══════════════════════════════════ -->
          <!--     DRAPEAUX DÉFILANTS            -->
          <!-- ══════════════════════════════════ -->
          <div class="sidebar-flags w-100">
            <p class="section-label">🌍 Nos destinations</p>
            <div class="flags-scroll-vertical">

              <div class="flag-row"><img src="https://flagcdn.com/w40/fr.png" alt="France"><span>France</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/ca.png" alt="Canada"><span>Canada</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/de.png" alt="Allemagne"><span>Allemagne</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/gb.png" alt="Royaume-Uni"><span>Royaume-Uni</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/us.png" alt="États-Unis"><span>États-Unis</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/be.png" alt="Belgique"><span>Belgique</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/pt.png" alt="Portugal"><span>Portugal</span></div>
              <!-- Duplication -->
              <div class="flag-row"><img src="https://flagcdn.com/w40/fr.png" alt="France"><span>France</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/ca.png" alt="Canada"><span>Canada</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/de.png" alt="Allemagne"><span>Allemagne</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/gb.png" alt="Royaume-Uni"><span>Royaume-Uni</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/us.png" alt="États-Unis"><span>États-Unis</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/be.png" alt="Belgique"><span>Belgique</span></div>
              <div class="flag-row"><img src="https://flagcdn.com/w40/pt.png" alt="Portugal"><span>Portugal</span></div>

            </div>
          </div>

        </aside>
      </div> 
      <div style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
        <nav id="navmenu" class="main-navbar" style="position:sticky; top:0; z-index:999; width:100%;">

          <!-- Logo -->
          <a class="navbar-brand" href="{{ url('/') }}">
            <div class="nav-brand-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L2 7l10 5 10-5-10-5z" fill="#F5A623"/>
                <path d="M2 17l10 5 10-5M2 12l10 5 10-5"
                      stroke="#F5A623" stroke-width="1.8" stroke-linecap="round"/>
              </svg>
            </div>
            <span class="nav-brand-text">Visa<span class="nav-brand-gold">Fly</span></span>
          </a>

          <!-- Hamburger mobile -->
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

          <!-- Liens -->
          <ul class="nav-links-list">
            <li><a href="{{ url('/') }}"         class="nav-link-item">Accueil</a></li>
            <li><a href="{{ url('/#about') }}"   class="nav-link-item">À propos</a></li>
            <li><a href="{{ url('/#langues') }}" class="nav-link-item">Les Langues</a></li>
            <li><a href="{{ url('/#services') }}" class="nav-link-item">Nos services</a></li>
            <li><a href="{{ url('/#portfolio') }}" class="nav-link-item">Réalisations</a></li>
            <li><a href="{{ url('/#team') }}"    class="nav-link-item">Team</a></li>
            <li><a href="{{ url('/#contact') }}" class="nav-link-item">Contact</a></li>
          </ul>

          <!-- Boutons CTA -->
          <div class="nav-cta-group">
            <a href="{{ route('login') }}" class="btn-nav-login">Se connecter</a>
            <a href="{{ route('consultation') }}" class="btn-nav-consult">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                <path d="M22 2L11 13" stroke="#1B3A6B" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M22 2L15 22l-4-9-9-4 20-7z" stroke="#1B3A6B" stroke-width="2.2"
                      stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Se consulter
            </a>
          </div>

        </nav>

        <!-- Main Content -->
        <div class="main-content" style="flex:1; overflow-y:auto; padding:2rem; background:#F8F9FA;">
          @yield('space-work')
        </div>
        <footer class="main-footer">
          <div class="footer-left">
            <a href="https://VisaFly.com">VisaFly</a>
          </div>
          <div class="footer-right">
          </div>
        </footer>
      </div>
    </div>
  </div>

    <!-- Modal de confirmation -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
          <button type="button" class="btn-close" data-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          Votre consultation sera traitée et un conseiller vous contactera 
          par WhatsApp ou Email.<br><br>
          Voulez-vous confirmer l’envoi ?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Annuler
          </button>

          <button type="button" class="btn btn-primary" id="confirmSubmit">
            D’accord
          </button>
        </div>

      </div>
    </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function () {
          const select  = document.getElementById('visa_history_select');
          const wrapper = document.getElementById('visa_history_details_wrapper');

          function toggleDetails() {
              if (select.value === '1') {
                  wrapper.style.display = 'block';
              } else {
                  wrapper.style.display = 'none';
              }
          }

          // Au changement
          select.addEventListener('change', toggleDetails);

          // Au chargement (en cas de old() après erreur de validation)
          toggleDetails();
      });
  </script>
  <!-- General JS Scripts -->
  <script src="{{asset('assets/js/app.min.js')}}"></script>
  <script src="{{asset('assets/bundles/jquery-validation/dist/jquery.validate.min.js')}}"></script>
  <!-- JS Libraies -->
  <script src="{{asset('assets/bundles/jquery-steps/jquery.steps.min.js')}}"></script>
  <!-- Page Specific JS File -->
  <script src="{{asset('assets/js/page/form-wizard.js')}}"></script>
  <!-- Template JS File -->
  <script src="{{asset('assets/js/scripts.js')}}"></script>
  <!-- Custom JS File -->
  <script src="{{asset('assets/js/custom.js')}}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalSteps = 4;

        // Surveille les changements d'étape du wizard (compatible jQuery Steps / SmartWizard)
        function updateProgress(currentStep) {
            for (let i = 1; i <= totalSteps; i++) {
                const circle    = document.getElementById('step-circle-' + i);
                const indicator = document.getElementById('step-indicator-' + i);

                circle.classList.remove('active', 'completed');
                indicator.classList.remove('active', 'completed');

                if (i < currentStep) {
                    // Étapes passées → ✓ vert
                    circle.classList.add('completed');
                    circle.textContent = '';
                    indicator.classList.add('completed');
                } else if (i === currentStep) {
                    // Étape actuelle → bleu
                    circle.classList.add('active');
                    circle.textContent = i;
                    indicator.classList.add('active');
                } else {
                    // Étapes futures → gris
                    circle.textContent = i;
                }
            }

            // Mettre à jour la ligne de progression
            const percent = ((currentStep - 1) / (totalSteps - 1)) * 90;
            document.getElementById('wizardProgressLine').style.width = percent + '%';
            document.getElementById('currentStepText').textContent = currentStep;
        }

        // --- Compatibilité jQuery Steps ---
        if (typeof $ !== 'undefined' && $('#wizard_with_validation').data('jquery-steps')) {
            $('#wizard_with_validation').on('stepChanged', function (e, currentIndex) {
                updateProgress(currentIndex + 1);
            });
        }

        // --- Compatibilité SmartWizard ---
        if (typeof $ !== 'undefined') {
            $(document).on('showStep', function (e, anchorObject, stepIndex) {
                updateProgress(stepIndex + 1);
            });
        }

        // Initialisation à l'étape 1
        updateProgress(1);
    });

    
    document.querySelector('.mobile-nav-toggle')?.addEventListener('click', function () {
      document.querySelector('.nav-links-list')?.classList.toggle('show');
      this.classList.toggle('bi-list');
      this.classList.toggle('bi-x');
    });
</script>

</body>


<!-- form-wizard.html  21 Nov 2019 03:55:20 GMT -->

</html>