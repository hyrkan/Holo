{{-- Student Sidebar Navigation Component --}}
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('student.dashboard') }}" class="b-brand">
                <img src="{{ asset('landing/img/logo.jpg') }}" alt="Holo Board" class="logo logo-lg" style="height: 40px; width: auto;" />
                <span class="logo logo-lg ms-2 fw-bold fs-4 text-dark">Holo Board</span>
                <img src="{{ asset('landing/img/logo.jpg') }}" alt="Holo Board" class="logo logo-sm" style="height: 30px; width: auto;" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Main Menu</label>
                </li>
                <li class="nxl-item {{ Request::routeIs('student.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('student.dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ url('/') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-home"></i></span>
                        <span class="nxl-mtext">Browse Events</span>
                    </a>
                </li>
                <li class="nxl-item {{ Request::routeIs('student.profile') ? 'active' : '' }}">
                    <a href="{{ route('student.profile') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user"></i></span>
                        <span class="nxl-mtext">My Profile</span>
                    </a>
                </li>
                
                <li class="nxl-item nxl-caption">
                    <label>Account</label>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('student.logout') }}" class="nxl-link" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                        <span class="nxl-micon"><i class="feather-log-out"></i></span>
                        <span class="nxl-mtext">Logout</span>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('student.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
