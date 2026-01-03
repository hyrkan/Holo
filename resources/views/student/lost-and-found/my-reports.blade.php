@extends('layouts.student')

@section('title', 'My Lost & Found Reports || Holo Board')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">My Reports</h5>
                    <a href="{{ route('lost-and-found.create') }}" class="btn btn-primary btn-sm">
                        <i class="feather-plus me-2"></i> New Report
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
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
                                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="" class="img-fluid rounded" style="width: 40px; height: 40px; object-fit: cover;">
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
                                    <td>{{ $item->date_reported->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('lost-and-found.show', $item) }}" class="btn btn-sm btn-soft-primary">
                                            <i class="feather-eye"></i> View
                                        </a>
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
                <div class="card-footer">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
