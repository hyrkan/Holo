@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title">Event Details</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning"><i class="feather-edit me-2"></i> Edit Event</a>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-light"><i class="feather-arrow-left me-2"></i> Back to Events</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $event->image ? asset('storage/'.$event->image) : asset('assets/images/no-image.png') }}" class="img-fluid rounded mb-3" alt="{{ $event->name }}">
                        </div>
                        <div class="col-md-8">
                            <h2 class="mb-3">{{ $event->name }}</h2>
                            <p class="text-muted"><i class="feather-map-pin me-2"></i> {{ $event->location }}</p>
                            
                            <div class="mb-3">
                                @if($event->tags && count($event->tags) > 0)
                                    @foreach($event->tags as $tag)
                                        <span class="badge bg-soft-info text-info me-1">{{ $tag }}</span>
                                    @endforeach
                                @endif
                            </div>

                            <div class="mb-4">
                                <p class="mb-1 fw-bold text-dark">Max Participants:</p>
                                <p class="text-muted">
                                    <i class="feather-users me-2"></i>
                                    @if($event->capacity)
                                        {{ $event->capacity }} people
                                    @else
                                        Unlimited
                                    @endif
                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="mb-1 fw-bold text-dark">Target Departments:</p>
                                <div class="d-flex flex-wrap gap-1">
                                    @if(!$event->departments || in_array('All', $event->departments))
                                        <span class="badge bg-soft-success text-success">All Departments</span>
                                    @else
                                        @foreach($event->departments as $dept)
                                            <span class="badge bg-soft-info text-info">{{ $dept }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="mb-4">
                                <h5>Dates</h5>
                                @if(is_array($event->dates) && count($event->dates) > 0)
                                    <div>
                                        @foreach($event->dates as $index => $date)
                                            <span class="badge bg-soft-primary text-primary me-2 mb-2" role="button" data-bs-toggle="modal" data-bs-target="#dateMethod{{ $index }}" style="cursor: pointer;">
                                                {{ \Carbon\Carbon::parse($date)->format('F d, Y') }} <i class="feather-maximize-2 ms-1"></i>
                                            </span>

                                            <!-- Date QR Modal -->
                                            <div class="modal fade date-modal" id="dateMethod{{ $index }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Event QR Code</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <div id="qr-content-{{ $index }}" class="p-4 border rounded fw-bold" style="background: white;">
                                                                <h4 class="mb-3">{{ $event->name }}</h4>
                                                                <p class="mb-3 text-muted">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</p>
                                                                <p class="mb-3 text-muted">{{ $event->location }}</p>
                                                                <div class="mb-3 d-inline-block p-2 bg-white rounded">
                                                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(json_encode(['event_id' => $event->id, 'date' => $date])) !!}
                                                                </div>
                                                                <p class="small text-muted mb-0">Scan for Attendance</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-primary" onclick="downloadQrContent('qr-content-{{ $index }}', '{{ Str::slug($event->name) }}-{{ $date }}-qr')">
                                                                <i class="feather-download me-2"></i> Download QR Card
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No dates scheduled.</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <h5>Description</h5>
                                <p>{{ $event->description }}</p>
                            </div>

                            <div class="mb-4">
                                <h5>Speakers</h5>
                                @if($event->speakers->count() > 0)
                                    <div class="row">
                                        @foreach($event->speakers as $speaker)
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex align-items-center gap-3 border p-3 rounded cursor-pointer" data-bs-toggle="modal" data-bs-target="#speakerModal{{ $speaker->id }}" style="cursor: pointer; transition: all 0.2s; background-color: #fff;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='#fff'">
                                                    <div class="avatar-image">
                                                        <img src="{{ $speaker->image ? asset('storage/'.$speaker->image) : asset('assets/images/no-image.png') }}" alt="" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $speaker->first_name }} {{ $speaker->last_name }}</h6>
                                                        <small class="text-muted d-block">{{ $speaker->title ?? 'N/A' }}</small>
                                                        <small class="text-muted d-block">{{ $speaker->company ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Speaker Modal -->
                                            <div class="modal fade speaker-modal" id="speakerModal{{ $speaker->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content bg-white">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Speaker Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <div class="mb-3">
                                                                <img src="{{ $speaker->image ? asset('storage/'.$speaker->image) : asset('assets/images/no-image.png') }}" alt="" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                                            </div>
                                                            <h4 class="mb-1">{{ $speaker->first_name }} {{ $speaker->middle_name }} {{ $speaker->last_name }}</h4>
                                                            <p class="text-muted mb-2">{{ $speaker->title ?? 'N/A' }} @if($speaker->company) at {{ $speaker->company }} @endif</p>
                                                            
                                                            @if($speaker->email)
                                                                <p class="mb-2"><i class="feather-mail me-2"></i> <a href="mailto:{{ $speaker->email }}">{{ $speaker->email }}</a></p>
                                                            @endif
                                                            
                                                            @if($speaker->website)
                                                                <p class="mb-3"><i class="feather-globe me-2"></i> <a href="{{ $speaker->website }}" target="_blank">{{ $speaker->website }}</a></p>
                                                            @endif

                                                            @if($speaker->bio)
                                                                <div class="text-start bg-light p-3 rounded mb-3">
                                                                    <h6 class="fw-bold">Bio</h6>
                                                                    <p class="mb-0 small">{{ $speaker->bio }}</p>
                                                                </div>
                                                            @endif

                                                            <div class="d-flex justify-content-center gap-3">
                                                                @if($speaker->facebook)
                                                                    <a href="{{ $speaker->facebook }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="feather-facebook"></i> Facebook</a>
                                                                @endif
                                                                @if($speaker->twitter)
                                                                    <a href="{{ $speaker->twitter }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="feather-twitter"></i> Twitter</a>
                                                                @endif
                                                                @if($speaker->linkedin)
                                                                    <a href="{{ $speaker->linkedin }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="feather-linkedin"></i> LinkedIn</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No speakers added yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Move all speaker modals to body to ensure they display correctly over the backdrop
        const speakerModals = document.querySelectorAll('.speaker-modal');
        speakerModals.forEach(modal => {
            document.body.appendChild(modal);
        });

        // Move all date modals to body
        const dateModals = document.querySelectorAll('.date-modal');
        dateModals.forEach(modal => {
            document.body.appendChild(modal);
        });
    });

    function downloadQrContent(elementId, filename) {
        const element = document.getElementById(elementId);
        
        // Use html2canvas to capture the element
        html2canvas(element).then(canvas => {
            const link = document.createElement('a');
            link.download = filename + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }).catch(err => {
            console.error('QR Download Error:', err);
            alert('Failed to download QR code.');
        });
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endsection
