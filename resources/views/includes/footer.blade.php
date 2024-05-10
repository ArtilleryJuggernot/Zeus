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
    <footer class="bg-gray-900 text-white py-8 w-full bottom-0">
        <div class="container mx-auto">
            <div class="flex items-center justify-between">
                <p class="text-sm">
                    &copy; 2024 Zeus. v{{ env("APP_VERSION", "N/A") }} Tous droits réservés.
                </p>
                <!-- Ajoutez d'autres informations ou liens de votre choix -->
            </div>
        </div>
    </footer>
    </body>
</html>
