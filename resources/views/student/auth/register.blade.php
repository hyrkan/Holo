<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Holo Board || Student Register</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .auth-minimal-inner, .minimal-card-wrapper {
            display: flex; justify-content: center; width: 100%; max-width: 100%;
        }
        @media (min-width: 992px) {
            .auth-minimal-wrapper .card { width: 100%; max-width: 1100px; margin: 0 auto; }
        }
        /* Enhanced Upload Styling */
        .id-upload-box {
            position: relative;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        .id-upload-box:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .id-upload-box.has-file {
            border-color: #10b981;
            background: #f0fdf4;
        }
        .id-file-input {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0; cursor: pointer;
        }
        .upload-icon { font-size: 24px; color: #64748b; margin-bottom: 8px; }
        .has-file .upload-icon { color: #10b981; }
        .file-name-preview { font-size: 11px; color: #64748b; margin-top: 5px; }
    </style>
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

                            <!-- Step 1 -->
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
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fs-12 fw-bold text-muted">Year <span class="text-danger">*</span></label>
                                        <select name="year" class="form-control" required>
                                            <option value="" disabled {{ old('year') ? '' : 'selected' }}>Select Year</option>
                                            <option value="1st year" {{ old('year') == '1st year' ? 'selected' : '' }}>1st year</option>
                                            <option value="2nd year" {{ old('year') == '2nd year' ? 'selected' : '' }}>2nd year</option>
                                            <option value="3rd year" {{ old('year') == '3rd year' ? 'selected' : '' }}>3rd year</option>
                                            <option value="4th year" {{ old('year') == '4th year' ? 'selected' : '' }}>4th year</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fs-12 fw-bold text-muted">Section <span class="text-danger">*</span></label>
                                        <input type="text" name="section" class="form-control" placeholder="Section" value="{{ old('section') }}" required>
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

                            <!-- Step 2 -->
                            <div id="step-2" class="d-none">
                                <div class="row border-bottom mb-4 pb-4">
                                    <div class="col-md-6 mb-4 mb-md-0">
                                        <label class="form-label fs-12 fw-bold text-muted text-uppercase">ID Card Front <span class="text-danger">*</span></label>
                                        <div class="id-upload-box text-center" id="box-front">
                                            <i class="feather-image upload-icon"></i>
                                            <span class="d-block fs-12 fw-bold text-muted">Front Side</span>
                                            <span class="d-block fs-10 text-muted file-name-preview">Select Image</span>
                                            <input type="file" name="id_front" id="id_front" class="id-file-input" accept="image/*" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-12 fw-bold text-muted text-uppercase">ID Card Back <span class="text-danger">*</span></label>
                                        <div class="id-upload-box text-center" id="box-back">
                                            <i class="feather-image upload-icon"></i>
                                            <span class="d-block fs-12 fw-bold text-muted">Back Side</span>
                                            <span class="d-block fs-10 text-muted file-name-preview">Select Image</span>
                                            <input type="file" name="id_back" id="id_back" class="id-file-input" accept="image/*" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-12 fw-bold text-muted text-uppercase">Face Verification <span class="text-danger">*</span></label>
                                    <div class="border rounded p-3 bg-white">
                                        <div class="ratio ratio-4x3 bg-dark rounded mb-3 overflow-hidden">
                                            <video id="camera-stream" autoplay playsinline class="w-100 h-100 d-none" style="object-fit: cover;"></video>
                                            <canvas id="camera-canvas" class="d-none"></canvas>
                                            <img id="photo-preview" class="w-100 h-100 d-none" style="object-fit: cover;">
                                            <div id="camera-placeholder" class="text-white d-flex flex-column align-items-center justify-content-center h-100">
                                                <i class="feather-camera fs-1 mb-2"></i>
                                                <span>Camera Ready</span>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <input type="hidden" name="face_image" id="face_image">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-start-camera">Start Camera</button>
                                            <button type="button" class="btn btn-sm btn-primary" id="btn-capture" disabled>Capture Face</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-retake" disabled>Retake</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-light" id="btn-prev-2">Back</button>
                                    <button type="button" class="btn btn-primary" id="btn-next-2">Next</button>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div id="step-3" class="d-none">
                                <div class="mb-4">
                                    <label class="form-label fs-12 fw-bold text-muted">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="name@usa.edu.ph" value="{{ old('email') }}" required pattern="^[^@\s]+@usa\.edu\.ph$" title="Use your usa.edu.ph email address">
                                    <small class="text-muted">Only @usa.edu.ph emails are allowed.</small>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-12 fw-bold text-muted">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password"><i class="feather-eye"></i></button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-12 fw-bold text-muted">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation"><i class="feather-eye"></i></button>
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-light" id="btn-prev-3">Back</button>
                                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                                </div>
                            </div>
                        </form>
                        <div class="mt-4 text-center">
                            <p class="fs-12 text-muted">Already have an account? <a href="{{ route('student.login') }}" class="text-primary fw-bold">Login here</a></p>
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
            const stepIndex = document.getElementById('step-index'), 
                  stepTitle = document.getElementById('step-title'),
                  stepProgress = document.getElementById('step-progress'),
                  steps = [document.getElementById('step-1'), document.getElementById('step-2'), document.getElementById('step-3')];

            function setStep(num) {
                steps.forEach((s, i) => s.classList.toggle('d-none', i !== num - 1));
                stepIndex.innerText = num;
                stepTitle.innerText = num === 1 ? 'Student Information' : (num === 2 ? 'Identity Verification' : 'Account Credentials');
                stepProgress.style.width = (num * 33.33) + '%';
            }

            // ID Upload feedback
            const frontInp = document.getElementById('id_front'),
                  backInp  = document.getElementById('id_back'),
                  boxFront = document.getElementById('box-front'),
                  boxBack  = document.getElementById('box-back');

            function handleFileSelect(inp, box) {
                inp.onchange = function() {
                    const file = this.files[0];
                    if (file) {
                        box.classList.add('has-file');
                        box.querySelector('.file-name-preview').innerText = file.name;
                    }
                };
            }
            handleFileSelect(frontInp, boxFront);
            handleFileSelect(backInp, boxBack);

            document.getElementById('btn-next-1').onclick = () => isStep1Valid() ? setStep(2) : toastr.warning('Please complete Step 1');
            document.getElementById('btn-prev-2').onclick = () => setStep(1);
            document.getElementById('btn-prev-3').onclick = () => setStep(2);

            const btnNext2 = document.getElementById('btn-next-2');
            btnNext2.onclick = () => isStep2Valid() ? verifyIdWithAI() : toastr.warning('Please complete Step 2 fields');

            function verifyIdWithAI() {
                const formData = new FormData();
                formData.append('id_front', document.getElementById('id_front').files[0]);
                formData.append('student_number', document.querySelector('input[name="student_number"]').value);
                formData.append('first_name', document.querySelector('input[name="first_name"]').value);
                formData.append('last_name', document.querySelector('input[name="last_name"]').value);
                formData.append('_token', '{{ csrf_token() }}');

                btnNext2.disabled = true;
                btnNext2.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Verifying...';

                fetch('{{ route("student.verify-id") }}', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    btnNext2.disabled = false; btnNext2.innerText = 'Next';
                    if (data.success) { toastr.success('ID Verified!'); setStep(3); }
                    else { toastr.error(data.message || 'Verification failed'); }
                })
                .catch(() => { btnNext2.disabled = false; btnNext2.innerText = 'Next'; toastr.error('Service Error'); });
            }

            // Camera handling
            const video = document.getElementById('camera-stream'), canvas = document.getElementById('camera-canvas'),
                  preview = document.getElementById('photo-preview'), placeholder = document.getElementById('camera-placeholder'),
                  faceInput = document.getElementById('face_image'), btnCapture = document.getElementById('btn-capture'),
                  btnRetake = document.getElementById('btn-retake');
            let streamRef = null;

            document.getElementById('btn-start-camera').onclick = function() {
                navigator.mediaDevices.getUserMedia({ video: true }).then(s => {
                    streamRef = s; video.srcObject = s; video.classList.remove('d-none');
                    placeholder.classList.add('d-none'); btnCapture.disabled = false; this.disabled = true;
                }).catch(() => toastr.error('Camera access denied'));
            };

            btnCapture.onclick = () => {
                canvas.width = video.videoWidth; canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                const data = canvas.toDataURL('image/jpeg');
                faceInput.value = data; preview.src = data; preview.classList.remove('d-none');
                video.classList.add('d-none'); btnCapture.disabled = true; btnRetake.disabled = false;
                if (streamRef) streamRef.getTracks().forEach(t => t.stop());
                toastr.success('Captured!');
            };

            btnRetake.onclick = () => {
                faceInput.value = ''; preview.classList.add('d-none'); placeholder.classList.remove('d-none');
                btnCapture.disabled = true; btnRetake.disabled = true; document.getElementById('btn-start-camera').disabled = false;
            };

            function isStep1Valid() { 
                return ['first_name', 'last_name', 'year', 'section', 'student_number'].every(n => {
                    const el = document.querySelector(`[name="${n}"]`);
                    return el && el.value.trim();
                }); 
            }
            function isStep2Valid() { 
                return document.getElementById('id_front').files.length && document.getElementById('id_back').files.length && faceInput.value; 
            }

            document.querySelectorAll('.toggle-password').forEach(b => b.onclick = function() {
                const i = document.getElementById(this.dataset.target);
                i.type = i.type === 'password' ? 'text' : 'password';
                this.querySelector('i').className = i.type === 'password' ? 'feather-eye' : 'feather-eye-off';
            });

            toastr.options = { positionClass: 'toast-top-right', progressBar: true };
            setStep(1);
        })();
    </script>
</body>
</html>
