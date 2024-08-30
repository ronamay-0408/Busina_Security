<!-- // resources/views/MainPartials/ssu_sidebar.blade.php -->

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('view_reports') ? 'active' : '' }}" href="{{ route('view_reports') }}">
            <i class="bi bi-sign-no-parking-fill"></i>
            <span>My Reports</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('scanned_qr') ? 'active' : '' }}" href="{{ route('scanned_qr') }}">
            <i class="bi bi-qr-code-scan"></i>
            <span>Scanned QR Code</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('guidelines') ? 'active' : '' }}" href="{{ route('guidelines') }}">
            <i class="bi bi-journal-richtext"></i>
            <span>Guidelines</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('myaccount') ? 'active' : '' }}" href="{{ route('myaccount') }}">
            <i class="bi bi-person-fill"></i>
            <span>My Account</span>
        </a>
    </li>

    <li class="nav-item sub_last">
        <a class="nav-link {{ request()->routeIs('gate_scanner') ? 'active' : '' }}" href="{{ route('gate_scanner') }}">
            <i class="bi bi-qr-code"></i>
            <span>Gate Scanner</span>
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
