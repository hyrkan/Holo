@extends('layouts.admin')

@section('title', 'Attendance Sheet || Holo Board')

@push('styles')
<style>
    .attendance-cell {
        min-width: 150px;
        text-align: center;
        vertical-align: middle !important;
    }
    .attendance-time {
        font-size: 0.75rem;
        display: block;
        color: #6c757d;
    }
    .photo-preview {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .photo-preview:hover {
        transform: scale(1.1);
    }
    .present-box {
        color: #10b981;
        font-size: 1.1rem;
    }
    .absent-box {
        color: #ef4444;
        font-size: 1.2rem;
    }
    .sticky-col {
        position: sticky;
        left: 0;
        background-color: white !important;
        z-index: 2;
        border-right: 2px solid #dee2e6 !important;
    }
    thead th.sticky-col {
        z-index: 3;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Attendance Sheet: {{ $event->name }}</h5>
                        <p class="text-muted small mb-0">Tracking attendance across all scheduled dates.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-light btn-sm">
                            <i class="feather-arrow-left me-2"></i> Back to Event
                        </a>
                        <button class="btn btn-primary btn-sm" onclick="window.print()">
                            <i class="feather-printer me-2"></i> Print Sheet
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs nav-tabs-custom px-4 pt-3 border-bottom-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.events.participants', $event) }}">
                                <i class="feather-users me-2"></i>Registration List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.events.attendance', $event) }}">
                                <i class="feather-check-square me-2"></i>Attendance Sheet
                            </a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th class="sticky-col" style="min-width: 250px;">Student Name</th>
                                    @foreach($dates as $date)
                                        <th class="attendance-cell">
                                            <div class="small fw-normal text-muted">{{ \Carbon\Carbon::parse($date->date)->format('D') }}</div>
                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($date->date)->format('M d') }}</div>
                                            <div class="small text-muted" style="font-size: 10px;">Clock In / Out</div>
                                        </th>
                                    @endforeach
                                    <th class="text-center fw-bold bg-soft-primary">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($participants as $student)
                                    <tr>
                                        <td class="sticky-col">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-text avatar-sm bg-soft-primary text-primary rounded-circle me-3">
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fs-13">{{ $student->first_name }} {{ $student->last_name }}</h6>
                                                    <small class="text-muted">{{ $student->student_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        @php $presentCount = 0; @endphp
                                        @foreach($dates as $date)
                                            <td class="attendance-cell">
                                                @php
                                                    $attendance = $student->attendances->where('event_date_id', $date->id)->first();
                                                    if($attendance) $presentCount++;
                                                @endphp
                                                @if($attendance)
                                                    <div class="d-flex flex-column align-items-center">
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            @if($attendance->photo)
                                                                <img src="{{ asset('storage/' . $attendance->photo) }}" 
                                                                     class="photo-preview" 
                                                                     alt="Student Photo"
                                                                     onclick="showPhoto('{{ asset('storage/' . $attendance->photo) }}', '{{ $student->first_name }} {{ $student->last_name }}')"
                                                                >
                                                            @endif
                                                            <span class="present-box" title="Recorded">
                                                                <i class="feather-check-circle"></i>
                                                            </span>
                                                        </div>
                                                        <div class="attendance-time">
                                                            @if($attendance->clock_in)
                                                                <span class="badge bg-soft-success text-success p-1" style="font-size: 9px;">
                                                                    IN: {{ $attendance->clock_in->format('h:i A') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="attendance-time mt-1">
                                                            @if($attendance->clock_out)
                                                                <span class="badge bg-soft-info text-info p-1" style="font-size: 9px;">
                                                                    OUT: {{ $attendance->clock_out->format('h:i A') }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-soft-secondary text-secondary p-1" style="font-size: 9px;">
                                                                    OUT: --:--
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="absent-box">
                                                        <i class="feather-x-circle opacity-25"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-center fw-bold bg-soft-primary">
                                            {{ $presentCount }} / {{ $dates->count() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $dates->count() + 2 }}" class="text-center py-5 text-muted">
                                            No students registered for this event.
                                        </td>
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

@push('scripts')
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalTitle">Student Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalPhoto" class="img-fluid rounded shadow" alt="Attendance Photo">
            </div>
        </div>
    </div>
</div>

<script>
function showPhoto(url, name) {
    document.getElementById('modalPhoto').src = url;
    document.getElementById('photoModalTitle').innerText = 'Clock-in Photo: ' + name;
    new bootstrap.Modal(document.getElementById('photoModal')).show();
}
</script>
@endpush
