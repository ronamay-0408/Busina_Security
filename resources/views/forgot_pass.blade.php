<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Forgot Password</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/head_login.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                <div class="login-title">
                    <h2>FORGOT PASSWORD</h2>
                </div>
                <form action="{{ route('password.email') }}" method="POST" class="login-form forgot-form2">
                    @csrf
                    <div class="forgot-info">
                        <p>Enter your Employee Number and we'll send you a reset URL.</p>
                        <p>The new password will be sent to the email address associated with your account.
                        Once received, use the new password to log in, and consider changing it to something memorable and secure.</p>
                        <p>If you have any issues, contact us through <span>BUsina@gmail.com</span></p>
                    </div>

                    @if(session('error'))
                    <div class="main-error">
                        <p id="errorMessage" class="error-message">
                            <span><i class="bi bi-exclamation-circle"></i></span> {{ session('error') }}
                            <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
                        </p>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="main-success">
                        <p id="successMessage" class="success-message">
                            <span><i class="bi bi-check-circle"></i></span> {{ session('success') }}
                            <a class="cancel-button" onclick="hideSuccessMessage()"><i class="bi bi-x"></i></a>
                        </p>
                    </div>
                    @endif

                    @if ($errors->has('emp_no'))
                    <div class="main-error">
                        <p id="errorMessage" class="error-message">
                            <span><i class="bi bi-exclamation-circle"></i></span> {{ $errors->first('emp_no') }}
                            <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
                        </p>
                    </div>
                    @endif

                    <div class="forgot-info3">
                        <div class="forgot-inputs">
                            <div class="forgot-input-form">
                                <label for="emp_no">Employee Number</label><br>
                                <input type="text" placeholder="" id="emp_no" name="emp_no" value="{{ old('emp_no') }}" required>
                            </div>
                        </div>
                    </div>

                    <button class="sendbtn" type="submit">Send Reset Code</button>
                </form>
                <div class="back-login">
                    <a href="{{ route('login') }}"><i class="bi bi-chevron-left"></i> Back to login</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/hide_error_message.js') }}"></script>
</body>

</html>
