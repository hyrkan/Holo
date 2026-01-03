@extends('layouts.admin')

@section('title', 'Attendance Sheet || Holo Board')

@push('styles')
<style>
    .attendance-cell {
        width: 100px;
        text-align: center;
        vertical-align: middle !important;
    }
    .present-box {
        color: #10b981;
        font-size: 1.2rem;
    }
    .absent-box {
        color: #ef4444;
        font-size: 1.2rem;
    }
    .table-responsive {
        max-height: 70vh;
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
                                                    $wasPresent = $student->attendances->where('event_date_id', $date->id)->first();
                                                    if($wasPresent) $presentCount++;
                                                @endphp
                                                @if($wasPresent)
                                                    <span class="present-box" title="Scanned at: {{ \Carbon\Carbon::parse($wasPresent->scanned_at)->format('h:i A') }}">
                                                        <i class="feather-check-circle"></i>
                                                    </span>
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
