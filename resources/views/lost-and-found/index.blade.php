@extends(Auth::guard('student')->check() ? 'layouts.student' : 'layouts.landing')

@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    #lf-table_wrapper .dataTables_filter {
        display: none;
    }
    #lf-table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: #6c757d;
        border-bottom: 2px solid #edeff2;
    }
    .img-cell {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endpush

<!-- lost-and-found-area -->
<section id="lost-and-found" class="{{ Auth::guard('student')->check() ? 'p-4' : 'pt-120 pb-120' }}">
    <div class="container">
        <div class="row mb-50">
            <div class="col-lg-12 text-center mb-40">
                <div class="section-title mb-20 {{ Auth::guard('student')->check() ? 'd-none' : '' }}">
                    <span class="sub-title">Lost & Found</span>
                    <h2 class="title">Campus Service Portal</h2>
                </div>
                <div class="lost-found-tabs d-flex align-items-center justify-content-center" style="border-bottom: 2px solid #eee; padding-bottom: 20px;">
                    <div class="d-sm-none me-3" style="width: 220px;">
                        <select id="lf-mobile-select" class="form-select">
                            <option value="{{ route('lost-and-found.index', ['type' => 'all']) }}" {{ $type == 'all' ? 'selected' : '' }}>All Reports</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'lost']) }}" {{ $type == 'lost' ? 'selected' : '' }}>Lost Items</option>
                            <option value="{{ route('lost-and-found.index', ['type' => 'found']) }}" {{ $type == 'found' ? 'selected' : '' }}>Found Items</option>
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
                    </ul>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-30 alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm p-4">
                    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="mb-0 fw-bold">Recent Reports</h4>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ count($items) }} Items</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center flex-grow-1" style="max-width: 450px;">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="feather-search"></i></span>
                                <input id="lfSearch" type="text" class="form-control border-start-0" placeholder="Search by item, location, or keyword...">
                            </div>
                            @if(!Auth::guard('student')->check())
                                <a href="{{ route('lost-and-found.create') }}" class="btn btn-primary text-nowrap">
                                    <i class="fas fa-plus me-1"></i> Report Item
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="lf-table" class="table table-hover align-middle w-100">
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
                                    $displayName = $item->is_anonymous ? 'Anonymous' : ($item->reporter_name ?: ($item->user ? ($item->user->name ?? ($item->user->student ? $item->user->student->full_name : 'User')) : 'Staff'));
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->image_path)
                                                <img src="{{ $item->image_url }}" alt="" class="img-cell border shadow-sm">
                                            @else
                                                <div class="bg-light border text-muted d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; border-radius: 8px;">
                                                    <i class="feather-image small"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('lost-and-found.show', $item) }}" class="fw-bold text-dark d-block">{{ $item->item_name }}</a>
                                                <small class="text-muted d-block text-truncate" style="max-width: 150px;">{{ Str::limit($item->description, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->type == 'lost' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} text-uppercase px-2 fs-10">
                                            {{ $item->type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="feather-map-pin me-1"></i>
                                            <span>{{ $item->location }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="small fw-medium">{{ $displayName }}</span>
                                    </td>
                                    <td>
                                        @if($item->status == 'resolved')
                                            <span class="badge bg-success px-2">Resolved</span>
                                        @elseif($item->status == 'active')
                                            <span class="badge bg-info px-2">Active</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-2">Pending</span>
                                        @endif
                                    </td>
                                    <td data-order="{{ $item->date_reported?->format('Y-m-d') }}">
                                        <span class="small text-muted">{{ $item->date_reported?->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('lost-and-found.show', $item) }}" class="btn btn-sm btn-icon btn-light" title="View Details">
                                            <i class="feather-eye"></i>
                                        </a>
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
    </div>
</section>
@endsection

@push('css')
<style>
    .custom-tabs .nav-link {
        color: #666;
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 30px;
        transition: all 0.3s;
        margin: 0 5px;
        background: #f8f9fa;
        border: 1px solid #eee;
    }
    .custom-tabs .nav-link:hover {
        background: #eee;
        color: #333;
    }
    .custom-tabs .nav-link.active {
        background: #4700c8 !important;
        color: #fff !important;
        border-color: #4700c8;
        box-shadow: 0 4px 10px rgba(71, 0, 200, 0.2);
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    #lf-table tr {
        transition: background 0.2s;
    }
    #lf-table tr:hover {
        background-color: #fcfbff !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
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
      responsive: true,
      dom: "<'row'<'col-sm-12'tr>>" +
           "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
      language: {
        paginate: {
            previous: '<i class="feather-chevron-left"></i>',
            next: '<i class="feather-chevron-right"></i>'
        }
      }
    });

    // Custom search input
    $('#lfSearch').on('keyup change', function() {
      table.search(this.value).draw();
    });
  }
});
</script>
@endpush
