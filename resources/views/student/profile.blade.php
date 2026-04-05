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
</style>
@endpush

@endsection
