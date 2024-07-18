
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>QR Code Scanner</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    
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
                <a class="nav-link hove" href="{{ route('scanned_qr') }}">
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
        
        <div class="scanner">
            <h3>SCAN QR CODE</h3>

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

    </main><!-- End #main -->

    <!-- Template Main JS File // NAVBAR // -->
    <script>
        (function() {
        "use strict";

        /**
         * Easy selector helper function
         */
        const select = (el, all = false) => {
            el = el.trim()
            if (all) {
            return [...document.querySelectorAll(el)]
            } else {
            return document.querySelector(el)
            }
        }
        /**
         * Easy event listener function
         */
        const on = (type, el, listener, all = false) => {
            if (all) {
            select(el, all).forEach(e => e.addEventListener(type, listener))
            } else {
            select(el, all).addEventListener(type, listener)
            }
        }
        /**
         * Sidebar toggle
         */
        if (select('.toggle-sidebar-btn')) {
            on('click', '.toggle-sidebar-btn', function(e) {
            select('body').classList.toggle('toggle-sidebar')
            })
        }
        })();
    </script>
    <!-- Template Main JS File // NAVBAR // -->

    <!-- DATE AND TIME -->
    <script>
        function formatDate(date) {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = days[date.getDay()];
            
            const year = date.getFullYear();
            
            const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed
            const day = date.getDate().toString().padStart(2, '0');
            
            let hours = date.getHours();
            const minutes = date.getMinutes().toString().padStart(2, '0');
            
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            const strTime = hours.toString().padStart(2, '0') + ':' + minutes + ' ' + ampm;

            return `${dayName}, ${year}-${month}-${day}     ${strTime}`;
        }

        function displayDateTime() {
            const now = new Date();
            const formattedDate = formatDate(now);
            console.log('Formatted Date:', formattedDate); // Debugging line
            document.querySelector('.date-time').textContent = formattedDate;
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            console.log('DOM fully loaded and parsed'); // Debugging line
            displayDateTime();
        });
    </script>
    <!-- DATE AND TIME -->

    <!--- QR CAMERA ---->
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script>
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
  
    </script>
    <!-- QR CAMERA ---> 
</body>
</html>
