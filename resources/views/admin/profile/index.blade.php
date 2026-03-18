@extends('layouts.admin')

@section('title', 'Profile - HoloBoard')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Profile</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Profile</li>
        </ul>
    </div>
</div>
<!-- [ page-header ] end -->

<!-- [ Main Content ] start -->
<div class="main-content">
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-center">
                        @php
                            $fullName = $user->employee ? trim(($user->employee->first_name ?? '') . ' ' . ($user->employee->last_name ?? '')) : 'Admin User';
                            $nameParts = explode(' ', $fullName);
                            $initials = '';
                            if (count($nameParts) > 1) {
                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($fullName, 0, 2));
                            }
                        @endphp
                        <div class="avatar-text bg-soft-primary text-primary d-inline-flex align-items-center justify-content-center fw-bold mb-3" style="width: 100px; height: 100px; font-size: 32px; border-radius: 50%;">
                            {{ $initials }}
                        </div>
                        <h4 class="mb-1">{{ $fullName }}</h4>
                        <p class="text-muted fs-12 mb-3">{{ $user->email }}</p>
                        <div class="badge bg-soft-primary text-primary text-uppercase">
                            {{ $user->getRoleNames()->first() ?? 'User' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->employee->first_name ?? '') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->employee->last_name ?? '') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->employee->phone ?? '') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required>{{ old('address', $user->employee->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required id="current_password">
                                    <button class="btn btn-outline-light border text-muted toggle-password" type="button" data-target="current_password">
                                        <i class="feather-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required id="password">
                                    <button class="btn btn-outline-light border text-muted toggle-password" type="button" data-target="password">
                                        <i class="feather-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_confirmation" required id="password_confirmation">
                                    <button class="btn btn-outline-light border text-muted toggle-password" type="button" data-target="password_confirmation">
                                        <i class="feather-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection
@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('feather-eye', 'feather-eye-off');
            } else {
                input.type = 'password';
                icon.classList.replace('feather-eye-off', 'feather-eye');
            }
        });
    });
</script>
@endpush
