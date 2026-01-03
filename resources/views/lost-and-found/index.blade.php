@extends('layouts.landing')

@section('content')

<!-- lost-and-found-area -->
<section id="lost-and-found" class="pt-120 pb-120">
    <div class="container">
        <div class="row align-items-center mb-60">
            <div class="col-lg-6">
                <div class="section-title">
                    <span>Recent Reports</span>
                    <h2>Lost & Found Items</h2>
                </div>
            </div>
            <div class="col-lg-6 text-right">
                <div class="lost-found-filter">
                    <a href="{{ route('lost-and-found.index', ['type' => 'all']) }}" class="btn mr-10 {{ $type == 'all' ? '' : 'btn-outline' }}">All</a>
                    <a href="{{ route('lost-and-found.index', ['type' => 'lost']) }}" class="btn mr-10 {{ $type == 'lost' ? '' : 'btn-outline' }}">Lost</a>
                    <a href="{{ route('lost-and-found.index', ['type' => 'found']) }}" class="btn mr-10 {{ $type == 'found' ? '' : 'btn-outline' }}">Found</a>
                    <a href="{{ route('lost-and-found.create') }}" class="btn" style="background: #28a745; border-color: #28a745;"><i class="fas fa-plus"></i> Report Item</a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-30">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            @forelse($items as $item)
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="single-post mb-30" style="border: 1px solid #eee; border-radius: 10px; overflow: hidden; height: 100%;">
                        <div class="blog-thumb">
                            <a href="{{ route('lost-and-found.show', $item) }}">
                                <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('landing/img/blog_img_1.jpg') }}" alt="{{ $item->item_name }}" style="height: 250px; width: 100%; object-fit: cover;">
                            </a>
                            <div class="type-badge" style="position: absolute; top: 10px; right: 10px; background: {{ $item->type == 'lost' ? '#dc3545' : '#28a745' }}; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                                {{ $item->type }}
                            </div>
                        </div>
                        <div class="blog-content" style="padding: 20px;">
                            <div class="b-meta mb-15">
                                <ul style="display: flex; gap: 15px; list-style: none; padding: 0; margin: 0; font-size: 13px; color: #666;">
                                    <li><i class="far fa-calendar-alt"></i> {{ $item->date_reported->format('M d, Y') }}</li>
                                    <li><i class="fas fa-map-marker-alt"></i> {{ $item->location }}</li>
                                </ul>
                            </div>
                            <h4 style="margin-bottom: 15px; min-height: 50px;">
                                <a href="{{ route('lost-and-found.show', $item) }}">{{ $item->item_name }}</a>
                            </h4>
                            <p style="color: #666; font-size: 14px; margin-bottom: 20px; min-height: 60px;">{{ Str::limit($item->description, 100) }}</p>
                            
                            <div class="reporter-info" style="border-top: 1px solid #eee; padding-top: 15px; display: flex; align-items: center; justify-content: space-between;">
                                <span>
                                    @php
                            $displayName = $item->is_anonymous ? 'Anonymous' : ($item->reporter_name ?: ($item->user ? $item->user->name : ''));
                        @endphp
                                    @if($displayName)
                                        <span style="font-size: 13px; font-weight: 500;">Reported by: {{ $displayName }}</span>
                                    @endif
                                </span>
                                @if($item->contact_info)
                                    <span style="font-size: 12px; color: #4700c8;"><i class="fas fa-phone"></i> Contact provided</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-30">
                        <i class="fas fa-search fa-4x text-muted"></i>
                    </div>
                    <h3>No items found</h3>
                    <p>There are no {{ $type !== 'all' ? $type : '' }} items reported at the moment.</p>
                </div>
            @endforelse
        </div>

        @if($recentlyResolved->count() > 0)
        <!-- Recently Resolved Section -->
        <div class="row mt-80">
            <div class="col-lg-12">
                <div class="section-title text-center mb-50">
                    <span>Happy News</span>
                    <h2>Recently Reunited</h2>
                    <p>These items have been successfully returned to their owners.</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($recentlyResolved as $resolved)
            <div class="col-lg-3 col-md-6 mb-30">
                <div class="resolved-item text-center p-20" style="border: 1px dashed #28a745; border-radius: 10px; opacity: 0.8;">
                    <div class="icon mb-15">
                        <i class="fas fa-check-circle" style="color: #28a745; font-size: 30px;"></i>
                    </div>
                    <h5 class="mb-10 text-success">{{ $resolved->item_name }}</h5>
                    <p class="small mb-0 text-muted">Reunited on {{ $resolved->resolved_at->format('M d') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="row">
            <div class="col-12 mt-50 d-flex justify-content-center">
                {{ $items->links() }}
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
    .lost-found-filter .btn {
        padding: 10px 25px;
    }
</style>
@endpush
