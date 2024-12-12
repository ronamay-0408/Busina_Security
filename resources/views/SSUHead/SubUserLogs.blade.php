<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - SubUserLogs</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ssu_head.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
    <style>
        /* Initial styles for the license number */
        .driver-license .license-number {
            padding-left: 5px;
        }
        .fa-eye-slash{
            position: absolute;
            cursor: pointer;
            color: #566a7f;
            font-size: 14px;
            right: 37px;
        }
        #eye-icon{
            position: absolute;
            cursor: pointer;
            color: #566a7f;
            font-size: 14px;
            right: 36px;
        }
        @media (max-width: 600px) {
            .fa-eye-slash{
                position: revert;
            }
            #eye-icon{
                position: revert;
            }
        }
    </style>
<body>
    @include('SSUHead.partials.sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>

        <div class="submain">
            <h3 class="userlog-title">Vehicle Owner Information</h3>
            <div class="owner-info">
                <ul>
                    <li>
                        <span>Full Name :</span> 
                        <span class="deets">{{ $vehicleOwner->fname }} {{ $vehicleOwner->mname }} {{ $vehicleOwner->lname }}</span>
                    </li>
                    <li>
                        <span>Contact Number :</span> 
                        <span class="deets">{{ $vehicleOwner->contact_no }}</span>
                    </li>
                </ul>
                <ul>
                    <li>
                        <span>Driver's License :</span>
                        <span class="deets driver-license">
                            <span class="license-number">••••••••••••••</span>
                            <span class="actual-license-number" style="display: none;">{{ $vehicleOwner->driver_license_no }}</span>
                        </span>
                        <i class="fa fa-eye-slash" aria-hidden="true" id="eye-icon" onclick="toggleLicenseVisibility()"></i>
                    </li>
                    <li>
                        <span>Owner Type :</span> 
                        <span class="deets">{{ $vehicleOwner->applicantType->type }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <h2>OWNED VEHICLES</h2>
        <div class="submain">
            <div class="vehicles-owned">
                @foreach($vehicleOwner->vehicles as $vehicle)
                    <div class="vehicle">
                        <h3 class="userlog-title">Vehicle Information</h3>
                        <div class="owner-info">
                            <ul>
                                <li>
                                    <span>Plate Number :</span> 
                                    <span class="deets">{{ $vehicle->plate_no }}</span>
                                </li>
                                <li>
                                    <span>Registration Number :</span> 
                                    <span class="deets">
                                        @if($vehicle->transactions)
                                            {{ $vehicle->transactions->registration_no }}
                                        @else
                                            No registration data available.
                                        @endif
                                    </span>
                                </li>
                                <li>
                                    <span>Sticker Expiry :</span> 
                                    <span class="deets">
                                        @if($vehicle->transactions && $vehicle->transactions->sticker_expiry)
                                            {{ \Carbon\Carbon::parse($vehicle->transactions->sticker_expiry)->format('Y-m-d') }}
                                        @else
                                            No sticker expiry data.
                                        @endif
                                    </span>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <span>Model Color :</span> 
                                    <span class="deets">{{ $vehicle->model_color }}</span>
                                </li>
                                <li>
                                    <span>Vehicle Status :</span>
                                    <span class="deets">
                                        @if($vehicle->transactions)
                                            {{ $vehicle->transactions->vehicle_status }}
                                        @else
                                            No vehicle status data.
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    @include('SSUHead.partials.footer')
    
    <script>
        function toggleLicenseVisibility() {
            var maskedLicense = document.querySelector('.driver-license .license-number');
            var actualLicense = document.querySelector('.driver-license .actual-license-number');
            var eyeIcon = document.getElementById('eye-icon');
            
            // Toggle the visibility of the actual license number and masked version
            if (maskedLicense.style.display === "none") {
                maskedLicense.style.display = "inline";  // Show the masked number
                actualLicense.style.display = "none";    // Hide the actual number
                eyeIcon.classList.remove('fa-eye');        // Remove normal eye icon
                eyeIcon.classList.add('fa-eye-slash');     // Add slashed eye icon
            } else {
                maskedLicense.style.display = "none";   // Hide the masked number
                actualLicense.style.display = "inline"; // Show the actual number
                eyeIcon.classList.remove('fa-eye-slash'); // Remove slashed eye icon
                eyeIcon.classList.add('fa-eye');          // Add normal eye icon
            }
        }
    </script>
    <!-- MODAL AND SEARCH JS -->
    <!-- <script src="{{ asset('js/head_violation_modal.js') }}"></script> -->

    <!-- Filtering JS File -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('js/head_violation_filtering.js') }}"></script>

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
