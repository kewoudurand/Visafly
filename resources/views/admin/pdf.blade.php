<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dossier Consultation</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
        h2 { background: #5494f3; color:white; padding:8px; }
        table { width:100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border:1px solid #ddd; padding:8px; }
        th { background:#f4f4f4; }
    </style>

</head>
<body>

    <h1>Dossier de Consultation</h1>
    <p><strong>Nom :</strong> {{ $consultation->full_name }}</p>
    <p><strong>Date :</strong> {{ now()->format('d/m/Y') }}</p>

    <h2>Informations personnelles</h2>
    <table>
        <tr><th>Full Name</th><td>{{ $consultation->full_name }}</td></tr>
        <tr><th>Naissance</th><td>{{ $consultation->birth_date }}</td></tr>
        <tr><th>Nationalité</th><td>{{ $consultation->nationality }}</td></tr>
        <tr><th>Pays résidence</th><td>{{ $consultation->residence_country }}</td></tr>
        <tr><th>Téléphone</th><td>{{ $consultation->phone }}</td></tr>
        <tr><th>Email</th><td>{{ $consultation->email }}</td></tr>
        <tr><th>Profession</th><td>{{ $consultation->profession }}</td></tr>
    </table>

    <h2>Projet Visa</h2>
    <table>
        <tr><th>Type de projet</th><td>{{ $consultation->project_type }}</td></tr>
        <tr><th>Pays souhaité</th><td>{{ $consultation->destination_country }}</td></tr>
        <tr><th>Déjà demandé visa</th><td>{{ $consultation->visa_history ? 'Oui' : 'Non' }}</td></tr>
        @if($consultation->visa_history_details)
        <tr><th>Détails</th><td>{{ $consultation->visa_history_details }}</td></tr>
        @endif
    </table>

    <h2>Profil Académique & Pro</h2>
    <table>
        <tr><th>Diplôme</th><td>{{ $consultation->last_degree }}</td></tr>
        <tr><th>Année</th><td>{{ $consultation->graduation_year }}</td></tr>
        <tr><th>Domaine</th><td>{{ $consultation->field_of_study }}</td></tr>
        <tr><th>Langue</th><td>{{ $consultation->language_level }}</td></tr>
        <tr><th>Expérience</th><td>{{ $consultation->work_experience }}</td></tr>
    </table>

    <h2>Documents & Infos supplémentaires</h2>
    <table>
        <tr><th>Passeport</th><td>{{ $consultation->passport_valid ? 'Oui' : 'Non' }}</td></tr>
        <tr><th>Diplômes</th><td>{{ $consultation->documents_available ? 'Oui' : 'Non' }}</td></tr>
        <tr><th>Admission/Contrat</th><td>{{ $consultation->admission_or_contract ? 'Oui' : 'Non' }}</td></tr>
        <tr><th>Preuve financière</th><td>{{ $consultation->financial_proof ? 'Oui' : 'Non' }}</td></tr>
        <tr><th>Budget</th><td>{{ $consultation->budget }}</td></tr>
        <tr><th>Départ</th><td>{{ $consultation->departure_date }}</td></tr>
        <tr><th>Source</th><td>{{ $consultation->referral_source }}</td></tr>
        <tr><th>Message</th><td>{{ $consultation->message }}</td></tr>
    </table>

</body>
</html>
