function hideMessage(messageId) {
    const messageElement = document.getElementById(messageId);
    if (messageElement) {
        messageElement.style.display = 'none';
    }
}

// Hide the messages after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 5000); // 5000 milliseconds = 5 seconds
    }
    
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 5000); // 5000 milliseconds = 5 seconds
    }
});