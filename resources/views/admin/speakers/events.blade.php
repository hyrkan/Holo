@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Events for Speaker: {{ $speaker->first_name }} {{ $speaker->last_name }}</h5>
                        <p class="text-muted small mb-0">{{ $speaker->title }} @if($speaker->company) at {{ $speaker->company }} @endif</p>
                    </div>
                    <a href="{{ route('admin.speakers.index') }}" class="btn btn-light btn-sm">
                        <i class="feather-arrow-left me-2"></i> Back to Speakers
                    </a>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Event Info</th>
                                    <th>Location</th>
                                    <th>Dates</th>
                                    <th>Target</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-image">
                                                <img src="{{ $event->image_url }}" alt="" class="img-fluid" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                            </div>
                                            <a href="{{ route('admin.events.show', $event) }}">
                                                <span class="d-block fw-bold">{{ $event->name }}</span>
                                                <span class="fs-12 d-block fw-normal text-muted">{{ Str::limit($event->description, 50) }}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="feather-map-pin me-1 text-muted"></i>
                                        {{ $event->location }}
                                    </td>
                                    <td>
                                        @php
                                            $allDates = $event->eventDates->sortBy('date');
                                            $hasFutureDate = $allDates->contains(function($d) {
                                                return \Carbon\Carbon::parse($d->date)->isFuture() || \Carbon\Carbon::parse($d->date)->isToday();
                                            });
                                        @endphp
                                        @if($allDates->count() > 0)
                                            @if(!$hasFutureDate)
                                                <span class="text-muted" data-bs-toggle="tooltip" data-bs-html="true" title="Past Dates:<br/>@foreach($allDates as $d){{ \Carbon\Carbon::parse($d->date)->format('M d, Y') }}<br/>@endforeach">event ended</span>
                                            @else
                                                @foreach($allDates as $eventDate)
                                                    @php $isPast = \Carbon\Carbon::parse($eventDate->date)->isPast() && !\Carbon\Carbon::parse($eventDate->date)->isToday(); @endphp
                                                    <span class="badge @if($isPast) bg-soft-secondary text-secondary @else bg-soft-primary text-primary @endif" @if($isPast) style="text-decoration: line-through;" @endif>
                                                        {{ \Carbon\Carbon::parse($eventDate->date)->format('M d, Y') }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        @else
                                            <span class="text-muted">No dates</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$event->departments || in_array('All', $event->departments))
                                            <span class="badge bg-soft-success text-success">All</span>
                                        @else
                                            @foreach($event->departments as $dept)
                                                <span class="badge bg-soft-info text-info me-1">{{ $dept }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="hstack gap-3 justify-content-end">
                                            <a href="{{ route('admin.events.show', $event) }}" class="text-secondary" data-bs-toggle="tooltip" title="View Detail">
                                                <i class="feather-eye fs-16"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="feather-calendar fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No events found for this speaker.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
