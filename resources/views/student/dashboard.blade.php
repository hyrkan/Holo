@extends('layouts.student')

@section('title', 'Student Dashboard || Holo Board')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="fw-bold mb-1">Welcome back, {{ Auth::guard('student')->user()->student->first_name ?? 'Student' }}!</h4>
                            <p class="text-muted mb-0">Here's what's happening with your account today.</p>
                        </div>
                        <div class="d-none d-sm-block">
                            <span class="badge bg-soft-primary text-primary fs-12 fw-medium px-3 py-2">
                                <i class="feather-calendar me-1"></i> {{ now()->format('D, M d, Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-xxl-3 col-md-6">
                            <div class="card border border-light-subtle shadow-none rounded-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-3">
                                            <i class="feather-user"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-1 text-truncate">My Program</h6>
                                    <h4 class="mb-0">{{ Auth::guard('student')->user()->student->program ?? 'N/A' }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6">
                            <div class="card border border-light-subtle shadow-none rounded-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="avatar-text avatar-md bg-soft-info text-info rounded-3">
                                            <i class="feather-layers"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-1 text-truncate">Year Level</h6>
                                    <h4 class="mb-0">{{ Auth::guard('student')->user()->student->year_level ?? 'N/A' }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6">
                            <div class="card border border-light-subtle shadow-none rounded-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="avatar-text avatar-md bg-soft-warning text-warning rounded-3">
                                            <i class="feather-hash"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-1 text-truncate">Student ID</h6>
                                    <h4 class="mb-0">{{ Auth::guard('student')->user()->student->student_number ?? 'N/A' }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6">
                            <div class="card border border-light-subtle shadow-none rounded-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="avatar-text avatar-md bg-soft-success text-success rounded-3">
                                            <i class="feather-check-circle"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-1 text-truncate">Account Status</h6>
                                    <h4 class="mb-0">Active</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <h5>Quick Actions</h5>
                        <div class="d-flex gap-3 mt-3">
                            <a href="{{ route('student.profile') }}" class="btn btn-outline-primary px-4">
                                <i class="feather-edit-2 me-2"></i> Edit Profile
                            </a>
                            <form action="{{ route('student.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger px-4">
                                    <i class="feather-log-out me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
