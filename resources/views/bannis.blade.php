<!DOCTYPE html>
<html>
<head>
    <title>Redirection automatique en POST</title>
    <script>
        // Fonction pour soumettre le formulaire automatiquement après un délai spécifié
        function submitFormAfterDelay() {
            setTimeout(function() {
                document.getElementById('logoutForm').submit(); // Soumission du formulaire
            }, 5000); // Délai en millisecondes (ici 5 secondes)
        }
    </script>
</head>
<body onload="submitFormAfterDelay()">

<h1>Vous avez été bannis</h1>
<p>Vous allez être automatiquement déconnecté dans 5 secondes.</p>

<form id="logoutForm" action="{{ route('logout') }}" method="POST">
    @csrf
</form>

</body>
</html>
