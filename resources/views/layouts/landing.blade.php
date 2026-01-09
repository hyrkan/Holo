<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Holo Board - Student Management System</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('landing/img') }}/favicon.ico">

    <!-- CSS here -->
    <link href="https://fonts.googleapis.com/css?family=Karla:400,400i,700,700i&amp;display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="{{ asset('landing/css') }}/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('landing/css') }}/animate.min.css">
    <link rel="stylesheet" href="{{ asset('landing/css') }}/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('landing/fontawesome') }}/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('landing/css') }}/dripicons.css">
    <link rel="stylesheet" href="{{ asset('landing/css') }}/slick.css">
    <link rel="stylesheet" href="{{ asset('landing/css') }}/default.css">
    <link rel="stylesheet" href="{{ asset('landing/css') }}/style.css">
    <link rel="stylesheet" href="{{ asset('landing/css') }}/responsive.css">
    <style>
        #parallax::before {
            background: rgba(71, 0, 200, 0.7) !important;
            opacity: 1 !important;
        }
        
        @if(Request::is('/'))
        .header-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
        }
        @else
        .header-area {
            background: #002691; /* Matches sticky menu color */
        }
        .second-menu {
            margin-bottom: 0 !important;
        }
        .main-menu ul li a {
            padding: 25px 0 !important;
        }
        @endif
    </style>
    @stack('css')
</head>
<body>
    <!-- header -->
    <header id="home" class="header-area">            
        <div id="header-sticky" class="menu-area">
            <div class="container">
                <div class="second-menu">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3">
                            <div class="logo">
                                <a href="{{ url('/') }}"><img src="{{ asset('landing/img/logo.jpg') }}" alt="Holo Board" style="max-height: 100px; width: auto;"></a>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9">
                            <div class="responsive"><i class="icon dripicons-align-right"></i></div>
                            <div class="main-menu text-right">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li><a href="{{ url('/') }}">Home</a></li>

                                        <li><a href="{{ route('lost-and-found.index') }}">Lost and Found</a></li>
                                        @if(Auth::guard('student')->check())
                                            <li><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                        @elseif(Auth::guard('web')->check())
                                            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        @else
                                            <li><a href="{{ route('student.login') }}">Login</a></li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->

    <main>
        @yield('content')
    </main>

    <!-- footer -->
    <footer class="footer-bg footer-p" style="background-image:url({{ asset('landing/img') }}/footer_bg_img.jpg);background-size: cover;">
        <div class="footer-top">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-xl-12 col-lg-12 col-sm-12 text-center">
                        <div class="footer-widget pt-120 mb-30">
                            <div class="logo mb-35">
                                <a href="{{ url('/') }}"><h2 class="text-white mb-0">Holo Board</h2></a>
                            </div>
                            <div class="footer-text mb-20">
                                <p>The issue with any content strategy is time. Time to sit down and think about what kind of content should be created, time to stop and write, or record, edit and publish, and time to engage with your audience to promote the content you created.</p>
                            </div>
                            <div class="footer-social">                                    
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-google-plus-g"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-wrap pb-120">
            <div class="container">
                <div class="row">
                    <div class="col-12">						
                        <div class="copyright-text text-center">
                            <div class="footer-link">
                                <ul>
                                    <li><a href="#">About</a></li>
                                    <li><a href="#">Blog</a></li>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="#">Tickets</a></li>
                                    <li><a href="#">Venue</a></li>
                                </ul>
                            </div>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer-end -->

    <!-- JS here -->
    <script src="{{ asset('landing/js') }}/vendor/modernizr-3.5.0.min.js"></script>
    <script src="{{ asset('landing/js') }}/vendor/jquery-1.12.4.min.js"></script>
    <script src="{{ asset('landing/js') }}/popper.min.js"></script>
    <script src="{{ asset('landing/js') }}/bootstrap.min.js"></script>
    <script src="{{ asset('landing/js') }}/one-page-nav-min.js"></script>
    <script src="{{ asset('landing/js') }}/slick.min.js"></script>
    <script src="{{ asset('landing/js') }}/ajax-form.js"></script>
    <script src="{{ asset('landing/js') }}/paroller.js"></script>
    <script src="{{ asset('landing/js') }}/wow.min.js"></script>
    <script src="{{ asset('landing/js') }}/parallax.min.js"></script>
    <script src="{{ asset('landing/js') }}/jquery.waypoints.min.js"></script>
    <script src="{{ asset('landing/js') }}/jquery.countdown.min.js"></script>
    <script src="{{ asset('landing/js') }}/jquery.counterup.min.js"></script>
    <script src="{{ asset('landing/js') }}/jquery.scrollUp.min.js"></script>
    <script src="{{ asset('landing/js') }}/jquery.magnific-popup.min.js"></script>
    <script src="{{ asset('landing/js') }}/element-in-view.js"></script>
    <script src="{{ asset('landing/js') }}/isotope.pkgd.min.js"></script>
    <script src="{{ asset('landing/js') }}/imagesloaded.pkgd.min.js"></script>
    <script src="{{ asset('landing/js') }}/main.js"></script>
    @stack('js')
</body>
</html>
