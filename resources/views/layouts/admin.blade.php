<!DOCTYPE html>
<html lang="zxx">

<!-- Mirrored from bestwpware.com/html/tf/duralux-demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 13 Dec 2025 13:02:36 GMT -->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="WRAPCODERS" />
    <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
    <!--! BEGIN: Apps Title-->
    <title>@yield('title', 'HoloBoard || Dashboard')</title>
    <!--! END:  Apps Title-->
    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('landing/img/favicon.ico') }}" />
    <!--! END: Favicon-->
    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <!--! END: Bootstrap CSS-->
    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/daterangepicker.min.css') }}" />
    <!--! END: Vendors CSS-->
    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />
    <!--! END: Custom CSS-->
    <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
    <!--! WARNING: Respond.js doesn't work if you view the page via file: !-->
    <!--[if lt IE 9]>
			<script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
    
    @stack('styles')
    <style>
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
      html.sidebar-minified .nxl-navigation {
        width: 80px;
      }
      html.sidebar-minified .nxl-navigation .nxl-mtext,
      html.sidebar-minified .nxl-navigation .nxl-arrow,
      html.sidebar-minified .nxl-navigation .nxl-submenu {
        display: none;
      }
    </style>
</head>

<body>
    <!--! ================================================================ !-->
    <!--! [Start] Navigation Menu !-->
    <!--! ================================================================ !-->
    @include('components.admin.sidebar')
    <!--! ================================================================ !-->
    <!--! [End]  Navigation Menu !-->
    <!--! ================================================================ !-->
    
    <!--! ================================================================ !-->
    <!--! [Start] Header !-->
    <!--! ================================================================ !-->
    @include('components.admin.header')
    <div id="mobileNavOverlay" class="mobile-nav-overlay"></div>
    <!--! ================================================================ !-->
    <!--! [End] Header !-->
    <!--! ================================================================ !-->
    
    <!--! ================================================================ !-->
    <!--! [Start] Main Content !-->
    <!--! ================================================================ !-->
    <main class="nxl-container d-flex flex-column justify-content-between">
        <div class="nxl-content">
            @yield('content')
        </div>
        
        <!--! [ Footer ] start !-->
        @include('components.admin.footer')
        <!--! [ Footer ] end !-->
    </main>
    <!--! ================================================================ !-->
    <!--! [End] Main Content !-->
    <!--! ================================================================ !-->
    
    <!--! ================================================================ !-->
    <!--! Footer Script !-->
    <!--! ================================================================ !-->
    <!--! BEGIN: Vendors JS !-->
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme-customizer-init.min.js') }}"></script>
    <!--! END: Apps Init !-->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var html = document.documentElement;
        var miniBtn = document.getElementById('menu-mini-button');
        var expandBtn = document.getElementById('menu-expend-button');
        var burger = document.getElementById('mobile-collapse');
        var overlay = document.getElementById('mobileNavOverlay');
        var saved = localStorage.getItem('holoboard-admin-sidebar');
        if (saved === 'min') {
          html.classList.add('sidebar-minified');
          if (miniBtn) miniBtn.style.display = 'none';
          if (expandBtn) expandBtn.style.display = 'inline-block';
        } else {
          html.classList.remove('sidebar-minified');
          if (expandBtn) expandBtn.style.display = 'none';
          if (miniBtn) miniBtn.style.display = 'inline-block';
        }
        if (miniBtn) {
          miniBtn.addEventListener('click', function() {
            html.classList.add('sidebar-minified');
            miniBtn.style.display = 'none';
            if (expandBtn) expandBtn.style.display = 'inline-block';
            localStorage.setItem('holoboard-admin-sidebar', 'min');
          });
        }
        if (expandBtn) {
          expandBtn.addEventListener('click', function() {
            html.classList.remove('sidebar-minified');
            expandBtn.style.display = 'none';
            if (miniBtn) miniBtn.style.display = 'inline-block';
            localStorage.setItem('holoboard-admin-sidebar', 'full');
          });
        }
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
    
    <!--! Toast Container !-->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000;">
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
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="feather-alert-octagon me-2"></i> Please check the form for errors.
                    </div>
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
        });
    </script>
    
    @yield('modals')
    @stack('scripts')
</body>

<!-- Mirrored from bestwpware.com/html/tf/duralux-demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 13 Dec 2025 13:02:36 GMT -->
</html>
