{{-- resources/views/consultants/dashboard.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Espace Consultant')
@section('meta_description', 'VisaFly - Gestion et suivi des dossiers de consultation d’immigration.')

@push('styles')
<style>
.stu-card{background:#fff;border-radius:14px;border:1px solid #eee;
          padding:20px;box-shadow:0 2px 12px rgba(27,58,107,.05);}
.stu-stat-num{font-size:2rem;font-weight:800;line-height:1;margin-bottom:4px;}
.stu-stat-lbl{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}
.badge-consult{padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;border: none;display: inline-block;}
.s-en_attente{background:rgba(245,166,35,.12);color:#633806;}
.s-en_cours  {background:rgba(27,58,107,.1); color:#1B3A6B;}
.s-approuvee {background:rgba(28,200,138,.1);color:#0f6e56;}
.s-declinee  {background:rgba(226,75,74,.1); color:#a32d2d;}
.s-annulee   {background:#f0f0f0;color:#888;}
.s-terminee  {background:rgba(127,119,221,.12);color:#3C3489;}

.consult-row {display:flex;align-items:center;justify-content:space-between;
              padding:16px 0;border-bottom:1px solid #f5f5f5;gap: 15px;}
.consult-row:last-child{border-bottom:none;}
.status-select {font-size: 12px; font-weight: 600; padding: 5px 10px; border-radius: 8px; border: 1px solid #ddd; color: #333; background-color: #fafafa;}
.status-select:focus {border-color: #1B3A6B; outline: none;}

/* ══ STYLES DU WIZARD MODAL ══ */
.wizard-progress-container { margin-bottom: 25px; position: relative; }
.wizard-steps-header { display: flex; justify-content: space-between; position: relative; margin-bottom: 15px; z-index: 1; }
.wizard-progress-line { position: absolute; top: 18px; left: 0; height: 3px; background: #eee; width: 100%; z-index: -1; }
.wizard-progress-bar-fill { position: absolute; top: 18px; left: 0; height: 3px; background: #1B3A6B; width: 0%; transition: width 0.3s ease; z-index: -1; }
.wizard-step-item { text-align: center; flex: 1; display: flex; flex-direction: column; align-items: center; }
.wizard-step-circle { width: 36px; height: 36px; border-radius: 50%; background: #fff; border: 2px solid #eee; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #999; transition: all 0.3s ease; }
.wizard-step-item.active .wizard-step-circle { background: #1B3A6B; border-color: #1B3A6B; color: #fff; }
.wizard-step-item.completed .wizard-step-circle { background: #1cc88a; border-color: #1cc88a; color: #fff; }
.wizard-step-label { font-size: 10px; font-weight: 600; color: #888; margin-top: 5px; text-transform: uppercase; line-height: 1.2; }
.wizard-step-item.active .wizard-step-label { color: #1B3A6B; font-weight: 700; }
.wizard-progress-text { font-size: 12px; font-weight: 700; color: #555; text-align: center; }
.wizard-step-content { display: none; }
.wizard-step-content.active { display: block; }
.wizard-modal-footer { display: flex; justify-content: space-between; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; }
.form-group { margin-bottom: 15px; }
.form-group label { font-size: 12px; font-weight: 600; color: #1B3A6B; margin-bottom: 5px; display: block; }
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.5rem;">
      Espace Consultant : {{ Auth::user()->first_name }} 💼
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">
      {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }} · <span class="badge bg-primary">Vérification en cours</span>
    </p>
  </div>
</div>

{{-- Alertes de mise à jour du statut --}}
@if(session('success'))
  <div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
      style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
  </div>
@endif

{{-- ══ STATS CONSULTANT ══ --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stu-card">
      <div class="stu-stat-num" style="color:#F5A623;">{{ $stats['consultations_en_attente'] ?? 0 }}</div>
      <div class="stu-stat-lbl">En attente</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stu-card">
      <div class="stu-stat-num" style="color:#1B3A6B;">{{ $stats['consultations_en_cours'] ?? 0 }}</div>
      <div class="stu-stat-lbl">En cours de traitement</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stu-card">
      <div class="stu-stat-num" style="color:#1cc88a;">{{ $stats['consultations_terminees'] ?? 0 }}</div>
      <div class="stu-stat-lbl">Clôturées / Terminées</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stu-card">
      <div class="stu-stat-num" style="color:#3C3489;">
        {{ ($stats['consultations_en_attente'] ?? 0) + ($stats['consultations_en_cours'] ?? 0) + ($stats['consultations_terminees'] ?? 0) }}
      </div>
      <div class="stu-stat-lbl">Total Assignées</div>
    </div>
  </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <form action="{{ route('consultant.dashboard') }}" method="GET" class="input-group">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Rechercher par dossier (ex: VF-202606-0001)..." 
                   value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if(request()->has('search'))
                <a href="{{ route('consultant.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </a>
            @endif
        </form>
    </div>
</div>

<div class="row g-4">

  {{-- ══ Colonne Gauche : Liste des consultations à vérifier ══ --}}
  <div class="col-lg-8">
    <div class="stu-card mb-4">
      <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div style="font-size:14px;font-weight:700;color:#1B3A6B;">
          <i class="bi bi-shield-check me-2" style="color:#F5A623;"></i>Consultations à vérifier & traiter
        </div>
        {{-- Nouveau Bouton qui déclenche le Modal Wizard --}}
        <button type="button" class="btn btn-sm text-white px-3 fw-bold rounded-3" style="background-color: #1B3A6B; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#wizardModal">
          <i class="bi bi-plus-circle me-1"></i> Ouvrir une consultation
        </button>
      </div>

        @forelse($mesConsultations as $c)
        <div class="consult-row">
          <div style="flex:1;min-width:0;">
            <div style="font-size:13px;font-weight:700;color:#1B3A6B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              {{ $c->objet ?? ($c->project_type ?? 'Consultation sans objet') }}
            </div>
            
            {{-- Infos de l'étudiant/client --}}
            <div style="font-size:12px; color:#333; margin-top:2px; font-weight: 500;">
              <i class="bi bi-person me-1"></i>Client : {{ $c->full_name ?? 'Utilisateur Inconnu' }}
            </div>

            <div style="font-size:11px;color:#888;margin-top:2px;">
              Soumise le {{ $c->created_at->format('d/m/Y à H:i') }}
              @if($c->date_confirmee)
                · <strong style="color: #1B3A6B;">RDV : {{ \Carbon\Carbon::parse($c->date_confirmee)->format('d/m/Y à H:i') }}</strong>
              @endif
            </div>
          </div>

          {{-- Actions : Statut rapide, Badge et Bouton Traiter (Show) --}}
          <div class="flex-shrink-0 d-flex align-items-center gap-2">
            @php
              $currentStatus = $c->status ?? 'en_attente';
            @endphp

            {{-- Formulaire d'action directe pour changer le statut --}}
            <form action="{{ route('consultant.updateStatus', $c->id) }}" method="POST" class="d-inline">
              @csrf
              @method('PATCH')
              <select name="status" class="status-select" onchange="this.form.submit()">
                <option value="en_attente" {{ $currentStatus == 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
                <option value="en_cours" {{ $currentStatus == 'en_cours' ? 'selected' : '' }}>⚙️ En cours</option>
                <option value="approuvee" {{ $currentStatus == 'approuvee' ? 'selected' : '' }}>✅ Approuvée</option>
                <option value="declinee" {{ $currentStatus == 'declinee' ? 'selected' : '' }}>❌ Déclinée</option>
                <option value="annulee" {{ $currentStatus == 'annulee' ? 'selected' : '' }}>🚫 Annulée</option>
                <option value="terminee" {{ $currentStatus == 'terminee' ? 'selected' : '' }}>🏁 Terminée</option>
              </select>
            </form>

            {{-- Badge visuel de l'état actuel --}}
            <span class="badge-consult s-{{ $currentStatus }} d-none d-sm-inline-block">
              @php
                $labels = [
                  'en_attente'=>'Attente', 'en_cours'=>'En cours', 'approuvee'=>'Approuvée',
                  'declinee'=>'Déclinée', 'annulee'=>'Annulée', 'terminee'=>'Terminée'
                ];
              @endphp
              {{ $labels[$currentStatus] ?? $currentStatus }}
            </span>

            {{-- 👁️ Nouveau Bouton pour Voir / Traiter la consultation --}}
            <a href="{{ route('consultant.show', $c->id) }}" 
              class="btn btn-sm text-white px-3 fw-bold rounded-3 d-flex align-items-center gap-1" 
              style="background-color: #1B3A6B; font-size: 11px; padding: 6px 12px; transition: background 0.2s;"
              onmouseover="this.style.backgroundColor='#142b52'"
              onmouseout="this.style.backgroundColor='#1B3A6B'">
              <i class="bi bi-eye-fill"></i> Traiter
            </a>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:40px 0;color:#aaa;">
          <i class="bi bi-folder-x" style="font-size:34px;display:block;margin-bottom:8px;"></i>
          <span style="font-size:13px;">Aucune demande de consultation ne vous est assignée actuellement.</span>
        </div>
        @endforelse
    </div>
  </div>

  {{-- ══ Colonne Droite : Vue d'ensemble Profil & Résumé ══ --}}
  <div class="col-lg-4">
    
    {{-- Profil rapide du consultant --}}
    <div class="stu-card mb-4">
      <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.6px;margin-bottom:14px;">
        Mon Profil Consultant
      </div>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
        <div style="width:46px;height:46px;border-radius:50%;background:#F5A623;
                    display:flex;align-items:center;justify-content:center;
                    font-size:16px;font-weight:700;color:#1B3A6B;flex-shrink:0;
                    border:2px solid #1B3A6B;">
          {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
        </div>
        <div>
          <div style="font-weight:700;color:#1B3A6B;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
          <div style="font-size:12px;color:#888;">Expert Visa & Immigration</div>
        </div>
      </div>
      
      <div style="font-size:12px;color:#888;display:flex;flex-direction:column;gap:6px;border-top:1px solid #f5f5f5;padding-top:12px;">
        <div><i class="bi bi-envelope me-2" style="color:#F5A623;"></i>{{ Auth::user()->email }}</div>
        @if(Auth::user()->phone)
          <div><i class="bi bi-telephone me-2" style="color:#F5A623;"></i>{{ Auth::user()->phone }}</div>
        @endif
        <div><i class="bi bi-briefcase me-2" style="color:#F5A623;"></i>Rôle : Spécialiste Consultant</div>
      </div>
    </div>

    {{-- Consignes de traitement VisaFly --}}
    <div class="stu-card" style="background: rgba(27,58,107,.02); border-color: rgba(27,58,107,.1);">
      <div style="font-size:12px;font-weight:700;color:#1B3A6B;margin-bottom:8px;">
        <i class="bi bi-info-circle-fill me-1" style="color:#F5A623;"></i> Guide de vérification
      </div>
      <ul style="font-size:12px; color:#555; padding-left:20px; margin-bottom:0; line-height:1.6;">
        <li>Passez le statut à <strong>En cours</strong> dès le début de l'analyse documentaire.</li>
        <li>Contactez le client si des pièces d'immigration manquent à son dossier.</li>
        <li>Clôturez via l'état <strong>Terminée</strong> une fois l'entretien ou le livrable fourni.</li>
      </ul>
    </div>

  </div>
</div>

{{-- ══ MODAL WIZARD FORMULAIRE ══ --}}
<div class="modal fade" id="wizardModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="wizardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header border-0 bg-light rounded-top-4 py-3">
        <h5 class="modal-title fw-bold" id="wizardModalLabel" style="color:#1B3A6B; font-size:16px;">
          <i class="bi bi-folder-plus me-2 text-warning"></i>Nouvelle demande de consultation
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        
        {{-- En-tête de Progression --}}
        <div class="wizard-progress-container">
          <div class="wizard-steps-header">
            <div class="wizard-progress-line"></div>
            <div class="wizard-progress-bar-fill" id="wizardProgressLine"></div>

            <div class="wizard-step-item active" id="step-indicator-1">
              <div class="wizard-step-circle">1</div>
              <span class="wizard-step-label">Informations<br>Personnelles</span>
            </div>
            <div class="wizard-step-item" id="step-indicator-2">
              <div class="wizard-step-circle">2</div>
              <span class="wizard-step-label">Projet<br>Visa</span>
            </div>
            <div class="wizard-step-item" id="step-indicator-3">
              <div class="wizard-step-circle">3</div>
              <span class="wizard-step-label">Profil<br>Académique</span>
            </div>
            <div class="wizard-step-item" id="step-indicator-4">
              <div class="wizard-step-circle">4</div>
              <span class="wizard-step-label">Documents &<br>Finalisation</span>
            </div>
          </div>
          <div class="wizard-progress-text">
            Étape <span id="currentStepText">1</span> sur 4
          </div>
        </div>

        {{-- Formulaire unique --}}
        <form id="wizard_with_validation" method="POST" action="{{ route('consultant.store') }}" enctype="multipart/form-data">
          @csrf

          <!-- 1️⃣ ÉTAPE : Informations personnelles -->
          <div class="wizard-step-content active" id="step-content-1">
            <h5 class="fw-bold mb-3 text-secondary" style="font-size:14px;">1. Informations Personnelles</h5>
            
            <div class="form-group mb-3">
              <label>Nom complet *</label>
              <input type="text" name="full_name" class="form-control rounded-3" value="{{ old('full_name') }}" required>
            </div>

            <div class="form-group mb-3">
              <label>Date de naissance *</label>
              <input type="date" name="birth_date" class="form-control rounded-3" value="{{ old('birth_date') }}" required>
            </div>

            <div class="form-group mb-3">
              <label>Nationalité *</label>
              <select name="nationality" class="form-control rounded-3" required>
                <option value="">-- Sélectionnez --</option>
                @foreach ($nationalities as $nat)
                  <option value="{{ $nat }}" {{ old('nationality') == $nat ? 'selected' : '' }}>{{ $nat }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group mb-3">
              <label>Pays de résidence *</label>
              <select name="residence_country" class="form-control rounded-3" required>
                <option value="">-- Sélectionnez --</option>
                @foreach ($countries as $country)
                  <option value="{{ $country }}" {{ old('residence_country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group mb-3">
              <label>Téléphone / WhatsApp *</label>
              <input type="text" name="phone" class="form-control rounded-3" value="{{ old('phone') }}" required>
            </div>

            <div class="form-group mb-3">
              <label>Email *</label>
              <input type="email" name="email" class="form-control rounded-3" value="{{ old('email') }}" required>
            </div>

            <div class="form-group mb-3">
              <label>Profession / Statut *</label>
              <input type="text" name="profession" class="form-control rounded-3" value="{{ old('profession') }}" required>
            </div>
          </div>

          <!-- 2️⃣ ÉTAPE : Projet Visa -->
          <div class="wizard-step-content" id="step-content-2">
            <h5 class="fw-bold mb-3 text-secondary" style="font-size:14px;">2. Projet de Visa</h5>
            
            <div class="form-group mb-3">
              <label>Objectif principal *</label>
              <select name="project_type" class="form-control rounded-3" required>
                <option value="">-- Sélectionnez --</option>
                <option {{ old('project_type') == 'Étudier à l’étranger' ? 'selected' : '' }}>Étudier à l’étranger</option>
                <option {{ old('project_type') == 'Travailler à l’étranger' ? 'selected' : '' }}>Travailler à l’étranger</option>
                <option {{ old('project_type') == 'Visa business / investissement' ? 'selected' : '' }}>Visa business / investissement</option>
                <option {{ old('project_type') == 'Voyage touristique / visite familiale' ? 'selected' : '' }}>Voyage touristique / visite familiale</option>
                <option {{ old('project_type') == 'Autre' ? 'selected' : '' }}>Autre</option>
              </select>
            </div>

            <div class="form-group mb-3">
              <label>Pays souhaité *</label>
              <select name="destination_country" class="form-control rounded-3" required>
                <option value="">-- Sélectionnez --</option>
                @foreach ($countries as $country)
                  <option value="{{ $country }}" {{ old('destination_country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group mb-3">
              <label>Avez-vous déjà demandé un visa ? *</label>
              <select name="visa_history" class="form-control rounded-3" required id="visa_history_select" onchange="toggleVisaDetails(this)">
                <option value="">-- Sélectionnez --</option>
                <option value="1" {{ old('visa_history') == '1' ? 'selected' : '' }}>Oui</option>
                <option value="0" {{ old('visa_history') == '0' ? 'selected' : '' }}>Non</option>
              </select>
            </div>

            <div class="form-group mb-3" id="visa_history_details_wrapper" style="display: none;">
              <label>Détails si OUI</label>
              <textarea name="visa_history_details" class="form-control rounded-3" rows="3">{{ old('visa_history_details') }}</textarea>
            </div>
          </div>

          <!-- 3️⃣ ÉTAPE : Profil Académique & Professionnel -->
          <div class="wizard-step-content" id="step-content-3">
            <h5 class="fw-bold mb-3 text-secondary" style="font-size:14px;">3. Profil Académique & Professionnel</h5>
            
            <div class="form-group mb-3">
              <label>Dernier diplôme obtenu *</label>
              <select name="last_degree" class="form-control rounded-3" required>
                <option value="">-- Sélectionnez --</option>
                <optgroup label="🏫 Primaire">
                  <option value="CEPE" {{ old('last_degree') == 'CEPE' ? 'selected' : '' }}>CEPE – Certificat d'Études Primaires</option>
                </optgroup>
                <optgroup label="🏫 Secondaire 1er cycle">
                  <option value="BEPC" {{ old('last_degree') == 'BEPC' ? 'selected' : '' }}>BEPC – Brevet d'Études du Premier Cycle</option>
                  <option value="CAP" {{ old('last_degree') == 'CAP' ? 'selected' : '' }}>CAP – Certificat d'Aptitude Professionnelle</option>
                  <option value="BEP" {{ old('last_degree') == 'BEP' ? 'selected' : '' }}>BEP – Brevet d'Études Professionnelles</option>
                </optgroup>
                <optgroup label="🎓 Secondaire 2ème cycle">
                  <option value="BAC_GENERAL" {{ old('last_degree') == 'BAC_GENERAL' ? 'selected' : '' }}>Baccalauréat Général</option>
                  <option value="BAC_TECHNO" {{ old('last_degree') == 'BAC_TECHNO' ? 'selected' : '' }}>Baccalauréat Technologique</option>
                  <option value="BAC_PRO" {{ old('last_degree') == 'BAC_PRO' ? 'selected' : '' }}>Baccalauréat Professionnel</option>
                  <option value="BAC_TECHNIQUE" {{ old('last_degree') == 'BAC_TECHNIQUE' ? 'selected' : '' }}>Baccalauréat Technique</option>
                  <option value="GCE_OL" {{ old('last_degree') == 'GCE_OL' ? 'selected' : '' }}>GCE O/L – Ordinary Level (système anglophone)</option>
                  <option value="GCE_AL" {{ old('last_degree') == 'GCE_AL' ? 'selected' : '' }}>GCE A/L – Advanced Level (système anglophone)</option>
                  <option value="BTI" {{ old('last_degree') == 'BTI' ? 'selected' : '' }}>BTI – Brevet de Technicien Industrien</option>
                </optgroup>
                <optgroup label="📘 Bac+2">
                  <option value="BTS" {{ old('last_degree') == 'BTS' ? 'selected' : '' }}>BTS – Brevet de Technicien Supérieur</option>
                  <option value="DUT" {{ old('last_degree') == 'DUT' ? 'selected' : '' }}>DUT – Diplôme Universitaire de Technologie</option>
                  <option value="DEUG" {{ old('last_degree') == 'DEUG' ? 'selected' : '' }}>DEUG – Diplôme d'Études Universitaires Générales</option>
                  <option value="CPGE" {{ old('last_degree') == 'CPGE' ? 'selected' : '' }}>CPGE – Classe Préparatoire aux Grandes Écoles</option>
                  <option value="DTS" {{ old('last_degree') == 'DTS' ? 'selected' : '' }}>DTS – Diplôme de Technicien Supérieur</option>
                </optgroup>
                <optgroup label="📘 Bac+3">
                  <option value="LICENCE" {{ old('last_degree') == 'LICENCE' ? 'selected' : '' }}>Licence (L3)</option>
                  <option value="LICENCE_PRO" {{ old('last_degree') == 'LICENCE_PRO' ? 'selected' : '' }}>Licence Professionnelle</option>
                  <option value="BSC" {{ old('last_degree') == 'BSC' ? 'selected' : '' }}>BSc – Bachelor of Science</option>
                  <option value="BA" {{ old('last_degree') == 'BA' ? 'selected' : '' }}>BA – Bachelor of Arts</option>
                  <option value="HND" {{ old('last_degree') == 'HND' ? 'selected' : '' }}>HND – Higher National Diploma</option>
                </optgroup>
                <optgroup label="📙 Bac+4">
                  <option value="MAITRISE" {{ old('last_degree') == 'MAITRISE' ? 'selected' : '' }}>Maîtrise (ancienne filière)</option>
                  <option value="M1" {{ old('last_degree') == 'M1' ? 'selected' : '' }}>Master 1 (M1)</option>
                </optgroup>
                <optgroup label="📕 Bac+5 (Master / Bac+5 Grandes Écoles)">
                  <option value="MASTER" {{ old('last_degree') == 'MASTER' ? 'selected' : '' }}>Master 2 (M2)</option>
                  <option value="MASTER_PRO" {{ old('last_degree') == 'MASTER_PRO' ? 'selected' : '' }}>Master Professionnel</option>
                  <option value="MASTER_RECHERCHE" {{ old('last_degree') == 'MASTER_RECHERCHE' ? 'selected' : '' }}>Master Recherche</option>
                  <option value="MSC" {{ old('last_degree') == 'MSC' ? 'selected' : '' }}>MSc – Master of Science</option>
                  <option value="MBA" {{ old('last_degree') == 'MBA' ? 'selected' : '' }}>MBA – Master of Business Administration</option>
                  <option value="INGENIEUR" {{ old('last_degree') == 'INGENIEUR' ? 'selected' : '' }}>Diplôme d'Ingénieur</option>
                  <option value="DESS" {{ old('last_degree') == 'DESS' ? 'selected' : '' }}>DESS – Diplôme d'Études Supérieures Spécialisées</option>
                </optgroup>
              </select>
            </div>
          </div>

          <!-- 4️⃣ ÉTAPE : Documents & Finalisation -->
          <div class="wizard-step-content" id="step-content-4">
            <h5 class="fw-bold mb-3 text-secondary" style="font-size:14px;">4. Documents & Finalisation</h5>
            
            <div class="text-center py-3">
              <i class="bi bi-file-earmark-check text-success" style="font-size: 40px;"></i>
              <p class="text-muted mt-2" style="font-size: 13px;">
                Toutes les sections précédentes ont été complétées.<br>
                Cliquez sur <strong>"Soumettre la demande"</strong> pour enregistrer la consultation.
              </p>
            </div>
          </div>

          {{-- Boutons de bas de page du Wizard --}}
          <div class="wizard-modal-footer">
            <button type="button" class="btn btn-light rounded-3 px-4" id="btnPrev" style="display:none; font-size:13px; font-weight:600;">
              <i class="bi bi-arrow-left me-1"></i> Précédent
            </button>
            <div class="ms-auto">
              <button type="button" class="btn text-white rounded-3 px-4" id="btnNext" style="background-color: #1B3A6B; font-size:13px; font-weight:600;">
                Suivant <i class="bi bi-arrow-right ms-1"></i>
              </button>
              <button type="submit" class="btn btn-success rounded-3 px-4" id="btnSubmit" style="display:none; font-size:13px; font-weight:600;">
                <i class="bi bi-check-circle me-1"></i> Soumettre la demande
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Gestion du champ conditionnel Visa Histoire
  function toggleVisaDetails(select) {
    var wrapper = document.getElementById('visa_history_details_wrapper');
    if (select.value === '1') {
      wrapper.style.display = 'block';
    } else {
      wrapper.style.display = 'none';
    }
  }

  // Script du fonctionnement pas-à-pas (Wizard)
  document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 4;

    const btnPrev = document.getElementById("btnPrev");
    const btnNext = document.getElementById("btnNext");
    const btnSubmit = document.getElementById("btnSubmit");
    const currentStepText = document.getElementById("currentStepText");
    const progressLine = document.getElementById("wizardProgressLine");
    const form = document.getElementById("wizard_with_validation");

    function updateWizard() {
      // Affichage des blocs de contenu
      document.querySelectorAll(".wizard-step-content").forEach((step, idx) => {
        if (idx + 1 === currentStep) {
          step.classList.add("active");
        } else {
          step.classList.remove("active");
        }
      });

      // Affichage des ronds d'en-tête de progression
      document.querySelectorAll(".wizard-step-item").forEach((item, idx) => {
        const stepNum = idx + 1;
        item.classList.remove("active", "completed");
        
        if (stepNum === currentStep) {
          item.classList.add("active");
        } else if (stepNum < currentStep) {
          item.classList.add("completed");
        }
      });

      // Ligne de progression
      const progressPercent = ((currentStep - 1) / (totalSteps - 1)) * 100;
      progressLine.style.width = progressPercent + "%";

      // Texte indicateur
      currentStepText.innerText = currentStep;

      // Visibilité dynamique des boutons de navigation
      btnPrev.style.display = currentStep === 1 ? "none" : "inline-block";
      if (currentStep === totalSteps) {
        btnNext.style.display = "none";
        btnSubmit.style.display = "inline-block";
      } else {
        btnNext.style.display = "inline-block";
        btnSubmit.style.display = "none";
      }
    }

    // Validation des inputs requis uniquement pour l'étape courante
    function validateCurrentStep() {
      const currentStepContainer = document.getElementById(`step-content-${currentStep}`);
      const requiredInputs = currentStepContainer.querySelectorAll("[required]");
      let isValid = true;

      requiredInputs.forEach(input => {
        if (!input.value.trim()) {
          isValid = false;
          input.classList.add("is-invalid");
        } else {
          input.classList.remove("is-invalid");
        }
      });

      return isValid;
    }

    btnNext.addEventListener("click", function () {
      if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
          currentStep++;
          updateWizard();
        }
      } else {
        // Alerte optionnelle ou scroll vers l'élément manquant
        const firstInvalid = document.querySelector(".is-invalid");
        if(firstInvalid) firstInvalid.focus();
      }
    });

    btnPrev.addEventListener("click", function () {
      if (currentStep > 1) {
        currentStep--;
        updateWizard();
      }
    });

    // Initialisation
    updateWizard();
    
    // Forcer le toggle au chargement de la page pour le select existant si nécessaire
    var selectHistory = document.getElementById('visa_history_select');
    if (selectHistory) toggleVisaDetails(selectHistory);
  });
</script>
@endpush