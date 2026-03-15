@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="card-title mb-0">Announcements</h5>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <select id="statusFilter" class="form-select form-select-sm">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div>
                            <select id="dateRangeFilter" class="form-select form-select-sm">
                                <option value="all">All Dates</option>
                                <option value="today">Today</option>
                                <option value="last7">Last Week</option>
                                <option value="last30">Last Month</option>
                                <option value="last365">Last Year</option>
                            </select>
                        </div>
                        <a href="{{ route('admin.announcements.archived') }}" class="btn btn-secondary me-2">
                            <i class="feather-archive me-2"></i> Archived
                        </a>
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Create Announcement
                        </a>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table id="announcements-table" class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Title</th>
                                    <th>Status</th>
                                    <th>Target</th>
                                    <th>Duration</th>
                                    <th class="d-none">Created At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($announcements as $announcement)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <a href="{{ route('admin.announcements.show', $announcement) }}" class="text-dark">
                                                <span class="d-block">{{ $announcement->title }}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @if($announcement->is_draft)
                                            <span class="badge bg-soft-warning text-warning">Draft</span>
                                        @elseif($announcement->is_active)
                                            <span class="badge bg-soft-success text-success">Active</span>
                                        @else
                                            <span class="badge bg-soft-secondary text-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fs-12">
                                            <span class="fw-bold">{{ ucfirst($announcement->target_audience) }}</span>
                                            @if($announcement->target_audience == 'students' && $announcement->target_year_levels)
                                                <br><small class="text-muted">{{ implode(', ', $announcement->target_year_levels) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $announcement->start_date->format('d M, Y H:i') }} - {{ $announcement->end_date->format('d M, Y H:i') }}
                                    </td>
                                    <td class="d-none" data-order="{{ $announcement->created_at->format('Y-m-d') }}">{{ $announcement->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <div class="hstack gap-3 justify-content-end">
                                            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-secondary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="feather-edit-3 fs-16"></i>
                                            </a>
                                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-warning border-0 bg-transparent p-0" 
                                                    data-confirm-title="Archive Announcement"
                                                    data-confirm-message="Are you sure you want to archive '{{ $announcement->title }}'?"
                                                    data-confirm-type="warning"
                                                    data-confirm-icon="archive"
                                                    data-confirm-btn-text="Archive"
                                                    data-bs-toggle="tooltip" title="Archive">
                                                    <i class="feather-archive fs-16"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="feather-bell fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No announcements found.</p>
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
    var table = $('#announcements-table').DataTable({
      order: [[4, 'desc']],
      pageLength: 10,
      dom: '<\'row mb-3\'<\'col-sm-6\'l><\'col-sm-6 d-flex justify-content-end\'f>>' +
           '<\'row\'<\'col-sm-12\'tr>>' +
           '<\'row mt-3\'<\'col-sm-5\'i><\'col-sm-7\'p>>',
      language: {
        search: '',
        searchPlaceholder: 'Search announcements...',
        lengthMenu: '_MENU_ per page'
      },
      columnDefs: [
        { targets: [4], visible: false, searchable: false } // hide Created At column
      ]
    });

    // Status text extractor for a row
    function getStatusText(row) {
      var txt = $(row).find('td:nth-child(2)').text().toLowerCase().trim();
      if (txt.indexOf('draft') >= 0) return 'draft';
      if (txt.indexOf('active') >= 0) return 'active';
      if (txt.indexOf('inactive') >= 0) return 'inactive';
      return 'unknown';
    }

    // Custom date filter using hidden Created At column (index 4)
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
      if (settings.nTable !== document.getElementById('announcements-table')) {
        return true;
      }
      var statusFilter = $('#statusFilter').val();
      if (statusFilter && statusFilter !== 'all') {
        var rowStatus = getStatusText($(settings.aoData[dataIndex].nTr));
        if (rowStatus !== statusFilter) {
          return false;
        }
      }

      var dateFilter = $('#dateRangeFilter').val();
      if (!dateFilter || dateFilter === 'all') {
        return true;
      }

      var api = new $.fn.dataTable.Api(settings);
      var td = api.cell(dataIndex, 4).node();
      var iso = td ? td.getAttribute('data-order') : null;
      var created = iso ? new Date(iso) : new Date(data[4]);
      if (isNaN(created)) {
        return true;
      }

      var now = new Date();
      var start = null;
      var end = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59, 999);

      if (dateFilter === 'today') {
        start = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0, 0, 0);
      } else if (dateFilter === 'last7') {
        start = new Date();
        start.setDate(start.getDate() - 7);
        start.setHours(0,0,0,0);
      } else if (dateFilter === 'last30') {
        start = new Date();
        start.setDate(start.getDate() - 30);
        start.setHours(0,0,0,0);
      } else if (dateFilter === 'last365') {
        start = new Date();
        start.setDate(start.getDate() - 365);
        start.setHours(0,0,0,0);
      }

      if (start) {
        return created >= start && created <= end;
      }
      return true;
    });

    $('#statusFilter, #dateRangeFilter').on('change', function() {
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
