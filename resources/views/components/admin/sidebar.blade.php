{{-- Sidebar Navigation Component --}}
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('admin.dashboard') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ asset('landing/img/logo.jpg') }}" alt="Holo Board" class="logo logo-lg" style="height: 40px; width: auto;" />
                <span class="logo logo-lg ms-2 fw-bold fs-4 text-dark">Holo Board</span>
                <img src="{{ asset('landing/img/logo.jpg') }}" alt="Holo Board" class="logo logo-sm" style="height: 30px; width: auto;" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                @can('view dashboard')
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                @endcan
                @can('manage employees')
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.employees.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Employees</span>
                    </a>
                </li>
                @endcan
                @can('manage students')
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.students.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user"></i></span>
                        <span class="nxl-mtext">Students</span>
                    </a>
                </li>
                @endcan
                @can('manage events')
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.events.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-calendar"></i></span>
                        <span class="nxl-mtext">Events</span>
                    </a>
                </li>
                @endcan
                @role('admin')
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.speakers.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-mic"></i></span>
                        <span class="nxl-mtext">Speakers</span>
                    </a>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.announcements.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-bell"></i></span>
                        <span class="nxl-mtext">Announcements</span>
                    </a>
                </li>
                @endrole
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.lost-and-found.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-search"></i></span>
                        <span class="nxl-mtext">Lost & Found</span>
                    </a>
                </li>
                @role('admin')
                <li class="nxl-item nxl-caption">
                    <label>Access Control</label>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.roles.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shield"></i></span>
                        <span class="nxl-mtext">Roles</span>
                    </a>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('admin.permissions.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-key"></i></span>
                        <span class="nxl-mtext">Permissions</span>
                    </a>
                </li>
                @endrole

            </ul>
        </div>
    </div>
</nav>
