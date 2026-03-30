{{-- Student Header Component --}}
@php
    $student = Auth::guard('student')->user()->student;
    $initials = strtoupper(substr($student->first_name ?? 'S', 0, 1) . substr($student->last_name ?? 'T', 0, 1));
@endphp

<header class="nxl-header">
    <div class="header-wrapper">
        <div class="header-left d-flex align-items-center gap-4">
            <!--! [Start] nxl-head-mobile-toggler !-->
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler d-lg-none" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <!--! [Start] nxl-head-mobile-toggler !-->
            <!--! [Start] nxl-navigation-toggle !-->
            <div class="nxl-navigation-toggle d-none d-lg-block">
                <a href="javascript:void(0);" class="nxl-btn nxl-button-icon-white text-bg-white" id="menu-mini-button">
                    <i class="feather-align-left"></i>
                </a>
                <a href="javascript:void(0);" class="nxl-btn nxl-button-icon-white text-bg-white" id="menu-expend-button">
                    <i class="feather-align-right"></i>
                </a>
            </div>
            <!--! [End] nxl-navigation-toggle !-->
        </div>
        
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <div class="avatar-text user-avtar me-0 bg-soft-primary text-primary d-flex align-items-center justify-content-center fw-bold">
                            {{ $initials }}
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <div class="avatar-text user-avtar bg-soft-primary text-primary d-flex align-items-center justify-content-center fw-bold me-3">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <h6 class="text-dark mb-0">{{ Auth::guard('student')->user()->student->first_name ?? 'Student' }}</h6>
                                    <span class="fs-12 fw-medium text-muted">{{ Auth::guard('student')->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('student.profile') }}" class="dropdown-item">
                            <i class="feather-user"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('student.profile') }}?tab=qr" class="dropdown-item">
                            <i class="feather-grid"></i>
                            <span>My QR</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('student.logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();">
                            <i class="feather-log-out"></i>
                            <span>Logout</span>
                        </a>
                        <form id="header-logout-form" action="{{ route('student.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
