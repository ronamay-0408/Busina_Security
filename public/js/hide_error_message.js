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