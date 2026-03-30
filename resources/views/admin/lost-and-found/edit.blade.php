@extends('layouts.admin')

@section('title', 'Edit ' . ucfirst($lost_and_found->type) . ' Item || Holo Board')

@section('content')
<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Edit {{ ucfirst($lost_and_found->type) }} Item: {{ $lost_and_found->item_name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lost-and-found.update', $lost_and_found) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="reporter_name">Reporter Name <span class="text-danger">*</span></label>
                                <input type="text" name="reporter_name" id="reporter_name" class="form-control" placeholder="Who is reporting this?" required value="{{ old('reporter_name', $lost_and_found->reporter_name) }}">
                                @error('reporter_name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="owner_name">Owner Name (If known)</label>
                                <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Who owns this item?" value="{{ old('owner_name', $lost_and_found->owner_name) }}">
                                @error('owner_name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="type">Report Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="lost" {{ old('type', $lost_and_found->type) == 'lost' ? 'selected' : '' }}>Lost Item</option>
                                    <option value="found" {{ old('type', $lost_and_found->type) == 'found' ? 'selected' : '' }}>Found Item</option>
                                </select>
                                @error('type') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="pending" {{ old('status', $lost_and_found->status) == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="active" {{ old('status', $lost_and_found->status) == 'active' ? 'selected' : '' }}>Active/Published</option>
                                    <option value="resolved" {{ old('status', $lost_and_found->status) == 'resolved' ? 'selected' : '' }}>Resolved/Returned</option>
                                </select>
                                @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="item_name">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" id="item_name" class="form-control" placeholder="e.g. Blue Wallet, iPhone 13" required value="{{ old('item_name', $lost_and_found->item_name) }}">
                            @error('item_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="location">Location Lost/Found <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control" placeholder="e.g. Room 302, Canteen" required value="{{ old('location', $lost_and_found->location) }}">
                            @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="description">Detailed Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="5" placeholder="Describe the item (color, brand, unique features)..." required>{{ old('description', $lost_and_found->description) }}</textarea>
                            @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block" for="image">Item Photo</label>
                            @if($lost_and_found->image_path)
                                <div class="mb-2">
                                    <img src="{{ $lost_and_found->image_url }}" alt="Item Photo" class="img-thumbnail" style="max-height: 200px;">
                                    <p class="small text-muted mt-1">Current photo - uploading a new one will replace this.</p>
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-control">
                            <div class="form-text">Attach a photo of the item if available.</div>
                            @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="contact_info">Reporter Email <span class="text-danger">*</span></label>
                            <input type="email" name="contact_info" id="contact_info" class="form-control" placeholder="e.g. reporter@example.com" required value="{{ old('contact_info', $lost_and_found->contact_info) }}">
                            <div class="form-text">We will use this email to notify you if the item is found/returned.</div>
                            @error('contact_info') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="form-check-input" value="1" {{ old('is_anonymous', $lost_and_found->is_anonymous) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_anonymous">Post Anonymously (Even if name is provided, it will show as Anonymous publicly)</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('admin.lost-and-found.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
