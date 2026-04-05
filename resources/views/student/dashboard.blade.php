@extends('layouts.student')

@section('title', 'Student Dashboard || Holo Board')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="fw-bold mb-1">Welcome back, {{ Auth::guard('student')->user()->student->first_name ?? 'Student' }}!</h4>
                            <p class="text-muted mb-0">Here's what's happening with your account today.</p>
                        </div>
                        <div class="d-none d-sm-block">
                            <span class="badge bg-soft-primary text-primary fs-12 fw-medium px-3 py-2">
                                <i class="feather-calendar me-1"></i> {{ now()->format('D, M d, Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-12">
                            <!-- Nav tabs -->
                            <div class="d-sm-none mb-3">
                                <select id="mobileTabsSelect" class="form-select">
                                    <option value="events" selected>Events & Projects</option>
                                    <option value="lost-found">Lost & Found</option>
                                    <option value="announcements">Announcements</option>
                                    <option value="analytics">Analytics</option>
                                </select>
                            </div>
                            <ul class="nav nav-pills custom-tabs-pill mb-4 d-none d-sm-flex" id="dashboardTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" role="tab" aria-controls="events" aria-selected="true">
                                        <i class="feather-calendar me-2"></i>Events & Projects
                                        @php $eventsCount = $events->count(); @endphp
                                        @if($eventsCount > 0)
                                            <span id="events-badge" class="badge bg-soft-primary text-primary ms-2">{{ $eventsCount }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="lost-found-tab" data-bs-toggle="tab" data-bs-target="#lost-found" type="button" role="tab" aria-controls="lost-found" aria-selected="false">
                                        <i class="feather-search me-2"></i>Lost & Found
                                        @php $lostFoundCount = $lostAndFoundItems->count(); @endphp
                                        @if($lostFoundCount > 0)
                                            <span id="lost-found-badge" class="badge bg-soft-info text-info ms-2">{{ $lostFoundCount }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements" type="button" role="tab" aria-controls="announcements" aria-selected="false">
                                        <i class="feather-bell me-2"></i>Announcements
                                        @php $announcementCount = $announcements->count(); @endphp
                                        @if($announcementCount > 0)
                                            <span id="announcements-badge" class="badge bg-soft-danger text-danger ms-2">{{ $announcementCount }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab" aria-controls="analytics" aria-selected="false">
                                        <i class="feather-pie-chart me-2"></i>Analytics
                                    </button>
                                </li>
                            </ul>
                            
                            <div id="events-meta" data-latest-id="{{ optional($events->first())->id ?? 0 }}" data-student-id="{{ Auth::guard('student')->user()->student->id ?? 0 }}" style="display:none"></div>
                            <div id="lost-found-meta" data-latest-id="{{ optional($lostAndFoundItems->first())->id ?? 0 }}" data-student-id="{{ Auth::guard('student')->user()->student->id ?? 0 }}" style="display:none"></div>
                            <div id="announcements-meta" data-latest-id="{{ optional($announcements->first())->id ?? 0 }}" data-student-id="{{ Auth::guard('student')->user()->student->id ?? 0 }}" style="display:none"></div>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- Events Pane -->
                                <div class="tab-pane fade show active" id="events" role="tabpanel" aria-labelledby="events-tab">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="mb-0">New Events & Projects</h5>
                                        <a href="{{ url('/') }}" class="btn btn-sm btn-link text-primary p-0">View All</a>
                                    </div>
                                    <div class="row g-4">
                                        @forelse($events as $event)
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="card border border-light-subtle shadow-none rounded-4 h-100 mb-0">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        @if($event->image)
                                                            <img src="{{ $event->image_url }}" alt="" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-3">
                                                                <i class="feather-calendar"></i>
                                                            </div>
                                                        @endif
                                                        <div class="overflow-hidden">
                                                            <h6 class="text-truncate mb-0">{{ $event->name }}</h6>
                                                            <small class="text-muted">{{ $event->location }}</small>
                                                        </div>
                                                    </div>
                                                    <p class="text-muted fs-13 mb-3 text-truncate-2-line">{{ Str::limit(strip_tags($event->description), 80) }}</p>
                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center mb-1 text-dark fs-13">
                                                            <i class="feather-calendar me-2 text-primary"></i>
                                                            @if($event->eventDates->count() > 1)
                                                                <span class="fw-medium">Multi-day Event</span>
                                                            @else
                                                                <span class="fw-medium">{{ \Carbon\Carbon::parse($event->eventDates->first()->date)->format('M d, Y') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted fs-12">
                                                            <i class="feather-clock me-2"></i>
                                                            @if($event->eventDates->first())
                                                                {{ \Carbon\Carbon::parse($event->eventDates->first()->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->eventDates->first()->end_time)->format('g:i A') }}
                                                            @else
                                                                TBA
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                                        @php 
                                                            $isJoined = $event->students()->where('student_id', Auth::guard('student')->user()->student->id)->exists();
                                                            $lastDate = $event->eventDates->sortByDesc('date')->first();
                                                            $isPast = $lastDate ? \Carbon\Carbon::parse($lastDate->date)->endOfDay()->isPast() : false;
                                                        @endphp
                                                        
                                                        @if($isPast)
                                                            <div class="w-100 text-center">
                                                                <span class="badge bg-soft-secondary text-secondary px-4 py-2 rounded-pill">
                                                                    <i class="feather-clock me-1"></i> Event Ended
                                                                </span>
                                                            </div>
                                                        @elseif($isJoined)
                                                            @php
                                                                $registration = $event->registrations()->where('student_id', Auth::guard('student')->user()->student->id)->first();
                                                                $isTodayEvent = $event->eventDates()->whereDate('date', now()->toDateString())->exists();
                                                                $canScan = false;
                                                                $eventDate = null;
                                                                $openingTime = null;
                                                                
                                                                if ($isTodayEvent) {
                                                                    $eventDate = $event->eventDates()->whereDate('date', now()->toDateString())->first();
                                                                    if ($eventDate->start_time) {
                                                                        $buffer = $event->attendance_start_buffer ?? 0;
                                                                        $startTime = \Carbon\Carbon::parse($eventDate->date . ' ' . $eventDate->start_time);
                                                                        $openingTime = $startTime->copy()->subMinutes($buffer);
                                                                        $canScan = now()->gte($openingTime);
                                                                    } else {
                                                                        $canScan = true;
                                                                    }
                                                                }
                                                            @endphp
                                                            <div class="w-100 flex-column">
                                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                                    <span class="badge bg-soft-success text-success px-3">Joined</span>
                                                                    <button type="button" 
                                                                            class="btn btn-sm {{ $canScan ? 'btn-primary' : 'btn-light' }} rounded-pill px-3 show-qr"
                                                                            data-event-name="{{ $event->name }}"
                                                                            data-qr-uuid="{{ $registration->uuid ?? '' }}"
                                                                            {{ $canScan ? '' : 'disabled' }}>
                                                                        <i class="feather-grid me-1"></i> QR Code
                                                                    </button>
                                                                </div>
                                                                
                                                                @if(!$canScan && $isTodayEvent && $openingTime)
                                                                    <div class="alert alert-soft-warning p-1 mb-0 mt-1 border-0 text-center" style="font-size: 10px;">
                                                                        <i class="feather-info me-1"></i> QR will be available at {{ $openingTime->format('g:i A') }}
                                                                    </div>
                                                                @elseif(!$canScan && !$isTodayEvent)
                                                                    <div class="text-muted text-center" style="font-size: 10px;">
                                                                        <i class="feather-lock me-1"></i> QR available on event day
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="fs-12 text-primary fw-medium">Open for Join</span>
                                                            <form action="{{ route('student.events.join', $event) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-xs btn-primary rounded-pill px-4 shadow-sm">Join Now</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="col-12 text-center py-5">
                                            <i class="feather-calendar fs-1 text-muted mb-3 d-block"></i>
                                            <p class="text-muted">No new events or projects this month.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Lost & Found Pane -->
                                <div class="tab-pane fade" id="lost-found" role="tabpanel" aria-labelledby="lost-found-tab">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="mb-0">Lost & Found</h5>
                                        <div class="d-flex gap-2 align-items-center">
                                            <a href="{{ route('lost-and-found.create') }}" class="btn btn-sm btn-primary py-1 px-3 rounded-pill fs-11">Report Item</a>
                                            <a href="{{ route('lost-and-found.index') }}" class="btn btn-sm btn-link text-primary p-0 ms-2">Browse All</a>
                                        </div>
                                    </div>
                                    <div class="row g-4">
                                        @forelse($lostAndFoundItems as $item)
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="card border border-light-subtle shadow-none rounded-4 h-100 mb-0">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="badge bg-{{ $item->type == 'lost' ? 'danger' : 'primary' }}-subtle text-{{ $item->type == 'lost' ? 'danger' : 'primary' }} text-uppercase fs-10 px-2">
                                                            {{ $item->type }}
                                                        </span>
                                                        <small class="text-muted">{{ $item->date_reported->diffForHumans() }}</small>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        @if($item->image_path)
                                                            <img src="{{ $item->image_url }}" alt="" class="rounded-3" style="width: 45px; height: 45px; object-fit: cover;">
                                                        @else
                                                            <div class="avatar-text avatar-sm bg-light text-muted rounded-3">
                                                                <i class="feather-image"></i>
                                                            </div>
                                                        @endif
                                                        <div class="overflow-hidden">
                                                            <h6 class="text-truncate mb-0">{{ $item->item_name }}</h6>
                                                            <small class="text-muted"><i class="feather-map-pin me-1"></i>{{ $item->location }}</small>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('lost-and-found.show', $item) }}" class="btn btn-xs btn-outline-primary w-100 rounded-pill">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="col-12 text-center py-5">
                                            <i class="feather-search fs-1 text-muted mb-3 d-block"></i>
                                            <p class="text-muted">No active reports.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Announcements Pane -->
                                <div class="tab-pane fade" id="announcements" role="tabpanel" aria-labelledby="announcements-tab">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="mb-0">Announcements</h5>
                                    </div>
                                    <div class="row g-4">
                                        @forelse($announcements as $announcement)
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="card border border-light-subtle shadow-none rounded-4 h-100 mb-0"
                                                 data-announcement="true"
                                                 data-title="{{ $announcement->title }}"
                                                 data-body="{{ $announcement->body }}"
                                                 data-image="{{ $announcement->image_url }}"
                                                 data-created="{{ $announcement->created_at->format('M d, Y') }}"
                                                 data-attachments="{{ json_encode($announcement->attachments->map(function($a) { 
                                                     return [
                                                         'name' => $a->file_name,
                                                         'url' => $a->file_url,
                                                         'type' => $a->file_type,
                                                         'size' => number_format($a->file_size / 1024, 2) . ' KB'
                                                     ];
                                                 })) }}"
                                                 role="button" tabindex="0">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        @if($announcement->image)
                                                            <img src="{{ $announcement->image_url }}" alt="" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="avatar-text avatar-md bg-soft-warning text-warning rounded-3">
                                                                <i class="feather-bell"></i>
                                                            </div>
                                                        @endif
                                                        <div class="overflow-hidden">
                                                            <h6 class="text-truncate mb-0">{{ $announcement->title }}</h6>
                                                            <small class="text-muted">
                                                                {{ $announcement->created_at->format('M d, Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <p class="text-muted fs-13 mb-3 text-truncate-2-line">{{ Str::limit(strip_tags($announcement->body), 100) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="col-12 text-center py-5">
                                            <i class="feather-bell fs-1 text-muted mb-3 d-block"></i>
                                            <p class="text-muted">No announcements available.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="announcementModalTitle"></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="announcementModalDates" class="text-muted small mb-3 text-end"></div>
                                                <div id="announcementModalImageWrap" class="mb-3 text-center" style="display:none;">
                                                    <img id="announcementModalImage" src="" alt="" class="img-fluid rounded-3" style="max-height: 400px; width: 100%; object-fit: cover;" />
                                                </div>
                                                <div id="announcementModalBodyText" class="fs-6 mb-4"></div>
                                                <div id="announcementModalAttachments" style="display:none;">
                                                    <h6 class="mb-3">Downloadable Files:</h6>
                                                    <div id="attachmentsList" class="row g-2"></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Analytics Pane -->
                                <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="mb-0">My Analytics</h5>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col-xxl-3 col-md-6">
                                            <div class="card border border-light-subtle shadow-none rounded-4">
                                                <div class="card-body">
                                                    <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-3 mb-3">
                                                        <i class="feather-calendar"></i>
                                                    </div>
                                                    <h6 class="text-muted mb-1">Events Joined</h6>
                                                    <h4 class="mb-0">{{ $analytics['total_events_joined'] }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-6">
                                            <div class="card border border-light-subtle shadow-none rounded-4">
                                                <div class="card-body">
                                                    <div class="avatar-text avatar-md bg-soft-info text-info rounded-3 mb-3">
                                                        <i class="feather-check-circle"></i>
                                                    </div>
                                                    <h6 class="text-muted mb-1">Attendances</h6>
                                                    <h4 class="mb-0">{{ $analytics['total_attendances'] }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-6">
                                            <div class="card border border-light-subtle shadow-none rounded-4">
                                                <div class="card-body">
                                                    <div class="avatar-text avatar-md bg-soft-warning text-warning rounded-3 mb-3">
                                                        <i class="feather-award"></i>
                                                    </div>
                                                    <h6 class="text-muted mb-1">Certificates</h6>
                                                    <h4 class="mb-0">{{ $analytics['total_certificates'] }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-6">
                                            <div class="card border border-light-subtle shadow-none rounded-4">
                                                <div class="card-body">
                                                    <div class="avatar-text avatar-md bg-soft-success text-success rounded-3 mb-3">
                                                        <i class="feather-search"></i>
                                                    </div>
                                                    <h6 class="text-muted mb-1">Reports Made</h6>
                                                    <h4 class="mb-0">{{ $analytics['total_reports'] }} ({{ $analytics['resolved_reports'] }} Resolved)</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border border-light-subtle shadow-none rounded-4 mt-4">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3">Recent Activity Summary</h6>
                                            <p class="text-muted small">You have participated in <strong>{{ $analytics['total_events_joined'] }}</strong> events and successfully claimed <strong>{{ $analytics['total_certificates'] }}</strong> certificates. Your contribution to the Lost & Found community includes <strong>{{ $analytics['total_reports'] }}</strong> reports.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-4">
                    <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-circle mb-3 mx-auto">
                        <i class="feather-grid"></i>
                    </div>
                    <h4 class="fw-bold mb-1" id="qrModalEventName">Event Name</h4>
                    <p class="text-muted small">Present this QR code to the scanner</p>
                </div>
                
                <div class="d-inline-block p-4 bg-white border rounded-4 shadow-sm mb-4">
                    <div id="qrPrintArea">
                        <div class="mb-3 d-none d-print-block">
                            <h5 class="mb-1 text-dark" id="qrPrintEventName">Event Name</h5>
                            <p class="small text-muted mb-0">{{ Auth::guard('student')->user()->student->first_name }} {{ Auth::guard('student')->user()->student->last_name }}</p>
                        </div>
                        <div id="qrPlaceholder" class="qr-responsive d-flex align-items-center justify-content-center" style="min-width: 250px; min-height: 250px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="mt-3 d-none d-print-block">
                            <p class="small fw-bold text-primary mb-0">HOLO BOARD ATTENDANCE</p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-soft-info text-start small mx-3">
                    <i class="feather-info me-2"></i> This QR code is unique to this event and your registration.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center pb-4 flex-column gap-2">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary rounded-pill px-3" onclick="printEventQR()">
                        <i class="feather-printer me-1"></i> Print
                    </button>
                    <button type="button" class="btn btn-primary rounded-pill px-3" onclick="downloadEventQR()">
                        <i class="feather-download me-1"></i> Download
                    </button>
                </div>
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #qrPrintArea, #qrPrintArea * {
            visibility: visible;
        }
        #qrPrintArea {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function printEventQR() {
    window.print();
}

function downloadEventQR() {
    const element = document.getElementById('qrPrintArea');
    const eventName = document.getElementById('qrModalEventName').textContent;
    
    // Ensure all styles are applied before capture
    html2canvas(element, {
        backgroundColor: '#ffffff',
        scale: 2, // Higher quality
        logging: false
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `attendance-qr-${eventName.toLowerCase().replace(/\s+/g, '-')}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Toastr Configuration
  if (typeof toastr !== 'undefined') {
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "timeOut": "3000"
    };
  }

  var mobileSelect = document.getElementById('mobileTabsSelect');
  function showTabByKey(key) {
    var btn = document.getElementById(key + '-tab');
    if (!btn) return;
    var tab = new bootstrap.Tab(btn);
    tab.show();
  }
  if (mobileSelect) {
    mobileSelect.addEventListener('change', function() {
      showTabByKey(this.value);
    });
  }
  var tabsList = document.getElementById('dashboardTabs');
  if (tabsList && mobileSelect) {
    tabsList.addEventListener('click', function(e) {
      var btn = e.target.closest('button[role="tab"]');
      if (!btn) return;
      var key = btn.id.replace('-tab', '');
      mobileSelect.value = key;
    });
  }
  ['events', 'lost-found', 'announcements'].forEach(function(key) {
    var t = document.getElementById(key + '-tab');
    var b = document.getElementById(key + '-badge');
    var m = document.getElementById(key + '-meta');
    if (!t || !b || !m) return;
    var latestId = parseInt(m.dataset.latestId || '0', 10);
    var studentId = parseInt(m.dataset.studentId || '0', 10);
    var storageKey = 'hb_session_hide_badge_' + key + '_' + studentId + '_' + latestId;
    if (sessionStorage.getItem(storageKey) === '1') {
      b.style.display = 'none';
    }
    var hide = function() {
      b.style.display = 'none';
      sessionStorage.setItem(storageKey, '1');
    };
    t.addEventListener('shown.bs.tab', hide);
    t.addEventListener('click', hide);
  });

  // Handle tab activation from URL query parameter
  const urlParams = new URLSearchParams(window.location.search);
  const activeTab = urlParams.get('tab');

  if (activeTab) {
    const targetTabButton = document.getElementById(activeTab + '-tab');
    if (targetTabButton) {
      // Deactivate currently active tab (Events & Projects by default)
      const currentActiveButton = document.querySelector('#dashboardTabs .nav-link.active');
      const currentActivePane = document.querySelector('.tab-content .tab-pane.show.active');

      if (currentActiveButton) {
        currentActiveButton.classList.remove('active');
        currentActiveButton.setAttribute('aria-selected', 'false');
      }
      if (currentActivePane) {
        currentActivePane.classList.remove('show', 'active');
      }

      // Activate the target tab
      targetTabButton.classList.add('active');
      targetTabButton.setAttribute('aria-selected', 'true');
      const targetPane = document.getElementById(activeTab);
      if (targetPane) {
        targetPane.classList.add('show', 'active');
      }

      // Update mobile select if it exists
      if (mobileSelect) {
        mobileSelect.value = activeTab;
      }
    }
  }

  var modalEl = document.getElementById('announcementModal');
  var modalTitle = document.getElementById('announcementModalTitle');
  var modalBody = document.getElementById('announcementModalBodyText');
  var modalDates = document.getElementById('announcementModalDates');
  var modalImgWrap = document.getElementById('announcementModalImageWrap');
  var modalImg = document.getElementById('announcementModalImage');
  var attachmentsContainer = document.getElementById('announcementModalAttachments');
  var attachmentsList = document.getElementById('attachmentsList');
  var clickable = document.querySelectorAll('[data-announcement="true"]');
  if (modalEl && modalTitle && modalBody && modalDates) {
    if (modalEl.parentNode !== document.body) {
      document.body.appendChild(modalEl);
    }
    clickable.forEach(function(card) {
      card.addEventListener('click', function() {
        var title = card.getAttribute('data-title') || '';
        var body = card.getAttribute('data-body') || '';
        var image = card.getAttribute('data-image') || '';
        var created = card.getAttribute('data-created') || '';
        var attachmentsRaw = card.getAttribute('data-attachments') || '[]';
        var attachments = JSON.parse(attachmentsRaw);

        modalTitle.textContent = title;
        modalBody.innerHTML = body;
        modalDates.textContent = 'Posted on: ' + created;

        if (image) {
          modalImg.src = image;
          modalImgWrap.style.display = '';
        } else {
          modalImg.src = '';
          modalImgWrap.style.display = 'none';
        }

        // Clear and fill attachments
        attachmentsList.innerHTML = '';
        if (attachments.length > 0) {
          attachmentsContainer.style.display = '';
          attachments.forEach(function(att) {
            var col = document.createElement('div');
            col.className = 'col-md-6 mb-2';
            col.innerHTML = `
              <div class="card bg-light border-0">
                <div class="card-body p-2">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center overflow-hidden">
                      <div class="avatar-text avatar-xs bg-primary text-white rounded me-2 flex-shrink-0">
                        <i class="feather-file fs-12"></i>
                      </div>
                      <div class="overflow-hidden">
                        <h6 class="mb-0 text-truncate small">${att.name}</h6>
                        <small class="text-muted text-uppercase fs-10">${att.type} • ${att.size}</small>
                      </div>
                    </div>
                    <a href="${att.url}" target="_blank" class="btn btn-xs btn-outline-primary ms-2" download>
                      <i class="feather-download"></i>
                    </a>
                  </div>
                </div>
              </div>
            `;
            attachmentsList.appendChild(col);
          });
        } else {
          attachmentsContainer.style.display = 'none';
        }

        var modal = new bootstrap.Modal(modalEl);
        modal.show();
      });
    });
  }

  // Handle QR Modal
  const qrModalEl = document.getElementById('qrModal');
  const qrPlaceholder = document.getElementById('qrPlaceholder');
  const qrEventName = document.getElementById('qrModalEventName');
  const qrButtons = document.querySelectorAll('.show-qr');
  
  if (qrModalEl) {
      if (qrModalEl.parentNode !== document.body) {
          document.body.appendChild(qrModalEl);
      }
      const qrModal = new bootstrap.Modal(qrModalEl);
      
      qrButtons.forEach(btn => {
          btn.addEventListener('click', function() {
              const uuid = this.getAttribute('data-qr-uuid');
              const eventName = this.getAttribute('data-event-name');
              
              if (!uuid) {
                  toastr.error('Registration UUID not found. Please contact admin.', 'Error');
                  return;
              }
              
              qrEventName.textContent = eventName;
              if (document.getElementById('qrPrintEventName')) {
                  document.getElementById('qrPrintEventName').textContent = eventName;
              }
              qrPlaceholder.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
              
              qrModal.show();
              
              // Load the QR code via our local route using a safer route builder
              const qrUrl = "{{ route('student.qr.generate', ['uuid' => ':uuid']) }}".replace(':uuid', uuid);
              
              fetch(qrUrl)
                  .then(response => {
                      if (!response.ok) throw new Error('Failed to load QR code.');
                      return response.text();
                  })
                  .then(svg => {
                      qrPlaceholder.innerHTML = svg;
                  })
                  .catch(err => {
                      console.error(err);
                      qrPlaceholder.innerHTML = '<p class="text-danger">Failed to load QR code.</p>';
                  });
          });
      });
  }
});
</script>
@endpush
