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

            @if($allow_new_users)
            <form method="POST" action="{{ route("register") }}" class="login100-form validate-form">
                @csrf
                <span class="login100-form-title">Register Zeus</span>

                <div class="wrap-input100 validate-input" data-validate="Name is required">
                    <input id="name" type="text" class="input100 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name" />
                    <span class="focus-input100"></span>
                    <span class="symbol-input100"><i class="fa fa-user" aria-hidden="true"></i></span>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>

                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input id="email" type="email" class="input100 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" />
                    <span class="focus-input100"></span>
                    <span class="symbol-input100"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input id="password" type="password" class="input100 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password" />
                    <span class="focus-input100"></span>
                    <span class="symbol-input100"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password confirmation is required">
                    <input id="password-confirm" type="password" class="input100" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
                    <span class="focus-input100"></span>
                    <span class="symbol-input100"><i class="fa fa-lock" aria-hidden="true"></i></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Acceptance of terms is required">
                    <input id="terms" type="checkbox" class="@error('terms') is-invalid @enderror" name="terms" required />
                    <label for="terms" class="txt2">I accept the <a href="/cgu" target="_blank">Terms and Conditions</a></label>
                    @error('terms')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>


                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">Register</button>
                </div>

                <div class="text-center p-t-50">
                    <a class="txt2" href="{{ route("login") }}">
                        Already have an account? Login
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
                </div>


            </form>
            @else
                <div class="alert alert-warning text-center font-bold p-4 rounded bg-yellow-100 border border-yellow-400 mt-8">
                    Les inscriptions sont actuellement désactivées par l'administrateur.
                </div>
            @endif
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
