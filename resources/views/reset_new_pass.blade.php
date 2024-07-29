<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Reset New Password</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/head_login.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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

            <div class="forgot1">
                <div class="resetnew-login-title">
                    <h2>RESET NEW PASSWORD</h2>
                </div>

                @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('update_password') }}" class="login-form reset-form2" method="POST">
                    @csrf <!-- CSRF protection token -->

                    <!-- Display error message, if any -->
                    @if (session('error'))
                        <div class="main-error">
                            <p id="errorMessage" class="error-message">
                                <span><i class="bi bi-exclamation-circle"></i></span> {!! htmlspecialchars(session('error')) !!}
                                <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
                            </p>
                        </div>
                    @endif

                    <div class="forgot-info3">
                        <div class="forgot-info">
                            <p>Enter your desire password, make sure to consider changing it to something memorable and secure. </p>
                        </div>

                        <div class="forgot-inputs">
                            <div class="forgot-input-form">
                                <label for="emp_no">Employee Number</label>
                                <input type="text" placeholder="" id="emp_no" name="emp_no" value="{{ $emp_no }}" readonly>
                                <input type="hidden" name="token" value="{{ $token }}" readonly>
                            </div>

                            <div class="forgot-inputs">
                                <div class="forgot-input-form">
                                    <label for="new_pass">NEW PASSWORD</label><br>
                                    <input type="password" placeholder="" id="new_pass" name="new_pass" required>
                                    <i class="fa fa-eye-slash eye-icon2" aria-hidden="true" onclick="togglePassword()" id="togglePassword"></i>
                                </div>
                            </div>
                            <div id="passwordError" class="inside-error-message">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.</div>
                            <div id="passwordSuccess" class="inside-success-message" style="display: none;">Password strength: <span id="passwordStrength"></span></div>
                            <div id="passwordAccept" class="inside-weak-message" style="display: none;">Password strength: <span id="passwordStrength"></span></div>

                            <div class="forgot-inputs">
                                <div class="forgot-input-form">
                                    <label for="con_pass">CONFIRM PASSWORD</label><br>
                                    <input type="password" placeholder="" id="con_pass" name="new_pass_confirmation" required>
                                    <i class="fa fa-eye-slash eye-icon2" aria-hidden="true" onclick="togglePassword2()" id="togglePassword2"></i>
                                </div>
                                <div id="confirmPasswordError" class="reset-error-message">Passwords do not match.</div>
                            </div>
                        </div>
                    </div>
                    <button class="sendbtn" type="submit">SUBMIT</button>
                </form>
                <div class="back-login">
                    <a href="{{ route('login') }}"><i class="bi bi-chevron-left"></i> Back to login</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/reset_new_pass.js') }}"></script>
</body>
</html>
