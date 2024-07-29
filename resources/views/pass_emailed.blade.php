<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Password Emailed Successfully</title>
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
                <div class="login-title pass-emailed-title">
                    <h2>FORGOT PASSWORD</h2>
                </div>

                <div class="check_img">
                    <img src="{{ asset('images/mail-check.png') }}" alt="">
                </div>
                
                <div class="newpass-note">
                    <h3>Notification successfully sent!</h3>
                    <p>An instruction on how to reset your password has been sent to your <span>Registered Email</span></p>
                </div>
                
                <div class="back-login">
                    <a href="{{ route('login') }}"><i class="bi bi-chevron-left"></i> Back to login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
