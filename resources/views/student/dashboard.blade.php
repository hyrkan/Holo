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
                                                    <p class="text-muted fs-13 mb-3 text-truncate-2-line">{{ Str::limit($event->description, 80) }}</p>
                                                    <div class="d-flex align-items-center justify-content-between mt-auto">
                                                        <span class="fs-12 text-muted">
                                                            <i class="feather-clock me-1"></i> 
                                                            @if($event->eventDates->first())
                                                                {{ \Carbon\Carbon::parse($event->eventDates->first()->date)->format('M d, Y') }}
                                                            @endif
                                                        </span>
                                                        @php 
                                                            $isJoined = $event->students()->where('student_id', Auth::guard('student')->user()->student->id)->exists();
                                                            $lastDate = $event->eventDates->sortByDesc('date')->first();
                                                            $isPast = $lastDate ? \Carbon\Carbon::parse($lastDate->date)->endOfDay()->isPast() : false;
                                                        @endphp
                                                        @if($isJoined)
                                                            <span class="badge bg-soft-success text-success">Joined</span>
                                                        @elseif($isPast)
                                                            <button type="button" class="btn btn-xs btn-secondary rounded-pill px-3" disabled>Ended</button>
                                                        @else
                                                            <form action="{{ route('student.events.join', $event) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-xs btn-primary rounded-pill px-3">Join</button>
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
                                                    <p class="text-muted fs-13 mb-3 text-truncate-2-line">{{ Str::limit($announcement->body, 100) }}</p>
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
                                                <div id="announcementModalBodyText" class="fs-6 mb-4" style="white-space: pre-wrap;"></div>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
        modalBody.textContent = body;
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
});
</script>
@endpush
