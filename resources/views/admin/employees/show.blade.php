@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Employee Details</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-primary">
                            <i class="feather-edit-3 me-2"></i> Edit Employee
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">First Name</label>
                            <div class="fs-16">{{ $employee->first_name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">Last Name</label>
                            <div class="fs-16">{{ $employee->last_name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">Email</label>
                            <div class="fs-16">{{ $employee->user->email }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">Phone</label>
                            <div class="fs-16">{{ $employee->phone }}</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold text-muted">Address</label>
                            <div class="fs-16">{{ $employee->address }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">Joined At</label>
                            <div class="fs-16">{{ $employee->created_at->format('d M, Y h:i A') }}</div>
                        </div>
                    </div>
                    <div class="text-end">
                         <a href="{{ route('admin.employees.index') }}" class="btn btn-light">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
