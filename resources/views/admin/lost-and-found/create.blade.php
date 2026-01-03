@extends('layouts.admin')

@section('title', 'Report ' . ucfirst($type) . ' Item || Holo Board')

@section('content')
<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Report New {{ ucfirst($type) }} Item</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lost-and-found.admin-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="reporter_name">Reporter Name (Optional)</label>
                                <input type="text" name="reporter_name" id="reporter_name" class="form-control" placeholder="Who is reporting this?" value="{{ old('reporter_name') }}">
                                @error('reporter_name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="owner_name">Owner Name (If known)</label>
                                <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Who owns this item?" value="{{ old('owner_name') }}">
                                @error('owner_name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="item_name">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" id="item_name" class="form-control" placeholder="e.g. Blue Wallet, iPhone 13" required value="{{ old('item_name') }}">
                            @error('item_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="location">Location Lost/Found <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control" placeholder="e.g. Room 302, Canteen" required value="{{ old('location') }}">
                            @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="description">Detailed Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="5" placeholder="Describe the item (color, brand, unique features)..." required>{{ old('description') }}</textarea>
                            @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="image">Item Photo (Optional)</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <div class="form-text">Attach a photo of the item if available.</div>
                            @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="contact_info">Contact Information (Optional)</label>
                            <input type="text" name="contact_info" id="contact_info" class="form-control" placeholder="Email or Phone Number" value="{{ old('contact_info') }}">
                            <div class="form-text">How people can contact you regarding this report.</div>
                            @error('contact_info') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>


                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="form-check-input" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_anonymous">Post Anonymously (Even if name is provided, it will show as Anonymous publicly)</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('admin.lost-and-found.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-{{ $type == 'lost' ? 'danger' : 'success' }}">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
