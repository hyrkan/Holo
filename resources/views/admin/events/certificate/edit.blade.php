@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Manage Certificate: {{ $event->name }}</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-light">
                            <i class="feather-arrow-left me-1"></i> Back to Event
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.events.certificate.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="title" class="form-label">Certificate Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $certificate->title ?? 'CERTIFICATE OF PARTICIPATION') }}" required>
                                    <div class="form-text">Example: CERTIFICATE OF RECOGNITION, CERTIFICATE OF ATTENDANCE</div>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="sub_title" class="form-label">Sub-title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sub_title') is-invalid @enderror" id="sub_title" name="sub_title" value="{{ old('sub_title', $certificate->sub_title ?? 'is hereby awarded to') }}" required>
                                    <div class="form-text">The text appearing before the participant's name.</div>
                                    @error('sub_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="body" class="form-label">Main Body Text <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="4" required>{{ old('body', $certificate->body ?? 'for their active participation during the \"' . $event->name . '\" held on ' . $event->eventDates->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('F d, Y'))->implode(', ') . ' at ' . $event->location . '.') }}</textarea>
                                    <div class="form-text">The text appearing after the participant's name.</div>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="background_image" class="form-label">Background Template</label>
                                    @if($certificate->background_image)
                                        <div class="mb-2 text-center">
                                            <img src="{{ asset('storage/' . $certificate->background_image) }}" alt="Background Template" class="img-fluid rounded border mb-2" style="max-height: 150px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('background_image') is-invalid @enderror" id="background_image" name="background_image" accept="image/*">
                                    <div class="form-text small text-muted">A4 Landscape Image (Recommended: 1123x794px).</div>
                                    @error('background_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $certificate->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Make Active & Available for Download</label>
                                    </div>
                                    <div class="form-text small">If enabled, eligible students can download this certificate.</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Signatories</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-signatory">
                                <i class="feather-plus me-1"></i> Add Signatory
                            </button>
                        </div>

                        <div id="signatories-container" class="row">
                            @php
                                $signatories = old('signatories', $certificate->signatories->toArray() ?? []);
                            @endphp
                            
                            @forelse($signatories as $index => $signatory)
                                <div class="col-md-4 mb-4 signatory-item">
                                    <div class="card border shadow-none mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-light text-dark">Signatory #<span class="signatory-num">{{ $loop->iteration }}</span></span>
                                                <button type="button" class="btn btn-link btn-sm text-danger p-0 remove-signatory"><i class="feather-trash-2"></i></button>
                                            </div>
                                            <input type="hidden" name="signatories[{{ $index }}][id]" value="{{ $signatory['id'] ?? '' }}">
                                            
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold">Name</label>
                                                <input type="text" name="signatories[{{ $index }}][name]" class="form-control form-control-sm" value="{{ $signatory['name'] }}" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Title/Label</label>
                                                <input type="text" name="signatories[{{ $index }}][label]" class="form-control form-control-sm" value="{{ $signatory['label'] }}">
                                            </div>

                                            <div class="mb-0">
                                                <label class="form-label small fw-bold">Signature Image</label>
                                                @if(isset($signatory['signature_image']))
                                                    <div class="mb-2 text-center bg-light p-2 rounded">
                                                        <img src="{{ asset('storage/' . $signatory['signature_image']) }}" alt="Signature" style="max-height: 40px;">
                                                    </div>
                                                @endif
                                                <input type="file" name="signatories[{{ $index }}][signature_image]" class="form-control form-control-sm" accept="image/*">
                                                <div class="form-text fs-10">Transparent PNG recommended.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4 no-signatories-msg">
                                    <p class="text-muted mb-0">No signatories added. Click "Add Signatory" to start.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            @if($certificate->id)
                                <a href="{{ route('admin.events.certificate.preview', $event) }}" target="_blank" class="btn btn-info">
                                    <i class="feather-eye me-1"></i> Preview Template
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('signatories-container');
    const addButton = document.getElementById('add-signatory');
    const noMsg = container.querySelector('.no-signatories-msg');
    
    let signatoryCount = {{ count($signatories) }};

    addButton.addEventListener('click', function() {
        if (noMsg) noMsg.remove();
        
        const index = signatoryCount++;
        const html = `
            <div class="col-md-4 mb-4 signatory-item">
                <div class="card border shadow-none mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-light text-dark">Signatory #<span class="signatory-num">${container.querySelectorAll('.signatory-item').length + 1}</span></span>
                            <button type="button" class="btn btn-link btn-sm text-danger p-0 remove-signatory"><i class="feather-trash-2"></i></button>
                        </div>
                        <input type="hidden" name="signatories[${index}][id]" value="">
                        
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Name</label>
                            <input type="text" name="signatories[${index}][name]" class="form-control form-control-sm" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Title/Label</label>
                            <input type="text" name="signatories[${index}][label]" class="form-control form-control-sm">
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold">Signature Image</label>
                            <input type="file" name="signatories[${index}][signature_image]" class="form-control form-control-sm" accept="image/*">
                            <div class="form-text fs-10">Transparent PNG recommended.</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-signatory')) {
            e.target.closest('.signatory-item').remove();
            updateNumbers();
            
            if (container.querySelectorAll('.signatory-item').length === 0) {
                container.innerHTML = '<div class="col-12 text-center py-4 no-signatories-msg"><p class="text-muted mb-0">No signatories added. Click "Add Signatory" to start.</p></div>';
            }
        }
    });

    function updateNumbers() {
        container.querySelectorAll('.signatory-item').forEach((item, i) => {
            item.querySelector('.signatory-num').textContent = i + 1;
        });
    }
});
</script>

<style>
.fs-10 { font-size: 10px; }
.signatory-item .card:hover { border-color: #6e62ff !important; }
</style>
@endsection
