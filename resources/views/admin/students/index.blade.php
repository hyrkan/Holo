@extends('layouts.admin')

@section('title', 'Students List || Holo Board')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Students</h5>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Name</th>
                                    <th>Student Number</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                    <th>Joined At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <span class="d-block">{{ $student->first_name }} {{ $student->last_name }}</span>
                                                <span class="fs-12 d-block fw-normal text-muted">{{ $student->user->email ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->student_number }}</td>
                                    <td>{{ $student->program }}</td>
                                    <td>{{ $student->year_level }}</td>
                                    <td>{{ $student->created_at->format('d M, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="feather-users fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No students found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
