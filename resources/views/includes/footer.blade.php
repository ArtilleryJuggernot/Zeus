<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{{ asset("css/includes/footer.css") }}" />
        @vite('resources/css/app.css')
    </head>




    <body class="flex flex-col min-h-screen">
    <!-- Contenu de votre site ici -->

    <!-- Footer en position fixe en bas de la page -->
    <footer class="bg-gray-900 text-white py-4 w-full text-center mt-auto">
        <span class="text-sm">&copy; {{ date('Y') }} Zeus. v{{ env('APP_VERSION', 'N/A') }} Tous droits réservés.</span>
    </footer>
    </body>
</html>
