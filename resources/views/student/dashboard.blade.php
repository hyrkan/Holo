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
                            <ul class="nav nav-pills custom-tabs-pill mb-4" id="dashboardTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" role="tab" aria-controls="events" aria-selected="true">
                                        <i class="feather-calendar me-2"></i>Events & Projects
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="lost-found-tab" data-bs-toggle="tab" data-bs-target="#lost-found" type="button" role="tab" aria-controls="lost-found" aria-selected="false">
                                        <i class="feather-search me-2"></i>Lost & Found
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab" aria-controls="analytics" aria-selected="false">
                                        <i class="feather-pie-chart me-2"></i>Analytics
                                    </button>
                                </li>
                            </ul>

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
                                                            <img src="{{ asset('storage/' . $event->image) }}" alt="" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
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
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('lost-and-found.index') }}" class="btn btn-sm btn-link text-primary p-0">Browse All</a>
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
                                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="" class="rounded-3" style="width: 45px; height: 45px; object-fit: cover;">
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
