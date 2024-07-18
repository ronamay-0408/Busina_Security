
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Report Vehicle</title>
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

        <div class="to_report">
            <h3>REPORT A VEHICLE</h3>
            <form action="">
                <div class="inputs">
                    <div class="input-form">
                        <label for="plate_no">Plate No.</label>
                        <input type="text" placeholder="Plate Number" id="plate_no" name="plate_no" required>
                    </div>

                    <div class="input-form">
                        <label for="location">Location</label>
                        <input type="text" placeholder="location" id="location" name="location" required>
                    </div>

                    <div class="input-form">
                        <label for="vio_type">Violation Type</label>
                        <select id="vio_type" name="vio_type" required>
                            <option value="">Select Violation Type</option>
                            <option value="speeding">Speeding</option>
                            <option value="parking">Wrong Parking</option>
                            <option value="signal_violation">Signal Violation</option>
                            <option value="reckless_driving">Reckless Driving</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <div class="input-form">
                        <label for="date">Date</label>
                        <input type="text" placeholder="" id="date"required>
                    </div>

                    <div class="input-form">
                        <label for="time">Time</label>
                        <input type="text" placeholder="" id="time" required>
                    </div>

                    <div class="input-form">
                        <label for="report_by">Reported by</label>
                        <input type="text" placeholder="" id="report_by" required>
                    </div>
                    
                    <div class="row2">
                        <div class="input-form2">
                            <label for="photo">Documentation</label>
                            
                            <div class="click_files">
                                <img src="{{ asset('images/upload 1.png') }}" alt="Upload icon" id="upload-icon">
                                <div class="file-label">
                                    <label for="files">Click to Attach Photo</label>
                                </div>
                                <input type="file" id="files" accept="image/*" style="display: none;">
                            </div>
                        </div>
                    </div>


                    <div class="save_not_btn">
                        <button type="submit" id="submit" class="done">DONE</button>
                        <a class="nav-link" href="{{ url('/index') }}">BACK</a>
                    </div>
                </div>
            </form>
        </div>
        
    </main><!-- End #main -->

    <!-- SELECTED IMAGE -->
    <script>
        document.getElementById('files').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const uploadIcon = document.getElementById('upload-icon');
                uploadIcon.src = URL.createObjectURL(file);
                uploadIcon.onload = function() {
                    URL.revokeObjectURL(uploadIcon.src); // Free memory
                }
            }
        });

        document.querySelector('.click_files').addEventListener('click', function() {
            document.getElementById('files').click();
        });
    </script>

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
    
            return `${dayName}, ${year}-${month}-${day}`;
        }
    
        function formatTime(date) {
            let hours = date.getHours();
            const minutes = date.getMinutes().toString().padStart(2, '0');
            
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
    
            return `${hours.toString().padStart(2, '0')}:${minutes} ${ampm}`;
        }
    
        function displayDateTime() {
            const now = new Date();
            const formattedDate = formatDate(now);
            const formattedTime = formatTime(now);
    
            document.querySelector('#date').value = formattedDate.split(', ')[1];
            document.querySelector('#time').value = formattedTime;
    
            document.querySelector('.date-time').textContent = `${formattedDate} ${formattedTime}`;
        }
    
        document.addEventListener('DOMContentLoaded', (event) => {
            displayDateTime();
        });
    </script>    
    <!-- DATE AND TIME -->
</body>
</html>
