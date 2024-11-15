
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Gate QR Code Scanner</title>
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
    
    <style>
        .side_vehicle_info {
            display: none; /* Hidden by default */
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center the div */
            z-index: 9999; /* Ensure it appears on top */
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
            opacity: 0; /* Start hidden for fade-in effect */
            transition: opacity 0.5s ease-in-out; /* Smooth transition */
            /* position: relative; */
            
        }
        @media (max-width: 650px) {
            .side_vehicle_info{
                width: 90%;
            }
        }

        /* Show and animate the pop-up */
        .side_vehicle_info.show {
            display: block;
            opacity: 1; /* Fade in */
        }

        /* Styling for the success state */
        .side_vehicle_info.found {
            background-color: #e6ffe6;
            border: 2px solid #4caf50;
        }

        /* Styling for the error state */
        .side_vehicle_info.not-found {
            background-color: #ffe6e6;
            border: 2px solid #f44336;
        }

        /* Close (X) button styling */
        .side_vehicle_info .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 18px;
            font-weight: bold;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        /* Add some padding for the title message */
        .side_vehicle_info .title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .side_vehicle_info .title h2 {
            margin: 0;
            font-size: 18px
        }
    </style>

</head>

<body>
    @include('MainPartials.ssu_sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>

        <div class="gate-scanner">
            <div class="scanner gate-scanner2">
                <h3>VEHICLE OWNER LOGS</h3>
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
                    <p>Ensure QR is fully visible in the viewfinder</p>
                </div>
                <div class="back-btn2">
                    <a class="nav-link" href="{{ url('/index') }}">BACK</a>
                </div>
            </div>

            <div class="side_vehicle_info" id="sideVehicleInfo">
                <!-- This will be dynamically updated based on the response -->
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const output = document.getElementById('output');
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
                        output.textContent = "Error accessing the camera.";
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
                            output.textContent = `QR Code Data: ${code.data}`;
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

            // Function to show the message
            function showMessage(content, isError = false) {
                const messageBox = document.getElementById('sideVehicleInfo');
                messageBox.innerHTML = `
                    <div class="title">
                        <button class="close-btn" onclick="hideMessage()">×</button>
                        <span>${content}</span>
                    </div>
                `;

                messageBox.classList.remove('found', 'not-found'); // Clear previous classes
                messageBox.classList.add('show', isError ? 'not-found' : 'found'); // Add show class

                console.log("Showing message:", content); // Debug log

                // Automatically hide the message after 5 seconds
                setTimeout(() => {
                    console.log("Hiding message after timeout"); // Debug log
                    hideMessage();
                }, 5000); // 3000ms = 3 seconds
            }

            // Function to hide the message
            function hideMessage() {
                const messageBox = document.getElementById('sideVehicleInfo');
                messageBox.classList.remove('show'); // Hide the message
                console.log("Message hidden"); // Debug log
            }

            // Example usage after receiving a response from the server
            function handleResponse(data) {
                console.log('Response received:', data); // Log the response for debugging

                if (data.success) {
                    // Vehicle entry successful
                    showMessage(`Vehicle entry successful! Welcome ${data.vehicleOwner.fname} ${data.vehicleOwner.mname} ${data.vehicleOwner.lname}, your entry has been recorded.`, false);
                } else {
                    // Vehicle owner not found or leaving
                    if (data.message && data.message.includes('Leaving the University Premises')) {
                        showMessage(data.message, false);
                    } else {
                        showMessage(data.message || "Vehicle owner not found.", true);
                    }
                }
            }
            const sendQRCodeDataToServer = debounce(function(qrCodeData) {
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    console.error('CSRF token meta tag not found');
                    alert('CSRF token not found.');
                    return;
                }

                const csrfToken = csrfTokenMeta.getAttribute('content');
                fetch('{{ route("gate_scanner.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ qr_code: qrCodeData })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);  // Check if you get the correct response
                    sideVehicleInfo.innerHTML = '';
                    sideVehicleInfo.classList.remove('show'); // Hide by default before updating
                    
                    if (data.success) {
                        successAudio.play();
                        setTimeout(() => {
                            console.log('Success case triggered');  // Debugging log

                            // handleResponse(data); FIX THIS, MAKE THE SUCCESS MESSAGE TO HIDE AUTOMATICALLY

                            sideVehicleInfo.classList.add('show'); // Show the div if there's a message
                            sideVehicleInfo.className = 'side_vehicle_info found show';

                            // Show the appropriate message based on the response
                            if (data.message && data.message.includes('Leaving the University Premises')) {
                                // Show message when vehicle is leaving
                                sideVehicleInfo.innerHTML = `
                                    <div class="title">
                                        <button class="close-btn" onclick="hideMessage()">×</button>
                                        <span>${data.message}</span>
                                    </div>
                                `;
                            } else if (data.vehicleOwner) {
                                // Show message when vehicle entry is successful
                                sideVehicleInfo.innerHTML = `
                                    <div class="title">
                                        <button class="close-btn" onclick="hideMessage()">×</button>
                                        <span>Vehicle entry successful! Welcome <h2>${data.vehicleOwner.fname} ${data.vehicleOwner.mname} ${data.vehicleOwner.lname}</h2></span>
                                    </div>
                                `;
                            }

                            // Automatically hide the success message after 3 seconds
                            setTimeout(() => {
                                hideMessage();
                            }, 3000); // 3000ms = 3 seconds

                        }, 300);
                    } else {
                        errorAudio.play();
                        setTimeout(() => {
                            console.log('Error case triggered');  // Debugging log
                            handleResponse(data); // Call handleResponse to show the message (THIS CODE SUPPORTS ON HIDING THE ERROR MESSAGE)
                            sideVehicleInfo.classList.add('show'); // Show the div when there's an error
                            sideVehicleInfo.className = 'side_vehicle_info not-found show';
                            sideVehicleInfo.innerHTML = `
                                <div class="title">
                                    <button class="close-btn" onclick="hideMessage()">×</button>
                                    <h4>Error: ${data.message || "Vehicle owner not found."}</h4>
                                </div>
                            `;
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    errorAudio.play();
                    setTimeout(() => {
                        console.log('Catch block triggered');  // Debugging log
                        sideVehicleInfo.classList.add('show'); // Show the div when there's an error
                        sideVehicleInfo.className = 'side_vehicle_info not-found show';
                        sideVehicleInfo.innerHTML = `<h3>Error occurred while scanning QR code.</h3>`;
                    }, 300);
                });
            }, 500);
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
        </script>
    </main><!-- End #main -->

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>

    <!--- QR CAMERA ---->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script src="{{ asset('js/qr_camera.js') }}"></script> -->
</body>
</html>
