@extends('layouts.admin')

@section('title', 'Lost & Found Management || Holo Board')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Lost & Found Reports</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.lost-and-found.create', ['type' => 'lost']) }}" class="btn btn-danger btn-sm">
                            <i class="feather-plus me-2"></i> Report Lost Item
                        </a>
                        <a href="{{ route('admin.lost-and-found.create', ['type' => 'found']) }}" class="btn btn-success btn-sm">
                            <i class="feather-plus me-2"></i> Report Found Item
                        </a>
                    </div>
                </div>
                <div class="card-body custom-card-action p-4">
                    <div class="table-responsive">
                        <table id="lost-found-table" class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th style="width: 300px;">Item</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Reported By</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->image_path)
                                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="" class="img-fluid rounded-circle" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #eee;">
                                            @else
                                                <div class="bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                    <i class="feather-image"></i>
                                                </div>
                                            @endif
                                            <div style="max-width: 220px; overflow: hidden;">
                                                <span class="d-block fw-bold text-dark text-truncate" data-bs-toggle="tooltip" data-bs-title="{{ $item->item_name }}">{{ $item->item_name }}</span>
                                                <small class="text-muted d-block text-truncate" data-bs-toggle="tooltip" data-bs-title="{{ $item->description }}">{{ $item->description }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->type == 'lost' ? 'danger' : 'success' }}-subtle text-{{ $item->type == 'lost' ? 'danger' : 'success' }} text-uppercase">
                                            {{ $item->type }}
                                        </span>
                                    </td>
                                    <td>{{ $item->location }}</td>
                                    <td>
                                        {{ $item->is_anonymous ? 'Anonymous' : ($item->user ? $item->user->name : 'Staff') }}
                                        @if($item->contact_info)
                                            <br><small class="text-muted">{{ $item->contact_info }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->status == 'active' ? 'warning' : 'primary' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                        @if(strtolower($item->status) == 'resolved' && $item->returned_by_name)
                                            <div class="mt-1 small text-success fw-bold">
                                                <i class="feather-user"></i> {{ $item->returned_by_name }}
                                            </div>
                                        @endif
                                        @if($item->matched_item_id)
                                            <div class="mt-1">
                                                <span class="badge bg-info-subtle text-info small" title="Matched with report #{{ $item->matched_item_id }}">
                                                    <i class="feather-link"></i> Matched
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->date_reported->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a href="javascript:void(0);" class="btn btn-light btn-icon btn-sm" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                <i class="feather-more-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @if($item->status == 'pending')
                                                    <form action="{{ route('admin.lost-and-found.approve', $item) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="feather-check me-2"></i> Approve & Publish
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($item->status == 'active')
                                                    <a href="{{ route('admin.lost-and-found.resolve', $item) }}" class="dropdown-item">
                                                        <i class="feather-check-circle me-2"></i> Resolve
                                                    </a>
                                                @endif

                                                <a href="{{ route('admin.lost-and-found.show', $item) }}" class="dropdown-item">
                                                    <i class="feather-eye me-2"></i> View Details
                                                </a>

                                                @if(strtolower($item->status) == 'resolved' && $item->handover_image_path)
                                                    <a href="{{ asset('storage/' . $item->handover_image_path) }}" target="_blank" class="dropdown-item">
                                                        <i class="feather-image me-2"></i> View Proof
                                                    </a>
                                                @endif

                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.lost-and-found.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?')">
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
                                @endforeach
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
        $('#lost-found-table').DataTable({
            "order": [[5, "desc"]], // Order by date reported
            "pageLength": 10,
            "dom": "<'row mb-3'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
            "language": {
                "search": "",
                "searchPlaceholder": "Search reports...",
                "lengthMenu": "_MENU_ per page",
            },
            "drawCallback": function(settings) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            }
        });
        
        // Custom styling for the search input
        $('.dataTables_filter input').addClass('form-control form-control-sm').css({
            'width': '250px',
            'display': 'inline-block',
            'margin-left': '0'
        });
        $('.dataTables_filter label').addClass('mb-0');
    });
</script>
@endpush
