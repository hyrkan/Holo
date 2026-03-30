@extends(Auth::guard('student')->check() ? 'layouts.student' : 'layouts.landing')

@section('content')

<!-- lost-and-found-area -->
<section id="lost-and-found" class="{{ Auth::guard('student')->check() ? 'p-4' : 'pt-120 pb-120' }}">
    <div class="container">
        <div class="row mb-50">
            <div class="col-lg-12">
                <div class="lost-found-tabs d-flex align-items-center justify-content-start" style="border-bottom: 2px solid #eee; padding-bottom: 20px;">
                    <div class="d-sm-none me-3" style="width: 220px;">
                        <select id="lf-mobile-select" class="form-select">
                            <option value="{{ route('lost-and-found.index', ['type' => 'all']) }}" {{ $type == 'all' ? 'selected' : '' }}>All Reports</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'lost']) }}" {{ $type == 'lost' ? 'selected' : '' }}>Lost Items</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'found']) }}" {{ $type == 'found' ? 'selected' : '' }}>Found Items</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'returned']) }}" {{ $type == 'returned' ? 'selected' : '' }}>Recently Returned</option>
                        </select>
                    </div>
                    <ul class="nav nav-pills custom-tabs d-none d-sm-flex">
                        <li class="nav-item">
                            <a href="{{ route('lost-and-found.index', ['type' => 'all']) }}" class="nav-link {{ $type == 'all' ? 'active' : '' }}">All Reports</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lost-and-found.index', ['type' => 'lost']) }}" class="nav-link {{ $type == 'lost' ? 'active' : '' }}">Lost Items</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lost-and-found.index', ['type' => 'found']) }}" class="nav-link {{ $type == 'found' ? 'active' : '' }}">Found Items</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lost-and-found.index', ['type' => 'returned']) }}" class="nav-link {{ $type == 'returned' ? 'active' : '' }}">Recently Returned</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-30">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label for="lfTypeFilter" class="fw-semibold mb-0">Filter</label>
                        <select id="lfTypeFilter" class="form-select form-select-sm" style="width: 240px;">
                            <option value="{{ route('lost-and-found.index', ['type' => 'all']) }}" {{ $type === 'all' ? 'selected' : '' }}>All Reports</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'lost']) }}" {{ $type === 'lost' ? 'selected' : '' }}>Lost Items</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'found']) }}" {{ $type === 'found' ? 'selected' : '' }}>Found Items</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'returned']) }}" {{ $type === 'returned' ? 'selected' : '' }}>Recently Returned</option>
                        </select>
                    </div>
                    <div class="ms-auto" style="max-width: 320px; width: 100%;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="{{ Auth::guard('student')->check() ? 'feather-search' : 'fas fa-search' }}"></i></span>
                            <input id="lfSearch" type="text" class="form-control" placeholder="Search reports...">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="lf-table" class="table table-hover w-100">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Reported By</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                            @php
                                $displayName = $item->is_anonymous ? 'Anonymous' : ($item->reporter_name ?: ($item->user ? ($item->user->name ?? '') : ''));
                            @endphp
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('lost-and-found.show', $item) }}">{{ $item->item_name }}</a>
                                </td>
                                <td class="text-uppercase">{{ $item->type }}</td>
                                <td>{{ $item->location }}</td>
                                <td>{{ $displayName }}</td>
                                <td>{{ ucfirst($item->status) }}</td>
                                <td data-order="{{ $item->date_reported?->format('Y-m-d') }}">{{ $item->date_reported?->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('lost-and-found.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@push('css')
<style>
    .custom-tabs .nav-link {
        color: #444;
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 5px;
        transition: all 0.3s;
        margin-right: 10px;
    }
    .custom-tabs .nav-link:hover {
        background: #f4f2f9;
        color: #4700c8;
    }
    .custom-tabs .nav-link.active {
        background: #4700c8 !important;
        color: #fff !important;
    }
    .btn-outline {
        background: transparent;
        border: 1px solid #4700c8;
        color: #4700c8;
    }
    .btn-outline:hover {
        background: #4700c8;
        color: white;
    }
    .dataTables_wrapper .dataTables_filter input {
        width: 250px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var sel = document.getElementById('lf-mobile-select');
  if (sel) {
    sel.addEventListener('change', function() {
      var url = this.value;
      if (url) window.location.href = url;
    });
  }

  if (typeof $ !== 'undefined' && $('#lf-table').length) {
    var table = $('#lf-table').DataTable({
      order: [[5, 'desc']],
      pageLength: 10,
      dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
      language: {
        lengthMenu: '_MENU_ per page'
      }
    });

    // Custom search input
    $('#lfSearch').on('keyup change', function() {
      table.search(this.value).draw();
    });

    // Filter dropdown redirects to server-side filtered tabs
    $('#lfTypeFilter').on('change', function() {
      var url = this.value;
      if (url) window.location.href = url;
    });
  }
});
</script>
@endpush
