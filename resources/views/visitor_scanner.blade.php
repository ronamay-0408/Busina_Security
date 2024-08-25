
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

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('index') }}">
                    <img src="images/Dashboard Layout.png" alt="">
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('view_reports') }}">
                    <img src="images/Foul.png" alt="">
                    <span>My Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('scanned_qr') }}">
                    <img src="images/Qr Code.png" alt="">
                    <span>Scanned QR Code</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('guidelines') }}">
                    <img src="images/Driving Guidelines.png" alt="">
                    <span>Guidelines</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('myaccount') }}">
                    <img src="images/Account.png" alt="">
                    <span>My Account</span>
                </a>
            </li>
            <li class="nav-item last">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="images/Open Pane.png" alt="">
                    <span>Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="date-time">
        </div>

        @if (session('error'))
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
        @endif

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
                        alert("Error accessing the camera.");
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
                        if (code.data !== lastScannedQR) {
                            console.log("QR Code found:", code.data);
                            sendQRCodeDataToServer(code.data);

                            lastScannedQR = code.data;

                            setTimeout(() => {
                                lastScannedQR = null;
                            }, 1000);
                        }
                    }
                }
                requestAnimationFrame(tick);
            }

            function sendQRCodeDataToServer(qrCodeData) {
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    console.error('CSRF token meta tag not found');
                    alert('CSRF token not found.');
                    return;
                }

                const csrfToken = csrfTokenMeta.getAttribute('content');
                console.log('Sending QR Code Data:', qrCodeData);

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

                    // Store message and redirect URL in local storage
                    if (data.message) {
                        localStorage.setItem('message', data.message);
                    }
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error occurred while scanning QR code: " + error.message);
                });
            }

            function hideMessage(id) {
                const element = document.getElementById(id);
                if (element) {
                    element.style.display = 'none';
                }
            }
        </script>
    </main><!-- End #main -->

    <script src="{{ asset('js/hide_errors_success_unauthorized.js') }}"></script>

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>

    <!--- QR CAMERA ---->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script src="{{ asset('js/qr_camera.js') }}"></script> -->
</body>
</html>
