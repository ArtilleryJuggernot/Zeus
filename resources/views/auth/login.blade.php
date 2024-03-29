<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Zeus</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{asset("css/vendor/bootstrap/css/bootstrap.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("fonts/font-awesome-4.7.0/css/font-awesome.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/vendor/animate/animate.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/vendor/css-hamburgers/hamburgers.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/vendor/select2/select2.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/login/util.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/login/main.css")}}">
</head>
<body>

@if(session("failure"))
    <span class="invalid-feedback" role="alert">
        <strong>{{ session("failure") }}</strong>
    </span>
@endif

@error('email')
<span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
@enderror

@error('password')
<span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
@enderror

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="{{asset("img/logo-zeus.jpeg")}}" alt="IMG">
            </div>

            <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                @csrf
					<span class="login100-form-title">
						Zeus Login
					</span>

                <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="email" placeholder="Email">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        Login
                    </button>
                </div>

                <div class="text-center p-t-136">
                    <a href="{{route('register')}}" class="txt2" href="#">
                        Create your Account
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
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
<script >
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

</body>
</html>
