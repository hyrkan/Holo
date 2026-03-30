@extends(Auth::guard('student')->check() ? 'layouts.student' : 'layouts.landing')

@section('content')

<section class="{{ Auth::guard('student')->check() ? 'p-4' : 'pt-120 pb-120' }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="item-image mb-30">
                    @if($lostAndFound->image_path)
                        <img src="{{ $lostAndFound->image_url }}" alt="{{ $lostAndFound->item_name }}" class="img-fluid rounded shadow-sm" style="width: 100%; max-height: 500px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded shadow-sm" style="width: 100%; height: 400px;">
                            <i class="{{ Auth::guard('student')->check() ? 'feather-image' : 'fas fa-image' }} fa-4x" style="color: #cbd5e1;"></i>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="item-details" style="padding: 20px;">
                    <span class="badge mb-20" style="background: {{ $lostAndFound->type == 'lost' ? '#dc3545' : '#28a745' }}; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; text-transform: uppercase;">
                        {{ $lostAndFound->type }}
                    </span>
                    <h2 class="mb-20">{{ $lostAndFound->item_name }}</h2>
                    
                    <div class="info-list mb-30">
                        <p><strong><i class="{{ Auth::guard('student')->check() ? 'feather-calendar me-2' : 'far fa-calendar-alt mr-10' }}"></i> Reported Date:</strong> {{ $lostAndFound->date_reported->format('M d, Y') }}</p>
                        <p><strong><i class="{{ Auth::guard('student')->check() ? 'feather-map-pin me-2' : 'fas fa-map-marker-alt mr-10' }}"></i> Location:</strong> {{ $lostAndFound->location }}</p>
                        @php
                            $displayName = $lostAndFound->is_anonymous ? 'Anonymous' : ($lostAndFound->reporter_name ?: ($lostAndFound->user ? $lostAndFound->user->name : ''));
                        @endphp
                        @if($displayName)
                            <p><strong><i class="{{ Auth::guard('student')->check() ? 'feather-user me-2' : 'fas fa-user mr-10' }}"></i> Reported By:</strong> {{ $displayName }}</p>
                        @endif
                        @if($lostAndFound->owner_name)
                            <p><strong><i class="{{ Auth::guard('student')->check() ? 'feather-tag me-2' : 'fas fa-id-badge mr-10' }}"></i> Owner:</strong> {{ $lostAndFound->owner_name }}</p>
                        @endif
                    </div>

                    <div class="description mb-40">
                        <h4>Description</h4>
                        <p>{{ $lostAndFound->description }}</p>
                    </div>

                    @if($lostAndFound->contact_info)
                        <div class="contact-box p-30 bg-light rounded" style="background: #f4f2f9 !important;">
                            <h4>Contact Information</h4>
                            <p class="mb-0">Please reach out via: <strong>{{ $lostAndFound->contact_info }}</strong></p>
                        </div>
                    @endif
                    
                    <div class="mt-40">
                        <a href="{{ route('student.dashboard', ['tab' => 'lost-found']) }}" class="btn btn-outline">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<style>
    .btn-outline {
        background: transparent;
        border: 1px solid #4700c8;
        color: #4700c8;
    }
    .btn-outline:hover {
        background: #4700c8;
        color: white;
    }
</style>
@endpush
