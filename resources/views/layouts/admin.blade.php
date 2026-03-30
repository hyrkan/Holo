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
    <style></style>
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
        $(document).ready(function() {
            // Initial check for minimenu class on page load for desktop toggle buttons
            if ($('html').hasClass('minimenu')) {
                $('#menu-mini-button').hide();
                $('#menu-expend-button').show();
            } else {
                $('#menu-mini-button').show();
                $('#menu-expend-button').hide();
            }

            // Desktop sidebar toggle
            $('#menu-mini-button').on('click', function() {
                $('html').addClass('minimenu');
                $(this).hide();
                $('#menu-expend-button').show();
            });

            $('#menu-expend-button').on('click', function() {
                $('html').removeClass('minimenu');
                $(this).hide();
                $('#menu-mini-button').show();
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

    <!--! Confirmation Modal !-->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i id="confirmModalIcon" class="feather-help-circle fs-1 text-primary me-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p id="confirmModalMessage" class="mb-0 fs-14 text-dark fw-medium"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmModalActionBtn" class="btn btn-primary px-4">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 5000 });
            })
            toastList.forEach(toast => toast.show());

            // Generic Confirmation Modal Handler
            const confirmModal = document.getElementById('confirmationModal');
            const confirmModalActionBtn = document.getElementById('confirmModalActionBtn');
            const confirmModalMessage = document.getElementById('confirmModalMessage');
            const confirmModalIcon = document.getElementById('confirmModalIcon');
            const confirmModalLabel = document.getElementById('confirmationModalLabel');
            let currentForm = null;

            // Trigger buttons with data-confirm-message
            document.addEventListener('click', function(e) {
                const target = e.target.closest('[data-confirm-message]');
                if (target) {
                    e.preventDefault();
                    currentForm = target.closest('form');
                    const message = target.getAttribute('data-confirm-message') || 'Are you sure you want to proceed?';
                    const title = target.getAttribute('data-confirm-title') || 'Confirm Action';
                    const type = target.getAttribute('data-confirm-type') || 'primary'; // primary, danger, warning, success
                    const icon = target.getAttribute('data-confirm-icon') || 'help-circle';
                    const btnText = target.getAttribute('data-confirm-btn-text') || 'Confirm';

                    confirmModalLabel.innerText = title;
                    confirmModalMessage.innerText = message;
                    confirmModalActionBtn.innerText = btnText;
                    
                    // Set color based on type
                    confirmModalActionBtn.className = `btn btn-${type} px-4`;
                    confirmModalIcon.className = `feather-${icon} fs-1 text-${type} me-3`;

                    const modal = new bootstrap.Modal(confirmModal);
                    modal.show();
                }
            });

            confirmModalActionBtn.addEventListener('click', function() {
                if (currentForm) {
                    currentForm.submit();
                }
            });
        });
    </script>
    
    @yield('modals')
    @stack('scripts')
</body>

<!-- Mirrored from bestwpware.com/html/tf/duralux-demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 13 Dec 2025 13:02:36 GMT -->
</html>
