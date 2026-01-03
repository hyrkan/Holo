@extends('layouts.admin')

@section('title', 'Resolve Lost & Found || Holo Board')

@section('content')
<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Resolve Item: {{ $lost_and_found->item_name }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="feather-info fs-4"></i>
                            <div>
                                <h6 class="mb-1">Item Details</h6>
                                <p class="mb-0"><strong>Type:</strong> {{ ucfirst($lost_and_found->type) }} | <strong>Location:</strong> {{ $lost_and_found->location }}</p>
                                <p class="mb-0">{{ $lost_and_found->description }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.lost-and-found.store-resolution', $lost_and_found) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label" for="matched_item_id">Link to a Matching {{ $lost_and_found->type == 'lost' ? 'Found' : 'Lost' }} Report (Optional)</label>
                            <select name="matched_item_id" id="matched_item_id" class="form-select">
                                <option value="">-- No Match Selected --</option>
                                @foreach($potentialMatches as $match)
                                    <option value="{{ $match->id }}">{{ $match->item_name }} (at {{ $match->location }})</option>
                                @endforeach
                            </select>
                            <div class="form-text">Select this if there is already a corresponding report for this item.</div>
                            @error('matched_item_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="identity_proof_ref">Identity Verification Reference <span class="text-danger">*</span></label>
                            <input type="text" name="identity_proof_ref" id="identity_proof_ref" class="form-control" placeholder="e.g. Student ID No, Driver's License Description" required>
                            <div class="form-text">Reference or description of the identification presented by the claimant.</div>
                            @error('identity_proof_ref') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="handover_image">Handover / Proof of Return Photo <span class="text-danger">*</span></label>
                            <input type="file" name="handover_image" id="handover_image" class="form-control" required>
                            <div class="form-text">Upload a photo showing the item being returned to the owner.</div>
                            @error('handover_image') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('admin.lost-and-found.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Complete Resolution</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
