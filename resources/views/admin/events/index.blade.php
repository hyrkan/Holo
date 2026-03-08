@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Events</h5>
                    <div class="card-header-action">
                        <form action="{{ route('admin.events.index') }}" method="GET" class="me-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search event..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit"><i class="feather-search"></i></button>
                            </div>
                        </form>
                        <a id="exportEventsCsvBtn" href="{{ route('admin.events.export') }}" class="btn btn-light me-2">
                            <i class="feather-download me-2"></i> Download CSV
                        </a>
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Create Event
                        </a>
                    </div>
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
                                    <th>Capacity</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-image">
                                                <img src="{{ $event->image ? asset('storage/'.$event->image) : asset('assets/images/no-image.png') }}" alt="" class="img-fluid" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
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
                                    <td>
                                        @if($event->capacity)
                                            <span class="fw-bold">{{ $event->capacity }}</span>
                                        @else
                                            <span class="text-muted">Unlimited</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="hstack gap-3 justify-content-end">
                                            <a href="{{ route('admin.events.show', $event) }}" class="text-secondary" data-bs-toggle="tooltip" title="View">
                                                <i class="feather-eye fs-16"></i>
                                            </a>
                                            <a href="{{ route('admin.events.edit', $event) }}" class="text-secondary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="feather-edit-3 fs-16"></i>
                                            </a>
                                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger border-0 bg-transparent p-0" onclick="return confirm('Are you sure?')" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="feather-trash-2 fs-16"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="feather-calendar fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No events found.</p>
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
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var btn = document.getElementById('exportEventsCsvBtn');
  var searchInput = document.querySelector('input[name="search"]');
  if (btn && searchInput) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var url = btn.getAttribute('href');
      var q = searchInput.value.trim();
      if (q) {
        url += (url.indexOf('?') === -1 ? '?' : '&') + 'search=' + encodeURIComponent(q);
      }
      window.location.href = url;
    });
  }
});
</script>
@endpush
@endsection
