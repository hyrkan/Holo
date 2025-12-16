@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Create Announcement</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                                @error('title')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Body</label>
                                <textarea name="body" class="form-control" rows="5" required>{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="datetime-local" name="start_date" class="form-control" required value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="datetime-local" name="end_date" class="form-control" required value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @error('image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-check form-check-inline">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="hidden" name="is_draft" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_draft" id="isDraft" value="1" {{ old('is_draft', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isDraft">Draft</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="{{ route('admin.announcements.index') }}" class="btn btn-light w-100">Cancel</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">Create Announcement</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
