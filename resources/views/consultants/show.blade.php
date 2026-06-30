{{-- resources/views/consultants/show.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Détails de la Consultation - VisaFly')

@push('styles')
<style>
.stu-card{background:#fff;border-radius:14px;border:1px solid #eee;padding:20px;box-shadow:0 2px 12px rgba(27,58,107,.05);}
.stu-stat-num{font-size:2rem;font-weight:800;line-height:1;margin-bottom:4px;}
.stu-stat-lbl{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}
.badge-consult{padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;border:none;display:inline-block;}
.s-en_attente{background:rgba(245,166,35,.12);color:#633806;}
.s-en_cours  {background:rgba(27,58,107,.1);color:#1B3A6B;}
.s-approuvee {background:rgba(28,200,138,.1);color:#0f6e56;}
.s-declinee  {background:rgba(226,75,74,.1);color:#a32d2d;}
.s-annulee   {background:#f0f0f0;color:#888;}
.s-terminee  {background:rgba(127,119,221,.12);color:#3C3489;}
.consult-row{display:flex;align-items:center;justify-content:space-between;padding:16px 0;border-bottom:1px solid #f5f5f5;gap:15px;}
.consult-row:last-child{border-bottom:none;}
.status-select{font-size:12px;font-weight:600;padding:5px 10px;border-radius:8px;border:1px solid #ddd;color:#333;background-color:#fafafa;}
.status-select:focus{border-color:#1B3A6B;outline:none;}
.note-item{border-left:3px solid #1B3A6B;padding-left:15px;margin-bottom:20px;position:relative;}
.note-item.internal{border-left-color:#F5A623;}

/* ── PIPELINE ── */
.wizard-progress-container{margin-bottom:25px;position:relative;}
.wizard-steps-header{display:flex;justify-content:space-between;position:relative;margin-bottom:15px;z-index:1;}
.wizard-progress-line{position:absolute;top:18px;left:0;height:3px;background:#eee;width:100%;z-index:-1;}
.wizard-progress-bar-fill{position:absolute;top:18px;left:0;height:3px;background:#1B3A6B;transition:width .4s ease;z-index:-1;}
.wizard-step-item{text-align:center;flex:1;display:flex;flex-direction:column;align-items:center;cursor:pointer;}
.wizard-step-circle{width:36px;height:36px;border-radius:50%;background:#fff;border:2px solid #eee;display:flex;align-items:center;justify-content:center;font-weight:700;color:#999;transition:all .3s ease;}
.wizard-step-item.active    .wizard-step-circle{background:#1B3A6B;border-color:#1B3A6B;color:#fff;box-shadow:0 0 0 3px rgba(27,58,107,.15);}
.wizard-step-item.completed .wizard-step-circle{background:#1cc88a;border-color:#1cc88a;color:#fff;}
.wizard-step-item.rejected  .wizard-step-circle{background:#e24b4a;border-color:#e24b4a;color:#fff;}
.wizard-step-label{font-size:10px;font-weight:600;color:#888;margin-top:5px;text-transform:uppercase;line-height:1.2;text-align:center;max-width:80px;}
.wizard-step-item.active    .wizard-step-label{color:#1B3A6B;font-weight:700;}
.wizard-step-item.completed .wizard-step-label{color:#0f6e56;}
.wizard-step-item.rejected  .wizard-step-label{color:#a32d2d;}

/* ── BLOC CONTENU DYNAMIQUE DE L'ÉTAPE ── */
.etape-content-block{display:none;}
.etape-content-block.active{display:block;}

/* ── DOCUMENT CARD ── */
.doc-review-card{background:#fafafa;border:1px solid #eee;border-radius:10px;padding:14px;display:flex;align-items:center;gap:12px;margin-bottom:8px;transition:border-color .2s;}
.doc-review-card.valide{background:rgba(28,200,138,.06);border-color:rgba(28,200,138,.3);}
.doc-review-card.rejete{background:rgba(226,75,74,.06);border-color:rgba(226,75,74,.3);}
.doc-review-card:hover{border-color:#1B3A6B;}

/* ── INFO ROW ── */
.info-row{display:flex;padding:10px 0;border-bottom:1px solid #f5f5f5;font-size:13px;}
.info-row:last-child{border-bottom:none;}
.info-label{width:180px;flex-shrink:0;font-weight:600;color:#888;font-size:12px;text-transform:uppercase;letter-spacing:.4px;}
.info-value{color:#1B3A6B;font-weight:600;}
</style>
@endpush

@section('content')

<div class="mb-3">
  <a href="{{ route('consultant.dashboard') }}" class="text-decoration-none small fw-bold" style="color:#1B3A6B;">
    <i class="bi bi-arrow-left me-1"></i> Retour au tableau de bord
  </a>
</div>

{{-- En-tête --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
  <div>
    <span class="text-muted small text-uppercase fw-bold">Dossier {{ $consultation->numero_dossier }}</span>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.5rem;">
      {{ $consultation->objet ?? ($consultation->project_type ?? 'Consultation Immigration') }}
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">
      Client : <strong>{{ $consultation->full_name  ?? 'Inconnu' }}</strong>
      · Soumis le {{ $consultation->created_at->format('d/m/Y à H:i') }}
    </p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <form action="{{ route('consultant.updateStatus', $consultation->id) }}" method="POST" class="d-inline">
      @csrf @method('PATCH')
      <select name="status" class="status-select" onchange="this.form.submit()">
        @foreach(['en_attente'=>'⏳ En attente','en_cours'=>'⚙️ En cours','approuvee'=>'✅ Approuvée','declinee'=>'❌ Déclinée','annulee'=>'🚫 Annulée','terminee'=>'🏁 Terminée'] as $key=>$label)
          <option value="{{ $key }}" {{ ($consultation->status??'en_attente')==$key?'selected':'' }}>{{ $label }}</option>
        @endforeach
      </select>
    </form>
    <span class="badge-consult s-{{ $consultation->status??'en_attente' }}">
      {{ strtoupper($consultation->status??'en_attente') }}
    </span>
  </div>
</div>

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

{{-- ══ PIPELINE VISUELLE CLIQUABLE ══ --}}
<div class="stu-card mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div style="font-size:14px;font-weight:700;color:#1B3A6B;">
      <i class="bi bi-bezier2 me-2 text-warning"></i>Pipeline & Avancement du Dossier
    </div>
    <div class="fw-bold" style="color:#1B3A6B;font-size:14px;">
      Progression : {{ $consultation->progression ?? 0 }}%
    </div>
  </div>

  <div class="wizard-progress-container mt-4">
    <div class="wizard-steps-header">
      <div class="wizard-progress-line"></div>
      <div class="wizard-progress-bar-fill" style="width:{{ $consultation->progression ?? 0 }}%;"></div>

      @foreach($consultation->pipelineEtapes as $etape)
        @php
          $stepClass = '';
          if ($etape->statut === 'valide')    $stepClass = 'completed';
          elseif ($etape->statut === 'rejete') $stepClass = 'rejected';
          elseif ($etape->statut === 'en_cours') $stepClass = 'active';

          $isActive = $etapeCourante && $etapeCourante->id === $etape->id;
        @endphp

        {{-- Chaque cercle est cliquable → affiche le bon contenu --}}
        <div class="wizard-step-item {{ $stepClass }}"
             onclick="afficherEtape({{ $etape->id }})"
             title="Cliquez pour voir le contenu de cette étape">
          <div class="wizard-step-circle">
            @if($etape->statut === 'valide')
              <i class="bi bi-check-lg"></i>
            @elseif($etape->statut === 'rejete')
              <i class="bi bi-x-lg"></i>
            @else
              {{ $loop->iteration }}
            @endif
          </div>
          <span class="wizard-step-label">{!! str_replace(' ', '<br>', e($etape->titre)) !!}</span>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Légende --}}
  <div class="d-flex gap-3 mt-2" style="font-size:11px;">
    <span><span class="badge" style="background:#1B3A6B;">&nbsp;</span> En cours</span>
    <span><span class="badge bg-success">&nbsp;</span> Validée</span>
    <span><span class="badge bg-danger">&nbsp;</span> Rejetée</span>
    <span><span class="badge bg-light text-secondary border">&nbsp;</span> En attente</span>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">

    {{-- ══ BLOC DYNAMIQUE : contenu affiché selon l'étape cliquée ══ --}}
    @foreach($consultation->pipelineEtapes as $etape)
      @php
        $isActive = $etapeCourante && $etapeCourante->id === $etape->id;
        $isFirst  = $loop->first;
        // Détermine quel type de contenu afficher selon le titre de l'étape
        $titreNorm = strtolower(str_replace(['é','è','ê','ë','à','â','î','ô','û','ù'], ['e','e','e','e','a','a','i','o','u','u'], $etape->titre));
        $typeContenu = 'generique'; // par défaut
        if (str_contains($titreNorm, 'information') || str_contains($titreNorm, 'identit') || str_contains($titreNorm, 'consultation'))
            $typeContenu = 'informations';
        elseif (str_contains($titreNorm, 'document') || str_contains($titreNorm, 'piece') || str_contains($titreNorm, 'justif') || str_contains($titreNorm, 'passeport'))
            $typeContenu = 'documents';
        elseif (str_contains($titreNorm, 'langue') || str_contains($titreNorm, 'test') || str_contains($titreNorm, 'ielts') || str_contains($titreNorm, 'tef') || str_contains($titreNorm, 'goethe'))
            $typeContenu = 'langue';
        elseif (str_contains($titreNorm, 'diplome') || str_contains($titreNorm, 'formation') || str_contains($titreNorm, 'etude') || str_contains($titreNorm, 'eca'))
            $typeContenu = 'diplomes';
        elseif (str_contains($titreNorm, 'rendez') || str_contains($titreNorm, 'rdv') || str_contains($titreNorm, 'entretien'))
            $typeContenu = 'rdv';
        elseif (str_contains($titreNorm, 'visa') || str_contains($titreNorm, 'obtention') || str_contains($titreNorm, 'confirmation'))
            $typeContenu = 'final';
      @endphp

      <div id="etape-block-{{ $etape->id }}"
           class="stu-card mb-4 etape-content-block {{ ($isActive || ($isFirst && !$etapeCourante)) ? 'active' : '' }}"
           style="border-left:4px solid {{ $etape->statut==='valide' ? '#1cc88a' : ($etape->statut==='rejete' ? '#e24b4a' : '#1B3A6B') }};">

        {{-- En-tête de l'étape --}}
        <div class="d-flex align-items-start justify-content-between mb-3">
          <div>
            <span class="badge mb-1"
                  style="background:{{ $etape->statut==='valide' ? 'rgba(28,200,138,.15)' : ($etape->statut==='rejete' ? 'rgba(226,75,74,.15)' : 'rgba(27,58,107,.1)') }};
                         color:{{ $etape->statut==='valide' ? '#0f6e56' : ($etape->statut==='rejete' ? '#a32d2d' : '#1B3A6B') }};">
              @if($etape->statut==='valide') ✅ Étape validée
              @elseif($etape->statut==='rejete') ❌ Étape rejetée
              @elseif($etape->statut==='en_cours') ⚙️ Étape active
              @else ⏳ En attente @endif
            </span>
            <h5 class="fw-bold mb-0" style="color:#1B3A6B;font-size:15px;">
              {{ $loop->iteration }}. {{ $etape->titre }}
            </h5>
            @if($etape->description)
              <p class="text-muted small mb-0 mt-1">{{ $etape->description }}</p>
            @endif
          </div>
          @if($etape->validee_le)
            <span class="text-muted" style="font-size:11px;white-space:nowrap;">
              <i class="bi bi-clock me-1"></i>{{ $etape->validee_le->format('d/m/Y H:i') }}
            </span>
          @endif
        </div>

        {{-- ── CONTENU SELON LE TYPE D'ÉTAPE ── --}}

        {{-- TYPE 1 : Informations personnelles --}}
        @if($typeContenu === 'informations')
          <div class="mb-3">
            <p class="text-muted small mb-2 fw-semibold text-uppercase" style="font-size:11px;letter-spacing:.5px;">
              <i class="bi bi-person-badge me-1"></i>Informations soumises par le client
            </p>
            <div class="info-row"><span class="info-label">Nom complet</span><span class="info-value">{{ $consultation->full_name ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Date de naissance</span><span class="info-value">{{ $consultation->birth_date ? \Carbon\Carbon::parse($consultation->birth_date)->format('d/m/Y') : '—' }}</span></div>
            <div class="info-row"><span class="info-label">Nationalité</span><span class="info-value">{{ $consultation->nationality ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Pays de résidence</span><span class="info-value">{{ $consultation->residence_country ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Téléphone</span><span class="info-value">{{ $consultation->phone ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $consultation->email ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Profession</span><span class="info-value">{{ $consultation->profession ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Destination</span><span class="info-value">{{ $consultation->destination_country ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Type de projet</span><span class="info-value">{{ $consultation->project_type ?? '—' }}</span></div>
            <div class="info-row">
              <span class="info-label">Refus de visa</span>
              <span class="info-value">
                @if($consultation->visa_history)
                  <span class="badge bg-danger bg-opacity-10 text-danger">Oui — {{ $consultation->visa_history_details }}</span>
                @else
                  <span class="badge bg-success bg-opacity-10 text-success">Non</span>
                @endif
              </span>
            </div>
          </div>

        {{-- TYPE 2 : Documents téléversés --}}
        @elseif($typeContenu === 'documents')
          <div class="mb-3">
            <p class="text-muted small mb-2 fw-semibold text-uppercase" style="font-size:11px;letter-spacing:.5px;">
              <i class="bi bi-files me-1"></i>Documents téléversés par le client
              <span class="badge bg-light text-muted border ms-1">{{ $consultation->documents->count() }} fichier(s)</span>
            </p>

            @forelse($consultation->documents as $doc)
              @php
                $docClasse = match($doc->status ?? 'en_attente') {
                  'valide'  => 'valide',
                  'rejete'  => 'rejete',
                  default   => '',
                };
              @endphp
              <div class="doc-review-card {{ $docClasse }}">
                {{-- Icône selon type --}}
                <div style="width:40px;height:40px;border-radius:8px;background:rgba(27,58,107,.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="bi {{ str_ends_with(strtolower($doc->file_path ?? ''), '.pdf') ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark-image text-primary' }} fs-5"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                  <div class="fw-bold text-truncate" style="font-size:13px;">{{ $doc->name }}</div>
                  <div class="text-muted" style="font-size:11px;">
                    Type : {{ $doc->type ?? 'N/A' }}
                    @if($doc->comment) · <span class="text-warning">{{ $doc->comment }}</span> @endif
                  </div>
                </div>
                {{-- Actions par document --}}
                <div class="d-flex gap-1 flex-shrink-0">
                  <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                     class="btn btn-sm btn-light border rounded-3" title="Voir">
                    <i class="bi bi-eye"></i>
                  </a>
                  <form action="{{ route('consultant.document.statut', $doc->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="valide">
                    <button type="submit" class="btn btn-sm rounded-3 {{ ($doc->status??'')=='valide' ? 'btn-success' : 'btn-outline-success' }}" title="Valider">
                      <i class="bi bi-check-lg"></i>
                    </button>
                  </form>
                  <form action="{{ route('consultant.document.statut', $doc->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="rejete">
                    <button type="submit" class="btn btn-sm rounded-3 {{ ($doc->status??'')=='rejete' ? 'btn-danger' : 'btn-outline-danger' }}" title="Rejeter">
                      <i class="bi bi-x-lg"></i>
                    </button>
                  </form>
                </div>
              </div>
            @empty
              <div class="text-center py-3 text-muted small">
                <i class="bi bi-file-earmark-x d-block mb-1 fs-3 text-black-50"></i>
                Aucun document téléversé par le client pour cette étape.
              </div>
            @endforelse
          </div>

        {{-- TYPE 3 : Test de langue --}}
        @elseif($typeContenu === 'langue')
          <div class="mb-3">
            <p class="text-muted small mb-3 fw-semibold text-uppercase" style="font-size:11px;letter-spacing:.5px;">
              <i class="bi bi-translate me-1"></i>Résultats de test de langue soumis
            </p>
            @php $langDocs = $consultation->documents->where('type', 'langue'); @endphp
            @forelse($langDocs as $doc)
              <div class="doc-review-card {{ match($doc->status??'') { 'valide'=>'valide','rejete'=>'rejete',default=>'' } }}">
                <i class="bi bi-file-earmark-text fs-4" style="color:#1B3A6B;flex-shrink:0;"></i>
                <div class="flex-grow-1"><div class="fw-bold" style="font-size:13px;">{{ $doc->name }}</div></div>
                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-light border rounded-3">
                  <i class="bi bi-eye"></i> Consulter
                </a>
              </div>
            @empty
              {{-- Fallback : chercher dans tous les docs --}}
              @php $allDocs = $consultation->documents->whereIn('type', ['ielts','tef','goethe','langue','test']); @endphp
              @forelse($allDocs as $doc)
                <div class="doc-review-card">
                  <i class="bi bi-file-earmark-text fs-4" style="color:#1B3A6B;flex-shrink:0;"></i>
                  <div class="flex-grow-1"><div class="fw-bold" style="font-size:13px;">{{ $doc->name }}</div></div>
                  <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-light border rounded-3"><i class="bi bi-eye"></i></a>
                </div>
              @empty
                <div class="text-center py-3 text-muted small">
                  <i class="bi bi-file-earmark-x d-block mb-1 fs-3 text-black-50"></i>
                  Aucun résultat de test de langue soumis.
                </div>
              @endforelse
            @endforelse
            {{-- Champ note du niveau de langue du client --}}
            <div class="mt-3 p-3 bg-light rounded-3" style="font-size:13px;">
              <span class="text-muted d-block small fw-bold mb-1">Niveau déclaré par le client</span>
              <strong class="text-dark">{{ $consultation->language_level ?? $consultation->metadata['language_level'] ?? 'Non renseigné' }}</strong>
            </div>
          </div>

        {{-- TYPE 4 : Diplômes / Formation --}}
        @elseif($typeContenu === 'diplomes')
          <div class="mb-3">
            <p class="text-muted small mb-3 fw-semibold text-uppercase" style="font-size:11px;letter-spacing:.5px;">
              <i class="bi bi-mortarboard me-1"></i>Parcours académique & Diplômes
            </p>
            <div class="info-row"><span class="info-label">Dernier diplôme</span><span class="info-value">{{ $consultation->last_degree ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Domaine d'études</span><span class="info-value">{{ $consultation->metadata['field_of_study'] ?? $consultation->field_of_study ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Année d'obtention</span><span class="info-value">{{ $consultation->metadata['graduation_year'] ?? $consultation->graduation_year ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Expérience pro</span><span class="info-value">{{ $consultation->metadata['work_experience'] ?? $consultation->work_experience ?? '—' }}</span></div>
            {{-- Diplômes joints --}}
            @php $dipDocs = $consultation->documents->whereIn('type', ['diplome','diploma','transcripts','releve']); @endphp
            @if($dipDocs->count())
              <p class="text-muted small mt-3 mb-2 fw-semibold">Documents joints :</p>
              @foreach($dipDocs as $doc)
                <div class="doc-review-card {{ match($doc->status??'') { 'valide'=>'valide','rejete'=>'rejete',default=>'' } }}">
                  <i class="bi bi-file-earmark-pdf text-danger fs-5" style="flex-shrink:0;"></i>
                  <div class="flex-grow-1"><div class="fw-bold" style="font-size:13px;">{{ $doc->name }}</div></div>
                  <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-light border rounded-3"><i class="bi bi-eye"></i></a>
                </div>
              @endforeach
            @endif
          </div>

        {{-- TYPE 5 : Final / Obtention du visa --}}
        @elseif($typeContenu === 'final')
          <div class="text-center py-4">
            <div style="font-size:3rem;">🏆</div>
            <h5 class="fw-bold mt-2" style="color:#1B3A6B;">Dossier complet</h5>
            <p class="text-muted small">Toutes les étapes ont été traitées. Vous pouvez valider l'obtention du visa.</p>
            <div class="d-flex justify-content-center gap-3 mt-2">
              <div class="text-center p-3 rounded-3 border" style="min-width:80px;">
                <div class="fw-bold" style="font-size:1.4rem;color:#1cc88a;">{{ $consultation->pipelineEtapes->where('statut','valide')->count() }}</div>
                <div class="text-muted" style="font-size:11px;">Étapes validées</div>
              </div>
              <div class="text-center p-3 rounded-3 border" style="min-width:80px;">
                <div class="fw-bold" style="font-size:1.4rem;color:#1B3A6B;">{{ $consultation->pipelineEtapes->count() }}</div>
                <div class="text-muted" style="font-size:11px;">Total étapes</div>
              </div>
            </div>
          </div>

        {{-- TYPE GÉNÉRIQUE (rendez-vous, profil Express Entrée, etc.) --}}
        @else
          <div class="mb-3">
            @if($etape->description)
              <p class="text-muted" style="font-size:13px;">{{ $etape->description }}</p>
            @endif
            <div class="p-3 rounded-3 bg-light" style="font-size:13px;">
              <i class="bi bi-info-circle me-1 text-primary"></i>
              <span class="text-muted">Vérifiez les éléments de cette étape et saisissez une observation avant de valider ou rejeter.</span>
            </div>
            @if($etape->note_consultant)
              <div class="mt-2 p-2 rounded-3 border-start border-3 border-warning bg-warning bg-opacity-10">
                <span class="small text-muted fw-bold d-block">Note précédente :</span>
                <span style="font-size:13px;">{{ $etape->note_consultant }}</span>
              </div>
            @endif
          </div>
        @endif

        {{-- Affichage informatif des exigences pour les étapes clôturées --}}
        @if(in_array($etape->statut, ['valide', 'rejete']) && !empty($etape->documents_requis))
          <div class="mt-2 p-2 bg-light rounded border" style="font-size:12px;">
            <span class="text-muted fw-bold d-block mb-1"><i class="bi bi-lock-fill me-1"></i>Exigences documentaires configurées :</span>
            <div class="d-flex gap-2 flex-wrap">
              @foreach($etape->documents_requis as $reqDoc)
                <span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $reqDoc) }}</span>
              @endforeach
            </div>
          </div>
        @endif

        {{-- ── FORMULAIRE ACTION : Valider / Rejeter (seulement si étape active) ── --}}
        @if($etape->statut === 'en_cours')
          {{-- Affichage des documents attendus / soumis pour cette étape --}}
          @if(!empty($etape->documents_requis))
            <div class="mt-3 p-3 bg-white rounded-3 border">
              <h6 class="fw-bold small text-secondary mb-2"><i class="bi bi-folder-check me-1"></i> Suivi des pièces demandées pour cette étape :</h6>
              <div class="list-group list-group-flush">
                @foreach($etape->documents_requis as $reqDoc)
                  @php
                    // Recherche si le client a déjà envoyé un fichier lié à cet index d'étape et ce type précis
                    $fichierRecu = $consultation->documents
                      ->where('etape_index', $etape->ordre)
                      ->where('type', $reqDoc)
                      ->first();
                  @endphp
                  <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2" style="font-size: 13px;">
                    <div>
                      <span class="text-capitalize fw-semibold">{{ str_replace('_', ' ', $reqDoc) }}</span>
                      @if($fichierRecu)
                        <span class="ms-2">
                          <a href="{{ Storage::url($fichierRecu->file_path) }}" target="_blank" class="text-decoration-none text-primary fw-bold">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Ouvrir le fichier
                          </a>
                        </span>
                      @endif
                    </div>
                    <div>
                      @if($fichierRecu)
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill text-capitalize">{{ $fichierRecu->status ?? 'Reçu' }}</span>
                      @else
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">⏳ En attente du client</span>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
          <hr class="my-3">
          <form action="{{ route('consultant.etapes.traiter', $etape->id) }}" method="POST">
            @csrf
            
            {{-- 🛠️ AJOUT : CONFIGURATION DES EXIGENCES DOCUMENTAIRES CLIENT --}}
            <div class="mb-3 p-3 bg-light rounded-3 border border-dashed border-primary">
              <label class="form-label fw-bold small text-primary d-block mb-2">
                <i class="bi bi-shield-plus me-1"></i> 🔑 Exiger des documents du client pour cette étape :
              </label>
              <p class="text-muted mb-3" style="font-size:11px; line-height: 1.3;">
                Cochez ci-dessous les pièces obligatoires. Les champs d'upload correspondants apparaîtront immédiatement sur l'espace client.
              </p>
              <div class="d-flex flex-wrap gap-3" style="font-size:13px;">
                @php
                  $requisActuels = $etape->documents_requis ?? [];
                  $listeDocsDispo = [
                    'passeport' => 'Passeport',
                    'diplome' => 'Diplôme',
                    'releve_note' => 'Relevé de notes',
                    'attestation_travail' => 'Attestation de travail',
                    'preuve_fonds' => 'Preuve de fonds',
                    'photo_identite' => 'Photo d\'identité',
                    'document' => 'Document supplementaire',
                    'bilan_médical' => 'Bilan médical',
                    'casier_judiciaire' => 'Casier judiciaire',
                  ];
                @endphp
                @foreach($listeDocsDispo as $key => $label)
                  <label class="form-check-label d-flex align-items-center gap-1 bg-white px-2 py-1 rounded shadow-sm border cursor-pointer">
                    <input type="checkbox" name="documents_requis[]" value="{{ $key }}" class="form-check-input m-0"
                      {{ in_array($key, $requisActuels) ? 'checked' : '' }}>
                    <span>{{ $label }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small" style="font-size:12px;color:#1B3A6B;">
                Observation / Note pour le client (obligatoire si vous demandez des modifications ou rejetez)
              </label>
              <textarea name="note_consultant" class="form-control rounded-3" rows="2"
                        placeholder="Ex: Veuillez téléverser un passeport valide d'au moins 6 mois...">{{ $etape->note_consultant }}</textarea>
            </div>

            <div class="d-flex flex-wrap gap-2">
              {{-- Action 1 : Mettre à jour les documents exigés sans clore l'étape --}}
              <button type="submit" name="action" value="demander_docs"
                      class="btn btn-primary fw-bold px-3 rounded-3" style="font-size:13px; background-color: #1B3A6B; border-color: #1B3A6B;">
                <i class="bi bi-send me-1"></i> Demander les documents cochés
              </button>

              {{-- Action 2 : Validation définitive (Le consultant a vérifié les fichiers et valide l'étape) --}}
              <button type="submit" name="action" value="valider" class="btn btn-success fw-bold px-3 rounded-3" style="font-size:13px;">
                  <i class="bi bi-check-circle me-1"></i>
                  @if($typeContenu === 'informations') Informations vérifiées ✓
                  @elseif($typeContenu === 'documents') Documents approuvés ✓
                  @elseif($typeContenu === 'langue') Test de langue validé ✓
                  @elseif($typeContenu === 'diplomes') Diplômes vérifiés ✓
                  @elseif($typeContenu === 'final') Visa accordé — Clôturer ✓
                  @else Valider l'étape (Passer à la suite) ✓
                  @endif
              </button>

              {{-- Action 3 : Rejet --}}
              <button type="submit" name="action" value="rejeter"
                      class="btn btn-outline-danger fw-bold px-3 rounded-3" style="font-size:13px;">
                <i class="bi bi-x-circle me-1"></i> Rejeter
              </button>
            </div>
          </form>
        @elseif($etape->statut === 'valide')
          <div class="p-2 rounded-3 d-flex align-items-center gap-2 mt-2"
               style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.2);font-size:13px;color:#0f6e56;">
            <i class="bi bi-check-circle-fill"></i>
            Étape validée par {{ optional($etape->validePar)->name ?? 'le consultant' }}
            le {{ $etape->validee_le?->format('d/m/Y à H:i') }}
            @if($etape->note_consultant)
              · <em>« {{ $etape->note_consultant }} »</em>
            @endif
          </div>
        @elseif($etape->statut === 'rejete')
          <div class="p-2 rounded-3 d-flex align-items-center gap-2 mt-2"
               style="background:rgba(226,75,74,.08);border:1px solid rgba(226,75,74,.2);font-size:13px;color:#a32d2d;">
            <i class="bi bi-x-circle-fill"></i>
            Étape rejetée — motif : {{ $etape->note_consultant ?? 'Non précisé' }}
          </div>
          {{-- Permettre une nouvelle tentative --}}
          <form action="{{ route('consultant.etapes.reactiverEtape', $etape->id) }}" method="POST" class="mt-2">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-primary rounded-3">
              <i class="bi bi-arrow-clockwise me-1"></i> Remettre en cours
            </button>
          </form>
        @else
          <div class="p-2 rounded-3 text-muted text-center mt-2"
               style="background:#f8f8f8;border:1px solid #eee;font-size:13px;">
            <i class="bi bi-lock me-1"></i> Cette étape sera accessible après validation des étapes précédentes.
          </div>
        @endif

      </div>{{-- fin etape-content-block --}}
    @endforeach

    {{-- ── RENDEZ-VOUS ── --}}
    <div class="stu-card mb-4">
      <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div style="font-size:14px;font-weight:700;color:#1B3A6B;">
          <i class="bi bi-calendar-event me-2 text-warning"></i>Planification & Rendez-vous
        </div>
        <button type="button" class="btn btn-sm text-white px-3 fw-bold rounded-3"
                style="background-color:#1B3A6B;font-size:12px;"
                data-bs-toggle="modal" data-bs-target="#rdvModal">
          <i class="bi bi-plus-circle me-1"></i> Programmer un RDV
        </button>
      </div>
      <div class="table-responsive">
        <table class="table table-borderless align-middle mb-0" style="font-size:13px;">
          <thead><tr class="text-muted" style="font-size:11px;text-transform:uppercase;">
            <th>Canal / Lien</th><th>Date & Heure</th><th>Statut</th><th>Compte-rendu</th>
          </tr></thead>
          <tbody>
          @forelse($consultation->rendezVous as $rdv)
            <tr class="border-top">
              <td>
                <span class="fw-bold text-dark">
                  @switch($rdv->canal)
                    @case('video')Vidéoconférence @break
                    @case('telephone')Téléphone @break
                    @case('presentiel') Présentiel @break
                    @default {{ ucfirst($rdv->canal) }}
                  @endswitch
                </span>
                @if($rdv->lien_visio)
                  <br><a href="{{ $rdv->lien_visio }}" target="_blank" class="small"><i class="bi bi-camera-video me-1"></i>Rejoindre</a>
                @endif
              </td>
              <td>{{ $rdv->date_heure->format('d/m/Y à H:i') }}</td>
              <td>
                @php $bc = match($rdv->statut) { 'confirme','prevu'=>'success','annule'=>'danger',default=>'warning' }; @endphp
                <span class="badge rounded-pill bg-{{ $bc }} bg-opacity-10 text-{{ $bc }}">
                  {{ match($rdv->statut) { 'prevu'=>'Prévu','confirme'=>'Confirmé','annule'=>'Annulé',default=>'En attente' } }}
                </span>
              </td>
              <td class="text-muted small">{{ $rdv->compte_rendu ?? 'Rien' }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center py-3 text-muted small">Aucun entretien planifié.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- ── NOTES ── --}}
    <div class="stu-card">
      <div style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:15px;">
        <i class="bi bi-chat-left-text me-2 text-warning"></i>Notes d'analyse & Échanges internes
      </div>
      <form action="{{ route('consultant.notes.store', $consultation->id) }}" method="POST"
            class="mb-4 bg-light p-3 rounded-3">
        @csrf
        <textarea name="contenu" class="form-control border-0 rounded-3 mb-2" rows="2"
                  placeholder="Ajouter une note..." required></textarea>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="d-flex gap-3">
            <div class="form-check"><input type="checkbox" name="visible_client" id="visible_client" class="form-check-input" value="1"><label for="visible_client" class="form-check-label text-muted small">Visible par le client</label></div>
            @if($etapeCourante)
              <div class="form-check"><input type="checkbox" name="lier_etape" value="{{ $etapeCourante->id }}" class="form-check-input" id="lier_etape"><label for="lier_etape" class="form-check-label text-muted small">Lier à l'étape active</label></div>
            @endif
          </div>
          <button type="submit" class="btn btn-sm text-white px-3 fw-bold rounded-3" style="background-color:#1B3A6B;">Enregistrer</button>
        </div>
      </form>
      @forelse($consultation->notes as $note)
        <div class="note-item {{ !$note->visible_client ? 'internal' : '' }}">
          <div class="d-flex justify-content-between align-items-start mb-1">
            <span class="fw-bold text-dark" style="font-size:13px;">{{ optional($note->auteur)->name ?? 'Consultant' }}</span>
            <span class="badge {{ !$note->visible_client ? 'bg-warning text-dark' : 'bg-info text-white' }}" style="font-size:9px;">
              {{ !$note->visible_client ? 'Interne' : 'Visible Client' }}
            </span>
          </div>
          <p class="text-muted mb-1" style="font-size:13px;">{{ $note->contenu }}</p>
          <div class="d-flex gap-2 align-items-center" style="font-size:11px;color:#aaa;">
            <span><i class="bi bi-clock me-1"></i>{{ $note->created_at->diffForHumans() }}</span>
            @if($note->pipelineEtape)<span>· <i class="bi bi-tag me-1"></i>{{ $note->pipelineEtape->titre }}</span>@endif
          </div>
        </div>
      @empty
        <p class="text-center text-muted small py-2">Aucune note saisie.</p>
      @endforelse
    </div>

  </div>

  {{-- ── COLONNE DROITE ── --}}
  <div class="col-lg-4">
    <div class="stu-card mb-4">
      <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.6px;margin-bottom:14px;">
        <i class="bi bi-person-badge-fill me-1 text-primary"></i>Fiche Client
      </div>
      <div style="font-size:13px;color:#333;display:flex;flex-direction:column;gap:12px;">
        <div><span class="text-muted d-block" style="font-size:11px;">Nom complet</span>
          <strong style="color:#1B3A6B;">{{ $consultation->full_name ?? optional($consultation->user)->name }}</strong></div>
        <div class="row g-2">
          <div class="col-6"><span class="text-muted d-block" style="font-size:11px;">Nationalité</span><strong>🌍 {{ $consultation->nationality ?? '—' }}</strong></div>
          <div class="col-6"><span class="text-muted d-block" style="font-size:11px;">Résidence</span><strong>📍 {{ $consultation->residence_country ?? '—' }}</strong></div>
        </div>
        <div class="row g-2">
          <div class="col-6"><span class="text-muted d-block" style="font-size:11px;">Naissance</span><strong>📅 {{ $consultation->birth_date ? \Carbon\Carbon::parse($consultation->birth_date)->format('d/m/Y') : '—' }}</strong></div>
          <div class="col-6"><span class="text-muted d-block" style="font-size:11px;">Profession</span><strong>💼 {{ $consultation->profession ?? '—' }}</strong></div>
        </div>
        <div class="border-top pt-2">
          <div><i class="bi bi-whatsapp text-success me-2"></i>{{ $consultation->phone ?? '—' }}</div>
          <div><i class="bi bi-envelope text-secondary me-2"></i>{{ $consultation->email ?? '—' }}</div>
        </div>
      </div>
    </div>

    <div class="stu-card mb-4">
      <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
        <i class="bi bi-compass me-1 text-warning"></i>Objectif Ciblé
      </div>
      <div style="font-size:13px;">
        <div class="mb-2"><span class="text-muted small d-block">Destination</span>
          <span class="fw-bold" style="font-size:14px;"><i class="bi bi-airplane-sign me-1 text-primary"></i>{{ $consultation->destination_country ?? '—' }}</span></div>
        <div class="mb-2"><span class="text-muted small d-block">Dernier diplôme</span>
          <span class="badge bg-light text-dark border p-2 fw-semibold w-100 text-start">{{ $consultation->last_degree ?? '—' }}</span></div>
        <div class="mt-2 p-2 bg-white rounded border">
          <span class="text-muted small d-block mb-1">Refus de visa antérieur</span>
          @if($consultation->visa_history)
            <span class="badge bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Oui</span>
            <p class="small text-muted mb-0 bg-light p-2 rounded mt-1" style="font-size:11px;">{{ $consultation->visa_history_details }}</p>
          @else
            <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-shield-check me-1"></i>Aucun refus</span>
          @endif
        </div>
      </div>
    </div>

    {{-- ── INVENTAIRE DES DOCUMENTS (Dossier Documentaire) ── --}}
    <div class="stu-card mb-4">
        <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
            <i class="bi bi-folder-fill me-1 text-info"></i>Dossier Documentaire Global
        </div>

        @if($consultation->documents->count() > 0)
            <div class="d-flex flex-column gap-2 custom-scrollbar" style="max-height: 400px; overflow-y: auto; padding-right: 4px;">
                @foreach($consultation->documents->sortByDesc('created_at') as $doc)
                    @php
                        // Définition des couleurs selon le statut
                        $color = match($doc->status) {
                            'valide' => '#1cc88a', // Vert
                            'rejete' => '#e74a3b', // Rouge
                            default  => '#f6c23e', // Jaune/Orange (en_attente)
                        };
                    @endphp

                    <div class="p-2 bg-white rounded-3 border-start border-4 shadow-sm" 
                        style="font-size:12px; border-left-color: {{ $color }} !important; border-top: 1px solid #eee; border-right: 1px solid #eee; border-bottom: 1px solid #eee;">
                        
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div class="overflow-hidden">
                                <div class="fw-bold text-dark text-truncate">{{ $doc->name }}</div>
                                <div class="text-muted" style="font-size:9px; text-transform:uppercase;">
                                    {{ str_replace('_', ' ', $doc->type) }} · Étape {{ $doc->etape_index }}
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-1">
                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-light border py-0 px-2" title="Voir le fichier">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Formulaire de changement de statut --}}
                        <form action="{{ route('consultant.updateDocumentsStatus', $doc->id) }}" method="POST" class="mt-2 pt-2 border-top d-flex align-items-center gap-2">
                            @csrf
                            @method('PATCH')
                            
                            <select name="status" class="form-select form-select-sm border-0 bg-light" style="font-size: 10px; height: 26px; font-weight:600; color: {{ $color }};" onchange="this.form.submit()">
                                <option value="en_attente" {{ $doc->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="valide" {{ $doc->status == 'valide' ? 'selected' : '' }}>Validé</option>
                                <option value="rejete" {{ $doc->status == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                            </select>
                            
                            <input type="text" name="comment" class="form-control form-control-sm" placeholder="Motif si rejet..." 
                                  value="{{ $doc->comment }}" style="font-size: 10px; height: 26px;">
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center p-3 text-muted small">Aucun document soumis.</div>
        @endif
    </div>

    {{-- Résumé pipeline dans la sidebar --}}
    <div class="stu-card">
      <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
        <i class="bi bi-list-check me-1 text-success"></i>Résumé de la Pipeline
      </div>
      @foreach($consultation->pipelineEtapes as $etape)
        <div class="d-flex align-items-center gap-2 py-1 cursor-pointer"
             onclick="afficherEtape({{ $etape->id }})"
             style="font-size:12px;cursor:pointer;border-radius:6px;padding:6px 8px !important;transition:background .2s;"
             onmouseover="this.style.background='#f5f7ff'"
             onmouseout="this.style.background='transparent'">
          <span style="width:20px;height:20px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;
                        background:{{ $etape->statut==='valide' ? '#1cc88a' : ($etape->statut==='rejete' ? '#e24b4a' : ($etape->statut==='en_cours' ? '#1B3A6B' : '#eee')) }};
                        color:{{ in_array($etape->statut, ['valide','rejete','en_cours']) ? '#fff' : '#999' }};">
            @if($etape->statut==='valide') <i class="bi bi-check" style="font-size:10px;"></i>
            @elseif($etape->statut==='rejete') <i class="bi bi-x" style="font-size:10px;"></i>
            @else {{ $loop->iteration }} @endif
          </span>
          <span class="{{ $etape->statut==='en_cours' ? 'fw-bold text-primary' : '' }}" style="line-height:1.3;">{{ $etape->titre }}</span>
          @if($etape->statut==='valide') <i class="bi bi-check-circle-fill text-success ms-auto" style="font-size:11px;"></i> @endif
          @if($etape->statut==='rejete') <i class="bi bi-x-circle-fill text-danger ms-auto" style="font-size:11px;"></i> @endif
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ── MODAL RDV ── --}}
<div class="modal fade" id="rdvModal" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header border-0 bg-light rounded-top-4 py-3">
        <h5 class="modal-title fw-bold" style="color:#1B3A6B;font-size:15px;">
          <i class="bi bi-calendar-plus me-2 text-primary"></i>Planifier un Entretien
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('consultant.rdv.programmer', $consultation->id) }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold small text-secondary">Canal *</label>
            <select name="canal" class="form-select rounded-3" required>
              <option value="video">Google Meet / Zoom</option>
              <option value="telephone">Téléphone / WhatsApp</option>
              <option value="presentiel">Présentiel au cabinet</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold small text-secondary">Date et heure *</label>
            <input type="datetime-local" name="date_heure" class="form-control rounded-3" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold small text-secondary">Lien visio (optionnel)</label>
            <input type="url" name="lien_visio" class="form-control rounded-3" placeholder="https://meet.google.com/...">
          </div>
          <div class="mb-0">
            <label class="form-label fw-bold small text-secondary">Ordre du jour / Note</label>
            <textarea name="compte_rendu" class="form-control rounded-3" rows="2" placeholder="Cadrage du dossier, revue des pièces..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0 px-4 pb-4">
          <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="font-size:13px;font-weight:600;">Annuler</button>
          <button type="submit" class="btn text-white rounded-3 px-4" style="background-color:#1B3A6B;font-size:13px;font-weight:600;">
            Programmer & Notifier <i class="bi bi-bell ms-1"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Affiche le bloc correspondant à l'étape cliquée et masque les autres
function afficherEtape(etapeId) {
  document.querySelectorAll('.etape-content-block').forEach(el => {
    el.classList.remove('active');
  });
  const target = document.getElementById('etape-block-' + etapeId);
  if (target) {
    target.classList.add('active');
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
  // Highlight le cercle sélectionné dans la pipeline
  document.querySelectorAll('.wizard-step-item').forEach(el => {
    el.style.opacity = '0.5';
  });
  // Retrouver le bon cercle par ordre
  const allSteps = document.querySelectorAll('.wizard-step-item');
  allSteps.forEach(el => { el.style.opacity = '1'; }); // reset
}
</script>
@endpush

@endsection