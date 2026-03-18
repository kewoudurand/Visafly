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
  <style>
      /* ===== Masquer la navigation native de jQuery Steps ===== */
      #wizard_with_validation.wizard > .steps {
          display: none !important;
      }

      /* ===== Barre de progression personnalisée ===== */
      .wizard-progress-container {
          padding: 20px 30px 10px;
          background: #fff;
          border-bottom: 1px solid #f0f0f0;
          margin-bottom: 10px;
      }

      .wizard-steps-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          position: relative;
          margin-bottom: 8px;
      }

      /* Ligne grise de fond */
      .wizard-steps-header::before {
          content: '';
          position: absolute;
          top: 18px;
          left: 5%;
          width: 90%;
          height: 3px;
          background: #e0e0e0;
          z-index: 0;
      }

      /* Ligne colorée de progression */
      .wizard-progress-line {
          position: absolute;
          top: 18px;
          left: 5%;
          height: 3px;
          background: linear-gradient(90deg, #6777ef, #1cc88a);
          z-index: 1;
          transition: width 0.4s ease;
          width: 0%;
      }

      .wizard-step-item {
          display: flex;
          flex-direction: column;
          align-items: center;
          z-index: 2;
          flex: 1;
      }

      .wizard-step-circle {
          width: 36px;
          height: 36px;
          border-radius: 50%;
          background: #e0e0e0;
          color: #999;
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: bold;
          font-size: 14px;
          border: 3px solid #e0e0e0;
          transition: all 0.3s ease;
      }

      .wizard-step-circle.active {
          background: #6777ef;
          border-color: #6777ef;
          color: #fff;
          box-shadow: 0 0 0 4px rgba(103, 119, 239, 0.2);
      }

      .wizard-step-circle.completed {
          background: #1cc88a;
          border-color: #1cc88a;
          color: #fff;
      }

      .wizard-step-label {
          font-size: 11px;
          margin-top: 6px;
          color: #aaa;
          text-align: center;
          transition: color 0.3s;
          line-height: 1.3;
      }

      .wizard-step-item.active   .wizard-step-label { color: #6777ef; font-weight: 600; }
      .wizard-step-item.completed .wizard-step-label { color: #1cc88a; }

      .wizard-progress-text {
          text-align: right;
          font-size: 12px;
          color: #888;
          margin-top: 4px;
      }

      .wizard-progress-text span {
          font-weight: bold;
          color: #6777ef;
      }

      /* Adapter la zone contenu de jQuery Steps */
      #wizard_with_validation.wizard > .content {
          border: none !important;
          padding: 10px 0 !important;
          min-height: auto !important;
      }
  </style>

</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
              </a></li>
            <li>
              <form class="form-inline mr-auto">
                <div class="search-element">
                  <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                  <button class="btn" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </form>
            </li>
          </ul>
        </div>
      </nav>
      <div class="main-sidebar sidebar-style-2" style="background:#5494f3;">
          <aside id="sidebar-wrapper" 
                class="d-flex flex-column justify-content-center align-items-center text-center p-4">

              <h2 class="text-white" style="font-size: 28px; font-weight:700; margin-top:150%">
                  Consultez-vous
              </h2>

          </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
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
</script>

</body>


<!-- form-wizard.html  21 Nov 2019 03:55:20 GMT -->

</html>