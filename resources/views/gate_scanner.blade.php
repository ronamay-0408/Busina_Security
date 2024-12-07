
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

        /* The modal background */
        .custom-modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4); /* Black with transparency */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 380px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Modal Header */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .modal-header h5 {
            margin: 0;
            font-size: 18px;
        }

        /* Close button */
        .close {
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        /* Modal Footer */
        .modal-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #007bff;
            color: #fff;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        #cancelBtn {
            background-color: #ccc;
        }

        #cancelBtn:hover {
            background-color: #999;
        }

        /* Input field */
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
                    <a class="nav-link log-link">LOG</a>
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

            <!-- Custom Modal -->
            <div id="driverLicenseModal" class="custom-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Please enter the Driver License</h5>
                        <span class="close" id="closeModal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form id="driverLicenseForm">
                            <label for="driverLicenseInput">Driver License</label>
                            <input type="text" placeholder="A00-00-000000" id="driverLicenseInput" required>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="cancelBtn" class="btn">Cancel</button>
                        <button type="button" id="saveDriverLicenseBtn" class="btn">Save</button>
                    </div>
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
                        timer: 2000, // Automatically close after 2 seconds
                        showConfirmButton: false // Hide the confirm button
                    }).then(() => {
                        // Reload the page after the success alert closes
                        location.reload();
                    });
                } else {
                    errorAudio.play();

                    // Check if 'showButtons' is true (indicating unsettled violations)
                    if (data.showButtons) {
                        // Show SweetAlert with two buttons (Deny and Allow) for unresolved violations
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
                    } else if (data.timeinbutton) {
                        // Show SweetAlert with two buttons (Deny and Allow) for time-in confirmation
                        Swal.fire({
                            title: 'Time-in Confirmation',
                            text: `Do you want to allow these vehicles: ${data.plateNumbers} inside the University Premises?`,
                            icon: 'question',
                            showCancelButton: true,
                            cancelButtonText: 'Deny',
                            confirmButtonText: 'Allow',
                            reverseButtons: true // Optional: makes 'Allow' the primary button
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Allow: Save the time-in in the database
                                saveTimeIn(data.plateNumbers);
                            } else {
                                // Deny: Cancel the time-in request
                                cancelLog();  // Ensure that this function doesn't save the time-in
                            }
                        });
                    } else {
                        // Show a normal error SweetAlert if no buttons are needed
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || "Vehicle owner not found.",
                            icon: 'error',
                            timer: 2000, // Automatically close after 2 seconds
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
                }).then(() => {
                    // Reload the page after the success alert closes
                    location.reload();
                });
            }

            // function confirmTimeIn(vehicleOwnerId, plateNumbers) {
            //     // Prepare the data to send to the server
            //     const data = {
            //         vehicle_owner_id: vehicleOwnerId,
            //         plate_numbers: plateNumbers,
            //         owner_name: `${data.vehicleOwner.fname} ${data.vehicleOwner.lname}`
            //     };

            //     // Send AJAX request to confirm the time-in
            //     $.ajax({
            //         url: '/confirmTimeIn',  // Ensure this URL matches the backend route
            //         method: 'POST',
            //         data: data,
            //         success: function(response) {
            //             if (response.success) {
            //                 Swal.fire({
            //                     title: 'Success!',
            //                     text: response.message,
            //                     icon: 'success',
            //                     timer: 2000, // Automatically close after 2 seconds
            //                     showConfirmButton: false
            //                 }).then(() => {
            //                     location.reload();  // Reload the page after successful time-in
            //                 });
            //             } else {
            //                 Swal.fire({
            //                     title: 'Error!',
            //                     text: response.message,
            //                     icon: 'error'
            //                 });
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             Swal.fire({
            //                 title: 'Error!',
            //                 text: 'An error occurred while confirming the time-in.',
            //                 icon: 'error'
            //             });
            //         }
            //     });
            // }

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

        <!-- // MODAL SCRIPT // -->
        <script>
            // Get the modal and buttons
            const logLink = document.querySelector('.log-link');
            const driverLicenseModal = document.getElementById('driverLicenseModal');
            const saveDriverLicenseBtn = document.getElementById('saveDriverLicenseBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const closeModal = document.getElementById('closeModal');
            const driverLicenseInput = document.getElementById('driverLicenseInput');

            // Open the modal when the LOG link is clicked
            logLink.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent the default action (if it's a link)
                driverLicenseModal.style.display = "block"; // Show the modal
            });

            // Close the modal when the user clicks on (x)
            closeModal.addEventListener('click', function () {
                driverLicenseModal.style.display = "none";
            });

            // Close the modal when the Cancel button is clicked
            cancelBtn.addEventListener('click', function () {
                driverLicenseModal.style.display = "none";
            });

            // Handle the Save button click
            saveDriverLicenseBtn.addEventListener('click', function () {
                const driverLicense = driverLicenseInput.value.trim();

                if (driverLicense === '') {
                    alert('Please enter the Driver License.');
                    return;
                }

                // Here you can handle the form submission, like sending data to the server.
                // Example of sending data using AJAX (make sure to update the URL and data format as needed):

                // You can send it via an AJAX request, for example:
                $.ajax({
                    url: '/save-driver-license', // Update with your actual endpoint
                    method: 'POST',
                    data: {
                        driver_license: driverLicense,
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Driver License saved successfully.');
                            driverLicenseModal.style.display = "none"; // Close the modal
                        } else {
                            alert('Error saving driver license.');
                        }
                    },
                    error: function() {
                        alert('An error occurred.');
                    }
                });
            });

            // Close the modal if the user clicks outside of the modal content
            window.addEventListener('click', function (event) {
                if (event.target == driverLicenseModal) {
                    driverLicenseModal.style.display = "none";
                }
            });
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
