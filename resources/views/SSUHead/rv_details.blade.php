<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Violation Details</title>
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
</head>
    <style>
        .subsubmain{
            display: flex;
        }
        .owner-info{
            flex: 3;
        }
        .sub-proof-image{
            flex: 2;
        }
        .sub-proof-image h3{
            font-weight: 550;
            margin: 0;
            color: #566a7f;
            font-size: 18px;
            padding: 10px 0px;
        }
        @media (max-width: 600px) {
            .subsubmain{
                display: flow;
            }
            .sub-proof-image img{
                max-width: 100%;
            }
        }
    </style>
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
                <img src="{{ asset('images/BUsina logo (1) 1.png') }}" alt="">
            </div>
            <div class="head_info">
                @if(Session::has('user'))
                    <h2>{{ Session::get('user')['fname'] }} {{ Session::get('user')['lname'] }}</h2>
                    <h3>{{ Session::get('user')['email'] }}</h3>
                @endif
            </div>
        </div>

        @include('SSUHead.partials.sidebar')
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="date-time"></div>

        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">VIOLATION DETAILS</h3>
            </div>
            <div class="subsubmain">
                <div class="owner-info">
                    <ul>
                        <li>
                            <span>Plate No :</span> 
                            <span class="deets">{{ $violation->plate_no }}</span>
                        </li>
                        <li>
                            <span>Violation Name :</span> 
                            <span class="deets">{{ $violation->violationType ? $violation->violationType->violation_name : 'N/A' }}</span>
                        </li>
                        <li>
                            <span>Reported By :</span>
                            <span class="deets">{{ $violation->reportedBy ? $violation->reportedBy->getFullNameAttribute() : 'N/A' }}</span>
                        </li>
                        <li>
                            <span>Reported At :</span> 
                            <span class="deets">{{ $violation->created_at->format('Y-m-d H:i:s') }}</span>
                        </li>
                        <li>
                            <span>Location :</span>
                            <span class="deets">{{ $violation->location }}</span>
                        </li>
                    </ul>
                </div>
                <div class="sub-proof-image">
                    <h3>Proof Image</h3>
                    @if($violation->proof_image)
                        <img src="{{ asset('storage/' . $violation->proof_image) }}" alt="Proof Image" style="width: 500px; height: auto;">
                    @else
                        <span class="deets">No proof image available</span>
                    @endif
                </div>
            </div>
            <div class="back-btn3">
                <a class="nav-link" href="{{ url('/reported_violations') }}">BACK</a>
            </div>
        </div>
    </main><!-- End #main -->

    @include('SSUHead.partials.footer')

    <script>
        // Make the entire div clickable to trigger file selection
        document.getElementById('click-files')?.addEventListener('click', function() {
            document.getElementById('files').click(); // Trigger the hidden file input
        });

        // Replace the "upload 1.png" with the selected image
        document.getElementById('files')?.addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the selected file
            const uploadIcon = document.getElementById('upload-icon');
            
            if (file) {
                // Create an object URL to display the image preview
                uploadIcon.src = URL.createObjectURL(file);
                uploadIcon.style.width = '350px';  // Adjust the width of the preview
                uploadIcon.style.height = '550px'; // Adjust the height of the preview
                
                uploadIcon.onload = function() {
                    URL.revokeObjectURL(uploadIcon.src); // Free memory after loading
                };
            }
        });

        // Trigger the file input when the "Edit Document" button is clicked
        document.getElementById('edit-button').addEventListener('click', function() {
            document.getElementById('new-document').click(); // Open file selection dialog
        });

        // Handle file selection and enable the Save button
        document.getElementById('new-document').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const uploadedDocument = document.getElementById('uploaded-document');
                uploadedDocument.src = URL.createObjectURL(file); // Update image preview

                // Release the memory after loading the new preview image
                uploadedDocument.onload = function() {
                    URL.revokeObjectURL(uploadedDocument.src);
                };

                // Enable the Save button after selecting a new document
                document.getElementById('save-button').disabled = false;
            }
        });
    </script>

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
