@extends('layouts/consultation')


@section('space-work')
    <section class="section">
        <div class="section-body">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h4>Passer une Consultation</h4>
                </div>
                {{-- Barre de progression --}}
                <div class="wizard-progress-container">
                    <div class="wizard-steps-header">
                        <div class="wizard-progress-line" id="wizardProgressLine"></div>

                        <div class="wizard-step-item active" id="step-indicator-1">
                            <div class="wizard-step-circle active" id="step-circle-1">1</div>
                            <span class="wizard-step-label">Informations<br>Personnelles</span>
                        </div>
                        <div class="wizard-step-item" id="step-indicator-2">
                            <div class="wizard-step-circle" id="step-circle-2">2</div>
                            <span class="wizard-step-label">Projet<br>Visa</span>
                        </div>
                        <div class="wizard-step-item" id="step-indicator-3">
                            <div class="wizard-step-circle" id="step-circle-3">3</div>
                            <span class="wizard-step-label">Profil<br>Académique</span>
                        </div>
                        <div class="wizard-step-item" id="step-indicator-4">
                            <div class="wizard-step-circle" id="step-circle-4">4</div>
                            <span class="wizard-step-label">Documents &<br>Finalisation</span>
                        </div>
                    </div>
                    <div class="wizard-progress-text">
                        Étape <span id="currentStepText">1</span> sur 4
                    </div>
                </div>
                <div class="card-body wizard clearfix">
                    <form id="wizard_with_validation" method="POST" action="{{ route('consultation.store') }}">
                        @csrf

                        <button type="submit" id="realSubmit" style="display:none;"></button>

                        <!-- Informations personnelles -->
                        <h3>Informations Personnelles</h3>
                        <fieldset>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Nom complet *</label>
                                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                                </div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Date de naissance *</label>
                                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}"  required>
                                </div>
                            </div>

                            {{-- Nationalité --}}
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Nationalité *</label>
                                    <select name="nationality" class="form-control" required>
                                        <option value="">-- Sélectionnez --</option>
                                        @foreach($nationalities as $nat)
                                            <option value="{{ $nat }}" {{ old('nationality') == $nat ? 'selected' : '' }}>
                                                {{ $nat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Pays de résidence --}}
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Pays de résidence *</label>
                                    <select name="residence_country" class="form-control" required>
                                        <option value="">-- Sélectionnez --</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country }}" {{ old('residence_country') == $country ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Téléphone / WhatsApp *</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                </div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Profession / Statut *</label>
                                    <input type="text" name="profession" class="form-control" value="{{ old('profession') }}" required>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Projet Visa -->
                        <h3>Projet de Visa</h3>
                        <fieldset>
                            <label class="form-label">Objectif principal *</label>
                            <div class="form-group">
                                <select name="project_type" class="form-control" required>
                                    <option value="">-- Sélectionnez --</option>
                                    <option>Étudier à l’étranger</option>
                                    <option>Travailler à l’étranger</option>
                                    <option>Visa business / investissement</option>
                                    <option>Voyage touristique / visite familiale</option>
                                    <option>Autre</option>
                                </select>
                            </div>

                            {{-- Pays souhaité (étape 2) --}}
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Pays souhaité *</label>
                                    <select name="destination_country" class="form-control" required>
                                        <option value="">-- Sélectionnez --</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country }}" {{ old('destination_country') == $country ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <label class="form-label">Avez-vous déjà demandé un visa ? *</label>
                            <div class="form-group">
                                <select name="visa_history" class="form-control" required 
                                        id="visa_history_select" 
                                        onchange="toggleVisaDetails(this)">
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="1" {{ old('visa_history') == '1' ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ old('visa_history') == '0' ? 'selected' : '' }}>Non</option>
                                </select>
                            </div>

                            <div class="form-group form-float" 
                                id="visa_history_details_wrapper" 
                                style="display: none !important;">
                                <div class="form-line">
                                    <label class="form-label">Détails si OUI</label>
                                    <textarea name="visa_history_details" 
                                            class="form-control" rows="3">{{ old('visa_history_details') }}</textarea>
                                </div>
                            </div>

                            {{-- Script inline directement ici --}}
                            <script>
                                function toggleVisaDetails(select) {
                                    var wrapper = document.getElementById('visa_history_details_wrapper');
                                    if (select.value === '1') {
                                        wrapper.style.setProperty('display', 'block', 'important');
                                    } else {
                                        wrapper.style.setProperty('display', 'none', 'important');
                                    }
                                }

                                // Restaurer l'état si old() après validation Laravel
                                window.addEventListener('load', function () {
                                    var select = document.getElementById('visa_history_select');
                                    if (select) toggleVisaDetails(select);
                                });
                            </script>
                        </fieldset>

                        <!-- 3️⃣ Infos Pro / Académiques -->
                        <h3>Profil Académique & Professionnel</h3>
                        <fieldset>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Dernier diplôme obtenu *</label>
                                    <select name="last_degree" class="form-control" required>
                                        <option value="">-- Sélectionnez --</option>

                                        <optgroup label="🏫 Primaire">
                                            <option value="CEPE" {{ old('last_degree') == 'CEPE' ? 'selected' : '' }}>
                                                CEPE – Certificat d'Études Primaires
                                            </option>
                                        </optgroup>

                                        <optgroup label="🏫 Secondaire 1er cycle">
                                            <option value="BEPC" {{ old('last_degree') == 'BEPC' ? 'selected' : '' }}>
                                                BEPC – Brevet d'Études du Premier Cycle
                                            </option>
                                            <option value="CAP" {{ old('last_degree') == 'CAP' ? 'selected' : '' }}>
                                                CAP – Certificat d'Aptitude Professionnelle
                                            </option>
                                            <option value="BEP" {{ old('last_degree') == 'BEP' ? 'selected' : '' }}>
                                                BEP – Brevet d'Études Professionnelles
                                            </option>
                                        </optgroup>

                                        <optgroup label="🎓 Secondaire 2ème cycle">
                                            <option value="BAC_GENERAL" {{ old('last_degree') == 'BAC_GENERAL' ? 'selected' : '' }}>
                                                Baccalauréat Général
                                            </option>
                                            <option value="BAC_TECHNO" {{ old('last_degree') == 'BAC_TECHNO' ? 'selected' : '' }}>
                                                Baccalauréat Technologique
                                            </option>
                                            <option value="BAC_PRO" {{ old('last_degree') == 'BAC_PRO' ? 'selected' : '' }}>
                                                Baccalauréat Professionnel
                                            </option>
                                            <option value="BAC_TECHNIQUE" {{ old('last_degree') == 'BAC_TECHNIQUE' ? 'selected' : '' }}>
                                                Baccalauréat Technique
                                            </option>
                                            <option value="GCE_OL" {{ old('last_degree') == 'GCE_OL' ? 'selected' : '' }}>
                                                GCE O/L – Ordinary Level (système anglophone)
                                            </option>
                                            <option value="GCE_AL" {{ old('last_degree') == 'GCE_AL' ? 'selected' : '' }}>
                                                GCE A/L – Advanced Level (système anglophone)
                                            </option>
                                            <option value="BTI" {{ old('last_degree') == 'BTI' ? 'selected' : '' }}>
                                                BTI – Brevet de Technicien Industriel
                                            </option>
                                        </optgroup>

                                        <optgroup label="📘 Bac+2">
                                            <option value="BTS" {{ old('last_degree') == 'BTS' ? 'selected' : '' }}>
                                                BTS – Brevet de Technicien Supérieur
                                            </option>
                                            <option value="DUT" {{ old('last_degree') == 'DUT' ? 'selected' : '' }}>
                                                DUT – Diplôme Universitaire de Technologie
                                            </option>
                                            <option value="DEUG" {{ old('last_degree') == 'DEUG' ? 'selected' : '' }}>
                                                DEUG – Diplôme d'Études Universitaires Générales
                                            </option>
                                            <option value="CPGE" {{ old('last_degree') == 'CPGE' ? 'selected' : '' }}>
                                                CPGE – Classe Préparatoire aux Grandes Écoles
                                            </option>
                                            <option value="DTS" {{ old('last_degree') == 'DTS' ? 'selected' : '' }}>
                                                DTS – Diplôme de Technicien Supérieur
                                            </option>
                                        </optgroup>

                                        <optgroup label="📗 Bac+3">
                                            <option value="LICENCE" {{ old('last_degree') == 'LICENCE' ? 'selected' : '' }}>
                                                Licence (L3)
                                            </option>
                                            <option value="LICENCE_PRO" {{ old('last_degree') == 'LICENCE_PRO' ? 'selected' : '' }}>
                                                Licence Professionnelle
                                            </option>
                                            <option value="BSC" {{ old('last_degree') == 'BSC' ? 'selected' : '' }}>
                                                BSc – Bachelor of Science
                                            </option>
                                            <option value="BA" {{ old('last_degree') == 'BA' ? 'selected' : '' }}>
                                                BA – Bachelor of Arts
                                            </option>
                                            <option value="HND" {{ old('last_degree') == 'HND' ? 'selected' : '' }}>
                                                HND – Higher National Diploma
                                            </option>
                                        </optgroup>

                                        <optgroup label="📙 Bac+4">
                                            <option value="MAITRISE" {{ old('last_degree') == 'MAITRISE' ? 'selected' : '' }}>
                                                Maîtrise (ancienne filière)
                                            </option>
                                            <option value="M1" {{ old('last_degree') == 'M1' ? 'selected' : '' }}>
                                                Master 1 (M1)
                                            </option>
                                        </optgroup>

                                        <optgroup label="📕 Bac+5 (Master / Bac+5 Grandes Écoles)">
                                            <option value="MASTER" {{ old('last_degree') == 'MASTER' ? 'selected' : '' }}>
                                                Master 2 (M2)
                                            </option>
                                            <option value="MASTER_PRO" {{ old('last_degree') == 'MASTER_PRO' ? 'selected' : '' }}>
                                                Master Professionnel
                                            </option>
                                            <option value="MASTER_RECHERCHE" {{ old('last_degree') == 'MASTER_RECHERCHE' ? 'selected' : '' }}>
                                                Master Recherche
                                            </option>
                                            <option value="MSC" {{ old('last_degree') == 'MSC' ? 'selected' : '' }}>
                                                MSc – Master of Science
                                            </option>
                                            <option value="MBA" {{ old('last_degree') == 'MBA' ? 'selected' : '' }}>
                                                MBA – Master of Business Administration
                                            </option>
                                            <option value="INGENIEUR" {{ old('last_degree') == 'INGENIEUR' ? 'selected' : '' }}>
                                                Diplôme d'Ingénieur
                                            </option>
                                            <option value="DESS" {{ old('last_degree') == 'DESS' ? 'selected' : '' }}>
                                                DESS – Diplôme d'Études Supérieures Spécialisées
                                            </option>
                                            <option value="DEA" {{ old('last_degree') == 'DEA' ? 'selected' : '' }}>
                                                DEA – Diplôme d'Études Approfondies
                                            </option>
                                        </optgroup>

                                        <optgroup label="🔬 Bac+6 et plus (Doctorat)">
                                            <option value="DOCTORAT" {{ old('last_degree') == 'DOCTORAT' ? 'selected' : '' }}>
                                                Doctorat (PhD)
                                            </option>
                                            <option value="DOCTORAT_MEDECINE" {{ old('last_degree') == 'DOCTORAT_MEDECINE' ? 'selected' : '' }}>
                                                Doctorat en Médecine
                                            </option>
                                            <option value="DOCTORAT_DROIT" {{ old('last_degree') == 'DOCTORAT_DROIT' ? 'selected' : '' }}>
                                                Doctorat en Droit
                                            </option>
                                            <option value="HDR" {{ old('last_degree') == 'HDR' ? 'selected' : '' }}>
                                                HDR – Habilitation à Dirige des recherches
                                            </option>
                                        </optgroup>
                                        <optgroup label="🔧 Formations professionnelles / certifications">
                                            <option value="CERT_PRO" {{ old('last_degree') == 'CERT_PRO' ? 'selected' : '' }}>
                                                Certification Professionnelle
                                            </option>
                                            <option value="FORMATION_PRO" {{ old('last_degree') == 'FORMATION_PRO' ? 'selected' : '' }}>
                                                Formation Professionnelle qualifiante
                                            </option>
                                            <option value="AUTRE" {{ old('last_degree') == 'AUTRE' ? 'selected' : '' }}>
                                                Autre
                                            </option>
                                        </optgroup>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Année d'obtention *</label>
                                    <select name="graduation_year" class="form-control" required>
                                        <option value="">-- Sélectionnez --</option>
                                        @for($year = date('Y'); $year >= 1970; $year--)
                                            <option value="{{ $year }}" {{ old('graduation_year') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Domaine d’études *</label>
                                    <input type="text" name="field_of_study" class="form-control" value="{{ old('field_of_study') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Niveau de langue *</label>
                                <select name="language_level" class="form-control" required>
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="Aucun" {{ old('language_level') == 'Aucun' ? 'selected' : '' }}>Aucun</option>
                                    <option value="Débutant" {{ old('language_level') == 'Débutant' ? 'selected' : '' }}>Débutant</option>
                                    <option value="Intermédiaire" {{ old('language_level') == 'Intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
                                    <option value="Avancé" {{ old('language_level') == 'Avancé' ? 'selected' : '' }}>Avancé</option>
                                    <option value="Bilingue" {{ old('language_level') == 'Bilingue' ? 'selected' : '' }}>Bilingue</option>
                                </select>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Expérience professionnelle</label>
                                    <textarea name="work_experience" ...>{{ old('work_experience') }}</textarea>
                                </div>
                            </div>
                        </fieldset>

                        <!--Documents / Budget / Message -->
                        <h3>Documents & Finalisation</h3>
                        <fieldset>
                            <label>Documents disponibles :</label>

                            <div class="form-check">
                                <input type="checkbox" id="passport" name="passport_valid" value="1" class="form-check-input" {{ old('passport_valid') ? 'checked' : '' }}>
                                <label for="passport" class="form-check-label">Passeport valide</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="school_docs" name="documents_available" value="1" class="form-check-input" {{ old('documents_available') ? 'checked' : '' }}>
                                <label for="school_docs" class="form-check-label">Diplômes / Relevés</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="admission_or_job" name="admission_or_contract" value="1" class="form-check-input" {{ old('admission_or_contract') ? 'checked' : '' }}>
                                <label for="admission_or_job" class="form-check-label">Lettre d'admission / contrat de travail</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="bank_proof" name="financial_proof" value="1" class="form-check-input" {{ old('financial_proof') ? 'checked' : '' }} >
                                <label for="bank_proof" class="form-check-label">Attestation bancaire / garant financier</label>
                            </div>


                            <hr>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Budget prévu</label>
                                    <input type="text" name="budget" class="form-control"
                                            value="{{ old('budget') }}">
                                </div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="form-label">Date de départ souhaitée</label>
                                    <input type="date" name="departure_date" value="{{ old('departure_date') }}" class="form-control">
                                </div>
                            </div>

                            <label class="form-label">Comment avez-vous connu Visafly ?</label>
                            <select name="referral_source" class="form-control">
                                @foreach(['Réseaux sociaux', 'Recommandation', 'Site internet', 'Autre'] as $source)
                                    <option value="{{ $source }}" {{ old('referral_source') == $source ? 'selected' : '' }}>
                                        {{ $source }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="form-group form-float mt-3">
                                <div class="form-line">
                                    <label class="form-label">Message libre</label>
                                    <textarea name="message" class="form-control" rows="4">{{ old('message') }}</textarea>
                                </div>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="need_consultation" name="need_consultation" value="1"
                                    {{ old('need_consultation') ? 'checked' : '' }}>
                                <label for="need_consultation" class="form-check-label">
                                    Voulez-vous une consultation individuelle ?
                                </label>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </section>
@endsection

