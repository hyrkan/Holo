@extends('layouts.admin')

@section('title', 'Students List || Holo Board')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Students</h5>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.students.index') }}" method="GET" class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Denied</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Name</th>
                                    <th>Student Number</th>
                                    <th>Program</th>
                                    <th>Status</th>
                                    <th>Joined At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <a href="{{ route('admin.students.show', $student) }}" class="d-block fw-bold text-dark">{{ $student->first_name }} {{ $student->last_name }}</a>
                                                <span class="fs-12 d-block fw-normal text-muted">{{ $student->user->email ?? 'N/A' }}</span>
                                                <span class="badge bg-soft-info text-info fs-10">{{ ucfirst($student->student_type) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->student_number }}</td>
                                    <td>{{ $student->program ?? 'Not Assigned' }}</td>
                                    <td>
                                        @if($student->status === 'pending')
                                            <span class="badge bg-soft-warning text-warning">Pending</span>
                                        @elseif($student->status === 'approved')
                                            <span class="badge bg-soft-success text-success">Approved</span>
                                        @elseif($student->status === 'denied')
                                            <span class="badge bg-soft-danger text-danger">Denied</span>
                                        @elseif($student->status === 'expired')
                                            <span class="badge bg-soft-secondary text-secondary">Expired</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->created_at->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($student->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-soft-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $student->id }}">
                                                    Approve
                                                </button>
                                                <button type="button" class="btn btn-sm btn-soft-danger" data-bs-toggle="modal" data-bs-target="#denyModal{{ $student->id }}">
                                                    Deny
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-soft-primary">View</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
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

@section('modals')
@foreach ($students as $student)
    @if($student->status === 'pending')
        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal{{ $student->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.students.approve', $student) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="approveModalLabel{{ $student->id }}">Approve Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <p>Assign a program and year level for <strong>{{ $student->full_name }}</strong>:</p>
                            <div class="mb-3">
                                <label class="form-label">Program</label>
                                <input type="text" name="program" class="form-control" placeholder="e.g. BSIT" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Year Level</label>
                                <select name="year_level" class="form-control" required>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve & Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Deny Modal -->
        <div class="modal fade" id="denyModal{{ $student->id }}" tabindex="-1" aria-labelledby="denyModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.students.deny', $student) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="denyModalLabel{{ $student->id }}">Deny Registration</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <p>Are you sure you want to deny <strong>{{ $student->full_name }}</strong>?</p>
                            <div class="mb-3">
                                <label class="form-label">Reason (Optional)</label>
                                <textarea name="reason" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Confirm Deny</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
