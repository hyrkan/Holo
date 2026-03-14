@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Archived Announcements</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                            <i class="feather-arrow-left me-2"></i> Back to Announcements
                        </a>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Title</th>
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
                                            <form action="{{ route('admin.announcements.restore', $announcement) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-soft-success" 
                                                    data-confirm-title="Restore Announcement"
                                                    data-confirm-message="Are you sure you want to restore '{{ $announcement->title }}'?"
                                                    data-confirm-type="success"
                                                    data-confirm-icon="rotate-ccw"
                                                    data-confirm-btn-text="Restore">
                                                    <i class="feather-rotate-ccw me-1"></i> Restore
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="feather-archive fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No archived announcements found.</p>
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
