<!-- // resources/views/SSUHead/partials/sidebar.blade.php -->

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('head_index') ? 'active' : '' }}" href="{{ route('head_index') }}">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('violation_list') ? 'active' : '' }}" href="{{ route('violation_list') }}">
            <i class="bi bi-sign-no-parking-fill"></i>
            <span>Violations</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('unauthorized_list') ? 'active' : '' }}" href="{{ route('unauthorized_list') }}">
            <i class="bi bi-car-front-fill"></i>
            <span>Unauthorized Vehicles</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('head_userlogs') ? 'active' : '' }}" href="{{ route('head_userlogs') }}">
            <i class="bi bi-truck-front-fill"></i>
            <span>Vehicle Owner Logs</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('ssu_personnel') ? 'active' : '' }}" href="{{ route('ssu_personnel') }}">
            <i class="bi bi-people-fill"></i>
            <span>SSU Personnels</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('head_guidelines') ? 'active' : '' }}" href="{{ route('head_guidelines') }}">
            <i class="bi bi-journal-richtext"></i>
            <span>Guidelines</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('head_account') ? 'active' : '' }}" href="{{ route('head_account') }}">
            <i class="bi bi-person-fill"></i>
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
