<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{{ asset("css/includes/footer.css") }}" />
    </head>
    <body>
        <footer>
            <div class="footer-container">
                <div class="footer-content">
                    <p>
                        &copy; 2024 Zeus. v{{ env("APP_VERSION", "N/A") }} Tous
                        droits réservés.
                    </p>
                    <!-- Ajoutez d'autres informations ou liens de votre choix -->
                </div>
            </div>
        </footer>
    </body>
</html>
