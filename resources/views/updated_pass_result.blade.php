<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Reset Password Successfully</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <main class="main1">
        <div class="child-main">
            <div class="login-con">
                <div class="login-asset">
                    <img src="images/Asset1.png">
                </div>
                <div class="spare-login-name">
                    <div class="login-name">
                        <h3>BICOL <span>UNIVERSITY</span></h3>
                        <h1>SECURITY</h1>
                    </div>
                </div>
            </div>
    
            <div class="newpass-info">
                <div class="newpass-title">
                    <h2>UPDATED PASSWORD</h2>
                </div>
    
                <div class="check_img">
                    <img src="{{ asset('images/password.png') }}" alt="">
                </div>
                
                <div class="newpass-note">
                    <h3>Reset Password Successfully</h3>
                    <p>If you have any questions or need further assistance, please don't hesitate to contact us at <span>busina@gmail.com</span></p>
                </div>
                <div class="back-login">
                    <!---TEMPORARY--->
                    <a href="{{ route('login') }}"><i class="bi bi-chevron-left"></i> Back to login</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
