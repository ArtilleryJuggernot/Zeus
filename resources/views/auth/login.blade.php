<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login Zeus</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        @vite('resources/css/app.css')
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset("css/vendor/bootstrap/css/bootstrap.min.css") }}"
        />
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset("fonts/font-awesome-4.7.0/css/font-awesome.min.css") }}"
        />
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset("css/vendor/animate/animate.css") }}"
        />
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset("css/vendor/css-hamburgers/hamburgers.min.css") }}"
        />
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset("css/vendor/select2/select2.min.css") }}"
        />
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset("css/login/util.css") }}"
        />
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset("css/login/main.css") }}"
        />
    </head>
    <body>
        @if (session("failure"))
            <span class="invalid-feedback" role="alert">
                <strong>{{ session("failure") }}</strong>
            </span>
        @endif

        @error("email")
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        @error("password")
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100">
                    <div class="login100-pic js-tilt" data-tilt>
                        <img
                            src="{{ asset("img/logo-zeus.jpeg") }}"
                            alt="IMG"
                        />
                    </div>

                    <form
                        method="POST"
                        action="{{ route("login") }}"
                        class="login100-form validate-form"
                    >
                        @csrf
                        <span class="login100-form-title">Zeus Login</span>

                        <div
                            class="wrap-input100 validate-input"
                            data-validate="Valid email is required: ex@abc.xyz"
                        >
                            <input
                                class="input100"
                                type="text"
                                name="email"
                                placeholder="Email"
                            />
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i
                                    class="fa fa-envelope"
                                    aria-hidden="true"
                                ></i>
                            </span>
                        </div>

                        <div
                            class="wrap-input100 validate-input"
                            data-validate="Password is required"
                        >
                            <input
                                class="input100"
                                type="password"
                                name="password"
                                placeholder="Password"
                            />
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="container-login100-form-btn">
                            <button class="login100-form-btn">Login</button>
                        </div>


                        <br>

                        <div class="text-center content-center">


                        @if($allow_new_users)
                        <a href="{{ route('google.redirect') }}">
                        <button type="button" class="text-white bg-[#4285F4] hover:bg-[#4285F4]/90 focus:ring-4 focus:outline-none focus:ring-[#4285F4]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#4285F4]/55 me-2 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 19">
                                <path fill-rule="evenodd" d="M8.842 18.083a8.8 8.8 0 0 1-8.65-8.948 8.841 8.841 0 0 1 8.8-8.652h.153a8.464 8.464 0 0 1 5.7 2.257l-2.193 2.038A5.27 5.27 0 0 0 9.09 3.4a5.882 5.882 0 0 0-.2 11.76h.124a5.091 5.091 0 0 0 5.248-4.057L14.3 11H9V8h8.34c.066.543.095 1.09.088 1.636-.086 5.053-3.463 8.449-8.4 8.449l-.186-.002Z" clip-rule="evenodd"/>
                            </svg>
                            Se connecter avec Google
                        </button>
                         </a>


                            <label for="terms" class="txt2">En se connectant via Google, vous acceptez les <a href="/cgu" target="_blank">Conditions Générales d'Utilisation (CGU)</a></label>

                        </div>
                        @endif





                        <div class="p-t-50 text-center">
                            @if($allow_new_users)
                            <a
                                href="{{ route("register") }}"
                                class="txt2"
                                href="#"
                            >
                                Créer votre compte
                                <i
                                    class="fa fa-long-arrow-right m-l-5"
                                    aria-hidden="true"
                                ></i>
                            </a>
                            @else
                            <div class="alert alert-info text-center font-bold p-2 rounded bg-blue-100 border border-blue-400 mt-2">
                                Les inscriptions sont désactivées.
                            </div>
                            @endif
                        </div>
                    </form>
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
