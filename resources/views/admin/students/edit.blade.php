@extends('layouts.admin')

@section('title', 'Edit Student || Holo Board')

@section('content')
<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Edit Student</h5>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-light btn-sm">Back</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.students.update', $student) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Student</label>
                            <input type="text" class="form-control" value="{{ $student->first_name }} {{ $student->last_name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $student->user->email ?? 'N/A' }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program</label>
                            <input type="text" name="program" class="form-control" value="{{ old('program', $student->program) }}" placeholder="e.g. BSIT">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year Level</label>
                            <select name="year_level" class="form-select">
                                <option value="">Select Year Level</option>
                                @php
                                    $years = ['1st Year','2nd Year','3rd Year','4th Year','N/A'];
                                @endphp
                                @foreach($years as $year)
                                <option value="{{ $year }}" {{ old('year_level', $student->year_level) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                @php
                                    $statuses = ['pending' => 'Pending', 'approved' => 'Approved', 'denied' => 'Denied', 'expired' => 'Expired', 'inactive' => 'Inactive'];
                                @endphp
                                @foreach($statuses as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $student->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
