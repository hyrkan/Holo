@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Announcements</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Create Announcement
                        </a>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Title</th>
                                    <th>Status</th>
                                    <th>Target</th>
                                    <th>Duration</th>
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
                                    <td class="text-end">
                                        <div class="hstack gap-3 justify-content-end">
                                            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-secondary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="feather-edit-3 fs-16"></i>
                                            </a>
                                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger border-0 bg-transparent p-0" onclick="return confirm('Are you sure?')" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="feather-trash-2 fs-16"></i>
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
                <div class="card-footer">
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
