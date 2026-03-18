<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur VisaFly</title>
</head>

<body>
    <h2>Bonjour {{ $user->frist_name }} {{ $user->last_name }},</h2>

    <p>
        Votre compte administrateur VisaFly a été créé avec succès.
    </p>

    <p>Voici vos informations de connexion :</p>

    <p>
        <strong>Email :</strong> {{ $user->email }}<br>
        <strong>Mot de passe :</strong> {{ $plainPassword }}
    </p>

    <p>
        Pour des raisons de sécurité, nous vous recommandons de modifier votre mot de passe dès votre première connexion.
    </p>

    <br>

    <p>Cordialement,<br>
    L’équipe VisaFly.</p>
</body>
</html>
