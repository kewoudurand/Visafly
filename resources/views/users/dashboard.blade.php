{{-- resources/views/users/dashboard.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Suivi de mon Dossier')
@section('meta_description', 'Suivez l\'avancement de votre dossier d\'immigration en temps réel avec VisaFly.')

@push('styles')
<style>
.stu-card{background:#fff;border-radius:14px;border:1px solid #eee;
          padding:20px;box-shadow:0 2px 12px rgba(27,58,107,.05);}
.badge-consult{padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;border: none;display: inline-block;}
.s-en_attente{background:rgba(245,166,35,.12);color:#633806;}
.s-en_cours   {background:rgba(27,58,107,.1); color:#1B3A6B;}
.s-approuvee {background:rgba(28,200,138,.1);color:#0f6e56;}
.s-declinee  {background:rgba(226,75,74,.1); color:#a32d2d;}
.s-annulee   {background:#f0f0f0;color:#888;}
.s-terminee  {background:rgba(127,119,221,.12);color:#3C3489;}

/* ══ WIZARD DYNAMIQUE CLIENT ══ */
.wizard-progress-container { margin-bottom: 25px; position: relative; }
.wizard-steps-header { display: flex; justify-content: space-between; position: relative; margin-bottom: 15px; z-index: 1; }
.wizard-progress-line { position: absolute; top: 18px; left: 0; height: 3px; background: #eee; width: 100%; z-index: -1; }
.wizard-progress-bar-fill { position: absolute; top: 18px; left: 0; height: 3px; background: #1B3A6B; transition: width 0.3s ease; z-index: -1; }
.wizard-step-item { text-align: center; flex: 1; display: flex; flex-direction: column; align-items: center; }
.wizard-step-circle { width: 36px; height: 36px; border-radius: 50%; background: #fff; border: 2px solid #eee; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #999; transition: all 0.3s ease; }

.wizard-step-item.active .wizard-step-circle { background: #1B3A6B; border-color: #1B3A6B; color: #fff; box-shadow: 0 0 0 3px rgba(27,58,107,0.15); }
.wizard-step-item.completed .wizard-step-circle { background: #1cc88a; border-color: #1cc88a; color: #fff; }
.wizard-step-item.rejected .wizard-step-circle { background: #e24b4a; border-color: #e24b4a; color: #fff; }

.wizard-step-label { font-size: 10px; font-weight: 600; color: #888; margin-top: 5px; text-transform: uppercase; line-height: 1.2; text-align: center; }
.wizard-step-item.active .wizard-step-label { color: #1B3A6B; font-weight: 700; }

/* Timeline pour les notes destinées au client */
.note-item { border-left: 3px solid #1cc88a; padding-left: 15px; margin-bottom: 20px; position: relative; }

/* Zone d'upload documents */
.doc-upload-card { background: #fafafa; border: 1.5px dashed #1B3A6B; border-radius: 10px; padding: 14px; margin-bottom: 10px; transition: border-color .2s; }
.doc-upload-card:hover { border-color: #F5A623; }
.doc-upload-card.already-sent { border-style: solid; border-color: #1cc88a; background: rgba(28,200,138,.04); }
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.5rem;">
      Bonjour, {{ Auth::user()->first_name }} 👋
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">
      Suivi en direct de vos démarches · {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
    </p>
  </div>
  @if(!$consultation)
    <a href="{{ route('user.create') }}"
       style="padding:10px 22px;background:#F5A623;color:#1B3A6B;border-radius:25px;
              font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;
              box-shadow:0 4px 14px rgba(245,166,35,.3);">
      <i class="bi bi-plus-circle me-2"></i>Initier mon dossier
    </a>
  @endif
</div>

{{-- ── Alertes flash ── --}}
@if(session('success'))
  <div class="alert rounded-3 d-flex align-items-center gap-2 mb-4"
       style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="alert rounded-3 d-flex align-items-center gap-2 mb-4"
       style="background:rgba(226,75,74,.08);border:1px solid rgba(226,75,74,.3);color:#a32d2d;">
    <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
  </div>
@endif

@if($consultation)
  {{-- ══ GRAPH-PIPELINE : VISUALISATION DE L'AVANCEMENT EN DIRECT ══ --}}
  <div class="stu-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div style="font-size:14px;font-weight:700;color:#1B3A6B;">
        <i class="bi bi-bezier2 me-2 text-warning"></i>Progression globale de vos démarches
      </div>
      <div class="fw-bold" style="color: #1B3A6B; font-size: 14px;">Avancement : {{ $consultation->progression ?? 0 }}%</div>
    </div>

    <div class="wizard-progress-container mt-4">
      <div class="wizard-steps-header">
        <div class="wizard-progress-line"></div>
        <div class="wizard-progress-bar-fill" style="width: {{ $consultation->progression ?? 0 }}%;"></div>

        @foreach($consultation->pipelineEtapes as $etape)
          @php
            $stepClass = '';
            if($etape->statut === 'valide') $stepClass = 'completed';
            elseif($etape->statut === 'rejete') $stepClass = 'rejected';
            elseif($etape->statut === 'en_cours') $stepClass = 'active';
          @endphp
          <div class="wizard-step-item {{ $stepClass }}">
            <div class="wizard-step-circle">
              @if($etape->statut === 'valide') <i class="bi bi-check-lg"></i> @else {{ $etape->ordre }} @endif
            </div>
            <span class="wizard-step-label">{!! str_replace(' ', '<br>', e($etape->titre)) !!}</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="row g-4">
    {{-- COLONNE GAUCHE : Étape Active Contextuelle & Documents & Notes --}}
    <div class="col-lg-8">

      {{-- FOCUS ÉTAPE ACTUELLE --}}
      @if($etapeCourante)
        <div class="stu-card mb-4" style="border-left: 4px solid #1B3A6B;">
          <div class="badge bg-primary mb-2">Étape en cours de traitement</div>
          <h4 class="fw-bold mb-2" style="color:#1B3A6B; font-size:16px;">
            {{ $etapeCourante->ordre }}. {{ $etapeCourante->titre }}
          </h4>

          @if($etapeCourante->note_consultant)
            <div class="p-3 rounded-3 bg-light text-dark small border-start border-warning border-3 mt-2">
              <strong>💡 Consigne de l'expert :</strong> {{ $etapeCourante->note_consultant }}
            </div>
          @else
            <p class="text-muted small mb-0">Nos équipes analysent actuellement les éléments associés à cette étape. Vous serez notifié dès validation.</p>
          @endif
        </div>
      @endif

      {{-- ══════════════════════════════════════════════════════════════
           DOCUMENTS REQUIS POUR L'ÉTAPE ACTUELLE
           
           CORRIGÉ : On lit $etapeCourante->documents_requis directement
           depuis le modèle (déjà casté en array via $casts).
           Le cast est dans PipelineEtape : 'documents_requis' => 'array'
      ══════════════════════════════════════════════════════════════ --}}
      {{-- Dans dashboard.blade.php du client --}}
      @if($etapeCourante && is_array($etapeCourante->documents_requis) && count($etapeCourante->documents_requis) > 0)
          
          {{-- FORMULAIRE DE SOUMISSION DES PIÈCES --}}
          <form action="{{ route('user.documents.store', $consultation->id) }}" method="POST" enctype="multipart/form-data">
              @csrf

              <input type="hidden" name="etape_index" value="{{ $etapeCourante->ordre }}">
              <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
              
              <div class="p-3 mb-4 rounded-3" style="background: rgba(27,58,107,.03); border: 1px dashed rgba(27,58,107,.2);">
                  <div class="d-flex align-items-center gap-2 mb-3">
                      <i class="bi bi-folder-plus fs-5" style="color: #1B3A6B;"></i>
                      <div>
                          <h6 class="fw-bold mb-0" style="color: #1B3A6B; font-size: 14px;">Pièces justificatives requises</h6>
                          <p class="text-muted mb-0 small" style="font-size: 11px;">Veuillez téléverser les documents obligatoires au format PDF pour valider cette étape.</p>
                      </div>
                  </div>

                  <div class="row g-3">
                      @foreach($etapeCourante->documents_requis as $typeDoc)
                          @php
                              $dejaSoumis = $consultation->documents
                                  ->where('etape_index', $etapeCourante->ordre)
                                  ->where('type', $typeDoc)
                                  ->first();

                              $statusClass = 'border-light shadow-sm';
                              if ($dejaSoumis) {
                                  $statusClass = match($dejaSoumis->status ?? 'en_attente') {
                                      'valide' => 'border-success bg-success bg-opacity-10',
                                      'rejete' => 'border-danger bg-danger bg-opacity-10',
                                      default  => 'border-warning bg-warning bg-opacity-10'
                                  };
                              }
                          @endphp

                          <div class="col-md-6">
                              <div class="card h-100 rounded-3 border {{ $statusClass }}" style="transition: all 0.2s ease;">
                                  <div class="card-body p-3 d-flex flex-column justify-content-between">
                                      
                                      <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                                          <label class="fw-bold text-dark text-capitalize mb-0 small" style="letter-spacing: 0.3px;">
                                              <i class="bi bi-file-earmark-text text-muted me-1"></i>
                                              {{ str_replace('_', ' ', $typeDoc) }}
                                          </label>
                                          
                                          @if($dejaSoumis)
                                              @php
                                                  $badgeBg = match($dejaSoumis->status) {
                                                      'valide' => 'bg-success text-success',
                                                      'rejete' => 'bg-danger text-danger',
                                                      default  => 'bg-warning text-warning'
                                                  };
                                              @endphp
                                              <span class="badge rounded-pill {{ $badgeBg }} bg-opacity-10 px-2 py-1 text-uppercase" style="font-size: 9px; font-weight: 700;">
                                                  {{ $dejaSoumis->status == 'en_cours' ? '⏳ Reçu' : $dejaSoumis->status }}
                                              </span>
                                          @else
                                              <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-2 py-1 text-uppercase" style="font-size: 9px; font-weight: 700;">
                                                  ⚠️ Manquant
                                              </span>
                                          @endif
                                      </div>

                                      <div class="mt-2">
                                          @if($dejaSoumis)
                                              <div class="d-flex align-items-center justify-content-between bg-white rounded-2 p-2 border" style="font-size: 12px;">
                                                  <span class="text-muted text-truncate me-2">
                                                      <i class="bi bi-file-pdf text-danger me-1"></i> Document envoyé
                                                  </span>
                                                  <a href="{{ Storage::url($dejaSoumis->file_path) }}" target="_blank" class="btn btn-sm btn-light border py-0 px-2 fw-bold" style="font-size: 11px;">
                                                      <i class="bi bi-eye"></i> Voir
                                                  </a>
                                              </div>
                                              @if($dejaSoumis->status == 'rejete' && $dejaSoumis->comment)
                                                  <div class="text-danger small mt-1 fw-semibold" style="font-size: 11px;">
                                                      <i class="bi bi-exclamation-triangle-fill"></i> Motif : {{ $dejaSoumis->comment }}
                                                  </div>
                                              @endif
                                          @else
                                              <div class="position-relative">
                                                  <input type="file" 
                                                        name="files[{{ $typeDoc }}]" 
                                                        id="file-{{ $typeDoc }}"
                                                        class="form-control form-control-sm rounded-2" 
                                                        accept=".pdf" 
                                                        required 
                                                        style="font-size: 12px; color: #555;">
                                              </div>
                                          @endif
                                      </div>

                                  </div>
                              </div>
                          </div>
                      @endforeach
                  </div>

                  {{-- ── LE BOUTON DE SOUMISSION AJOUTÉ ICI ── --}}
                  @php
                      // On vérifie s'il reste au moins un document à uploader
                      $hasMissingDocs = false;
                      foreach($etapeCourante->documents_requis as $typeDoc) {
                          $dejaSoumis = $consultation->documents->where('etape_index', $etapeCourante->ordre)->where('type', $typeDoc)->first();
                          if(!$dejaSoumis || $dejaSoumis->status == 'rejete') {
                              $hasMissingDocs = true;
                              break;
                          }
                      }
                  @endphp

                  @if($hasMissingDocs)
                      <div class="d-flex justify-content-end mt-3 border-top pt-3">
                          <button type="submit" class="btn text-white fw-bold px-4 rounded-3 shadow-sm" 
                                  style="background-color: #1B3A6B; font-size: 13px; transition: background 0.2s;">
                              <i class="bi bi-cloud-arrow-up-fill me-1"></i> Envoyer les documents au consultant
                          </button>
                      </div>
                  @endif

              </div>
          </form>
      @endif

            {{-- 💬 MESSAGES & RECOMMANDATIONS DE L'EXPERT --}}
            <div class="stu-card">
              <div style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:15px;">
                <i class="bi bi-chat-left-text me-2 text-warning"></i>Recommandations & Instructions de votre consultant
              </div>

              <div class="notes-timeline mt-3">
                @forelse($consultation->notes->where('visible_client', true) as $note)
                  <div class="note-item">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                      <span class="fw-bold text-dark" style="font-size: 13px;">
                        {{ optional($note->auteur)->name ?? 'Consultant VisaFly' }}
                      </span>
                      <span class="text-muted small" style="font-size: 11px;">{{ $note->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-muted mb-1" style="font-size: 13px;">{{ $note->contenu }}</p>
                    @if($note->pipelineEtape)
                      <span class="badge bg-light text-dark border" style="font-size: 10px;">
                        <i class="bi bi-tag me-1"></i>Concerne l'étape : {{ $note->pipelineEtape->titre }}
                      </span>
                    @endif
                  </div>
                @empty
                  <p class="text-center text-muted small py-3 mb-0">Aucune directive particulière affichée pour le moment.</p>
                @endforelse
              </div>
            </div>
          </div>

          {{-- COLONNE DROITE : Résumé du dossier & Entretiens --}}
          <div class="col-lg-4">

            {{-- STATUT DU DOSSIER --}}
            <div class="stu-card mb-4 text-center">
              <span class="text-muted small d-block text-uppercase fw-bold mb-2">Statut Général</span>
              <span class="badge-consult s-{{ $consultation->statut ?? 'en_attente' }} fs-6 px-4 py-2 w-100">
                {{ strtoupper($consultation->statut ?? 'En attente') }}
              </span>
            </div>

            {{-- RENDEZ-VOUS PLANIFIÉS --}}
            <div class="stu-card mb-4">
              <div style="font-size:12px;font-weight:700;color:#1B3A6B;margin-bottom:12px;text-transform:uppercase;">
                <i class="bi bi-calendar-event text-warning me-1"></i>Mes Entretiens
              </div>
              <div class="d-flex flex-column gap-3">
                @forelse($consultation->rendezVous as $rdv)
                  <div class="p-2 border rounded-3 bg-light text-dark small">
                    <div class="fw-bold">
                      @switch($rdv->canal)
                        @case('video') 🎥 Visioconférence @break
                        @case('telephone') 📞 Entretien Téléphonique @break
                        @case('presentiel') 🏢 Cabinet VisaFly @break
                        @default {{ ucfirst($rdv->canal) }}
                      @endswitch
                    </div>
                    <div class="text-muted mt-1" style="font-size: 12px;">
                      📅 {{ $rdv->date_heure->format('d/m/Y à H:i') }}
                    </div>
                    @if($rdv->lien_visio && ($rdv->statut ?? '') !== 'annule')
                      <a href="{{ $rdv->lien_visio }}" target="_blank"
                        class="btn btn-sm btn-primary w-100 mt-2 fw-bold" style="font-size:11px;">
                        <i class="bi bi-camera-video me-1"></i>Rejoindre la réunion
                      </a>
                    @endif
                  </div>
                @empty
                  <div class="text-muted small text-center py-2">Aucun entretien planifié pour l'instant.</div>
                @endforelse
              </div>
            </div>

            {{-- RÉCAPITULATIF DU DOSSIER --}}
            <div class="stu-card">
              <div style="font-size:12px;font-weight:700;color:#888;text-transform:uppercase;margin-bottom:12px;">
                <i class="bi bi-info-circle text-primary me-1"></i>Récapitulatif de ma Demande
              </div>
              <div style="font-size:13px;" class="text-dark">
                <div class="mb-2">
                  <span class="text-muted small d-block">Destination ciblée:</span>
                  <strong>🍁 {{ $consultation->destination_country ?? 'Non spécifié' }}</strong>
                </div>
                <div class="mb-2">
                  <span class="text-muted small d-block">Type de Projet:</span>
                  <strong>{{ $consultation->objet ?? ($consultation->project_type ?? 'Immigration') }}</strong>
                </div>
                <div class="border-top pt-2 mt-2">
                  <span class="text-muted small d-block">Soumis le:</span>
                  <span class="small font-monospace">{{ $consultation->created_at->format('d/m/Y à H:i') }}</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      @else
        {{-- Cas où le client n'a pas encore de consultation initiée --}}
        <div class="stu-card text-center py-5">
          <i class="bi bi-folder-x text-muted display-3 d-block mb-3"></i>
          <h4 class="fw-bold" style="color: #1B3A6B;">Vous n'avez aucun dossier d'immigration actif</h4>
          <p class="text-muted small mx-auto" style="max-width: 450px;">
            Pour démarrer votre parcours vers le Canada ou l'étranger, commencez par soumettre votre profil en créant votre première demande de consultation.
          </p>
          <a href="{{ route('consultations.create') }}"
            class="btn fw-bold px-4 py-2 text-white rounded-pill mt-2"
            style="background:#1B3A6B;">
            Soumettre mon profil maintenant
          </a>
        </div>
      @endif

@push('scripts')
<script>
/**
 * Affiche le nom du fichier sélectionné sous le champ file
 * et révèle le bloc "preview" caché.
 */
function previewFile(input) {
  const wrapper = input.closest('.doc-upload-card') ?? input.closest('.mb-3');
  const preview = wrapper ? wrapper.querySelector('.file-preview') : null;

  if (preview && input.files.length > 0) {
    preview.classList.remove('d-none');
    preview.querySelector('.filename').textContent = input.files[0].name;
  }
}
</script>
@endpush

@endsection