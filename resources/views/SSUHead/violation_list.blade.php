<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Violations</title>
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

    <style>
        /* Custom CSS for pagination */
        .pagination {
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 20px 0;
        }

        .page-item {
            margin: 0 2px;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #007bff;
            cursor: pointer;
        }

        .page-item.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .page-item.disabled {
            color: #ccc;
            cursor: not-allowed;
            pointer-events: none;
        }

        .page-item:hover {
            background-color: #f0f0f0;
        }

        /* Custom CSS for per-page dropdown */
        .per-page-form {
            display: flex;
            align-items: center;
            margin: 0 0 10px 0;
        }

        .per-page-form label {
            margin-right: 10px;
            font-size: 14px;
            color: #333;
        }

        .per-page-form select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .per-page-form select:focus {
            border-color: #007bff;
            outline: none;
        }


        .modal-details {
            padding: 10px;
        }
        .modal-details p {
            margin: 0 0 5px 0;
        }
        .modal-details h3 {
            /* margin: 0; */
            text-align: center;
        }
        .vio-details p {
            margin: 0;
            padding: 0 0 5px 0;
        }

        @media (max-width: 600px) {
            .page-item {
                font-size: 12px;
            }
            
            .content {
                flex-wrap: wrap;
            }
            .dropdown-month {
                flex-wrap: wrap;
            }
            .filter-container {
                flex-wrap: wrap;
            }
            .filter-item {
                gap: 10px;
            }
            .filter-item input {
                width: 100%;
            }

            .modal-content {
                width: 90%;
            }
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
                <img src="{{ asset('images/BUsina logo (1) 1.png') }}" alt="">
            </div>
            <div class="head_info">
                @if(Session::has('user'))
                    <h2>{{ Session::get('user')['fname'] }} {{ Session::get('user')['lname'] }}</h2>
                    <h3>{{ Session::get('user')['email'] }}</h3>
                @endif
            </div>
        </div>

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('head_index') }}">
                    <img src="images/Dashboard Layout.png" alt="">
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link hove" href="{{ route('violation_list') }}">
                    <img src="images/Foul.png" alt="">
                    <span>Violations</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('unauthorized_list') }}">
                    <img src="images/Qr Code.png" alt="">
                    <span>Unauthorized Vehicles</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('ssu_personnel') }}">
                    <img src="images/Foul.png" alt="">
                    <span>SSU Personnels</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('head_guidelines') }}">
                    <img src="images/Driving Guidelines.png" alt="">
                    <span>Guidelines</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('head_account') }}">
                    <img src="images/Account.png" alt="">
                    <span>My Account</span>
                </a>
            </li>
            <li class="nav-item last head-last">
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
        <div class="date-time"></div>
        <div class="main-title">
            <h3 class="per-title">VIOLATIONS</h3>
        </div>

        <div class="content">
            <div class="dropdown-month">
                <label>FILTERING FIELDS</label>

                <div class="filter-container">
                    <div class="filter-item">
                        <label>YEAR</label>
                        <input class="filter-year" type="text" id="year-filter" placeholder="Select Year" readonly>
                        <button class="btn btn-secondary btn-clear" id="clear-year">Clear</button>
                    </div>
                    <div class="filter-item">
                        <label>MONTH</label>
                        <input class="filter-month" type="text" id="month-filter" placeholder="Select Month" readonly>
                        <button class="btn btn-secondary btn-clear" id="clear-month">Clear</button>
                    </div>
                    <div class="filter-item">
                        <label>DAY</label>
                        <input class="filter-day" type="text" id="day-filter" placeholder="Select Day" readonly>
                        <button class="btn btn-secondary btn-clear" id="clear-day">Clear</button>
                    </div>
                </div>
            </div>
            <div class="export-tbn">
                <button class="export-child btn btn-primary">EXPORT</button>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search..">
        </div>

        <div class="head_view_violation_table">
            <!-- Dropdown to select number of rows per page -->
            <form method="GET" action="{{ url()->current() }}" class="per-page-form">
                <label for="per_page">Show:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()">
                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                    <option value="250" {{ request('per_page', 25) == 250 ? 'selected' : '' }}>250</option>
                    <option value="500" {{ request('per_page', 25) == 500 ? 'selected' : '' }}>500</option>
                    <option value="1000" {{ request('per_page', 25) == 1000 ? 'selected' : '' }}>1000</option>
                </select>
            </form>

            <!-- Table -->
            <table id="violationTable">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Plate No</th>
                        <!-- <th>Violation Type</th> -->
                        <!-- <th>Location</th> -->
                        <!-- <th>Reported By</th> -->
                        <!-- <th>Remarks</th> -->
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($violations as $violation)
                        <tr>
                            <td>{{ $violation->created_at->format('F j, Y, g:i a') }}</td>
                            <td>{{ $violation->plate_no }}</td>
                            <!-- <td>{{ $violation->violationType->violation_name }}</td> -->
                            <!-- <td>{{ $violation->location }}</td> -->
                            <!-- <td>{{ $violation->reportedBy->fullName }}</td> -->
                            <!-- <td>{{ $violation->remarks }}</td> -->
                            <td>
                                @if($violation->proof_image)
                                    <button 
                                        class="view-btn" 
                                        data-image="{{ asset('storage/' . $violation->proof_image) }}" 
                                        data-date="{{ $violation->created_at->format('F j, Y, g:i a') }}" 
                                        data-plate="{{ $violation->plate_no }}" 
                                        data-violation="{{ $violation->violationType->violation_name }}" 
                                        data-location="{{ $violation->location }}" 
                                        data-reported="{{ $violation->reportedBy->fullName }}" 
                                        data-remarks="{{ $violation->remarks }}"
                                    >View</button>
                                @else
                                    No Image
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($violations->onFirstPage())
                    <span class="page-item disabled">« Previous</span>
                @else
                    <a class="page-item" href="{{ $violations->previousPageUrl() }}&per_page={{ request('per_page', 25) }}">« Previous</a>
                @endif

                {{-- Pagination Links --}}
                @foreach ($violations->getUrlRange(1, $violations->lastPage()) as $page => $url)
                    @if ($page == $violations->currentPage())
                        <span class="page-item active">{{ $page }}</span>
                    @else
                        <a class="page-item" href="{{ $url }}&per_page={{ request('per_page', 25) }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($violations->hasMorePages())
                    <a class="page-item" href="{{ $violations->nextPageUrl() }}&per_page={{ request('per_page', 25) }}">Next »</a>
                @else
                    <span class="page-item disabled">Next »</span>
                @endif
            </div>
        </div>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-details">
                    <h3>VIOLATION DETAILS</h3>
                    <div class="vio-details">
                        <p><strong>Date & Time:</strong> <span id="modal-date"></span></p>
                        <p><strong>Plate No:</strong> <span id="modal-plate"></span></p>
                        <p><strong>Violation Type:</strong> <span id="modal-violation"></span></p>
                        <p><strong>Location:</strong> <span id="modal-location"></span></p>
                        <p><strong>Reported By:</strong> <span id="modal-reported"></span></p>
                        <p><strong>Remarks:</strong> <span id="modal-remarks"></span></p>
                    </div>
                    <p><strong>Proof Image :</strong></p>
                    <img id="modal-image" src="" alt="Proof Image" style="width: 100%;"/>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    <!-- MODAL AND SEARCH JS -->
    <script src="{{ asset('js/head_violation_modal.js') }}"></script>

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
