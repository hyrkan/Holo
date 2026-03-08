@extends('layouts.student')

@section('title', 'My Profile || Holo Board')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-tabs-custom" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="true">
                                <i class="feather-user me-2"></i>General Info
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="qr-tab" data-bs-toggle="tab" data-bs-target="#qr-tab-pane" type="button" role="tab" aria-controls="qr-tab-pane" aria-selected="false">
                                <i class="feather-grid me-2"></i>My QR Code
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-tab-pane" type="button" role="tab" aria-controls="security-tab-pane" aria-selected="false">
                                <i class="feather-lock me-2"></i>Security
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content p-4" id="myTabContent">
                        <!-- General Info Tab -->
                        <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                            <h5 class="mb-4">Personal Information</h5>
                            <form action="{{ route('student.profile.update') }}" method="POST">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" required value="{{ old('first_name', $student->first_name) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $student->middle_name) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" required value="{{ old('last_name', $student->last_name) }}">
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Student Number</label>
                                        <input type="text" class="form-control bg-light" value="{{ $student->student_number }}" readonly>
                                        <small class="text-muted">Student number cannot be changed.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                                        <small class="text-muted">Email address cannot be changed for students.</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Program/Course</label>
                                        <input type="text" class="form-control bg-light" value="{{ $student->program ?? 'Not Assigned Yet' }}" readonly>
                                        <small class="text-muted">Wait for admin to assign your program.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Year Level</label>
                                        <select name="year_level" class="form-control" required>
                                            @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year', 'Irregular'] as $level)
                                                <option value="{{ $level }}" {{ old('year_level', $student->year_level) == $level ? 'selected' : '' }}>{{ $level }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        <!-- My QR Code Tab -->
                        <div class="tab-pane fade" id="qr-tab-pane" role="tabpanel" aria-labelledby="qr-tab" tabindex="0">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="text-center p-4">
                                        <h4 class="fw-bold mb-3">Attendance QR Code</h4>
                                        <p class="text-muted mb-4">This unique QR code is used to mark your attendance at events. Simply present this to the event coordinator for scanning.</p>
                                        
                                        <div class="d-inline-block p-4 bg-white border rounded-4 shadow-sm mb-4" id="qr-printable">
                                            <div class="mb-3">
                                                <h5 class="mb-1 text-dark">{{ $student->first_name }} {{ $student->last_name }}</h5>
                                                <p class="small text-muted mb-0">{{ $student->student_number }}</p>
                                            </div>
                                            <div class="qr-responsive">{!! QrCode::size(250)->generate($student->uuid) !!}</div>
                                            <div class="mt-3">
                                                <p class="small fw-bold text-primary mb-0">HOLO BOARD STUDENT ID</p>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-center gap-3 mt-2">
                                            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                                                <i class="feather-printer me-2"></i> Print QR
                                            </button>
                                            <button type="button" class="btn btn-primary" onclick="downloadQR()">
                                                <i class="feather-download me-2"></i> Save Image
                                            </button>
                                        </div>
                                        
                                        <div class="mt-4 alert alert-soft-info text-start">
                                            <h6><i class="feather-info me-2"></i> Usage Instructions:</h6>
                                            <ul class="mb-0 small">
                                                <li>Ensure your screen brightness is turned up when scanning.</li>
                                                <li>Do not share your QR code with others.</li>
                                                <li>If you lose access to this account, your QR code will be invalidated.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security-tab-pane" role="tabpanel" aria-labelledby="security-tab" tabindex="0">
                            <h5 class="mb-4">Update Password</h5>
                            <form action="{{ route('student.password.update') }}" method="POST">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" name="password_confirmation" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-light p-4 rounded-4 border border-dashed border-primary">
                                            <h6>Password Requirements:</h6>
                                            <ul class="small text-muted mb-0">
                                                <li>Minimum 8 characters long</li>
                                                <li>Include at least one uppercase letter</li>
                                                <li>Include at least one lowercase letter</li>
                                                <li>Include at least one number</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-warning">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .nav-tabs-custom {
        border-bottom: 2px solid #f1f1f1;
        background: #f8f9fa;
        padding: 0 1rem;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        padding: 1.25rem 1.5rem;
        color: #6c757d;
        font-weight: 500;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s;
    }
    .nav-tabs-custom .nav-link:hover {
        color: var(--bs-primary);
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--bs-primary);
        background: transparent;
        border-bottom: 2px solid var(--bs-primary);
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        #qr-printable, #qr-printable * {
            visibility: visible;
        }
        #qr-printable {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle tab activation from URL
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab === 'qr') {
            const qrTab = document.querySelector('#qr-tab');
            if (qrTab) {
                const bootstrapTab = new bootstrap.Tab(qrTab);
                bootstrapTab.show();
            }
        }
    });

    function downloadQR() {
        const element = document.getElementById('qr-printable');
        html2canvas(element).then(canvas => {
            const link = document.createElement('a');
            link.download = 'my-attendance-qr.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }
</script>
@endpush

@push('styles')
<style>
    #qr-printable {
        max-width: 100%;
        overflow: hidden;
    }
    #qr-printable .qr-responsive svg {
        display: block;
        width: 100%;
        height: auto;
        max-width: 320px;
        margin: 0 auto;
    }
    @media (max-width: 480px) {
        #qr-printable {
            padding: 1rem !important;
            border-radius: 12px;
        }
        #qr-printable .qr-responsive svg {
            max-width: 85vw;
        }
    }
</style>
@endpush
@endsection
