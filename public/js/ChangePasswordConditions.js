function togglePasswordVisibility(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.getElementById('new_pass').addEventListener('input', function () {
    validatePassword();
    validateConfirmPassword();
});

document.getElementById('con_pass').addEventListener('input', function () {
    validateConfirmPassword();
});

function validatePassword() {
    const password = document.getElementById('new_pass').value;

    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasDigit = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    const isValidLength = password.length >= 8;

    let metCount = 0;
    if (hasUppercase) metCount++;
    if (hasLowercase) metCount++;
    if (hasDigit) metCount++;
    if (hasSpecialChar) metCount++;

    const strengthText = document.getElementById('passwordStrength');
    if (isValidLength && metCount === 4) {
        strengthText.textContent = 'Strong Password';
        strengthText.style.color = 'green';
    } else if (isValidLength && metCount >= 2) {
        strengthText.textContent = 'Moderate Password';
        strengthText.style.color = 'orange';
    } else {
        strengthText.textContent = 'Weak Password';
        strengthText.style.color = 'red';
    }
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
}

document.querySelector('.sendbtn').addEventListener('click', function (event) {
    if (!validatePassword() || !validateConfirmPassword()) {
        event.preventDefault(); // Prevent form submission
        showPasswordWarning();
    }
});

function showPasswordWarning() {
    alert('Please ensure your password meets the requirements and that both passwords match.');
}