
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Visitor QR Code Scanner</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    @include('MainPartials.ssu_sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>

        <!-- @if (session('error'))
            <div class="main-error unauthorized_report_error">
                <p id="errorMessage" class="error-message">
                    <span><i class="bi bi-exclamation-circle"></i></span>
                    {{ session('error') }}
                    <a class="cancel-button" onclick="hideMessage('errorMessage')"><i class="bi bi-x"></i></a>
                </p>
            </div>
        @endif

        @if (session('success'))
            <div class="main-success unauthorized_report_success">
                <p id="successMessage" class="success-message">
                    <span><i class="bi bi-check-circle"></i></span>
                    {{ session('success') }}
                    <a class="cancel-button-success" onclick="hideMessage('successMessage')"><i class="bi bi-x"></i></a>
                </p>
            </div>
        @endif -->

        <!-- Display messages if present in local storage -->
        <div id="message-container" style="display: none;">
            <p id="message-text"></p>
        </div>
    
        <div class="scanner">
            <h3>VISITOR SCAN QR CODE</h3>
            <div class="body-container">
                <div id="video-container">
                    <video id="video" autoplay></video>
                    <div id="scanner-frame">
                        <div class="corner" id="top-left"></div>
                        <div class="corner" id="top-right"></div>
                        <div class="corner" id="bottom-left"></div>
                        <div class="corner" id="bottom-right"></div>
                    </div>
                    <canvas id="canvas" hidden></canvas>
                </div>
                <p id="output">Scanning...</p>
            </div>
            <div class="scan-info">
                <p>Hold steady until scan is complete.</p>
                <p>Ensure QR is fully visible in the view finder</p>
            </div>
            <div class="back-btn2">
                <a class="nav-link" href="{{ url('/index') }}">BACK</a>
            </div>
        </div>
    
        <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                // Retrieve and display message from local storage if available
                const message = localStorage.getItem('message');
                if (message) {
                    const messageContainer = document.getElementById('message-container');
                    const messageText = document.getElementById('message-text');

                    messageText.innerText = message;
                    messageContainer.style.display = 'block';

                    // Hide the message after 5 seconds
                    setTimeout(() => {
                        messageContainer.style.display = 'none';
                    }, 5000); // 5000 milliseconds = 5 seconds

                    localStorage.removeItem('message'); // Clear message after displaying
                }
            });

            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            const sideVehicleInfo = document.getElementById('sideVehicleInfo');

            // Load the success and error audio files
            const successAudio = new Audio('/scanner_sound/success_audio.mp3');
            const errorAudio = new Audio('/scanner_sound/error_audio.mp3');

            const videoConstraints = {
                facingMode: 'environment',
                frameRate: { ideal: 30, max: 30 }
            };

            function startCamera() {
                navigator.mediaDevices.getUserMedia({ video: videoConstraints })
                    .then(function(stream) {
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true);
                        video.play();
                        requestAnimationFrame(tick);
                    })
                    .catch(function(err) {
                        console.error("Error accessing the camera: ", err);
                        if (errorAudio) {
                            errorAudio.play();
                        }
                        alert("Error accessing the camera.");
                    });
            }

            startCamera();

            let lastScannedQR = null;
            let qrResetTimeout = null;

            function tick() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.height = video.videoHeight;
                    canvas.width = video.videoWidth;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code && isValidQRCode(code.data)) {
                        if (code.data !== lastScannedQR) {
                            lastScannedQR = code.data;
                            console.log("QR Code found:", code.data);
                            sendQRCodeDataToServer(code.data);

                            // Set a timeout to reset the lastScannedQR after 5 seconds
                            if (qrResetTimeout) {
                                clearTimeout(qrResetTimeout);
                            }
                            qrResetTimeout = setTimeout(() => {
                                lastScannedQR = null;
                            }, 5000); // 5000 milliseconds = 5 seconds
                        }
                    } else if (!code) {
                        output.textContent = "Scanning for QR code...";
                    }
                }
                requestAnimationFrame(tick);
            }

            function isValidQRCode(data) {
                return data && data.trim().length > 0;
            }

            function sendQRCodeDataToServer(qrCodeData) {
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    console.error('CSRF token meta tag not found');
                    alert('CSRF token not found.');
                    return;
                }

                const csrfToken = csrfTokenMeta.getAttribute('content');
                fetch('{{ url("/visitor-scan-qr") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ qr_code: qrCodeData })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Server Response:', data);

                    if (data.message) {
                        // Store message and redirect URL in local storage
                        localStorage.setItem('message', data.message);
                        
                        // Determine which sound to play
                        if (data.message.includes('is leaving the university premises')) {
                            if (successAudio) {
                                successAudio.play();
                            }
                        } else {
                            if (errorAudio) {
                                errorAudio.play();
                            }
                        }

                        // Delay the redirection to allow the sound to play first
                        setTimeout(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        }, (successAudio.duration || errorAudio.duration) * 1000); // Convert duration to milliseconds

                    } else if (data.redirect) {
                        // Handle redirects without a message
                        if (data.redirect.includes('unauthorized')) {
                            if (successAudio) {
                                successAudio.play();
                            }
                        } else {
                            if (errorAudio) {
                                errorAudio.play();
                            }
                        }

                        // Delay the redirection to allow the sound to play first
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, (successAudio.duration || errorAudio.duration) * 1000); // Convert duration to milliseconds
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    if (errorAudio) {
                        errorAudio.play();
                    }

                    // Delay the display of error message to allow the sound to play first
                    setTimeout(() => {
                        output.textContent = "Error occurred while scanning QR code.";
                    }, (errorAudio.duration || successAudio.duration) * 1000); // Convert duration to milliseconds
                });
            }

            function isValidQRCode(data) {
                return data && data.trim().length > 0;
            }
        </script>
    </main><!-- End #main -->

    <script src="{{ asset('js/hide_errors_success_unauthorized.js') }}"></script>
    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.1/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.1/dist/sweetalert2.min.js"></script>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",  <!-- Using double quotes around session data -->
                timer: 3000, // Closes after 3 seconds
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "Visitor Time In is Log",  <!-- Using double quotes around session data -->
                timer: 3000, // Closes after 3 seconds
                showConfirmButton: false
            });
        </script>
    @endif
</body>
</html>
