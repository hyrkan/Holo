@extends('layouts.admin')

@section('title', 'Student Profile || Holo Board')

@section('content')
<div class="main-content pb-5">
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
                            <span class="fw-bold text-dark">{{ $student->program ?? 'Not Assigned' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small d-block">Enrollment Status</span>
                            @if($student->enrollment_status === 'enrolled')
                                <span class="badge bg-soft-success text-success">Enrolled</span>
                            @elseif($student->enrollment_status === 'graduate')
                                <span class="badge bg-soft-primary text-primary">Graduate</span>
                            @else
                                <span class="text-muted small">Not set</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small d-block">Year Level</span>
                            <span class="fw-bold text-dark">{{ $student->year_level ?? 'Not Assigned' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small d-block">Classification</span>
                            @if($student->classification === 'freshie')
                                <span class="badge bg-soft-info text-info">Freshie</span>
                            @elseif($student->classification === 'cross_enrollee')
                                <span class="badge bg-soft-warning text-warning">Cross Enrollee</span>
                            @elseif($student->classification === 'enrolled')
                                <span class="badge bg-soft-success text-success">Enrolled</span>
                            @else
                                <span class="text-muted small">Not set</span>
                            @endif
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
            <div class="card stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Student Details</h5>
                    <ul class="nav nav-pills custom-tabs-pill" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-verification" data-bs-toggle="tab" data-bs-target="#pane-verification" type="button" role="tab" aria-controls="pane-verification" aria-selected="true">
                                Verification Images
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-participation" data-bs-toggle="tab" data-bs-target="#pane-participation" type="button" role="tab" aria-controls="pane-participation" aria-selected="false">
                                Events Participation
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-logs" data-bs-toggle="tab" data-bs-target="#pane-logs" type="button" role="tab" aria-controls="pane-logs" aria-selected="false">
                                Event Logs
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pane-verification" role="tabpanel" aria-labelledby="tab-verification">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="border rounded-4 p-2 text-center">
                                        <h6 class="small text-muted mb-2">ID Front</h6>
                                        @if($student->id_front_path)
                                            <img src="{{ asset('storage/' . $student->id_front_path) }}" class="img-fluid rounded" alt="ID Front">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 160px;">
                                                <i class="feather-image fs-2 text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded-4 p-2 text-center">
                                        <h6 class="small text-muted mb-2">ID Back</h6>
                                        @if($student->id_back_path)
                                            <img src="{{ asset('storage/' . $student->id_back_path) }}" class="img-fluid rounded" alt="ID Back">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 160px;">
                                                <i class="feather-image fs-2 text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded-4 p-2 text-center">
                                        <h6 class="small text-muted mb-2">Face Photo</h6>
                                        @if($student->face_photo_path)
                                            <img src="{{ asset('storage/' . $student->face_photo_path) }}" class="img-fluid rounded" alt="Face Photo">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 160px;">
                                                <i class="feather-user fs-2 text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pane-participation" role="tabpanel" aria-labelledby="tab-participation">
                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <input id="evp-search" type="text" class="form-control" placeholder="Search event name">
                                </div>
                                <div class="col-md-3">
                                    <select id="evp-period" class="form-select">
                                        <option value="all">All time</option>
                                        <option value="this_week">This week</option>
                                        <option value="this_month">This month</option>
                                        <option value="this_year">This year</option>
                                        <option value="custom">Custom range</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input id="evp-start" type="date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input id="evp-end" type="date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <select id="evp-attendance" class="form-select">
                                        <option value="any">All</option>
                                        <option value="any_present">Any Present</option>
                                        <option value="fully_present">Fully Present</option>
                                        <option value="partial">Partially Present</option>
                                        <option value="fully_absent">Fully Absent</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="evp-date-mode">
                                        <label class="form-check-label small" for="evp-date-mode">Use event dates</label>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button id="evp-download" data-student-id="{{ $student->id }}" type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="feather-download me-2"></i>Download CSV
                                    </button>
                                </div>
                            </div>
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
                                    <tbody id="evp-tbody">
                                        @forelse($student->events as $event)
                                            @php
                                                $attendanceCount = $student->attendances
                                                    ->whereIn('event_date_id', $event->eventDates->pluck('id'))
                                                    ->count();
                                                $totalDates = $event->eventDates->count();
                                                $joinedISO = optional($event->pivot->created_at)->format('Y-m-d');
                                            @endphp
                                            <tr data-joined="{{ $joinedISO }}" data-total="{{ $totalDates }}" data-present="{{ $attendanceCount }}">
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
                                                    <span class="badge bg-soft-info text-info">
                                                        {{ $attendanceCount }} / {{ $totalDates }} Days
                                                    </span>
                                                    @foreach($event->eventDates as $eventDate)
                                                        @php $has = $student->attendances->contains('event_date_id', $eventDate->id); @endphp
                                                        <span class="d-none evp-session" data-date="{{ \Carbon\Carbon::parse($eventDate->date)->format('Y-m-d') }}" data-present="{{ $has ? 1 : 0 }}"></span>
                                                    @endforeach
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
                        <div class="tab-pane fade" id="pane-logs" role="tabpanel" aria-labelledby="tab-logs">
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
    </div>
</div>
@endsection

@section('modals')
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('admin.students.approve', $student) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Approve Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <p>Assign details for <strong>{{ $student->full_name }}</strong>:</p>
                        <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
                        <div class="mb-3">
                            <label class="form-label">Program <span class="text-danger">*</span></label>
                            <select name="program" id="approve_program" class="form-control" required>
                                <option value="">-- Select Program --</option>
                                @foreach(($programs ?? collect())->where('is_active', true) as $prog)
                                    <option value="{{ strtoupper($prog->name) }}">{{ strtoupper($prog->name) }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <button id="manageProgramsBtn" class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#managePrograms" aria-expanded="false" aria-controls="managePrograms" data-list-url="{{ route('admin.programs.index') }}">
                                    <i class="feather-settings me-1"></i> Manage Programs
                                </button>
                            </div>
                            <div class="collapse mt-3" id="managePrograms">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-2">Add Program</h6>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-4">
                                            <input id="program_add_name" type="text" class="form-control" placeholder="Program name">
                                        </div>
                                        <div class="col-md-6">
                                            <input id="program_add_desc" type="text" class="form-control" placeholder="Description (optional)">
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button id="program_add_btn" type="button" class="btn btn-primary btn-sm" data-url="{{ route('admin.programs.store') }}">Add</button>
                                        </div>
                                    </div>
                                    <h6 class="mb-2">Programs</h6>
                                    <div id="programs_list" class="list-group">
                                        @foreach(($programs ?? collect()) as $prog)
                                            <div class="list-group-item">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-4">
                                                        <input id="program_name_{{ $prog->id }}" type="text" class="form-control form-control-sm" value="{{ strtoupper($prog->name) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input id="program_desc_{{ $prog->id }}" type="text" class="form-control form-control-sm" value="{{ $prog->description }}">
                                                    </div>
                                                    <div class="col-md-2 d-flex justify-content-end align-items-center">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm me-2 program-update-btn" data-url="{{ route('admin.programs.update', $prog) }}" data-id="{{ $prog->id }}">Update</button>
                                                        @if($prog->is_active)
                                                            <button type="button" class="btn btn-outline-danger btn-sm program-archive-btn" data-url="{{ route('admin.programs.archive', $prog) }}">Archive</button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-primary btn-sm program-restore-btn" data-url="{{ route('admin.programs.restore', $prog) }}">Restore</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Enrollment Status <span class="text-danger">*</span></label>
                            <select name="enrollment_status" id="approve_enrollment_status" class="form-control" required>
                                <option value="">-- Select Status --</option>
                                @foreach(($statuses ?? collect())->where('is_active', true) as $status)
                                    <option value="{{ $status->name }}">{{ $status->label }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <button id="manageStatusesBtn" class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#manageStatuses" aria-expanded="false" aria-controls="manageStatuses" data-list-url="{{ route('admin.enrollment-statuses.index') }}">
                                    <i class="feather-settings me-1"></i> Manage Enrollment Statuses
                                </button>
                            </div>
                            <div class="collapse mt-3" id="manageStatuses">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-2">Add Status</h6>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <input id="status_add_name" type="text" class="form-control" placeholder="slug (e.g. enrolled)">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="status_add_label" type="text" class="form-control" placeholder="Label (e.g. Enrolled)">
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button id="status_add_btn" type="button" class="btn btn-primary btn-sm" data-url="{{ route('admin.enrollment-statuses.store') }}">Add</button>
                                        </div>
                                    </div>
                                    <h6 class="mb-2">Statuses</h6>
                                    <div id="statuses_list" class="list-group">
                                        @foreach(($statuses ?? collect()) as $status)
                                            <div class="list-group-item">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-3">
                                                            <input id="status_name_{{ $status->id }}" type="text" class="form-control form-control-sm" value="{{ $status->name }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                            <input id="status_label_{{ $status->id }}" type="text" class="form-control form-control-sm" value="{{ $status->label }}">
                                                    </div>
                                                    <div class="col-md-5 d-flex justify-content-end align-items-center">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm me-2 status-update-btn" data-url="{{ route('admin.enrollment-statuses.update', $status) }}" data-id="{{ $status->id }}">Update</button>
                                                        @if($status->is_active)
                                                            <button type="button" class="btn btn-outline-danger btn-sm status-archive-btn" data-url="{{ route('admin.enrollment-statuses.archive', $status) }}">Archive</button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-primary btn-sm status-restore-btn" data-url="{{ route('admin.enrollment-statuses.restore', $status) }}">Restore</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year Level <span class="text-danger">*</span></label>
                            <select name="year_level" id="show_year_level" class="form-control" required>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="N/A">N/A</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Classification <span class="text-danger">*</span></label>
                            <select name="classification" id="show_classification" class="form-control" required>
                                <option value="">-- Select Classification --</option>
                                @foreach(($classifications ?? collect())->where('is_active', true) as $cls)
                                    <option value="{{ $cls->name }}">{{ $cls->label }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <button id="manageClassificationsBtn" class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#manageClassifications" aria-expanded="false" aria-controls="manageClassifications" data-list-url="{{ route('admin.classifications.index') }}">
                                    <i class="feather-settings me-1"></i> Manage Classifications
                                </button>
                            </div>
                            <div class="collapse mt-3" id="manageClassifications">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-2">Add Classification</h6>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <input id="classification_add_name" type="text" class="form-control" placeholder="slug (e.g. freshie)">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="classification_add_label" type="text" class="form-control" placeholder="Label (e.g. Freshie)">
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button id="classification_add_btn" type="button" class="btn btn-primary btn-sm" data-url="{{ route('admin.classifications.store') }}">Add</button>
                                        </div>
                                    </div>
                                    <h6 class="mb-2">Classifications</h6>
                                    <div id="classifications_list" class="list-group">
                                        @foreach(($classifications ?? collect()) as $cls)
                                            <div class="list-group-item">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-3">
                                                        <input id="classification_name_{{ $cls->id }}" type="text" class="form-control form-control-sm" value="{{ $cls->name }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input id="classification_label_{{ $cls->id }}" type="text" class="form-control form-control-sm" value="{{ $cls->label }}">
                                                    </div>
                                                    <div class="col-md-5 d-flex justify-content-end align-items-center">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm me-2 classification-update-btn" data-url="{{ route('admin.classifications.update', $cls) }}" data-id="{{ $cls->id }}">Update</button>
                                                        @if($cls->is_active)
                                                            <button type="button" class="btn btn-outline-danger btn-sm classification-archive-btn" data-url="{{ route('admin.classifications.archive', $cls) }}">Archive</button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-primary btn-sm classification-restore-btn" data-url="{{ route('admin.classifications.restore', $cls) }}">Restore</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Auto-set to <em>Freshie</em> when Year Level is 1st Year.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Approve &amp; Assign</button>
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
 
@push('scripts')
<script>
    (function () {
        var yearSel = document.getElementById('show_year_level');
        var classSel = document.getElementById('show_classification');
        function autoSelectClassification() {
            if (!yearSel || !classSel) return;
            if (yearSel.value === '1st Year') {
                var opts = Array.from(classSel.options || []);
                var freshieOpt = opts.find(function(o){ return o.value === 'freshie'; }) || opts.find(function(o){ return (o.textContent || '').trim().toLowerCase() === 'freshie'; });
                if (freshieOpt) {
                    classSel.value = freshieOpt.value;
                }
            }
        }
        if (yearSel && classSel) {
            yearSel.addEventListener('change', function () {
                autoSelectClassification();
            });
            autoSelectClassification();
        }
        var approveModal = document.getElementById('approveModal');
        if (approveModal) {
            approveModal.addEventListener('show.bs.modal', function(){
                autoSelectClassification();
            });
        }

        var csrf = document.getElementById('csrf_token') ? document.getElementById('csrf_token').value : '';
        function showToast(message, type) {
            var container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                container.style.zIndex = '2000';
                document.body.appendChild(container);
            }
            var bg = 'bg-success';
            var icon = 'feather-check-circle';
            if (type === 'danger') { bg = 'bg-danger'; icon = 'feather-alert-triangle'; }
            if (type === 'info') { bg = 'bg-info'; icon = 'feather-info'; }
            if (type === 'warning') { bg = 'bg-warning'; icon = 'feather-alert-circle'; }
            var toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white ' + bg + ' border-0';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = '<div class="d-flex"><div class="toast-body"><i class="' + icon + ' me-2"></i> ' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>';
            container.appendChild(toast);
            var t = window.bootstrap ? window.bootstrap.Toast.getOrCreateInstance(toast, { delay: 2200 }) : null;
            if (t) t.show();
        }
        function post(url, data) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: new URLSearchParams(data || {})
            });
        }

        var addProgBtn = document.getElementById('program_add_btn');
        if (addProgBtn) {
            addProgBtn.addEventListener('click', function () {
                var name = document.getElementById('program_add_name').value.trim();
                var desc = document.getElementById('program_add_desc').value.trim();
                if (!name) return;
                post(this.getAttribute('data-url'), { name: name, description: desc })
                    .then(function (res) { return res.json(); })
                    .then(function (json) {
                        if (!json || !json.program) return;
                        var opt = document.createElement('option');
                        opt.value = (json.program.name || '').toUpperCase();
                        opt.textContent = opt.value;
                        var sel = document.getElementById('approve_program');
                        if (sel) sel.appendChild(opt);
                        if (sel) sel.value = opt.value;
                        var list = document.getElementById('programs_list');
                        if (list) {
                            var item = document.createElement('div');
                            item.className = 'list-group-item d-flex align-items-center justify-content-between';
                            item.innerHTML = '<div><span class=\"fw-bold\">' + opt.value + '</span><span class=\"badge bg-soft-success text-success ms-2\">Active</span></div><div><button type=\"button\" class=\"btn btn-outline-danger btn-sm program-archive-btn\" data-url=\"' + (json.program.archive_url || '') + '\">Archive</button></div>';
                            list.appendChild(item);
                            item.querySelector('.program-archive-btn').addEventListener('click', function(){
                                post(this.getAttribute('data-url')).then(function(){});
                            });
                        }
                        document.getElementById('program_add_name').value = '';
                        document.getElementById('program_add_desc').value = '';
                        var collapseEl = document.getElementById('managePrograms');
                        if (collapseEl && window.bootstrap) {
                            var c = window.bootstrap.Collapse.getOrCreateInstance(collapseEl);
                            c.hide();
                        }
                        showToast('Program added successfully', 'success');
                    });
            });
        }

        function refreshProgramsUI() {
            var manageBtn = document.getElementById('manageProgramsBtn');
            var url = manageBtn ? manageBtn.getAttribute('data-list-url') : null;
            if (!url) return;
            fetch(url, { headers: { 'Accept': 'application/json' }})
                .then(function(res){ return res.json(); })
                .then(function(json){
                    var programs = json.programs || [];
                    var list = document.getElementById('programs_list');
                    var sel = document.getElementById('approve_program');
                    if (list) list.innerHTML = '';
                    var keep = sel ? sel.value : '';
                    if (sel) sel.innerHTML = '<option value=\"\">-- Select Program --</option>';
                    programs.forEach(function(p){
                        var name = (p.name || '').toUpperCase();
                        var item = document.createElement('div');
                        item.className = 'list-group-item';
                        item.innerHTML = '<div class=\"row g-2 align-items-center\"><div class=\"col-md-4\"><input id=\"program_name_' + p.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + name + '\"></div><div class=\"col-md-6\"><input id=\"program_desc_' + p.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + (p.description || '') + '\"></div><div class=\"col-md-2 d-flex justify-content-end align-items-center\"><button type=\"button\" class=\"btn btn-outline-secondary btn-sm me-2 program-update-btn\" data-url=\"/admin/programs/' + p.id + '\" data-id=\"' + p.id + '\">Update</button>' + (p.is_active ? '<button type=\"button\" class=\"btn btn-outline-danger btn-sm program-archive-btn\" data-url=\"/admin/programs/' + p.id + '/archive\">Archive</button>' : '<button type=\"button\" class=\"btn btn-outline-primary btn-sm program-restore-btn\" data-url=\"/admin/programs/' + p.id + '/restore\">Restore</button>') + '</div></div>';
                        list.appendChild(item);
                        if (sel && p.is_active) {
                            var opt = document.createElement('option');
                            opt.value = name;
                            opt.textContent = name;
                            sel.appendChild(opt);
                        }
                    });
                    if (sel && keep) sel.value = keep;
                    bindProgramActionHandlers();
                });
        }
        function bindProgramActionHandlers() {
            document.querySelectorAll('.program-archive-btn').forEach(function(btn){
                btn.addEventListener('click', function(){
                    var url = this.getAttribute('data-url');
                    post(url).then(function(res){ return res.json(); }).then(function(json){
                        showToast('Program archived', 'success');
                        refreshProgramsUI();
                    });
                });
            });
            document.querySelectorAll('.program-restore-btn').forEach(function(btn){
                btn.addEventListener('click', function(){
                    var url = this.getAttribute('data-url');
                    post(url).then(function(res){ return res.json(); }).then(function(json){
                        showToast('Program restored', 'success');
                        refreshProgramsUI();
                    });
                });
            });
            document.querySelectorAll('.program-update-btn').forEach(function(btn){
                btn.addEventListener('click', function(){
                    var id = this.getAttribute('data-id');
                    var url = this.getAttribute('data-url');
                    var name = document.getElementById('program_name_' + id).value.trim().toUpperCase();
                    var desc = document.getElementById('program_desc_' + id).value.trim();
                    post(url, { name: name, description: desc })
                        .then(function(res){ return res.json(); })
                        .then(function(json){
                            showToast('Program updated', 'success');
                            refreshProgramsUI();
                        });
                });
            });
        }
        bindProgramActionHandlers();

        var manageProgramsBtn = document.getElementById('manageProgramsBtn');
        if (manageProgramsBtn) {
            var collapseEl = document.getElementById('managePrograms');
            if (collapseEl) {
                collapseEl.addEventListener('show.bs.collapse', function(){
                    refreshProgramsUI();
                });
            }
        }
        var addStatusBtn = document.getElementById('status_add_btn');
        if (addStatusBtn) {
            addStatusBtn.addEventListener('click', function(){
                var name = document.getElementById('status_add_name').value.trim();
                var label = document.getElementById('status_add_label').value.trim();
                if (!name || !label) return;
                post(this.getAttribute('data-url'), { name: name, label: label })
                    .then(function(res){ return res.json(); })
                    .then(function(json){
                        if (!json || !json.status) return;
                        var sel = document.getElementById('approve_enrollment_status');
                        if (sel) {
                            var opt = document.createElement('option');
                            opt.value = json.status.name;
                            opt.textContent = json.status.label;
                            sel.appendChild(opt);
                            sel.value = json.status.name;
                        }
                        var list = document.getElementById('statuses_list');
                        if (list) {
                            var row = document.createElement('div');
                            row.className = 'list-group-item';
                            row.innerHTML = '<div class=\"row g-2 align-items-center\"><div class=\"col-md-3\"><input id=\"status_name_' + json.status.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + json.status.name + '\"></div><div class=\"col-md-4\"><input id=\"status_label_' + json.status.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + json.status.label + '\"></div><div class=\"col-md-5 d-flex justify-content-end align-items-center\"><button type=\"button\" class=\"btn btn-outline-secondary btn-sm me-2 status-update-btn\" data-url=\"' + (json.status.update_url || '') + '\" data-id=\"' + json.status.id + '\">Update</button><button type=\"button\" class=\"btn btn-outline-danger btn-sm status-archive-btn\" data-url=\"' + (json.status.archive_url || '') + '\">Archive</button></div></div>';
                            list.appendChild(row);
                        }
                        document.getElementById('status_add_name').value = '';
                        document.getElementById('status_add_label').value = '';
                        showToast('Status added', 'success');
                        var collapseEl = document.getElementById('manageStatuses');
                        if (collapseEl && window.bootstrap) {
                            var c = window.bootstrap.Collapse.getOrCreateInstance(collapseEl);
                            c.hide();
                        }
                    });
            });
        }
        document.querySelectorAll('.status-update-btn').forEach(function(btn){
            btn.addEventListener('click', function(){
                var id = this.getAttribute('data-id');
                var name = document.getElementById('status_name_' + id).value.trim();
                var label = document.getElementById('status_label_' + id).value.trim();
                var url = this.getAttribute('data-url');
                post(url, { name: name, label: label })
                    .then(function(res){ return res.json(); })
                    .then(function(json){
                        var sel = document.getElementById('approve_enrollment_status');
                        if (sel) {
                            Array.from(sel.options).forEach(function(o){
                                if (o.value === name) { o.textContent = label; }
                            });
                        }
                        showToast('Status updated', 'success');
                    });
            });
        });
        document.querySelectorAll('.status-archive-btn').forEach(function(btn){
            btn.addEventListener('click', function(){
                var url = this.getAttribute('data-url');
                var button = this;
                post(url).then(function(res){ return res.json(); }).then(function(json){
                    var sel = document.getElementById('approve_enrollment_status');
                    if (sel) {
                        Array.from(sel.options).forEach(function(o){
                            if (o.value === json.status.name) { o.remove(); }
                        });
                    }
                    button.outerHTML = '<button type=\"button\" class=\"btn btn-outline-primary btn-sm status-restore-btn\" data-url=\"' + url.replace('/archive','/restore') + '\">Restore</button>';
                    showToast('Status archived', 'success');
                });
            });
        });
        document.querySelectorAll('.status-restore-btn').forEach(function(btn){
            btn.addEventListener('click', function(){
                var url = this.getAttribute('data-url');
                var button = this;
                post(url).then(function(res){ return res.json(); }).then(function(json){
                    var sel = document.getElementById('approve_enrollment_status');
                    if (sel) {
                        var exists = Array.from(sel.options).some(function(o){ return o.value === json.status.name; });
                        if (!exists) {
                            var opt = document.createElement('option');
                            opt.value = json.status.name;
                            opt.textContent = json.status.label;
                            sel.appendChild(opt);
                        }
                    }
                    button.outerHTML = '<button type=\"button\" class=\"btn btn-outline-danger btn-sm status-archive-btn\" data-url=\"' + url.replace('/restore','/archive') + '\">Archive</button>';
                    showToast('Status restored', 'success');
                });
            });
        });

        var manageStatusesBtn = document.getElementById('manageStatusesBtn');
        if (manageStatusesBtn) {
            var collapseElS = document.getElementById('manageStatuses');
            if (collapseElS) {
                collapseElS.addEventListener('show.bs.collapse', function(){
                    var url = manageStatusesBtn.getAttribute('data-list-url');
                    fetch(url, { headers: { 'Accept': 'application/json' }})
                        .then(function(res){ return res.json(); })
                        .then(function(json){
                            var statuses = json.statuses || [];
                            var list = document.getElementById('statuses_list');
                            var sel = document.getElementById('approve_enrollment_status');
                            if (list) list.innerHTML = '';
                            if (sel) {
                                var keep = sel.value;
                                sel.innerHTML = '<option value=\"\">-- Select Status --</option>';
                            }
                            statuses.forEach(function(s){
                                var name = s.name;
                                var label = s.label;
                                var item = document.createElement('div');
                                item.className = 'list-group-item';
                                item.innerHTML = '<div class=\"row g-2 align-items-center\"><div class=\"col-md-3\"><input id=\"status_name_' + s.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + name + '\"></div><div class=\"col-md-4\"><input id=\"status_label_' + s.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + label + '\"></div><div class=\"col-md-5 d-flex justify-content-end align-items-center\"><button type=\"button\" class=\"btn btn-outline-secondary btn-sm me-2 status-update-btn\" data-url=\"/admin/enrollment-statuses/' + s.id + '\" data-id=\"' + s.id + '\">Update</button>' + (s.is_active ? '<button type=\"button\" class=\"btn btn-outline-danger btn-sm status-archive-btn\" data-url=\"/admin/enrollment-statuses/' + s.id + '/archive\">Archive</button>' : '<button type=\"button\" class=\"btn btn-outline-primary btn-sm status-restore-btn\" data-url=\"/admin/enrollment-statuses/' + s.id + '/restore\">Restore</button>') + '</div></div>';
                                list.appendChild(item);
                                if (sel && s.is_active) {
                                    var opt = document.createElement('option');
                                    opt.value = name;
                                    opt.textContent = label;
                                    sel.appendChild(opt);
                                }
                            });
                            document.querySelectorAll('.status-update-btn').forEach(function(btn){
                                btn.addEventListener('click', function(){
                                    var id = this.getAttribute('data-id');
                                    var name = document.getElementById('status_name_' + id).value.trim();
                                    var label = document.getElementById('status_label_' + id).value.trim();
                                    var url = this.getAttribute('data-url');
                                    post(url, { name: name, label: label })
                                        .then(function(res){ return res.json(); })
                                        .then(function(json){
                                            showToast('Status updated', 'success');
                                            var sel = document.getElementById('approve_enrollment_status');
                                            if (sel) {
                                                Array.from(sel.options).forEach(function(o){
                                                    if (o.value === name) { o.textContent = label; }
                                                });
                                            }
                                        });
                                });
                            });
                            document.querySelectorAll('.status-archive-btn').forEach(function(btn){
                                btn.addEventListener('click', function(){
                                    var urlA = this.getAttribute('data-url');
                                    var button = this;
                                    post(urlA).then(function(res){ return res.json(); }).then(function(json){
                                        showToast('Status archived', 'success');
                                        var sel = document.getElementById('approve_enrollment_status');
                                        if (sel) {
                                            Array.from(sel.options).forEach(function(o){
                                                if (o.value === json.status.name) { o.remove(); }
                                            });
                                        }
                                        button.outerHTML = '<button type=\"button\" class=\"btn btn-outline-primary btn-sm status-restore-btn\" data-url=\"' + urlA.replace('/archive','/restore') + '\">Restore</button>';
                                    });
                                });
                            });
                            document.querySelectorAll('.status-restore-btn').forEach(function(btn){
                                btn.addEventListener('click', function(){
                                    var urlR = this.getAttribute('data-url');
                                    var button = this;
                                    post(urlR).then(function(res){ return res.json(); }).then(function(json){
                                        showToast('Status restored', 'success');
                                        var sel = document.getElementById('approve_enrollment_status');
                                        if (sel) {
                                            var exists = Array.from(sel.options).some(function(o){ return o.value === json.status.name; });
                                            if (!exists) {
                                                var opt = document.createElement('option');
                                                opt.value = json.status.name;
                                                opt.textContent = json.status.label;
                                                sel.appendChild(opt);
                                            }
                                        }
                                        button.outerHTML = '<button type=\"button\" class=\"btn btn-outline-danger btn-sm status-archive-btn\" data-url=\"' + urlR.replace('/restore','/archive') + '\">Archive</button>';
                                    });
                                });
                            });
                            if (sel && keep) sel.value = keep;
                        });
                });
            }
        }

        var manageClassificationsBtn = document.getElementById('manageClassificationsBtn');
        if (manageClassificationsBtn) {
            var collapseElC = document.getElementById('manageClassifications');
            if (collapseElC) {
                collapseElC.addEventListener('show.bs.collapse', function(){
                    var url = manageClassificationsBtn.getAttribute('data-list-url');
                    fetch(url, { headers: { 'Accept': 'application/json' }})
                        .then(function(res){ return res.json(); })
                        .then(function(json){
                            var classifications = json.classifications || [];
                            var list = document.getElementById('classifications_list');
                            var sel = document.getElementById('show_classification');
                            if (list) list.innerHTML = '';
                            if (sel) {
                                var keep = sel.value;
                                sel.innerHTML = '<option value=\"\">-- Select Classification --</option>';
                            }
                            classifications.forEach(function(c){
                                var name = c.name;
                                var label = c.label;
                                var item = document.createElement('div');
                                item.className = 'list-group-item';
                                item.innerHTML = '<div class=\"row g-2 align-items-center\"><div class=\"col-md-3\"><input id=\"classification_name_' + c.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + name + '\"></div><div class=\"col-md-4\"><input id=\"classification_label_' + c.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + label + '\"></div><div class=\"col-md-5 d-flex justify-content-end align-items-center\"><button type=\"button\" class=\"btn btn-outline-secondary btn-sm me-2 classification-update-btn\" data-url=\"/admin/classifications/' + c.id + '\" data-id=\"' + c.id + '\">Update</button>' + (c.is_active ? '<button type=\"button\" class=\"btn btn-outline-danger btn-sm classification-archive-btn\" data-url=\"/admin/classifications/' + c.id + '/archive\">Archive</button>' : '<button type=\"button\" class=\"btn btn-outline-primary btn-sm classification-restore-btn\" data-url=\"/admin/classifications/' + c.id + '/restore\">Restore</button>') + '</div></div>';
                                list.appendChild(item);
                                if (sel && c.is_active) {
                                    var opt = document.createElement('option');
                                    opt.value = name;
                                    opt.textContent = label;
                                    sel.appendChild(opt);
                                }
                            });
                            document.querySelectorAll('.classification-update-btn').forEach(function(btn){
                                btn.addEventListener('click', function(){
                                    var id = this.getAttribute('data-id');
                                    var name = document.getElementById('classification_name_' + id).value.trim();
                                    var label = document.getElementById('classification_label_' + id).value.trim();
                                    var url = this.getAttribute('data-url');
                                    post(url, { name: name, label: label })
                                        .then(function(res){ return res.json(); })
                                        .then(function(json){
                                            showToast('Classification updated', 'success');
                                            var sel = document.getElementById('show_classification');
                                            if (sel) {
                                                Array.from(sel.options).forEach(function(o){
                                                    if (o.value === name) { o.textContent = label; }
                                                });
                                            }
                                        });
                                });
                            });
                            document.querySelectorAll('.classification-archive-btn').forEach(function(btn){
                                btn.addEventListener('click', function(){
                                    var urlA = this.getAttribute('data-url');
                                    var button = this;
                                    post(urlA).then(function(res){ return res.json(); }).then(function(json){
                                        showToast('Classification archived', 'success');
                                        var sel = document.getElementById('show_classification');
                                        if (sel) {
                                            Array.from(sel.options).forEach(function(o){
                                                if (o.value === json.classification.name) { o.remove(); }
                                            });
                                        }
                                        button.outerHTML = '<button type=\"button\" class=\"btn btn-outline-primary btn-sm classification-restore-btn\" data-url=\"' + urlA.replace('/archive','/restore') + '\">Restore</button>';
                                    });
                                });
                            });
                            document.querySelectorAll('.classification-restore-btn').forEach(function(btn){
                                btn.addEventListener('click', function(){
                                    var urlR = this.getAttribute('data-url');
                                    var button = this;
                                    post(urlR).then(function(res){ return res.json(); }).then(function(json){
                                        showToast('Classification restored', 'success');
                                        var sel = document.getElementById('show_classification');
                                        if (sel) {
                                            var exists = Array.from(sel.options).some(function(o){ return o.value === json.classification.name; });
                                            if (!exists) {
                                                var opt = document.createElement('option');
                                                opt.value = json.classification.name;
                                                opt.textContent = json.classification.label;
                                                sel.appendChild(opt);
                                            }
                                        }
                                        button.outerHTML = '<button type=\"button\" class=\"btn btn-outline-danger btn-sm classification-archive-btn\" data-url=\"' + urlR.replace('/restore','/archive') + '\">Archive</button>';
                                    });
                                });
                            });
                            if (sel && keep) sel.value = keep;
                        });
                });
            }
        }

        var addClassificationBtn = document.getElementById('classification_add_btn');
        if (addClassificationBtn) {
            addClassificationBtn.addEventListener('click', function(){
                var name = document.getElementById('classification_add_name').value.trim();
                var label = document.getElementById('classification_add_label').value.trim();
                if (!name || !label) return;
                post(this.getAttribute('data-url'), { name: name, label: label })
                    .then(function(res){ return res.json(); })
                    .then(function(json){
                        if (!json || !json.classification) return;
                        var sel = document.getElementById('show_classification');
                        if (sel) {
                            var opt = document.createElement('option');
                            opt.value = json.classification.name;
                            opt.textContent = json.classification.label;
                            sel.appendChild(opt);
                            sel.value = json.classification.name;
                        }
                        var list = document.getElementById('classifications_list');
                        if (list) {
                            var row = document.createElement('div');
                            row.className = 'list-group-item';
                            row.innerHTML = '<div class=\"row g-2 align-items-center\"><div class=\"col-md-3\"><input id=\"classification_name_' + json.classification.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + json.classification.name + '\"></div><div class=\"col-md-4\"><input id=\"classification_label_' + json.classification.id + '\" type=\"text\" class=\"form-control form-control-sm\" value=\"' + json.classification.label + '\"></div><div class=\"col-md-5 d-flex justify-content-end align-items-center\"><button type=\"button\" class=\"btn btn-outline-secondary btn-sm me-2 classification-update-btn\" data-url=\"/admin/classifications/' + json.classification.id + '\" data-id=\"' + json.classification.id + '\">Update</button><button type=\"button\" class=\"btn btn-outline-danger btn-sm classification-archive-btn\" data-url=\"/admin/classifications/' + json.classification.id + '/archive\">Archive</button></div></div>';
                            list.appendChild(row);
                        }
                        document.getElementById('classification_add_name').value = '';
                        document.getElementById('classification_add_label').value = '';
                        showToast('Classification added', 'success');
                        var collapseEl = document.getElementById('manageClassifications');
                        if (collapseEl && window.bootstrap) {
                            var c = window.bootstrap.Collapse.getOrCreateInstance(collapseEl);
                            c.hide();
                        }
                    });
            });
        }
    })();
</script>
@endpush
@endsection
@push('scripts')
<script>
(function(){
  var search = document.getElementById('evp-search');
  var start = document.getElementById('evp-start');
  var end = document.getElementById('evp-end');
  var attendanceSel = document.getElementById('evp-attendance');
  var dateMode = document.getElementById('evp-date-mode');
  var tbody = document.getElementById('evp-tbody');
  var dlBtn = document.getElementById('evp-download');
  var period = document.getElementById('evp-period');
  function toISODate(text){
    if (!text) return '';
    var map = {Jan:'01',Feb:'02',Mar:'03',Apr:'04',May:'05',Jun:'06',Jul:'07',Aug:'08',Sep:'09',Oct:'10',Nov:'11',Dec:'12'};
    var t = text.replace(',', '').trim().split(/\s+/);
    if (t.length === 3 && map[t[0]]) {
      var d = t[1];
      if (d.length === 1) d = '0' + d;
      return t[2] + '-' + map[t[0]] + '-' + d;
    }
    var d2 = new Date(text);
    if (!isNaN(d2)) return d2.toISOString().slice(0,10);
    return '';
  }
  function fmt(d){
    var y = d.getFullYear();
    var m = (d.getMonth() + 1).toString().padStart(2, '0');
    var day = d.getDate().toString().padStart(2, '0');
    return y + '-' + m + '-' + day;
  }
  function setDatesDisabled(disabled){
    if (start) start.disabled = disabled;
    if (end) end.disabled = disabled;
  }
  function applyPeriod(){
    if (!period) { applyFilters(); return; }
    var v = period.value || 'all';
    if (v === 'all') {
      if (start) start.value = '';
      if (end) end.value = '';
      setDatesDisabled(true);
      applyFilters();
      return;
    }
    if (v === 'custom') {
      setDatesDisabled(false);
      applyFilters();
      return;
    }
    var now = new Date();
    var s, e;
    if (v === 'this_week') {
      var dow = now.getDay();
      var diff = dow === 0 ? 6 : (dow - 1);
      s = new Date(now);
      s.setDate(now.getDate() - diff);
      e = new Date(s);
      e.setDate(s.getDate() + 6);
    } else if (v === 'this_month') {
      s = new Date(now.getFullYear(), now.getMonth(), 1);
      e = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    } else if (v === 'this_year') {
      s = new Date(now.getFullYear(), 0, 1);
      e = new Date(now.getFullYear(), 11, 31);
    } else {
      s = '';
      e = '';
    }
    if (start) start.value = s ? fmt(s) : '';
    if (end) end.value = e ? fmt(e) : '';
    setDatesDisabled(true);
    applyFilters();
  }
  function applyFilters(){
    if (!tbody) return;
    var term = (search && search.value ? search.value : '').trim().toLowerCase();
    var s = start && start.value ? start.value : '';
    var e = end && end.value ? end.value : '';
    var stat = attendanceSel && attendanceSel.value ? attendanceSel.value : 'any';
    var rows = tbody.querySelectorAll('tr');
    rows.forEach(function(row){
      var nameText = row.cells[0] ? row.cells[0].innerText.toLowerCase() : '';
      var joinedISO = row.getAttribute('data-joined') || '';
      var ok = true;
      if (term && nameText.indexOf(term) === -1) ok = false;
      if (dateMode && dateMode.checked) {
        var sessions = Array.from(row.querySelectorAll('.evp-session'));
        var inRange = sessions.filter(function(ses){
          var d = ses.getAttribute('data-date') || '';
          if (s && (!d || d < s)) return false;
          if (e && (!d || d > e)) return false;
          return true;
        });
        var totalInRange = (s || e) ? inRange.length : sessions.length;
        var presentInRange = (s || e ? inRange : sessions).filter(function(ses){
          return ses.getAttribute('data-present') === '1';
        }).length;
        if (s || e) {
          if (totalInRange === 0) ok = false;
        }
        if (stat === 'any_present' && presentInRange <= 0) ok = false;
        if (stat === 'fully_present' && !(totalInRange > 0 && presentInRange === totalInRange)) ok = false;
        if (stat === 'fully_absent' && !(totalInRange > 0 && presentInRange === 0)) ok = false;
        if (stat === 'partial' && !(presentInRange > 0 && presentInRange < totalInRange)) ok = false;
      } else {
        if (s || e) {
          if (s && (!joinedISO || joinedISO < s)) ok = false;
          if (e && (!joinedISO || joinedISO > e)) ok = false;
        }
        var presentCount = parseInt(row.getAttribute('data-present') || '0', 10);
        var totalCount = parseInt(row.getAttribute('data-total') || '0', 10);
        if (stat === 'any_present' && presentCount <= 0) ok = false;
        if (stat === 'fully_present' && !(totalCount > 0 && presentCount === totalCount)) ok = false;
        if (stat === 'fully_absent' && !(totalCount > 0 && presentCount === 0)) ok = false;
        if (stat === 'partial' && !(presentCount > 0 && presentCount < totalCount)) ok = false;
      }
      row.style.display = ok ? '' : 'none';
    });
  }
  function downloadCSV(){
    if (!tbody) return;
    var rows = Array.from(tbody.querySelectorAll('tr')).filter(function(r){ return r.style.display !== 'none'; });
    var data = [['Event','Joined On','Status','Attendance']];
    rows.forEach(function(r){
      var cells = r.querySelectorAll('td');
      if (cells.length >= 4) {
        var eventName = cells[0].innerText.replace(/\s+/g,' ').trim();
        var joined = cells[1].innerText.trim();
        var status = cells[2].innerText.replace(/\s+/g,' ').trim();
        var attendance = cells[3].innerText.trim();
        data.push([eventName, joined, status, attendance]);
      }
    });
    var csv = data.map(function(row){
      return row.map(function(field){
        var v = ('' + field).replace(/"/g, '""');
        return '"' + v + '"';
      }).join(',');
    }).join('\r\n');
    var id = dlBtn ? (dlBtn.getAttribute('data-student-id') || 'student') : 'student';
    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'student-' + id + '-events.csv';
    document.body.appendChild(a);
    a.click();
    setTimeout(function(){ URL.revokeObjectURL(url); a.remove(); }, 0);
  }
  if (search) search.addEventListener('input', applyFilters);
  if (start) start.addEventListener('change', function(){ if (period) period.value = 'custom'; setDatesDisabled(false); applyFilters(); });
  if (end) end.addEventListener('change', function(){ if (period) period.value = 'custom'; setDatesDisabled(false); applyFilters(); });
  if (attendanceSel) attendanceSel.addEventListener('change', applyFilters);
  if (dateMode) dateMode.addEventListener('change', applyFilters);
  if (period) period.addEventListener('change', applyPeriod);
  if (dlBtn) dlBtn.addEventListener('click', downloadCSV);
  applyPeriod();
})();
</script>
@endpush
