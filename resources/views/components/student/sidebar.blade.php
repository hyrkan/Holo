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
                <li class="nxl-item {{ Request::routeIs('student.events.joined') ? 'active' : '' }}">
                    <a href="{{ route('student.events.joined') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-check-square"></i></span>
                        <span class="nxl-mtext">Events Joined</span>
                    </a>
                </li>
                <li class="nxl-item {{ Request::routeIs('student.profile') ? 'active' : '' }}">
                    <a href="{{ route('student.profile') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user"></i></span>
                        <span class="nxl-mtext">My Profile</span>
                    </a>
                </li>

                <li class="nxl-item nxl-hasmenu {{ Request::is('lost-and-found*') || Request::is('student/lost-and-found*') ? 'active nxl-trigger' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-search"></i></span>
                        <span class="nxl-mtext">Lost & Found</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item {{ Request::routeIs('lost-and-found.index') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('lost-and-found.index') }}">Browse Items</a></li>
                        <li class="nxl-item {{ Request::routeIs('lost-and-found.create') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('lost-and-found.create') }}">Report Item</a></li>
                        <li class="nxl-item {{ Request::routeIs('student.lost-and-found.my-reports') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('student.lost-and-found.my-reports') }}">My Reports</a></li>
                    </ul>
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
