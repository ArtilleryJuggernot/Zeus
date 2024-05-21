<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Zeus</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" type="text/css" href="{{ asset("css/vendor/bootstrap/css/bootstrap.min.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("fonts/font-awesome-4.7.0/css/font-awesome.min.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/vendor/animate/animate.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/vendor/css-hamburgers/hamburgers.min.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/vendor/select2/select2.min.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/login/util.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/login/main.css") }}" />
</head>
<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="{{ asset("img/logo-zeus.jpeg") }}" alt="IMG" />
            </div>

            Un email de confirmation a été envoyé à l'adresse mail correspondante, cliqué sur le lien pour activer votre compte.
        </div>
    </div>
</div>

<!--===============================================================================================-->
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/tilt/tilt.jquery.min.js"></script>
<script>
    $('.js-tilt').tilt({
        scale: 1.1,
    });
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>
</body>
</html>
