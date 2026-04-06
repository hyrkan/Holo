@extends('layouts.admin')
@section('title', 'Default Certificates || Holo Board')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
  .dataTables_filter input {
    width: 250px !important;
    display: inline-block !important;
  }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Default Certificate Templates</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.settings.certificates.create') }}" class="btn btn-sm btn-primary">
                            <i class="feather-plus me-1"></i> Add Default Template
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive p-3">
                        <table id="certificates-table" class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $certificate->name }}</strong>
                                            @if($certificate->is_selected)
                                                <i class="feather-star text-warning ms-1" title="Currently Selected Default"></i>
                                            @endif
                                        </td>
                                        <td>{{ $certificate->title }}</td>
                                        <td>
                                            <span class="badge bg-soft-info text-info">{{ $certificate->signatories->count() }} Signatories</span>
                                        </td>
                                        <td>
                                            @if($certificate->is_selected)
                                                <span class="badge bg-success">Selected Default</span>
                                            @else
                                                <form action="{{ route('admin.settings.certificates.select', $certificate) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-outline-primary py-0 px-2">
                                                        Select as Default
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                                    <i class="feather-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('admin.settings.certificates.edit', $certificate) }}" class="dropdown-item">
                                                        <i class="feather-edit me-2"></i> Edit Template
                                                    </a>
                                                    <a href="{{ route('admin.settings.certificates.preview', $certificate) }}" target="_blank" class="dropdown-item">
                                                        <i class="feather-eye me-2"></i> Preview Template
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('admin.settings.certificates.destroy', $certificate) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this default template?')">
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
                                            <h5>No Default Templates Found</h5>
                                            <p class="text-muted">Create a default certificate template that will be automatically awarded to students upon completion of an event.</p>
                                            <a href="{{ route('admin.settings.certificates.create') }}" class="btn btn-primary mt-3">Add First Template</a>
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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#certificates-table').DataTable({
            order: [[1, 'asc']],
            pageLength: 10,
            dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
            language: {
                search: "",
                searchPlaceholder: "Search templates...",
                lengthMenu: "_MENU_ per page",
            }
        });

        // Style search box
        $('.dataTables_filter input').addClass('form-control form-control-sm');
        $('.dataTables_filter label').addClass('mb-0');
    });
</script>
@endpush
