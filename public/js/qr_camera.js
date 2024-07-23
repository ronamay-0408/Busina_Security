const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const output = document.getElementById('output');
const context = canvas.getContext('2d');
const verifyingLine = document.querySelector('.verifying-line');

// Check if permission is already granted
const permissionStatus = localStorage.getItem('cameraPermission');

if (permissionStatus === 'granted') {
    startCamera();
} else {
    requestCameraPermission();
}

function requestCameraPermission() {
    navigator.permissions.query({ name: 'camera' })
        .then(permissionStatus => {
            if (permissionStatus.state === 'granted') {
                localStorage.setItem('cameraPermission', 'granted');
                startCamera();
            } else if (permissionStatus.state === 'prompt') {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(function(stream) {
                        localStorage.setItem('cameraPermission', 'granted');
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true);
                        video.play();
                        requestAnimationFrame(tick);
                    })
                    .catch(function(err) {
                        console.error("Error accessing the camera: ", err);
                        output.textContent = "Error accessing the camera.";
                    });
            } else {
                output.textContent = "Camera permission denied.";
            }
        });
}

function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            video.srcObject = stream;
            video.setAttribute('playsinline', true);
            video.play();
            requestAnimationFrame(tick);
        })
        .catch(function(err) {
            console.error("Error accessing the camera: ", err);
            output.textContent = "Error accessing the camera.";
        });
}

let lastScannedQR = null; // Keep track of the last scanned QR code

function tick() {
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.height = video.videoHeight;
        canvas.width = video.videoWidth;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: "dontInvert",
        });
        
        if (code && code.data !== lastScannedQR) { // Check if a new QR code is detected and it's different from the last one
            console.log("QR Code found:", code);
            output.textContent = `QR Code Data: ${code.data}`;
            
            // Alert the content of the scanned QR code
            // alert(`QR Code Data: ${code.data}`);
            
            // Send the scanned QR code data to the server using AJAX
            sendQRCodeDataToServer(code.data);

            // Store the current QR code as the last scanned QR code
            lastScannedQR = code.data;
            
            // Reset the scanning process after a successful scan
            setTimeout(() => {
                output.textContent = "Scanning...";
                lastScannedQR = null; // Allow scanning the same QR code again after the delay
            }, 3000); // Adjust the delay as needed
        } else if (!code) { // If no QR code is detected
            output.textContent = "Scanning...";
        }
    }
    requestAnimationFrame(tick);
}

function sendQRCodeDataToServer(userId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "record_attendance.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                showAlert(xhr.responseText); // Show server response as an auto-dismissing alert
            } else {
                showAlert("Try Again!"); // Show "Try Again!" message as an auto-dismissing alert on error
            }
        }
    };
    xhr.send("user_id=" + encodeURIComponent(userId));
}

function showAlert(message) {
    var alertBox = document.createElement('div');
    alertBox.textContent = message;
    alertBox.className = 'alert'; // Apply the CSS class 'alert'

    document.body.appendChild(alertBox); // Append the alert to the body

    setTimeout(function(){
        alertBox.style.opacity = "1";
    }, 100);

    setTimeout(function(){
        alertBox.style.opacity = "0"; // Make the alert transparent
        setTimeout(function(){
            document.body.removeChild(alertBox); // Remove the alert from the DOM after the animation finishes
        }, 1000); // Wait for the same duration as the transition
    }, 1000);
}
  