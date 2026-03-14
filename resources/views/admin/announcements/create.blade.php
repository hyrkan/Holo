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
                                <label class="form-label">Target Audience</label>
                                <select name="target_audience" id="target_audience" class="form-control" required>
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Everyone</option>
                                    <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Students Only</option>
                                    <option value="guests" {{ old('target_audience') == 'guests' ? 'selected' : '' }}>Guests Only</option>
                                </select>
                                @error('target_audience')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @error('image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4" id="year_level_container" style="display: none;">
                            <div class="col-md-12">
                                <div class="p-3 bg-light rounded border">
                                    <label class="form-label d-flex justify-content-between">
                                        Target Year Levels (For Students)
                                        <small><a href="javascript:void(0)" id="selectAllYears" class="text-primary">Select All</a></small>
                                    </label>
                                    <div class="d-flex flex-wrap gap-4 mt-2">
                                        @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year'] as $year)
                                            <div class="form-check">
                                                <input class="form-check-input year-checkbox" type="checkbox" name="target_year_levels[]" value="{{ $year }}" id="year_{{ $loop->index }}" {{ is_array(old('target_year_levels')) && in_array($year, old('target_year_levels')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="year_{{ $loop->index }}">{{ $year }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('target_year_levels')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Downloadable Files (PDF, Word, Excel, Images)</label>
                                <input type="file" name="attachments[]" class="form-control" multiple>
                                <small class="text-muted">You can select multiple files. Allowed types: pdf, doc, docx, xls, xlsx, jpeg, png, jpg, gif</small>
                                @error('attachments.*')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const targetAudience = document.getElementById('target_audience');
        const yearLevelContainer = document.getElementById('year_level_container');

        function toggleYearLevel() {
            if (targetAudience.value === 'students') {
                yearLevelContainer.style.display = 'block';
            } else {
                yearLevelContainer.style.display = 'none';
            }
        }

        targetAudience.addEventListener('change', toggleYearLevel);
        toggleYearLevel(); // Initial check

        const selectAllLink = document.getElementById('selectAllYears');
        if (selectAllLink) {
            selectAllLink.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.year-checkbox');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                checkboxes.forEach(cb => cb.checked = !allChecked);
                this.textContent = allChecked ? 'Select All' : 'Deselect All';
            });
        }
    });
</script>
@endpush
