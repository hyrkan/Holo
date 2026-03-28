@extends('layouts.student')

@section('title', 'My Lost & Found Reports || Holo Board')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">My Reports</h5>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <select id="dateRangeFilter" class="form-select form-select-sm">
                                <option value="all">All Dates</option>
                                <option value="last7">Last Week</option>
                                <option value="last30">Last Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                        <a href="{{ route('lost-and-found.create') }}" class="btn btn-primary btn-sm">
                            <i class="feather-plus me-2"></i> New Report
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="my-reports-table" class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Date Reported</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->image_path)
                                                <img src="{{ $item->image_url }}" alt="" class="img-fluid rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-soft-primary text-primary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="feather-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="d-block fw-bold text-dark">{{ $item->item_name }}</span>
                                                <small class="text-muted">{{ Str::limit($item->description, 30) }}</small>
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
                                        <span class="badge bg-{{ $item->status == 'active' ? 'warning' : 'primary' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td data-order="{{ $item->date_reported->format('Y-m-d') }}">{{ $item->date_reported->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a href="javascript:void(0);" class="btn btn-light btn-icon btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="feather-more-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="{{ route('lost-and-found.show', $item) }}" class="dropdown-item">
                                                    <i class="feather-eye me-2"></i> View
                                                </a>
                                                @if($item->status == 'active')
                                                <a href="{{ route('student.lost-and-found.edit', $item) }}" class="dropdown-item">
                                                    <i class="feather-edit-2 me-2"></i> Edit
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="feather-search fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">You haven't reported any items yet.</p>
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
        var table = $('#my-reports-table').DataTable({
            order: [[4, 'desc']],
            pageLength: 10,
            dom: '<\'row mb-3\'<\'col-sm-6\'l><\'col-sm-6 d-flex justify-content-end\'f>>' +
                 '<\'row\'<\'col-sm-12\'tr>>' +
                 '<\'row mt-3\'<\'col-sm-5\'i><\'col-sm-7\'p>>',
            language: {
                search: '',
                searchPlaceholder: 'Search my reports...',
                lengthMenu: '_MENU_ per page'
            }
        });

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable !== document.getElementById('my-reports-table')) {
                return true;
            }
            var filter = $('#dateRangeFilter').val();
            if (!filter || filter === 'all') {
                return true;
            }
            var api = new $.fn.dataTable.Api(settings);
            var td = api.cell(dataIndex, 4).node();
            var iso = td ? td.getAttribute('data-order') : null;
            var date = iso ? new Date(iso) : new Date(data[4]);
            if (isNaN(date)) {
                return true;
            }
            var now = new Date();
            var end = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59, 999);
            var start = null;

            if (filter === 'last7') {
                start = new Date();
                start.setDate(start.getDate() - 7);
            } else if (filter === 'last30') {
                start = new Date();
                start.setDate(start.getDate() - 30);
            } else if (filter === 'year') {
                start = new Date(new Date().getFullYear(), 0, 1);
            }

            if (start) {
                start.setHours(0,0,0,0);
                return date >= start && date <= end;
            }
            return true;
        });

        $('#dateRangeFilter').on('change', function() {
            table.draw();
        });

        $('.dataTables_filter input').addClass('form-control form-control-sm').css({
            'width': '250px',
            'display': 'inline-block',
            'margin-left': '0'
        });
        $('.dataTables_filter label').addClass('mb-0');
    });
</script>
@endpush
