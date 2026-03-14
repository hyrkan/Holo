{{-- Header Component --}}
@php
    $userName = Auth::user()->name ?? 'Admin User';
    $nameParts = explode(' ', $userName);
    $initials = '';
    if (count($nameParts) > 1) {
        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
    } else {
        $initials = strtoupper(substr($userName, 0, 2));
    }
@endphp

<header class="nxl-header">
    <div class="header-wrapper">
        <!--! [Start] Header Left !-->
        <div class="header-left d-flex align-items-center gap-4"></div>
        <!--! [End] Header Left !-->
        
        <!--! [Start] Header Right !-->
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">
                {{-- Search, Notifications, Profile dropdown etc --}}

                
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
                                    <h6 class="text-dark mb-0">{{ Auth::user()->name ?? 'Admin User' }}</h6>
                                    <span class="fs-12 fw-medium text-muted">{{ Auth::user()->email ?? 'admin@example.com' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">
                            <i class="feather-user"></i>
                            <span>Profile Details</span>
                        </a>
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">
                            <i class="feather-settings"></i>
                            <span>Account Settings</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="feather-log-out"></i>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--! [End] Header Right !-->
    </div>
</header>
