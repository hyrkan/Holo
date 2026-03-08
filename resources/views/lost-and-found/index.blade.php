@extends(Auth::guard('student')->check() ? 'layouts.student' : 'layouts.landing')

@section('content')

<!-- lost-and-found-area -->
<section id="lost-and-found" class="{{ Auth::guard('student')->check() ? 'p-4' : 'pt-120 pb-120' }}">
    <div class="container">
        <div class="row mb-50">
            <div class="col-lg-12">
                <div class="lost-found-tabs d-flex align-items-center justify-content-start" style="border-bottom: 2px solid #eee; padding-bottom: 20px;">
                    <div class="d-sm-none me-3" style="width: 220px;">
                        <select id="lf-mobile-select" class="form-select">
                            <option value="{{ route('lost-and-found.index', ['type' => 'all']) }}" {{ $type == 'all' ? 'selected' : '' }}>All Reports</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'lost']) }}" {{ $type == 'lost' ? 'selected' : '' }}>Lost Items</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'found']) }}" {{ $type == 'found' ? 'selected' : '' }}>Found Items</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'returned']) }}" {{ $type == 'returned' ? 'selected' : '' }}>Recently Returned</option>
                        </select>
                    </div>
                    <ul class="nav nav-pills custom-tabs d-none d-sm-flex">
                        <li class="nav-item">
                            <a href="{{ route('lost-and-found.index', ['type' => 'all']) }}" class="nav-link {{ $type == 'all' ? 'active' : '' }}">All Reports</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lost-and-found.index', ['type' => 'lost']) }}" class="nav-link {{ $type == 'lost' ? 'active' : '' }}">Lost Items</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lost-and-found.index', ['type' => 'found']) }}" class="nav-link {{ $type == 'found' ? 'active' : '' }}">Found Items</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lost-and-found.index', ['type' => 'returned']) }}" class="nav-link {{ $type == 'returned' ? 'active' : '' }}">Recently Returned</a>
                        </li>
                    </ul>
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
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->item_name }}" style="height: 250px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 250px; width: 100%;">
                                        <i class="{{ Auth::guard('student')->check() ? 'feather-image' : 'fas fa-image' }} fa-3x" style="color: #cbd5e1;"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="type-badge" style="position: absolute; top: 10px; right: 10px; background: {{ $item->status == 'resolved' ? '#28a745' : ($item->type == 'lost' ? '#dc3545' : '#4700c8') }}; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; text-transform: uppercase; font-size: 11px; letter-spacing: 1px;">
                                {{ $item->status == 'resolved' ? 'Returned' : $item->type }}
                            </div>
                        </div>
                        <div class="blog-content" style="padding: 20px;">
                            <div class="b-meta mb-15">
                                <ul style="display: flex; gap: 15px; list-style: none; padding: 0; margin: 0; font-size: 13px; color: #666;">
                                    <li><i class="{{ Auth::guard('student')->check() ? 'feather-calendar me-1' : 'far fa-calendar-alt' }}"></i> {{ $item->date_reported->format('M d, Y') }}</li>
                                    <li><i class="{{ Auth::guard('student')->check() ? 'feather-map-pin me-1' : 'fas fa-map-marker-alt' }}"></i> {{ $item->location }}</li>
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
                                    <span style="font-size: 12px; color: #4700c8;"><i class="{{ Auth::guard('student')->check() ? 'feather-phone me-1' : 'fas fa-phone' }}"></i> Contact provided</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-30">
                        <i class="{{ Auth::guard('student')->check() ? 'feather-search' : 'fas fa-search' }} fa-4x text-muted"></i>
                    </div>
                    <h3>No items found</h3>
                    <p>There are no {{ $type !== 'all' ? $type : '' }} items reported at the moment.</p>
                </div>
            @endforelse
        </div>

        @if($type == 'all' && $recentlyResolved->count() > 0)
        <!-- Recently Resolved Section (Shown only on 'All' tab) -->
        <div class="row mt-80">
            <div class="col-lg-12">
                <div class="section-title text-center mb-50">
                    <span>Happy News</span>
                    <h2>Recently Returned</h2>
                    <p>These items have been successfully returned to their owners.</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($recentlyResolved as $resolved)
            <div class="col-lg-3 col-md-6 mb-30">
                <div class="resolved-item text-center p-20" style="border: 1px dashed #28a745; border-radius: 10px; opacity: 0.8;">
                    <div class="icon mb-15">
                        <i class="{{ Auth::guard('student')->check() ? 'feather-check-circle' : 'fas fa-check-circle' }}" style="color: #28a745; font-size: 30px;"></i>
                    </div>
                    <h5 class="mb-10 text-success">{{ $resolved->item_name }}</h5>
                    <p class="small mb-0 text-muted">Returned on {{ $resolved->resolved_at->format('M d') }}</p>
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
    .custom-tabs .nav-link {
        color: #444;
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 5px;
        transition: all 0.3s;
        margin-right: 10px;
    }
    .custom-tabs .nav-link:hover {
        background: #f4f2f9;
        color: #4700c8;
    }
    .custom-tabs .nav-link.active {
        background: #4700c8 !important;
        color: #fff !important;
    }
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var sel = document.getElementById('lf-mobile-select');
  if (sel) {
    sel.addEventListener('change', function() {
      var url = this.value;
      if (url) window.location.href = url;
    });
  }
});
</script>
@endpush
