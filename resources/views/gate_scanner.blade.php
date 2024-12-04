
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
    <link rel="stylesheet" href="{{ asset('css/ssu_head.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
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


        .userlog-tDiv {
            max-height: 700px; /* Set a max height for the table container */
            overflow-y: auto;  /* Enable vertical scrolling */
        }
        #userlogsTable th, #userlogsTable td {
            padding: 10px;
            text-align: left;
        }

        #userlogsTable th {
            position: sticky;
            top: 0; /* Keeps the header at the top of the table */
            z-index: 1;
            color: white;
            background-color: #607D8B;
            font-weight: 450;
        }
        .logs-timein{
            color: #4caf50;
        }

        @media (max-width: 800px) {
            .tDiv{
                display: none;
            }
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
            <div class="tDiv">
            <div class="userlog-tDiv">
                <table id="userlogsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Full Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($userLog->isEmpty())
                            <tr>
                                <td colspan="4">No logs available.</td>
                            </tr>
                        @else
                            @foreach($userLog as $log)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->log_date)->format('Y-m-d') }}</td>  
                                    <td>{{ $log->vehicleOwner->fname }} {{ $log->vehicleOwner->lname }}</td>
                                    <td class="logs-timein">{{ \Carbon\Carbon::parse($log->time_in)->format('g:i A') }}</td>
                                    <td>
                                        @if($log->time_out)
                                            {{ \Carbon\Carbon::parse($log->time_out)->format('g:i A') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div>

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const output = document.getElementById('output');
            const context = canvas.getContext('2d');
            const successAudio = new Audio('/scanner_sound/success_audio.mp3');
            const errorAudio = new Audio('/scanner_sound/error_audio.mp3');

            const videoConstraints = {
                facingMode: 'environment',
                frameRate: { ideal: 30, max: 30 }
            };

            function startCamera() {
                navigator.mediaDevices.getUserMedia({ video: videoConstraints })
                    .then(function (stream) {
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true);
                        video.play();
                        requestAnimationFrame(scanFrame);
                    })
                    .catch(function (err) {
                        console.error("Error accessing the camera: ", err);
                        alert("Error accessing the camera.");
                    });
            }

            startCamera();

            let lastScannedQR = null;
            let qrResetTimeout = null;

            function scanFrame() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.height = video.videoHeight;
                    canvas.width = video.videoWidth;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code && code.data.trim() && code.data !== lastScannedQR) {
                        lastScannedQR = code.data;
                        sendQRCodeDataToServer(code.data);

                        // Reset the QR scanner after 5 seconds
                        if (qrResetTimeout) clearTimeout(qrResetTimeout);
                        qrResetTimeout = setTimeout(() => lastScannedQR = null, 5000);
                    } else {
                        output.textContent = "Scanning for QR code...";
                    }
                }
                requestAnimationFrame(scanFrame);
            }

            function showAlert(message, isError = false) {
                alert(isError ? `Error: ${message}` : message);
            }

            function handleResponse(data) {
                if (data.success) {
                    successAudio.play();
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000, // Automatically close after 3 seconds
                        showConfirmButton: false // Hide the confirm button
                        }).then(() => {
                        // Reload the page after the success alert closes
                        location.reload();
                    });
                } else {
                    errorAudio.play();

                    // Check if 'showButtons' is true (indicating unsettled violations)
                    if (data.showButtons) {
                        // Show SweetAlert with two buttons (Deny and Allow)
                        Swal.fire({
                            title: 'Unresolved Violations',
                            text: data.message,
                            icon: 'warning',
                            showCancelButton: true,
                            cancelButtonText: 'Deny',
                            confirmButtonText: 'Allow',
                            reverseButtons: true // Optional: makes 'Allow' the primary button
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Allow: Save the time-in in the database
                                saveTimeIn(data.plateNumbers);
                            } else {
                                // Deny: Cancel the log entry
                                cancelLog();
                            }
                        });
                    } else {
                        // Show a normal error SweetAlert if no buttons are needed
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || "Vehicle owner not found.",
                            icon: 'error',
                            timer: 2000, // Automatically close after 3 seconds
                            showConfirmButton: false // Hide the confirm button
                        });
                    }
                }
            }

            // Function to save the time-in to the database
            function saveTimeIn(plateNumbers) {
                // Here, you can make an AJAX request to save the time-in record
                console.log("Allowing vehicle entry for plate numbers: " + plateNumbers);
                // Example AJAX request (use your actual endpoint)
                $.ajax({
                    url: '/save-time-in',
                    method: 'POST',
                    data: {
                        plate_numbers: plateNumbers,
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Vehicle entry has been recorded.',
                            icon: 'success',
                            timer: 2000, // Automatically close after 3 seconds
                            showConfirmButton: false // Hide the confirm button
                        }).then(() => {
                            // Reload the page after the success alert closes
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong while saving the entry.',
                            icon: 'error',
                            timer: 2000, // Automatically close after 3 seconds
                            showConfirmButton: false // Hide the confirm button
                        });
                    }
                });
            }

            // Function to cancel the log entry
            function cancelLog() {
                Swal.fire({
                    title: 'Log Canceled',
                    text: 'Vehicle entry has been denied.',
                    icon: 'info',
                    timer: 2000, // Automatically close after 3 seconds
                    showConfirmButton: false // Hide the confirm button
                });
            }

            function sendQRCodeDataToServer(qrCodeData) {
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    alert("CSRF token not found.");
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
                .then(data => handleResponse(data))
                .catch(err => {
                    console.error("Error processing the QR code:", err);
                    showAlert("An error occurred while processing the QR code.", true);
                });
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
