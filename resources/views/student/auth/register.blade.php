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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .auth-minimal-inner,
        .minimal-card-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 100%;
        }
        @media (min-width: 992px) {
            .auth-minimal-wrapper .card {
                width: 100%;
                max-width: 1100px;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
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
                        <form action="{{ route('student.register.post') }}" method="POST" enctype="multipart/form-data" class="w-100 mt-4 pt-2" id="student-register-form">
                            @csrf
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="hstack gap-2">
                                        <span class="badge bg-soft-primary text-primary">Step <span id="step-index">1</span> of 3</span>
                                        <span class="fs-12 text-muted" id="step-title">Student Information</span>
                                    </div>
                                    <div class="progress flex-grow-1 ms-3" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: 33%;" id="step-progress"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="step-1">
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <label class="form-label fs-12 fw-bold text-muted">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}" required>
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label fs-12 fw-bold text-muted">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-12 fw-bold text-muted">Student Number <span class="text-danger">*</span></label>
                                    <input type="text" id="student_number" name="student_number" class="form-control" placeholder="Student Number" value="{{ old('student_number') }}" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-12 fw-bold text-muted">Student Type <span class="text-danger">*</span></label>
                                    <select id="student_type" name="student_type" class="form-control" required>
                                        <option value="regular" {{ old('student_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="guest" {{ old('student_type') == 'guest' ? 'selected' : '' }}>Guest</option>
                                    </select>
                                </div>
                                <div class="mt-4 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary" id="btn-next-1">Next</button>
                                </div>
                            </div>
                            <div id="step-2" class="d-none">
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <label class="form-label fs-12 fw-bold text-muted">ID Card Front <span class="text-danger">*</span></label>
                                        <input type="file" name="id_front" id="id_front" class="form-control" accept="image/*" required>
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label fs-12 fw-bold text-muted">ID Card Back <span class="text-danger">*</span></label>
                                        <input type="file" name="id_back" id="id_back" class="form-control" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-12 fw-bold text-muted">Capture Your Face <span class="text-danger">*</span></label>
                                    <div class="border rounded-4 p-3">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-12">
                                                <div class="ratio ratio-4x3 bg-light rounded-3 d-flex align-items-center justify-content-center overflow-hidden">
                                                    <video id="camera-stream" autoplay playsinline class="w-100 h-100 d-none"></video>
                                                    <canvas id="camera-canvas" class="w-100 h-100 d-none"></canvas>
                                                    <img id="photo-preview" class="img-fluid d-none" alt="Captured Photo">
                                                    <div id="camera-placeholder" class="text-muted d-flex flex-column align-items-center justify-content-center w-100 h-100">
                                                        <i class="feather-camera fs-1 mb-2"></i>
                                                        <span class="small">Click Start Camera to begin</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid gap-2">
                                                    <input type="hidden" name="face_image" id="face_image">
                                                    <button type="button" class="btn btn-outline-primary" id="btn-start-camera">Start Camera</button>
                                                    <button type="button" class="btn btn-primary" id="btn-capture" disabled>Capture Photo</button>
                                                    <button type="button" class="btn btn-outline-secondary" id="btn-retake" disabled>Retake</button>
                                                </div>
                                                <small class="text-muted d-block mt-2">Ensure your face is clearly visible.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-light" id="btn-prev-2">Back</button>
                                    <button type="button" class="btn btn-primary" id="btn-next-2">Next</button>
                                </div>
                            </div>
                            <div id="step-3" class="d-none">
                                <div class="mb-4">
                                    <label class="form-label fs-12 fw-bold text-muted">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="name@usa.edu.ph" value="{{ old('email') }}" required pattern="^[^@\s]+@usa\.edu\.ph$" title="Use your usa.edu.ph email address">
                                    <small class="text-muted d-block mt-1">Only usa.edu.ph email addresses are allowed.</small>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                        <label class="form-label fs-12 fw-bold text-muted w-100">Password <span class="text-danger">*</span></label>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password" aria-label="Show password">
                                            <i class="feather-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                        <label class="form-label fs-12 fw-bold text-muted w-100">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation" aria-label="Show password">
                                            <i class="feather-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-light" id="btn-prev-3">Back</button>
                                    <button type="submit" class="btn btn-lg btn-primary">Register</button>
                                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        (function () {
            var typeEl = document.getElementById('student_type');
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
            var stepIndexEl = document.getElementById('step-index');
            var stepTitleEl = document.getElementById('step-title');
            var stepProgressEl = document.getElementById('step-progress');
            var step1El = document.getElementById('step-1');
            var step2El = document.getElementById('step-2');
            var step3El = document.getElementById('step-3');
            var btnNext1 = document.getElementById('btn-next-1');
            var btnNext2 = document.getElementById('btn-next-2');
            var btnPrev2 = document.getElementById('btn-prev-2');
            var btnPrev3 = document.getElementById('btn-prev-3');
            function setStep(step) {
                step1El.classList.toggle('d-none', step !== 1);
                step2El.classList.toggle('d-none', step !== 2);
                step3El.classList.toggle('d-none', step !== 3);
                stepIndexEl.innerText = String(step);
                stepTitleEl.innerText = step === 1 ? 'Student Information' : (step === 2 ? 'Identity Verification' : 'Account Credentials');
                stepProgressEl.style.width = step === 1 ? '33%' : (step === 2 ? '66%' : '100%');
            }
            if (btnNext1) btnNext1.addEventListener('click', function () {
                if (isStep1Valid()) {
                    setStep(2);
                } else {
                    if (window.toastr) toastr.warning('Please complete all fields in Step 1');
                }
            });
            if (btnNext2) btnNext2.addEventListener('click', function () {
                if (isStep2Valid()) {
                    setStep(3);
                } else {
                    if (window.toastr) toastr.warning('Please complete all fields in Step 2');
                }
            });
            if (btnPrev2) btnPrev2.addEventListener('click', function () { setStep(1); });
            if (btnPrev3) btnPrev3.addEventListener('click', function () { setStep(2); });
            setStep(1);
            var btnStartCamera = document.getElementById('btn-start-camera');
            var btnCapture = document.getElementById('btn-capture');
            var btnRetake = document.getElementById('btn-retake');
            var videoEl = document.getElementById('camera-stream');
            var canvasEl = document.getElementById('camera-canvas');
            var photoPreviewEl = document.getElementById('photo-preview');
            var placeholderEl = document.getElementById('camera-placeholder');
            var faceInput = document.getElementById('face_image');
            var streamRef = null;
            function isStep1Valid() {
                var first = document.querySelector('input[name=\"first_name\"]');
                var last = document.querySelector('input[name=\"last_name\"]');
                var type = document.getElementById('student_type');
                var number = document.getElementById('student_number');
                if (!first || !first.value.trim()) return false;
                if (!last || !last.value.trim()) return false;
                if (!type || !type.value) return false;
                if (!number || !number.value.trim()) return false;
                return true;
            }
            function isStep2Valid() {
                var idFront = document.getElementById('id_front');
                var idBack = document.getElementById('id_back');
                var faceVal = faceInput ? faceInput.value : '';
                if (!idFront || !(idFront.files && idFront.files.length)) return false;
                if (!idBack || !(idBack.files && idBack.files.length)) return false;
                if (!faceVal) return false;
                return true;
            }
            if (window.toastr) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: '2500'
                };
            }
            function stopStream() {
                if (streamRef) {
                    streamRef.getTracks().forEach(function(t){ t.stop(); });
                    streamRef = null;
                }
            }
            function showPreview(dataUrl) {
                photoPreviewEl.src = dataUrl;
                photoPreviewEl.classList.remove('d-none');
                canvasEl.classList.add('d-none');
                videoEl.classList.add('d-none');
                placeholderEl.classList.add('d-none');
                btnCapture.disabled = true;
                btnRetake.disabled = false;
                if (window.toastr) toastr.success('Face captured successfully');
                if (isStep2Valid()) setStep(3);
            }
            if (btnStartCamera) btnStartCamera.addEventListener('click', function() {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function(stream) {
                        streamRef = stream;
                        videoEl.srcObject = stream;
                        videoEl.classList.remove('d-none');
                        canvasEl.classList.add('d-none');
                        photoPreviewEl.classList.add('d-none');
                        placeholderEl.classList.add('d-none');
                        btnCapture.disabled = false;
                        btnRetake.disabled = true;
                    })
                    .catch(function() {
                        placeholderEl.classList.remove('d-none');
                    });
            });
            if (btnCapture) btnCapture.addEventListener('click', function() {
                if (!videoEl || videoEl.classList.contains('d-none')) return;
                var w = videoEl.videoWidth;
                var h = videoEl.videoHeight;
                if (!w || !h) return;
                canvasEl.width = w;
                canvasEl.height = h;
                var ctx = canvasEl.getContext('2d');
                ctx.drawImage(videoEl, 0, 0, w, h);
                var dataUrl = canvasEl.toDataURL('image/jpeg');
                faceInput.value = dataUrl;
                showPreview(dataUrl);
                stopStream();
            });
            if (btnRetake) btnRetake.addEventListener('click', function() {
                faceInput.value = '';
                photoPreviewEl.classList.add('d-none');
                placeholderEl.classList.remove('d-none');
                btnCapture.disabled = true;
                btnRetake.disabled = true;
                stopStream();
            });
            var formEl = document.getElementById('student-register-form');
            if (formEl) formEl.addEventListener('submit', function(e) {
                if (!isStep1Valid() || !isStep2Valid()) {
                    e.preventDefault();
                    if (!isStep1Valid()) { setStep(1); }
                    else { setStep(2); }
                }
            });
        })();
    </script>
</body>

</html>
