<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Login Page</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Meta tags and CSS links -->
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/head_login.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script type="text/javascript">
        function preventBack(){window.history.forward()};
        setTimeout("preventBack()",0);
        window.onunload=function(){null;}
    </script>
</head>

<body>
<div class="semi-body">
        <div class="container">
            <div class="cover">
                <div class="login-name">
                    <h3>BICOL <span>UNIVERSITY</span></h3>
                    <h1>SECURITY SECTION</h1>
                </div>
                <div class="login-asset">
                    <img src="{{ asset('images/Asset2.png') }}">
                </div>
            </div>

            <div class="forms">
                <div class="login-title">
                    <h2>LOG IN</h2>
                </div>

                <form action="{{ route('login') }}" method="post" class="login-form" id="loginForm">
                    @csrf
                    @if ($errors->any())
                    <div class="main-error">
                        <p id="errorMessage" class="error-message">
                            <span><i class="bi bi-exclamation-circle"></i></span>
                            {{ $errors->first() }}
                            <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
                        </p>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="main-error">
                        <p id="errorMessage" class="error-message">
                            <span><i class="bi bi-exclamation-circle"></i></span>
                            {{ session('error') }}
                            <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
                        </p>
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="main-success">
                        <p id="successMessage" class="success-message">
                            <span><i class="bi bi-check-circle"></i></span>
                            {{ session('success') }}
                            <a class="cancel-button-success" onclick="hideSuccessMessage()"><i class="bi bi-x"></i></a>
                        </p>
                    </div>
                    @endif

                    <div class="login-inputs">
                        <div class="login-input-form">
                            <label for="email">Email Address</label>
                            <input type="email" placeholder="" id="email" name="email" required>
                        </div>

                        <div class="login-input-form">
                            <label for="password">Password</label>
                            <input type="password" placeholder="" id="password" name="password" required>
                            <i class="fa fa-eye-slash eye-icon" aria-hidden="true" onclick="togglePassword()"></i>
                        </div>

                        <div class="forgot">
                            <a href="{{ url('forgot_pass') }}">Forgot Password?</a>
                        </div>
                    </div>

                    <button type="submit" id="loginButton">LOG IN</button>
                    <div class="countdown"  id="countdownContainer" data-remaining-seconds="{{ $remainingSeconds }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/disableForm.js') }}"></script>
    <script src="{{ asset('js/login_toggle_password.js') }}"></script>
    <script src="{{ asset('js/hide_error_message.js') }}"></script>
</body>
</html>
