<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login Page</title>
    <!-- Meta tags and CSS links -->
     <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <main class="main1">
        <div class="child-main">
            <div class="login-con">
                <div class="login-asset">
                    <img src="{{ asset('images/Asset1.png') }}" alt="">
                </div>
                <div class="spare-login-name">
                    <div class="login-name">
                        <h3>BICOL <span>UNIVERSITY</span></h3>
                        <h1>SECURITY</h1>
                    </div>
                </div>
            </div>

            <div class="login-info">
                <div class="login-title">
                    <h2>LOG IN</h2>
                </div>
                <form action="{{ route('login') }}" method="post" class="login-form">
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
                            <label for="email">Email</label>
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

                    <button type="submit">LOG IN</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var eyeIcon = document.querySelector('.eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }

        function hideErrorMessage() {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }

        // Hide the error message after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            }
        });

        function hideSuccessMessage() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }

        // Hide the success message after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            }
        });
    </script>
</body>

</html>
