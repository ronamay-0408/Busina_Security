
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

// Disable copy, cut, and paste on the confirm password field
var confirmPasswordField = document.getElementById('con_pass');
confirmPasswordField.addEventListener('copy', function(e) {
    e.preventDefault();
});
confirmPasswordField.addEventListener('cut', function(e) {
    e.preventDefault();
});
confirmPasswordField.addEventListener('paste', function(e) {
    e.preventDefault();
});
confirmPasswordField.addEventListener('keydown', function(e) {
    if (e.ctrlKey && (e.key === 'v' || e.key === 'c')) {
        e.preventDefault();
    }
});