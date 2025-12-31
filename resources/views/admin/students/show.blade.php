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
                        <p class="text-muted small mb-0">{{ $student->student_number }}</p>
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
</div>
@endsection
