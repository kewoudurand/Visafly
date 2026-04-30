<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Vérification Email</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial,sans-serif;">

<div style="max-width:600px;margin:auto;background:#ffffff;padding:40px;border-radius:12px;">

    <h2 style="color:#1B3A6B;text-align:center;margin-bottom:10px;">
        Vérification de votre compte
    </h2>

    <p style="font-size:15px;color:#555;text-align:center;">
        Merci de vous être inscrit sur <strong>VisaFly</strong>.
    </p>

    <p style="font-size:15px;color:#555;text-align:center;">
        Utilisez le code ci-dessous pour vérifier votre adresse email :
    </p>

    <div style="text-align:center;margin:30px 0;">
        <span style="
            display:inline-block;
            background:#F5A623;
            color:#1B3A6B;
            font-size:32px;
            font-weight:800;
            padding:14px 30px;
            border-radius:12px;
            letter-spacing:6px;
        ">
            {{ $code }}
        </span>
    </div>

    <p style="font-size:13px;color:#888;text-align:center;">
        Ce code expire dans 10 minutes.
    </p>

    <p style="font-size:13px;color:#888;text-align:center;margin-top:30px;">
        Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.
    </p>

</div>

</body>
</html>