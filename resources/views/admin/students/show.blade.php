@extends('layouts.admin')

@section('title', 'Student Profile || Holo Board')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-4">
            <!-- Student Basic Info -->
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-text avatar-xl bg-soft-primary text-primary rounded-circle mb-3 mx-auto">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </div>
                        <h4 class="fw-bold mb-1">{{ $student->first_name }} {{ $student->last_name }}</h4>
                        <p class="text-muted small mb-2">{{ $student->student_number }}</p>
                        <div class="mb-3">
                            <span class="badge bg-soft-info text-info me-1">{{ ucfirst($student->student_type) }}</span>
                            @if($student->status === 'pending')
                                <span class="badge bg-soft-warning text-warning">Pending Approval</span>
                            @elseif($student->status === 'approved')
                                <span class="badge bg-soft-success text-success">Approved</span>
                            @elseif($student->status === 'denied')
                                <span class="badge bg-soft-danger text-danger">Denied</span>
                            @elseif($student->status === 'expired')
                                <span class="badge bg-soft-secondary text-secondary">Expired</span>
                            @endif
                        </div>

                        @if($student->status === 'pending')
                            <div class="d-flex justify-content-center gap-2 mb-4">
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    Approve Account
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#denyModal">
                                    Deny Registration
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fs-13 fw-bold text-uppercase mb-3">Contact Information</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="feather-mail me-2 text-muted"></i>
                            <span>{{ $student->user->email }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fs-13 fw-bold text-uppercase mb-3">Academic Details</h6>
                        <div class="mb-2">
                            <span class="text-muted small d-block">Program</span>
                            <span class="fw-bold text-dark">{{ $student->program }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small d-block">Year Level</span>
                            <span class="fw-bold text-dark">{{ $student->year_level }}</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="p-3 bg-light rounded-4 border border-dashed">
                            <h6 class="mb-2 small">Attendance QR Code</h6>
                            {!! QrCode::size(150)->generate($student->uuid) !!}
                            <p class="mt-2 mb-0 x-small text-muted">{{ $student->uuid }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Events Participation -->
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Events Participation</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Joined On</th>
                                    <th>Status</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->events as $event)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 text-truncate" style="max-width: 200px;">{{ $event->name }}</h6>
                                        </td>
                                        <td>{{ $event->pivot->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-soft-{{ $event->pivot->status === 'registered' ? 'success' : 'danger' }} text-{{ $event->pivot->status === 'registered' ? 'success' : 'danger' }}">
                                                {{ ucfirst($event->pivot->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $attendanceCount = $student->attendances
                                                    ->whereIn('event_date_id', $event->eventDates->pluck('id'))
                                                    ->count();
                                                $totalDates = $event->eventDates->count();
                                            @endphp
                                            <span class="badge bg-soft-info text-info">
                                                {{ $attendanceCount }} / {{ $totalDates }} Days
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No events joined yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Recent Attendance Logs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Time Scanned</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->attendances->sortByDesc('scanned_at')->take(10) as $attendance)
                                    <tr>
                                        <td>{{ $attendance->eventDate->event->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->eventDate->date)->format('M d, Y') }}</td>
                                        <td>{{ $attendance->scanned_at ? \Carbon\Carbon::parse($attendance->scanned_at)->format('h:i A') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No attendance recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('modals')
@if($student->status === 'pending')
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.students.approve', $student) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Approve Student</h5>
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
    <div class="modal fade" id="denyModal" tabindex="-1" aria-labelledby="denyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.students.deny', $student) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="denyModalLabel">Deny Registration</h5>
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
@endsection
