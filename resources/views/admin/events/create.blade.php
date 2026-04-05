@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Create New Event</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Event Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Conference, Workshop, Tech">
                            <div class="form-text">Separate tags with commas.</div>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="capacity" class="form-label">Max Participants</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity') }}" min="0" placeholder="Leave empty for unlimited">
                            <div class="form-text">Set the maximum number of people who can attend. Leave blank for no limit.</div>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Target Departments</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="departments[]" value="All" id="dept_all" {{ is_array(old('departments')) && in_array('All', old('departments')) ? 'checked' : (!old('departments') ? 'checked' : '') }}>
                                    <label class="form-check-label" for="dept_all">All Departments</label>
                                </div>
                                @php
                                    $availableDepts = ['BSIT', 'BSCS', 'BSCPE', 'BSBA', 'BSED', 'BEED', 'BSHM', 'BSTM'];
                                @endphp
                                @foreach($availableDepts as $dept)
                                    <div class="form-check">
                                        <input class="form-check-input dept-checkbox" type="checkbox" name="departments[]" value="{{ $dept }}" id="dept_{{ $dept }}" {{ is_array(old('departments')) && in_array($dept, old('departments')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dept_{{ $dept }}">{{ $dept }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text">Select which departments this event is for. Select "All Departments" to make it available to everyone.</div>
                            @error('departments')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Event Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="attendance_start_buffer" class="form-label">Attendance Opens (Minutes Before)</label>
                            <input type="number" class="form-control @error('attendance_start_buffer') is-invalid @enderror" id="attendance_start_buffer" name="attendance_start_buffer" value="{{ old('attendance_start_buffer', 0) }}" min="0">
                            <div class="form-text">How many minutes before the start time can students scan for attendance?</div>
                            @error('attendance_start_buffer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Event Dates & Times</label>
                            <div id="dates-container">
                                @if(old('dates'))
                                    @foreach(old('dates') as $index => $dateData)
                                        <div class="date-item card border mb-3">
                                            <div class="card-body p-3">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label small">Date</label>
                                                        <input type="date" class="form-control form-control-sm" name="dates[{{ $index }}][date]" value="{{ $dateData['date'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label small">Start Time</label>
                                                        <input type="time" class="form-control form-control-sm" name="dates[{{ $index }}][start_time]" value="{{ $dateData['start_time'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label small">End Time</label>
                                                        <input type="time" class="form-control form-control-sm" name="dates[{{ $index }}][end_time]" value="{{ $dateData['end_time'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-date" {{ count(old('dates')) > 1 ? '' : 'disabled' }}>
                                                            <i class="feather-trash-2"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="date-item card border mb-3">
                                        <div class="card-body p-3">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small">Date</label>
                                                    <input type="date" class="form-control form-control-sm" name="dates[0][date]" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">Start Time</label>
                                                    <input type="time" class="form-control form-control-sm" name="dates[0][start_time]">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">End Time</label>
                                                    <input type="time" class="form-control form-control-sm" name="dates[0][end_time]">
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-date" disabled>
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-date"><i class="feather-plus"></i> Add Date</button>
                            @error('dates')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="speakers" class="form-label mb-0">Speakers</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#createSpeakerModal">
                                    <i class="feather-plus"></i> New Speaker
                                </button>
                            </div>
                            <select class="form-select @error('speakers') is-invalid @enderror" id="speakers" name="speakers[]" multiple size="5">
                                @foreach($speakers as $speaker)
                                    <option value="{{ $speaker->id }}" {{ in_array($speaker->id, old('speakers', [])) ? 'selected' : '' }}>
                                        {{ $speaker->first_name }} {{ $speaker->last_name }} ({{ $speaker->title ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl (Windows) or Command (Mac) to select multiple speakers.</div>
                            @error('speakers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.events.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Speaker Modal -->
<div class="modal fade" id="createSpeakerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title">Create New Speaker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="speaker-alert-container"></div>
                <form id="createSpeakerForm" action="{{ route('admin.speakers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="speaker_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="speaker_first_name" name="first_name" required>
                            <div class="invalid-feedback" id="error-first_name"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="speaker_middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="speaker_middle_name" name="middle_name">
                            <div class="invalid-feedback" id="error-middle_name"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="speaker_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="speaker_last_name" name="last_name" required>
                            <div class="invalid-feedback" id="error-last_name"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="speaker_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="speaker_email" name="email">
                            <div class="invalid-feedback" id="error-email"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="speaker_title" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="speaker_title" name="title">
                            <div class="invalid-feedback" id="error-title"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="speaker_company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="speaker_company" name="company">
                            <div class="invalid-feedback" id="error-company"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="speaker_bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="speaker_bio" name="bio" rows="3"></textarea>
                        <div class="invalid-feedback" id="error-bio"></div>
                    </div>

                    <div class="mb-3">
                        <label for="speaker_image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="speaker_image" name="image" accept="image/*">
                        <div class="invalid-feedback" id="error-image"></div>
                    </div>

                    <h6 class="mb-3">Social Media</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="speaker_website" class="form-label">Website</label>
                            <input type="url" class="form-control" id="speaker_website" name="website" placeholder="https://example.com">
                            <div class="invalid-feedback" id="error-website"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="speaker_facebook" class="form-label">Facebook</label>
                            <input type="url" class="form-control" id="speaker_facebook" name="facebook" placeholder="https://facebook.com/user">
                            <div class="invalid-feedback" id="error-facebook"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="speaker_twitter" class="form-label">Twitter (X)</label>
                            <input type="url" class="form-control" id="speaker_twitter" name="twitter" placeholder="https://twitter.com/user">
                            <div class="invalid-feedback" id="error-twitter"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="speaker_linkedin" class="form-label">LinkedIn</label>
                            <input type="url" class="form-control" id="speaker_linkedin" name="linkedin" placeholder="https://linkedin.com/in/user">
                            <div class="invalid-feedback" id="error-linkedin"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSpeakerBtn">Create Speaker</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Move modal to body to avoid z-index/transform issues and ensure visibility
        const modalEl = document.getElementById('createSpeakerModal');
        if (modalEl) {
            document.body.appendChild(modalEl);
        }

        // Date Repeater Logic
        const container = document.getElementById('dates-container');
        const addButton = document.getElementById('add-date');

        if (container && addButton) {
            addButton.addEventListener('click', function() {
                const index = container.querySelectorAll('.date-item').length;
                const firstItem = container.querySelector('.date-item');
                const newItem = firstItem.cloneNode(true);
                
                // Update names with the correct index
                newItem.querySelectorAll('input').forEach(input => {
                    input.value = '';
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });
                
                const removeBtn = newItem.querySelector('.remove-date');
                if (removeBtn) removeBtn.disabled = false;
                
                // Enable all remove buttons if we are adding a second item
                if (container.children.length === 1) {
                    const firstRemoveBtn = container.querySelector('.remove-date');
                    if (firstRemoveBtn) firstRemoveBtn.disabled = false;
                }
                
                container.appendChild(newItem);
            });

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-date')) {
                    const item = e.target.closest('.date-item');
                    if (container.querySelectorAll('.date-item').length > 1) {
                        item.remove();
                        
                        // Re-index remaining items to ensure sequential array submission
                        container.querySelectorAll('.date-item').forEach((dateItem, idx) => {
                            dateItem.querySelectorAll('input').forEach(input => {
                                const name = input.getAttribute('name');
                                if (name) {
                                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${idx}]`));
                                }
                            });
                        });
                    }
                    
                    // Disable remove button if only one item remains
                    if (container.querySelectorAll('.date-item').length === 1) {
                        const remainingBtn = container.querySelector('.remove-date');
                        if (remainingBtn) remainingBtn.disabled = true;
                    }
                }
            });
        }

        
        // Department Selection Logic
        const deptAll = document.getElementById('dept_all');
        const deptCheckboxes = document.querySelectorAll('.dept-checkbox');

        if (deptAll) {
            deptAll.addEventListener('change', function() {
                if (this.checked) {
                    deptCheckboxes.forEach(cb => {
                        cb.checked = false;
                    });
                }
            });

            deptCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        deptAll.checked = false;
                    }
                });
            });
        }

        // Create Speaker Modal Logic
        const saveSpeakerBtn = document.getElementById('saveSpeakerBtn');
        const createSpeakerForm = document.getElementById('createSpeakerForm');
        // Initialize Modal after moving to body
        const speakerModal = new bootstrap.Modal(modalEl);
        const speakerSelect = document.getElementById('speakers');
        const alertContainer = document.getElementById('speaker-alert-container');

        if (saveSpeakerBtn) {
            saveSpeakerBtn.addEventListener('click', function() {
                // Clear previous errors
                document.querySelectorAll('#createSpeakerModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('#createSpeakerModal .invalid-feedback').forEach(el => el.textContent = '');
                alertContainer.innerHTML = '';

                const formData = new FormData(createSpeakerForm);

                // Fetch request
                fetch(createSpeakerForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200 || status === 201) {
                        // Success
                        const speaker = body.speaker;
                        const option = new Option(
                            `${speaker.first_name} ${speaker.last_name} (${speaker.title || 'N/A'})`, 
                            speaker.id, 
                            true, 
                            true
                        );
                        speakerSelect.add(option);
                        
                        // Show success message briefly (optional) or just close
                        speakerModal.hide();
                        createSpeakerForm.reset();
                        
                        // Optional: Show a global toast or alert on the main page
                        // For now, let's just alert
                        alert('Speaker created and selected successfully!');
                    } else if (status === 422) {
                        // Validation Errors
                        const errors = body.errors;
                        for (const [key, messages] of Object.entries(errors)) {
                            const input = document.getElementById(`speaker_${key}`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.getElementById(`error-${key}`);
                                if (feedback) feedback.textContent = messages[0];
                            }
                        }
                    } else {
                        // General Error
                        alertContainer.innerHTML = `<div class="alert alert-danger">An error occurred: ${body.message || 'Unknown error'}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alertContainer.innerHTML = `<div class="alert alert-danger">An unexpected error occurred.</div>`;
                });
            });
        }
    });
</script>
@endsection
