@extends('layouts.student')

@section('title', 'Events Joined || Holo Board')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Events Joined</h5>
                    <a href="{{ url('/') }}" class="btn btn-sm btn-primary">
                        <i class="feather-plus me-2"></i> Browse More Events
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Date(s)</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th class="text-end">Certificates</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-image avatar-md me-3">
                                                    <img src="{{ $event->image_url }}" alt="" class="img-fluid rounded">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $event->name }}</h6>
                                                    <small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($event->eventDates as $eventDate)
                                                <span class="badge bg-soft-info text-info d-block mb-1">
                                                    {{ \Carbon\Carbon::parse($eventDate->date)->format('M d, Y') }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <small><i class="feather-map-pin me-1"></i> {{ $event->location }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-success text-success">Joined</span>
                                        </td>
                                        <td class="text-end">
                                            @php
                                                $awardedCerts = $event->certificates->filter(function($cert) use ($awardedCertificateIds) {
                                                    return in_array($cert->id, $awardedCertificateIds);
                                                });
                                            @endphp
                                            
                                            @if($awardedCerts->count() > 0)
                                                <div class="d-flex flex-column align-items-end gap-2">
                                                    @foreach($awardedCerts as $awardedCert)
                                                        <a href="{{ route('student.events.certificate.download', $awardedCert) }}" target="_blank" class="btn btn-sm btn-info w-100 text-nowrap">
                                                            <i class="feather-download me-1"></i> Download {{ $awardedCert->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted small">Not Available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="avatar-text avatar-xl bg-soft-warning text-warning rounded-circle mb-3 mx-auto">
                                                <i class="feather-alert-circle fs-2"></i>
                                            </div>
                                            <h5>No Events Joined Yet</h5>
                                            <p class="text-muted">You haven't registered for any events yet. Browse our upcoming events to get started!</p>
                                            <a href="{{ url('/') }}" class="btn btn-primary mt-3">Browse Events</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($events->hasPages())
                    <div class="card-footer">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
