<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
</head>
<body>
<h1>Bonjour {{ $user->name }},</h1>

<p>Votre mot de passe a été réinitialisé avec succès. Voici votre nouveau mot de passe :</p>
<p><strong>{{ $newPassword }}</strong></p>

<p>Nous vous recommandons de vous connecter et de changer ce mot de passe pour des raisons de sécurité.</p>

<p>Cordialement,<br>L'équipe de support</p>
</body>
</html>
