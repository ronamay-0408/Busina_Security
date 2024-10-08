
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
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="bar">
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        
        <div class="logo">
            <img src="{{ asset('images/BUsina logo (1) 2.png') }}" alt="">
        </div>
    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="qr_sidebar">

        <div class="profile">
            <div class="image">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="info">
                @if(Session::has('user'))
                    <h2>{{ Session::get('user')['fname'] }} {{ Session::get('user')['lname'] }}</h2>
                    <h3>{{ Session::get('user')['email'] }}</h3>
                @endif
            </div>
        </div>

        @include('MainPartials.ssu_sidebar')
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="date-time">
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
                    console.log('Server response:', data);
                    sideVehicleInfo.innerHTML = '';
                    if (data.success) {
                        // Play success sound first
                        successAudio.play();
                        // Delay the output of the message by 300ms to allow the sound to play first
                        setTimeout(() => {
                            if (data.message) {
                                sideVehicleInfo.className = 'side_vehicle_info found vehicle-out';
                                sideVehicleInfo.innerHTML = data.message;
                            } else if (data.vehicleOwner) {
                                sideVehicleInfo.className = 'side_vehicle_info found';
                                sideVehicleInfo.innerHTML = `
                                    <div class="title">
                                        <h3>NAME: <span>${data.vehicleOwner.fname} ${data.vehicleOwner.mname} ${data.vehicleOwner.lname}</span></h3>
                                    </div>
                                    <div class="title">
                                        <h3>REGISTERED VEHICLE</h3>
                                    </div>
                                    <div class="registered_vehicle">
                                        ${data.vehicles.map(vehicle => `
                                            <div class="vehicle_con">
                                                <div class="vehicle_info">
                                                    <h3>
                                                        REGISTRATION NUMBER: 
                                                        <a href="/vehicle-info/${encodeURIComponent(vehicle.registration_no)}" class="vehicle-link">
                                                            ${vehicle.registration_no || 'N/A'}
                                                        </a>
                                                    </h3>
                                                    <p>PLATE NUMBER: <span>${vehicle.plate_no || 'N/A'}</span></p>
                                                    <p>STICKER EXPIRY: <span>${vehicle.sticker_expiry ? new Date(vehicle.sticker_expiry).toLocaleDateString() : 'N/A'}</span></p>
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                `;
                            }
                        }, 300); // 300 milliseconds delay
                    } else {
                        // Play error sound first
                        errorAudio.play();
                        // Delay the output of the message by 300ms to allow the sound to play first
                        setTimeout(() => {
                            sideVehicleInfo.className = 'side_vehicle_info not-found';
                            sideVehicleInfo.innerHTML = `<h3>Error: ${data.message || "Vehicle owner not found."}</h3>`;
                        }, 300); // 300 milliseconds delay
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    // Play error sound first
                    errorAudio.play();
                    // Delay the output of the message by 300ms to allow the sound to play first
                    setTimeout(() => {
                        sideVehicleInfo.className = 'side_vehicle_info not-found';
                        sideVehicleInfo.innerHTML = `<h3>Error occurred while scanning QR code.</h3>`;
                    }, 300); // 300 milliseconds delay
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
