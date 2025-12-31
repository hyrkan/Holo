@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Edit Event: {{ $event->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Event Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $event->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $event->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags', $event->tags ? implode(', ', $event->tags) : '') }}" placeholder="Conference, Workshop, Tech">
                            <div class="form-text">Separate tags with commas.</div>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="capacity" class="form-label">Max Participants</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $event->capacity) }}" min="0" placeholder="Leave empty for unlimited">
                            <div class="form-text">Set the maximum number of people who can attend. Leave blank for no limit.</div>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Target Departments</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="departments[]" value="All" id="dept_all" {{ (is_array(old('departments')) && in_array('All', old('departments'))) || (!old('departments') && (!$event->departments || in_array('All', $event->departments))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dept_all">All Departments</label>
                                </div>
                                @php
                                    $availableDepts = ['BSIT', 'BSCS', 'BSCPE', 'BSBA', 'BSED', 'BEED', 'BSHM', 'BSTM'];
                                    $selectedDepts = old('departments', $event->departments ?? []);
                                @endphp
                                @foreach($availableDepts as $dept)
                                    <div class="form-check">
                                        <input class="form-check-input dept-checkbox" type="checkbox" name="departments[]" value="{{ $dept }}" id="dept_{{ $dept }}" {{ in_array($dept, $selectedDepts) ? 'checked' : '' }}>
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
                            @if($event->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$event->image) }}" alt="Current Image" style="height: 100px; border-radius: 5px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            <div class="form-text">Leave blank to keep current image.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Event Dates</label>
                            <div id="dates-container">
                                @php
                                    $dates = old('dates', $event->eventDates->pluck('date')->toArray());
                                @endphp
                                @if(count($dates) > 0)
                                    @foreach($dates as $date)
                                        <div class="input-group mb-2">
                                            <input type="date" class="form-control" name="dates[]" value="{{ $date }}" required>
                                            <button type="button" class="btn btn-outline-danger remove-date" {{ count($dates) > 1 ? '' : 'disabled' }}><i class="feather-trash-2"></i></button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2">
                                        <input type="date" class="form-control" name="dates[]" required>
                                        <button type="button" class="btn btn-outline-danger remove-date" disabled><i class="feather-trash-2"></i></button>
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
                                @php
                                    $selectedSpeakers = old('speakers', $event->speakers->pluck('id')->toArray());
                                @endphp
                                @foreach($speakers as $speaker)
                                    <option value="{{ $speaker->id }}" {{ in_array($speaker->id, $selectedSpeakers) ? 'selected' : '' }}>
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
                            <button type="submit" class="btn btn-primary">Update Event</button>
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
        // Move modal to body
        const modalEl = document.getElementById('createSpeakerModal');
        if (modalEl) {
            document.body.appendChild(modalEl);
        }

        const container = document.getElementById('dates-container');
        const addButton = document.getElementById('add-date');

        addButton.addEventListener('click', function() {
            const firstItem = container.querySelector('.input-group');
            const newItem = firstItem.cloneNode(true);
            newItem.querySelector('input').value = '';
            newItem.querySelector('.remove-date').disabled = false;
            
            if (container.children.length === 1) {
                container.querySelector('.remove-date').disabled = false;
            }
            
            container.appendChild(newItem);
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-date')) {
                const item = e.target.closest('.input-group');
                if (container.children.length > 1) {
                    item.remove();
                }
                
                if (container.children.length === 1) {
                    container.querySelector('.remove-date').disabled = true;
                }
            }
        });

        
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
        const speakerModal = new bootstrap.Modal(modalEl);
        const speakerSelect = document.getElementById('speakers');
        const alertContainer = document.getElementById('speaker-alert-container');

        // Helper to show bootstrap toast
        function showToast(message, type = 'success') {
            // Check if our dynamic toast container exists, if not create it
            let toastContainer = document.getElementById('dynamic-toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'dynamic-toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '1060';
                document.body.appendChild(toastContainer);
            }

            const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
            const iconClass = type === 'success' ? 'feather-check-circle' : 'feather-alert-triangle';

            const toastHtml = `
                <div class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="${iconClass} me-2"></i> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            // Append toast to container
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = toastHtml;
            const toastEl = tempDiv.firstElementChild;
            toastContainer.appendChild(toastEl);

            // Initialize and show
            const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
            toast.show();
            
            // Remove after hide
            toastEl.addEventListener('hidden.bs.toast', function () {
                toastEl.remove();
            });
        }

        saveSpeakerBtn.addEventListener('click', function() {
            document.querySelectorAll('#createSpeakerModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#createSpeakerModal .invalid-feedback').forEach(el => el.textContent = '');
            alertContainer.innerHTML = '';

            const formData = new FormData(createSpeakerForm);

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
                    const speaker = body.speaker;
                    const option = new Option(
                        `${speaker.first_name} ${speaker.last_name} (${speaker.title || 'N/A'})`, 
                        speaker.id, 
                        true, 
                        true
                    );
                    speakerSelect.add(option);
                    
                    speakerModal.hide();
                    createSpeakerForm.reset();
                    
                    // Use Toast instead of alert
                    showToast('Speaker created and selected successfully!');
                } else if (status === 422) {
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
                    alertContainer.innerHTML = `<div class="alert alert-danger">An error occurred: ${body.message || 'Unknown error'}</div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertContainer.innerHTML = `<div class="alert alert-danger">An unexpected error occurred.</div>`;
            });
        });
    });
</script>
@endsection
