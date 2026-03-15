@extends(Auth::guard('student')->check() ? 'layouts.student' : 'layouts.landing')

@section('title', (isset($mode) && $mode === 'edit') ? 'Edit Report || Holo Board' : 'Report Item || Holo Board')

@section('content')
<div class="{{ Auth::guard('student')->check() ? 'main-content p-4' : 'pt-120 pb-120' }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="section-title mb-0">
                            <span class="text-primary fw-bold text-uppercase fs-12 d-block mb-1">{{ (isset($mode) && $mode === 'edit') ? 'Edit Report' : 'New Report' }}</span>
                            <h2 class="fw-bold mb-0">Item Details</h2>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="{{ (isset($mode) && $mode === 'edit') ? route('student.lost-and-found.update', $lost_and_found) : route('lost-and-found.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($mode) && $mode === 'edit')
                                @method('PUT')
                            @endif
                            <div class="row g-4">
                                @if(Auth::guard('student')->check())
                                    <input type="hidden" name="type" value="{{ isset($lost_and_found) ? $lost_and_found->type : 'lost' }}">
                                @else
                                <div class="col-lg-12 mt-2">
                                    <label class="form-label fw-bold mb-3 d-block">Report Type</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="type" id="type-lost" value="lost" {{ old('type', isset($lost_and_found) ? $lost_and_found->type : 'lost') == 'lost' ? 'checked' : '' }} autocomplete="off">
                                        <label class="btn btn-outline-danger py-2 fw-medium" for="type-lost">
                                            <i class="feather-alert-octagon me-2"></i> Lost Item
                                        </label>

                                        <input type="radio" class="btn-check" name="type" id="type-found" value="found" {{ old('type', isset($lost_and_found) ? $lost_and_found->type : null) == 'found' ? 'checked' : '' }} autocomplete="off">
                                        <label class="btn btn-outline-success py-2 fw-medium" for="type-found">
                                            <i class="feather-search me-2"></i> Found Item
                                        </label>
                                    </div>
                                    @error('type') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label small fw-bold">Reporter Name *</label>
                                        <input type="text" name="reporter_name" class="form-control form-control-lg bg-light border-0 px-4" placeholder="Enter your name" required value="{{ old('reporter_name', isset($lost_and_found) ? $lost_and_found->reporter_name : (Auth::guard('student')->check() ? Auth::guard('student')->user()->student->full_name : '')) }}">
                                        @error('reporter_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label small fw-bold">Owner Name (If known)</label>
                                        <input type="text" name="owner_name" class="form-control form-control-lg bg-light border-0 px-4" placeholder="Enter owner's name" value="{{ old('owner_name', isset($lost_and_found) ? $lost_and_found->owner_name : (Auth::guard('student')->check() ? Auth::guard('student')->user()->student->full_name : '')) }}">
                                        @error('owner_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label small fw-bold">Item Name *</label>
                                        <input type="text" name="item_name" class="form-control form-control-lg bg-light border-0 px-4" placeholder="e.g. Blue Wallet, iPhone 13" required value="{{ old('item_name', isset($lost_and_found) ? $lost_and_found->item_name : null) }}">
                                        @error('item_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label small fw-bold">Location *</label>
                                        <input type="text" name="location" class="form-control form-control-lg bg-light border-0 px-4" placeholder="Where was it lost/found? (e.g. Room 302, Canteen)" required value="{{ old('location', isset($lost_and_found) ? $lost_and_found->location : null) }}">
                                        @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label small fw-bold">Description *</label>
                                        <textarea name="description" class="form-control bg-light border-0 px-4 py-3" rows="4" placeholder="Describe the item (color, brand, unique features)..." required>{{ old('description', isset($lost_and_found) ? $lost_and_found->description : null) }}</textarea>
                                        @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label small fw-bold">Item Image (Optional)</label>
                                        <input type="file" name="image" class="form-control bg-light border-0">
                                        <small class="text-muted d-block mt-1">Upload a photo to help identify the item.</small>
                                        @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label small fw-bold">Reporter Email *</label>
                                        <input type="email" name="contact_info" class="form-control form-control-lg bg-light border-0 px-4" placeholder="e.g. reporter@example.com" required value="{{ old('contact_info', isset($lost_and_found) ? $lost_and_found->contact_info : (Auth::guard('student')->check() ? Auth::guard('student')->user()->email : '')) }}">
                                        <small class="text-muted">We will use this email to notify you of any updates.</small>
                                        @error('contact_info') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow-none fw-bold" style="border-radius: 10px;">{{ (isset($mode) && $mode === 'edit') ? 'Update Report' : 'Submit Report' }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .form-control-lg {
        font-size: 0.95rem;
        height: auto;
    }
    .form-control:focus {
        background-color: #fff !important;
        border: 1px solid #4700c8 !important;
        box-shadow: none;
    }
    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-check:checked + .btn-outline-success {
        background-color: #198754;
        color: white;
    }
    @if(!Auth::guard('student')->check())
    .pt-120 { padding-top: 120px; }
    .pb-120 { padding-bottom: 120px; }
    .section-title h2 { font-size: 36px; color: #002691; }
    .btn-primary { background: #4700c8; border-color: #4700c8; }
    @endif
</style>
@endpush
