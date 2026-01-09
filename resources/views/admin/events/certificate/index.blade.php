@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Certificates: {{ $event->name }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-light">
                            <i class="feather-arrow-left me-1"></i> Back to Event
                        </a>
                        <a href="{{ route('admin.events.certificates.create', $event) }}" class="btn btn-sm btn-primary">
                            <i class="feather-plus me-1"></i> Add Certificate Type
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name (Internal)</th>
                                    <th>Title (Display)</th>
                                    <th>Signatories</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($certificates as $certificate)
                                    <tr>
                                        <td><strong>{{ $certificate->name }}</strong></td>
                                        <td>{{ $certificate->title }}</td>
                                        <td>
                                            <span class="badge bg-soft-info text-info">{{ $certificate->signatories->count() }} Signatories</span>
                                        </td>
                                        <td>
                                            @if($certificate->is_active)
                                                <span class="badge bg-soft-success text-success">Active</span>
                                            @else
                                                <span class="badge bg-soft-secondary text-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                                    <i class="feather-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('admin.events.certificates.edit', [$event, $certificate]) }}" class="dropdown-item">
                                                        <i class="feather-edit me-2"></i> Edit Template
                                                    </a>
                                                    <a href="{{ route('admin.events.certificate.preview', $certificate) }}" target="_blank" class="dropdown-item">
                                                        <i class="feather-eye me-2"></i> Preview Template
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('admin.events.certificates.destroy', [$event, $certificate]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this certificate template?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="feather-trash-2 me-2"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="avatar-text avatar-xl bg-soft-warning text-warning rounded-circle mb-3 mx-auto">
                                                <i class="feather-award fs-2"></i>
                                            </div>
                                            <h5>No Certificate Types Created</h5>
                                            <p class="text-muted">Create different types of certificates for this event (e.g. Participation, Recognition).</p>
                                            <a href="{{ route('admin.events.certificates.create', $event) }}" class="btn btn-primary mt-3">Add First Certificate</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
