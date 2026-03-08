<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Student Dashboard - Holo Board" />
    <meta name="keyword" content="holoboard, dashboard, student" />
    <meta name="author" content="Holo Board" />
    <title>@yield('title', 'Student Dashboard || Holo Board')</title>
    
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/fontawesome') }}/css/all.min.css">
    
    @stack('styles')
    <style>
        .custom-tabs-pill .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            background: #f8fafc;
            margin-right: 10px;
        }
        .custom-tabs-pill .nav-link:hover {
            background: #f1f5f9;
            color: #4700c8;
        }
        .custom-tabs-pill .nav-link.active {
            background: #4700c8 !important;
            color: #fff !important;
            box-shadow: 0 4px 6px -1px rgba(71, 0, 200, 0.2);
        }
        @media (max-width: 767.98px) {
            .nxl-navigation {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100vh;
                z-index: 1050;
                transition: left .3s ease;
                background: #ffffff;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
            }
            .mobile-nav-open .nxl-navigation {
                left: 0;
            }
            .mobile-nav-overlay {
                display: none;
            }
            .mobile-nav-open .mobile-nav-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.35);
                z-index: 1049;
            }
        }
    </style>
</head>

<body>
    <!--! Sidebar !-->
    @include('components.student.sidebar')
    
    <!--! Header !-->
    @include('components.student.header')
    
    <div id="mobileNavOverlay" class="mobile-nav-overlay"></div>
    
    <main class="nxl-container d-flex flex-column justify-content-between">
        <div class="nxl-content">
            @yield('content')
        </div>
        
        <!--! [ Footer ] start !-->
        @include('components.admin.footer')
        <!--! [ Footer ] end !-->
    </main>
    
    <!--! Footer Scripts !-->
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
    
    <!--! Toast Container !-->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        @if(session('success'))
            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="feather-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="feather-alert-triangle me-2"></i> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex" style="flex-direction: column;">
                    @foreach ($errors->all() as $error)
                        <div class="toast-body">
                            <i class="feather-alert-octagon me-2"></i> {{ $error }}
                        </div>
                    @endforeach
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 5000 });
            })
            toastList.forEach(toast => toast.show());
            
            var burger = document.getElementById('mobile-collapse');
            var overlay = document.getElementById('mobileNavOverlay');
            function toggleMobileNav() {
                document.body.classList.toggle('mobile-nav-open');
            }
            if (burger) {
                burger.addEventListener('click', toggleMobileNav);
            }
            if (overlay) {
                overlay.addEventListener('click', function() {
                    document.body.classList.remove('mobile-nav-open');
                });
            }
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    document.body.classList.remove('mobile-nav-open');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
