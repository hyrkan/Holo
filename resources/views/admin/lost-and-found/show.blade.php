@extends('layouts.admin')

@section('title', 'Report Details || Holo Board')

@section('content')
<div class="main-content">
    <div class="row mb-4">
        <div class="col-12 text-end d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.lost-and-found.index') }}" class="btn btn-light btn-md">
                <i class="feather-arrow-left me-2"></i> Back to List
            </a>
            <div class="d-flex gap-2">
                @if($lost_and_found->status == 'active')
                    <a href="{{ route('admin.lost-and-found.resolve', $lost_and_found) }}" class="btn btn-primary btn-md">
                        <i class="feather-check-circle me-2"></i> Resolve This Item
                    </a>
                    
                    <form action="{{ route('admin.lost-and-found.destroy', $lost_and_found) }}" method="POST" onsubmit="return confirm('CAUTION: This will permanently delete this report. Continue?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-soft-danger btn-md">
                            <i class="feather-trash-2 me-2"></i> Delete Report
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <!-- Item Details -->
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Item Details: {{ $lost_and_found->item_name }}</h5>
                    <span class="badge bg-{{ $lost_and_found->status == 'active' ? 'warning' : 'success' }} text-uppercase">
                        {{ $lost_and_found->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold small text-uppercase mb-2">Item Name</h6>
                            <p class="fs-5 fw-bold">{{ $lost_and_found->item_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold small text-uppercase mb-2">Report Type</h6>
                            <p>
                                <span class="badge bg-{{ $lost_and_found->type == 'lost' ? 'danger' : 'success' }}-subtle text-{{ $lost_and_found->type == 'lost' ? 'danger' : 'success' }} text-uppercase">
                                    {{ $lost_and_found->type }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold small text-uppercase mb-2">Location</h6>
                            <p><i class="feather-map-pin me-2"></i>{{ $lost_and_found->location }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold small text-uppercase mb-2">Date Reported</h6>
                            <p><i class="feather-calendar me-2"></i>{{ $lost_and_found->date_reported->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted fw-bold small text-uppercase mb-2">Description</h6>
                        <p class="bg-light p-3 rounded">{{ $lost_and_found->description }}</p>
                    </div>

                    @if($lost_and_found->image_path)
                    <div class="mb-0">
                        <h6 class="text-muted fw-bold small text-uppercase mb-2">Item Photo</h6>
                        <img src="{{ asset('storage/' . $lost_and_found->image_path) }}" alt="Item Photo" class="img-fluid rounded border shadow-sm" style="max-height: 400px;">
                    </div>
                    @endif

                    <!-- Resolution Details integrated into main card -->
                    @if(strtolower($lost_and_found->status) == 'resolved')
                        <hr class="my-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar-text bg-success text-white rounded-circle me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="feather-check"></i>
                            </div>
                            <h5 class="mb-0">Resolution & Handover Details</h5>
                        </div>

                        <div class="row mb-4">
                            <!-- Left Column: Text Details -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h6 class="text-muted fw-bold small text-uppercase mb-2">Resolved At</h6>
                                    <p>{{ $lost_and_found->resolved_at->format('M d, Y h:i A') }}</p>
                                </div>

                                @if($lost_and_found->returned_by_name)
                                <div class="mb-4">
                                    <h6 class="text-muted fw-bold small text-uppercase mb-2">Returned By / Claimed By</h6>
                                    <p class="fw-bold text-success fs-5">{{ $lost_and_found->returned_by_name }}</p>
                                </div>
                                @endif

                                @if($lost_and_found->matched_item_id)
                                <div class="mb-0">
                                    <h6 class="text-muted fw-bold small text-uppercase mb-2">Matched Report</h6>
                                    <p><a href="{{ route('admin.lost-and-found.show', $lost_and_found->matched_item_id) }}" class="btn btn-xs btn-info">View Matched Item #{{ $lost_and_found->matched_item_id }}</a></p>
                                </div>
                                @endif
                            </div>

                            <!-- Right Column: Photo Proof -->
                            @if($lost_and_found->handover_image_path)
                            <div class="col-md-6 border-start ps-md-4">
                                <h6 class="text-muted fw-bold small text-uppercase mb-2">Handover Photo / Proof</h6>
                                <a href="{{ asset('storage/' . $lost_and_found->handover_image_path) }}" target="_blank" title="Click to enlarge" class="d-block">
                                    <img src="{{ asset('storage/' . $lost_and_found->handover_image_path) }}" alt="Handover Proof" class="img-fluid rounded border shadow-sm" style="width: 100%; max-width: 300px; height: 200px; object-fit: cover;">
                                    <div class="mt-2 small text-muted"><i class="feather-maximize-2 me-1"></i> Click to enlarge</div>
                                </a>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Reporter Info -->
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title mb-0">Reporter Information</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <small class="text-muted d-block text-uppercase fw-bold mb-1">Name</small>
                            <span class="fw-bold">{{ $lost_and_found->reporter_name }}</span>
                            @if($lost_and_found->is_anonymous)
                                <span class="badge bg-secondary ms-2 small">Posted Anonymously</span>
                            @endif
                        </div>
                        <div class="list-group-item">
                            <small class="text-muted d-block text-uppercase fw-bold mb-1">Email / Contact</small>
                            <span class="text-primary">{{ $lost_and_found->contact_info }}</span>
                        </div>
                        @if($lost_and_found->owner_name)
                        <div class="list-group-item">
                            <small class="text-muted d-block text-uppercase fw-bold mb-1">Stated Owner</small>
                            <span>{{ $lost_and_found->owner_name }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
