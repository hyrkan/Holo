@extends('layouts.admin')

@section('title', 'Lost & Found Management || Holo Board')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<style>
    #lost-found-table_wrapper .dataTables_filter {
        display: none;
    }
    .dt-buttons {
        margin-bottom: 1rem;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
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
                
                <div class="card-body p-4 border-bottom shadow-sm bg-light-subtle">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                                <input type="text" id="customSearch" class="form-control border-start-0" placeholder="Search items, locations, reporters...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="typeFilter" class="form-select">
                                <option value="">All Types</option>
                                <option value="lost">Lost</option>
                                <option value="found">Found</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-end">
                            <button id="resetFilters" class="btn btn-outline-secondary w-100">
                                <i class="feather-refresh-cw me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body custom-card-action p-4">
                    <div class="table-responsive">
                        <table id="lost-found-table" class="table table-hover mb-0 w-100">
                            <thead>
                                <tr class="border-b">
                                    <th style="min-width: 250px;">Item</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Reported By</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->image_path)
                                                <img src="{{ $item->image_url }}" alt="" class="img-fluid rounded border shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="bg-soft-primary text-primary rounded d-flex align-items-center justify-content-center border shadow-sm" style="width: 48px; height: 48px;">
                                                    <i class="feather-image fs-16"></i>
                                                </div>
                                            @endif
                                            <div style="max-width: 200px;">
                                                <span class="d-block fw-bold text-dark text-truncate" title="{{ $item->item_name }}">{{ $item->item_name }}</span>
                                                <small class="text-muted d-block text-truncate" title="{{ $item->description }}">{{ $item->description }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->type == 'lost' ? 'danger' : 'success' }}-subtle text-{{ $item->type == 'lost' ? 'danger' : 'success' }} text-uppercase">
                                            {{ $item->type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="feather-map-pin text-muted me-1 fs-12"></i>
                                            <span class="text-truncate" style="max-width: 150px;">{{ $item->location }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">
                                            {{ $item->is_anonymous ? 'Anonymous' : ($item->reporter_name ?: ($item->user ? ($item->user->employee ? trim(($item->user->employee->first_name ?? '').' '.($item->user->employee->last_name ?? '')) : ($item->user->student ? $item->user->student->full_name : '')) : 'Staff')) }}
                                        </div>
                                        @if($item->contact_info)
                                            <small class="text-muted d-block text-truncate" style="max-width: 150px;">
                                                <i class="feather-mail fs-10 me-1"></i>{{ $item->contact_info }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="status-wrapper">
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($item->status == 'active')
                                                <span class="badge bg-info">Active</span>
                                            @else
                                                <span class="badge bg-success">Resolved</span>
                                            @endif
                                            
                                            @if(strtolower($item->status) == 'resolved' && $item->returned_by_name)
                                                <div class="mt-1 small text-success fw-bold d-flex align-items-center">
                                                    <i class="feather-user me-1"></i> {{ $item->returned_by_name }}
                                                </div>
                                            @endif
                                            @if($item->matched_item_id)
                                                <div class="mt-1">
                                                    <span class="badge bg-soft-info text-info small" title="Matched with report #{{ $item->matched_item_id }}">
                                                        <i class="feather-link me-1"></i> Matched
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $item->date_reported->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <button type="button" class="btn btn-light btn-icon btn-sm" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                <i class="feather-more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                                @if($item->status == 'pending')
                                                    <form action="{{ route('admin.lost-and-found.approve', $item) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="feather-check text-success me-2"></i> Approve & Publish
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($item->status == 'active')
                                                    <a href="{{ route('admin.lost-and-found.resolve', $item) }}" class="dropdown-item">
                                                        <i class="feather-check-circle text-primary me-2"></i> Resolve
                                                    </a>
                                                @endif

                                                <a href="{{ route('admin.lost-and-found.show', $item) }}" class="dropdown-item">
                                                    <i class="feather-eye text-info me-2"></i> View Details
                                                </a>

                                                <a href="{{ route('admin.lost-and-found.edit', $item) }}" class="dropdown-item">
                                                    <i class="feather-edit text-warning me-2"></i> Edit report
                                                </a>

                                                @if(strtolower($item->status) == 'resolved' && $item->handover_image_path)
                                                    <a href="{{ $item->handover_image_url }}" target="_blank" class="dropdown-item">
                                                        <i class="feather-image text-primary me-2"></i> View Proof Image
                                                    </a>
                                                @endif

                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.lost-and-found.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="feather-trash-2 me-2"></i> Delete Report
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
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        const table = $('#lost-found-table').DataTable({
            order: [[5, "desc"]], // Order by date reported
            pageLength: 10,
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-end'B>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'excel',
                    className: 'btn btn-soft-success btn-sm me-1',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-soft-danger btn-sm me-1',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                },
                {
                    extend: 'print',
                    className: 'btn btn-soft-info btn-sm',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                }
            ],
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="feather-chevron-left"></i>',
                    next: '<i class="feather-chevron-right"></i>'
                }
            },
            drawCallback: function() {
                // Initialize tooltips after every draw
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            }
        });

        // Global Search
        $('#customSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Type Filter (Column index 1)
        $('#typeFilter').on('change', function() {
            table.column(1).search(this.value).draw();
        });

        // Status Filter (Column index 4)
        $('#statusFilter').on('change', function() {
            table.column(4).search(this.value).draw();
        });

        // Reset Filters
        $('#resetFilters').on('click', function() {
            $('#customSearch').val('');
            $('#typeFilter').val('');
            $('#statusFilter').val('');
            table.search('').columns().search('').draw();
        });
    });
</script>
@endpush
