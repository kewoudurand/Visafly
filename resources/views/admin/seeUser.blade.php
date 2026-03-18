@extends('layouts/admin')

@section('space-work')

    <div class="main-content">
        <section class="section">

            <div class="section-header">
                <h1>Dossier Consultation : {{ $consultation->full_name }}</h1>

                <div class="d-flex ml-auto">

                    <!-- Bouton WhatsApp -->
                    <a href="https://wa.me/{{ $consultation->phone }}?text=Bonjour {{ urlencode($consultation->full_name) }}, nous avons bien reçu votre demande de consultation VisaFly."
                    target="_blank"
                    class="btn btn-success mr-2">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>

                    <!-- Bouton PDF -->
                    <a href="{{ route('admin.consultations.pdf', $consultation->id) }}"
                    class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Télécharger PDF
                    </a>

                </div>
            </div>


            <div class="section-body">

                <!-- Informations principales -->
                <div class="card">
                    <div class="card-header">
                        <h4>Informations personnelles</h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered">
                            <tr><th>Nom complet :</th><td>{{ $consultation->full_name }}</td></tr>
                            <tr><th>Date de naissance :</th><td>{{ $consultation->birth_date }}</td></tr>
                            <tr><th>Nationalité :</th><td>{{ $consultation->nationality }}</td></tr>
                            <tr><th>Pays de résidence :</th><td>{{ $consultation->residence_country }}</td></tr>
                            <tr><th>Téléphone :</th><td>{{ $consultation->phone }}</td></tr>
                            <tr><th>Email :</th><td>{{ $consultation->email }}</td></tr>
                            <tr><th>Profession :</th><td>{{ $consultation->profession }}</td></tr>
                        </table>

                    </div>
                </div>

                <!-- Projet Visa -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Projet Visa</h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered">
                            <tr><th>Type de projet :</th><td>{{ $consultation->project_type }}</td></tr>
                            <tr><th>Pays souhaité :</th><td>{{ $consultation->destination_country }}</td></tr>
                            <tr><th>Antécédent visa :</th>
                                <td>
                                    {{ $consultation->visa_history ? 'Oui' : 'Non' }}
                                </td>
                            </tr>
                            @if ($consultation->visa_history_details)
                                <tr><th>Détails :</th><td>{{ $consultation->visa_history_details }}</td></tr>
                            @endif
                        </table>

                    </div>
                </div>

                <!-- Profil académique -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Études & Profession</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr><th>Dernier diplôme :</th><td>{{ $consultation->last_degree }}</td></tr>
                            <tr><th>Année d’obtention :</th><td>{{ $consultation->graduation_year }}</td></tr>
                            <tr><th>Domaine d’étude :</th><td>{{ $consultation->field_of_study }}</td></tr>
                            <tr><th>Niveau de langue :</th><td>{{ $consultation->language_level }}</td></tr>
                            <tr><th>Expérience professionnelle :</th><td>{{ $consultation->work_experience }}</td></tr>
                        </table>
                    </div>
                </div>

                <!-- Documents -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Documents & Informations complémentaires</h4>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr><th>Passeport valide :</th><td>{{ $consultation->passport_valid ? 'Oui' : 'Non' }}</td></tr>
                            <tr><th>Diplômes disponibles :</th><td>{{ $consultation->documents_available ? 'Oui' : 'Non' }}</td></tr>
                            <tr><th>Lettre d'admission :</th><td>{{ $consultation->admission_or_contract ? 'Oui' : 'Non' }}</td></tr>
                            <tr><th>Attestation bancaire :</th><td>{{ $consultation->financial_proof ? 'Oui' : 'Non' }}</td></tr>

                            <tr><th>Budget :</th><td>{{ $consultation->budget }}</td></tr>
                            <tr><th>Date de départ :</th><td>{{ $consultation->departure_date }}</td></tr>
                            <tr><th>Source :</th><td>{{ $consultation->referral_source }}</td></tr>
                            <tr><th>Message :</th><td>{{ $consultation->message }}</td></tr>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>

@endsection
