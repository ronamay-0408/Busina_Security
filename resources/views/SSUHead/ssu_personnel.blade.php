<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - SSU Personnel</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ssu_head.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Style the select to look disabled */
        .disabled-dropdown {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            color: #666;
            cursor: not-allowed; /* Show a not-allowed cursor */
            pointer-events: none; /* Disable interaction */
            /* Optional: Remove default styling */
            -webkit-appearance: none;
            appearance: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }
        .input-form1 span{
            padding: 5px 5px 5px 10px;
            border: 0.5px solid #80808066;
            border-radius: 5px 0px 0px 5px;
            /* box-sizing: border-box; */
            font-family: 'Poppins';
            background: #607d8b42;
            color: black;
            font-size: 14px;
            font-weight: 500;
        }
        input#contact {
            border-radius: 0px 5px 5px 0px;
            flex: 4.5;
        }

        @media (max-width: 600px) {
            input#contact {
                flex: 0.2;
            }
        }
    </style>
</head>

<body>
    @include('SSUHead.partials.sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>

        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">SSU PERSONNELS</h3>
            </div>

            <div class="content">
                <div class="search-bar head-search">
                    <input type="text" id="searchInputSSU" placeholder="Search.." name="search">
                </div>
                <div class="ssu-buttons">
                    <div class="export-tbn">
                        <button class="export-child" onclick="exportTableToExcel()">Export</button>
                    </div>

                    <div class="add-new">
                        <img src="images/plus.png" alt="Add New">
                    </div>

                    <div class="export-tbn">
                        <button type="button" id="printSSUPersonnel" class="export-child">Print</button>
                    </div>
                </div>
            </div>

            <!-- @if ($errors->any())
            <div class="main-error head-main-error">
                <p id="errorMessage" class="error-message">
                    <span><i class="bi bi-exclamation-circle"></i></span>
                    {{ $errors->first() }}
                    <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
                </p>
            </div>
            @endif

            @if (session('error'))
            <div class="main-error head-main-error">
                <p id="errorMessage" class="error-message">
                    <span><i class="bi bi-exclamation-circle"></i></span>
                    {{ session('error') }}
                    <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
                </p>
            </div>
            @endif

            @if (session('success'))
            <div class="main-success head-main-success">
                <p id="successMessage" class="success-message">
                    <span><i class="bi bi-check-circle"></i></span>
                    {{ session('success') }}
                    <a class="cancel-button-success" onclick="hideSuccessMessage()"><i class="bi bi-x"></i></a>
                </p>
            </div>
            @endif -->

            <div class="head_view_ssu_table">
                <table id="ssuTable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Middle Name</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authorizedUsers as $user)
                            <tr>
                                <td>{{ $user->fname }}</td>
                                <td>{{ $user->lname }}</td>
                                <td>{{ $user->mname }}</td>
                                <td>{{ $user->contact_no }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="blur-bg-overlay"></div>
            <div class="form-popup">
                <span class="close-btn">&times;</span>
                <div class="form-box">
                    <div class="form-content">
                        <h2>Add Authorize SSU Personnel</h2>

                        <form action="{{ route('ssu_personnel') }}" method="post" onsubmit="return validateEmail()">
                            @csrf
                            <div class="inputs1">
                                <div class="input-form1">
                                    <label for="fname">First Name</label>
                                    <input type="text" placeholder="Juan" id="fname" name="fname" required>
                                </div>

                                <div class="input-form1">
                                    <label for="lname">Last Name</label>
                                    <input type="text" placeholder="Santos" id="lname" name="lname" required>
                                </div>

                                <div class="input-form1">
                                    <label for="mname">Middle Name</label>
                                    <input type="text" placeholder="Carlos" id="mname" name="mname">
                                </div>

                                <div class="input-form1">
                                    <label for="contact">Contact #</label>
                                    <span>+63</span>
                                    <input type="text" id="contact" name="contact" placeholder="Enter your number" required>
                                </div>

                                <div class="input-form1">
                                    <label for="email">Email</label>
                                    <input type="email" placeholder="juancarlossantos@gmail.com" id="email" name="email" pattern="[a-z0-9._%+-]+@gmail\.com$" required>
                                </div>

                                <div class="input-form2">
                                    <small id="email-error" class="error-message" style="display: none;">Please enter a valid Gmail email address.</small>
                                </div>

                                <div class="input-form1">
                                    <label for="user_type">User Type</label>
                                    <select id="user_type" name="user_type" class="disabled-dropdown" required>
                                        <option value="2" selected>Security Personnel</option>
                                        <!-- Add more options as needed -->
                                    </select>
                                </div>
                                
                                <div class="submit">
                                    <button type="submit" id="submit" class="done">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
    <script>
        function exportTableToExcel() {
            window.location.href = "{{ route('export.authorized_users') }}";
        }
    </script>

    <script>
        document.getElementById('printSSUPersonnel').addEventListener('click', function() {
            printSSUPersonnelTable();
        });

        function printSSUPersonnelTable() {
            // Fetch the current user data (Blade data passed into JS)
            const userFname = '{{ Session::get("user")["fname"] ?? "Unknown" }}';
            const userLname = '{{ Session::get("user")["lname"] ?? "User" }}';

            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const formattedTime = currentDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

            // Get the content of the SSU Personnel table
            const tableContent = document.getElementById('ssuTable').outerHTML;

            // Open the print window
            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print SSU Personnel</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                            }
                            @media print {
                                /* Hide default browser print header and footer */
                                @page {
                                    margin: 10px 20px 20px 20px;
                                }
                                    
                                /* Hide the default header (browser-specific) */
                                .no-print {
                                    display: none;
                                }
                                /* Header Styles */
                                .print-header {
                                    text-align: center;
                                    margin-bottom: 20px;
                                }
                                .print-header h1 {
                                    margin: 0;
                                    font-size: 20px;
                                    font-weight: bold;
                                }
                                .print-header h2 {
                                    margin: 0;
                                    font-size: 16px;
                                    font-weight: normal;
                                }
                                .print-header h3 {
                                    margin: 0;
                                    font-size: 16px;
                                    font-weight: bold;
                                    text-decoration: underline;
                                    color: black;
                                }
                                .details {
                                    margin: 10px 0;
                                    font-family: Arial, sans-serif;
                                    font-size: 14px;
                                    line-height: 1.5;
                                }
                                .details p {
                                    margin: 0;
                                }
                                /* Table Styles */
                                table { 
                                    width: 100%; 
                                    border-collapse: collapse; 
                                    margin-top: 20px; 
                                }
                                th, td { 
                                    border: 1px solid #000; 
                                    padding: 8px; 
                                    text-align: left; 
                                } 
                            }
                            /* Optional styles for "Time In/Out" formatting */
                            .time-format { font-style: italic; }
                        </style>
                    </head>
                    <body>
                        <!-- Header -->
                        <div class="print-header">
                            <h1>Bicol University</h1>
                            <h2>Rizal St., Legazpi City, Albay</h2>
                            <h3>BU Head SSU Section</h3>
                        </div>
                        <!-- Details Section -->
                        <div class="details">
                            <p><b>Title:</b> SSU Personnel Records</p>
                            <p><b>Print By:</b> ${userFname} ${userLname}</p>
                            <p><b>Date:</b> ${formattedDate} at ${formattedTime}</p>
                        </div>
                        <!-- SSU Personnel Table -->
                        ${tableContent}
                    </body>
                </html>
            `);
            printWindow.document.close();

            // Trigger print dialog and close the window afterward
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>

    @include('SSUHead.partials.footer')
    <script src="{{ asset('js/head_ssu_search.js') }}"></script>
    <!-- ERROR AND SUCCESS -->
    <script src="{{ asset('js/error_success_message.js') }}"></script>
    <script src="{{ asset('js/validate_email.js') }}"></script>
    <script src="{{ asset('js/adduser_popup.js') }}"></script>
    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
