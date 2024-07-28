function validateEmail() {
    var emailInput = document.getElementById('email');
    var email = emailInput.value.trim(); // Trim whitespace from the input
    var gmailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/; // Match Gmail addresses
    var eduPattern = /^[a-zA-Z0-9._%+-]+@bicol-u\.edu\.ph$/; // Match specific domain

    if (!(gmailPattern.test(email) || eduPattern.test(email))) {
        document.getElementById('email-error').style.display = 'block';
        emailInput.focus(); // Keep focus on the email input
        return false;
    } else {
        document.getElementById('email-error').style.display = 'none';
        return true;
    }
}