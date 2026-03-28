@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Edit Announcement</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required value="{{ old('title', $announcement->title) }}">
                                @error('title')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Body</label>
                                <textarea name="body" class="form-control" rows="5" required>{{ old('body', $announcement->body) }}</textarea>
                                @error('body')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Target Audience</label>
                                <select name="target_audience" id="target_audience" class="form-control" required>
                                    <option value="all" {{ old('target_audience', $announcement->target_audience) == 'all' ? 'selected' : '' }}>Everyone</option>
                                    <option value="students" {{ old('target_audience', $announcement->target_audience) == 'students' ? 'selected' : '' }}>Students Only</option>
                                    <option value="guests" {{ old('target_audience', $announcement->target_audience) == 'guests' ? 'selected' : '' }}>Guests Only</option>
                                </select>
                                @error('target_audience')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if($announcement->image)
                                    <div class="mt-2">
                                        <img src="{{ $announcement->image_url }}" alt="Current Image" class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                @endif
                                @error('image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4" id="year_level_container" style="display: {{ $announcement->target_audience == 'students' ? 'block' : 'none' }};">
                            <div class="col-md-12">
                                <div class="p-3 bg-light rounded border">
                                    <label class="form-label d-flex justify-content-between">
                                        Target Year Levels (For Students)
                                        <small><a href="javascript:void(0)" id="selectAllYears" class="text-primary">Select All</a></small>
                                    </label>
                                    <div class="d-flex flex-wrap gap-4 mt-2">
                                        @php
                                            $selectedYears = old('target_year_levels', $announcement->target_year_levels ?? []);
                                        @endphp
                                        @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year'] as $year)
                                            <div class="form-check">
                                                <input class="form-check-input year-checkbox" type="checkbox" name="target_year_levels[]" value="{{ $year }}" id="year_{{ $loop->index }}" {{ in_array($year, $selectedYears) ? 'checked' : '' }}>
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
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="datetime-local" name="start_date" class="form-control" required value="{{ old('start_date', $announcement->start_date->format('Y-m-d\TH:i')) }}">
                                @error('start_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="datetime-local" name="end_date" class="form-control" required value="{{ old('end_date', $announcement->end_date->format('Y-m-d\TH:i')) }}">
                                @error('end_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Downloadable Files (PDF, Word, Excel, Images)</label>
                                <input type="file" name="attachments[]" class="form-control" multiple>
                                <small class="text-muted">You can select multiple files to add. Allowed types: pdf, doc, docx, xls, xlsx, jpeg, png, jpg, gif</small>
                                @error('attachments.*')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                @if($announcement->attachments->count() > 0)
                                    <div class="mt-3">
                                        <h6>Current Attachments:</h6>
                                        <ul class="list-group">
                                            @foreach($announcement->attachments as $attachment)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="feather-file me-2"></i>
                                                        {{ $attachment->file_name }}
                                                        <small class="text-muted">({{ number_format($attachment->file_size / 1024, 2) }} KB)</small>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ $attachment->file_url }}" target="_blank" class="text-primary" title="Download">
                                                            <i class="feather-download"></i>
                                                        </a>
                                                        <button type="button" class="text-danger border-0 bg-transparent p-0 delete-attachment" data-id="{{ $attachment->id }}" title="Delete">
                                                            <i class="feather-trash-2"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check form-check-inline">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="hidden" name="is_draft" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_draft" id="isDraft" value="1" {{ old('is_draft', $announcement->is_draft) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isDraft">Draft</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="{{ route('admin.announcements.index') }}" class="btn btn-light w-100">Cancel</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">Update Announcement</button>
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
          // toggleYearLevel(); // Removed so we don't overwrite server-side display style on load

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
 
      document.querySelectorAll('.delete-attachment').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this attachment?')) {
                const attachmentId = this.getAttribute('data-id');
                const url = `{{ route('admin.announcements.attachments.destroy', ':id') }}`.replace(':id', attachmentId);
                
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('li').remove();
                    } else {
                        alert('Something went wrong!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting attachment');
                });
            }
        });
    });
</script>
@endpush
