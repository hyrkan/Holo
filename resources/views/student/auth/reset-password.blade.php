<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Holo Board || Reset Password</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
</head>
<body>
    <main class="auth-minimal-wrapper">
        <div class="auth-minimal-inner">
            <div class="minimal-card-wrapper">
                <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                    <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                        <img src="{{ asset('landing/img/logo.jpg') }}" alt="Logo" class="img-fluid" style="border-radius: 50%;">
                    </div>
                    <div class="card-body p-sm-5">
                        <h2 class="fs-20 fw-bolder mb-4">Holo Board</h2>
                        <h4 class="fs-13 fw-bold mb-2">Create a new password</h4>
                        <p class="fs-12 fw-medium text-muted">Enter your new password below to reset your account.</p>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('student.password.store') }}" method="POST" class="w-100 mt-4 pt-2">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="mb-4">
                                <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ $email }}" required>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="password" id="reset_password" name="password" class="form-control" placeholder="New Password" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="reset_password" aria-label="Show password">
                                        <i class="feather-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="password" id="reset_password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm New Password" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="reset_password_confirmation" aria-label="Show password">
                                        <i class="feather-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100">Reset Password</button>
                            </div>
                        </form>
                        <div class="mt-4 text-center">
                            <p class="fs-12 fw-medium text-muted"><a href="{{ route('student.login') }}" class="text-primary">Back to login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
    <script>
        (function () {
            var toggles = document.querySelectorAll('.toggle-password');
            toggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var targetId = this.getAttribute('data-target');
                    var input = document.getElementById(targetId);
                    if (!input) return;
                    var showing = input.getAttribute('type') === 'text';
                    input.setAttribute('type', showing ? 'password' : 'text');
                    var icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.remove(showing ? 'feather-eye-off' : 'feather-eye');
                        icon.classList.add(showing ? 'feather-eye' : 'feather-eye-off');
                    }
                });
            });
        })();
    </script>
</body>
</html>
