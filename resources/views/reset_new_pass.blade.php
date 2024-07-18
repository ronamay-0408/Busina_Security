<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Reset New Password</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    
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
                <form action="{{ route('update_password') }}" class="login-form" method="POST">
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
                        <p>Enter your desired password, make sure to consider changing it to something memorable and secure.</p>

                        <div class="login-inputs">
                            <div class="login-input-form3">
                                <label for="emp_no">Employee Number</label>
                                <input type="text" placeholder="" id="emp_no" name="emp_no" value="{{ $emp_no }}" readonly>
                                <input type="hidden" name="token" value="{{ $token }}" readonly>
                            </div>

                            <div class="login-input-form3">
                                <label for="new_pass">NEW PASSWORD</label><br>
                                <input type="password" placeholder="" id="new_pass" name="new_pass" required>
                                <i class="fa fa-eye-slash eye-icon2" aria-hidden="true" onclick="togglePassword()" id="togglePassword"></i>
                            </div>
                            <div id="passwordError" class="inside-error-message">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.</div>
                            <div id="passwordSuccess" class="inside-success-message" style="display: none;">Password strength: <span id="passwordStrength"></span></div>
                            <div id="passwordAccept" class="inside-weak-message" style="display: none;">Password strength: <span id="passwordStrength"></span></div>

                            <div class="login-input-form3">
                                <label for="con_pass">CONFIRM PASSWORD</label><br>
                                <input type="password" placeholder="" id="con_pass" name="new_pass_confirmation" required>
                                <i class="fa fa-eye-slash eye-icon2" aria-hidden="true" onclick="togglePassword2()" id="togglePassword2"></i>
                            </div>
                            <div id="confirmPasswordError" class="inside-error-message">Passwords do not match.</div>
                        </div>
                    </div>
                    <button class="sendbtn" type="submit">SUBMIT</button>
                </form>
                <div class="back-login">
                    <a href="{{ route('login') }}"><i class="bi bi-chevron-left"></i> Back to login</a>
                </div>
            </div>
        </div>
    </main>
    <script>
        function hideErrorMessage() {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }

        document.getElementById('new_pass').addEventListener('input', function () {
            validatePassword();
            validateConfirmPassword();
        });

        document.getElementById('con_pass').addEventListener('input', function () {
            validateConfirmPassword();
        });

        document.querySelector('.sendbtn').addEventListener('click', function (event) {
            // Validate password before allowing form submission
            if (!validatePassword() || !validateConfirmPassword()) {
                event.preventDefault(); // Prevent form submission
                showPasswordWarning();
            }
        });

        function validatePassword() {
            const password = document.getElementById('new_pass').value;

            // Regular expressions to check for presence of required characters
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasDigit = /\d/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            // Check if password meets minimum length requirement
            const isValidLength = password.length >= 8;

            // Count how many conditions are met
            let metCount = 0;
            if (hasUppercase) metCount++;
            if (hasLowercase) metCount++;
            if (hasDigit) metCount++;
            if (hasSpecialChar) metCount++;

            // Determine password strength based on conditions met
            if (isValidLength && metCount === 4) {
                // Strong Password: meets all conditions
                document.getElementById('new_pass').classList.add('strong');
                document.getElementById('new_pass').classList.remove('moderate', 'weak');
                document.getElementById('passwordError').style.display = 'none';
                document.getElementById('passwordSuccess').style.display = 'block';
                document.getElementById('passwordAccept').style.display = 'none';
                document.getElementById('passwordStrength').textContent = 'Strong Password';
                return true;
            } else if (isValidLength && metCount >= 2) {
                // Moderately Strong Pass: meets at least two conditions
                document.getElementById('new_pass').classList.add('moderate');
                document.getElementById('new_pass').classList.remove('strong', 'weak');
                document.getElementById('passwordError').style.display = 'none';
                document.getElementById('passwordSuccess').style.display = 'none';
                document.getElementById('passwordAccept').style.display = 'block';
                document.getElementById('passwordAccept').textContent = 'Password strength: Moderate Password';
                return true;
            } else if (isValidLength) {
                // Weak Password: meets minimum length requirement
                document.getElementById('new_pass').classList.add('weak');
                document.getElementById('new_pass').classList.remove('strong', 'moderate');
                document.getElementById('passwordError').style.display = 'none';
                document.getElementById('passwordSuccess').style.display = 'none';
                document.getElementById('passwordAccept').style.display = 'block';
                document.getElementById('passwordAccept').textContent = 'Password strength: Weak Password';
                return true;
            }

            // Default case: password does not meet the criteria
            document.getElementById('new_pass').classList.add('weak');
            document.getElementById('new_pass').classList.remove('strong', 'moderate');
            document.getElementById('passwordError').style.display = 'block';
            document.getElementById('passwordSuccess').style.display = 'none';
            document.getElementById('passwordAccept').style.display = 'none';
            document.getElementById('passwordStrength').textContent = 'Weak Password';
            return false;
        }


        function validateConfirmPassword() {
            const password = document.getElementById('new_pass').value;
            const confirmPassword = document.getElementById('con_pass').value;
            const confirmPasswordError = document.getElementById('confirmPasswordError');

            if (confirmPassword === "") {
                confirmPasswordError.style.display = 'none';
                document.getElementById('con_pass').classList.remove('invalid');
                document.getElementById('con_pass').classList.remove('valid');
            } else if (password === confirmPassword) {
                document.getElementById('con_pass').classList.add('valid');
                document.getElementById('con_pass').classList.remove('invalid');
                confirmPasswordError.style.display = 'none';
            } else {
                document.getElementById('con_pass').classList.add('invalid');
                document.getElementById('con_pass').classList.remove('valid');
                confirmPasswordError.style.display = 'block';
            }

            return password === confirmPassword;
        }

        function togglePassword() {
            const passwordField = document.getElementById('new_pass');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            const icon = document.getElementById('togglePassword');
            icon.classList.toggle('fa-eye-slash');
            icon.classList.toggle('fa-eye');
        }

        function togglePassword2() {
            const passwordField = document.getElementById('con_pass');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            const icon = document.getElementById('togglePassword2');
            icon.classList.toggle('fa-eye-slash');
            icon.classList.toggle('fa-eye');
        }

        function showPasswordWarning() {
            const password = document.getElementById('new_pass').value;
            const isValidLength = password.length >= 8;
            let metCount = 0;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasDigit = /\d/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            if (hasUppercase) metCount++;
            if (hasLowercase) metCount++;
            if (hasDigit) metCount++;
            if (hasSpecialChar) metCount++;

            if (isValidLength && metCount >= 2) {
                alert('Warning: Password is moderately strong or weak. Please consider a stronger password for security.');
            }
            else {
                alert('Warning: Password does not meet the minimum requirements. Please enter a password that is at least 8 characters long and includes at least one uppercase letter, one lowercase letter, one digit, and one special character.');
            }
        }
    </script>
</body>
</html>
