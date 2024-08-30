
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - QR Code Scanner</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: red;
            color: white;
            padding: 10px;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.5s;
        }
    </style>
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
    <aside id="sidebar" class="sidebar">

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

        <div class="scanner">
            <h3>VERIFICATION SCANNER</h3>
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
    
        <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const output = document.getElementById('output');
            const context = canvas.getContext('2d');

            // Configure video constraints for optimal performance
            const videoConstraints = {
                facingMode: 'environment',
                frameRate: { ideal: 30, max: 30 } // Adjust frame rate as needed
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

            function tick() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.height = video.videoHeight;
                    canvas.width = video.videoWidth;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code && code.data) {
                        // QR Code detected
                        if (code.data !== lastScannedQR) {
                            console.log("QR Code found:", code.data);
                            output.textContent = `QR Code Data: ${code.data}`;

                            sendQRCodeDataToServer(code.data);

                            lastScannedQR = code.data;

                            // Reset lastScannedQR to null after a short delay
                            setTimeout(() => {
                                output.textContent = "Scanning...";
                                lastScannedQR = null;
                            }, 1000); // Adjust delay to be shorter or longer depending on your needs
                        }
                    } else {
                        // No QR Code detected
                        output.textContent = "Scanning for QR code...";
                    }
                }
                requestAnimationFrame(tick);
            }

            // Debounced function for sending QR code data to the server
            const sendQRCodeDataToServer = debounce(function(qrCodeData) {
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    console.error('CSRF token meta tag not found');
                    alert('CSRF token not found.');
                    return;
                }

                const csrfToken = csrfTokenMeta.getAttribute('content');
                fetch('{{ route("scan.qr") }}', {
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
                    if (data.success) {
                        window.location.href = '{{ route("scanned.result") }}';
                    } else {
                        if (data.redirect) {
                            window.location.href = data.redirect; // Redirect to the URL provided in the response
                        } else {
                            alert("Error: " + (data.message || "Unknown error occurred"));
                        }
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error occurred while scanning QR code.");
                });
            }, 500); // Debounce for 0.5 seconds

            // Debounce function definition
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
