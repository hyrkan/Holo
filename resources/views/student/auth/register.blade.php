<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="WRAPCODERS">
    <title>Holo Board || Student Register</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
    <!--[if lt IE 9]>
            <script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
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
                        <h4 class="fs-13 fw-bold mb-2">Create your Student Account</h4>
                        <p class="fs-12 fw-medium text-muted">Fill in the details below to register.</p>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('student.register.post') }}" method="POST" class="w-100 mt-4 pt-2">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <input type="text" name="student_number" class="form-control" placeholder="Student Number" value="{{ old('student_number') }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fs-12 fw-bold text-muted">Student Type</label>
                                <select name="student_type" class="form-control" required>
                                    <option value="regular" {{ old('student_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="guest" {{ old('student_type') == 'guest' ? 'selected' : '' }}>Guest</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-4">
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="mb-4">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                            </div>
                            <div class="mt-5">
                                <button type="submit" class="btn btn-lg btn-primary w-100">Register</button>
                            </div>
                        </form>
                        <div class="mt-4 text-center">
                            <p class="fs-12 fw-medium text-muted">Already have an account? <a href="{{ route('student.login') }}" class="text-primary">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
</body>

</html>
