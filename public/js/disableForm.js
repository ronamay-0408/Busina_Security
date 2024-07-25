document.addEventListener('DOMContentLoaded', function () {
    var countdownContainer = document.getElementById('countdownContainer');
    var remainingSeconds = parseInt(countdownContainer.getAttribute('data-remaining-seconds'), 10);
    var loginForm = document.getElementById('loginForm');
    var loginButton = document.getElementById('loginButton');

    function updateCountdown(seconds) {
        var display = document.createElement('div');
        display.id = 'countdownDisplay';
        display.innerText = `Too many login attempts. Please try again in ${seconds} seconds.`;
        countdownContainer.appendChild(display);
    }

    if (remainingSeconds > 0) {
        loginForm.querySelectorAll('input, button').forEach(function (element) {
            element.disabled = true;
        });

        updateCountdown(remainingSeconds);

        var countdown = setInterval(function () {
            remainingSeconds--;
            if (remainingSeconds <= 0) {
                clearInterval(countdown);
                document.getElementById('countdownDisplay').remove();
                loginForm.querySelectorAll('input, button').forEach(function (element) {
                    element.disabled = false;
                });
            } else {
                document.getElementById('countdownDisplay').innerText = `Too many login attempts. Please try again in ${remainingSeconds} seconds.`;
            }
        }, 1000);
    }
});