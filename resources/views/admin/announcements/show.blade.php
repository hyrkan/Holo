@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Announcement Details</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-primary">
                            <i class="feather-edit me-2"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="mb-3">{{ $announcement->title }}</h2>
                            <div class="mb-4">
                                <span class="badge bg-soft-primary text-primary me-2">
                                    Start: {{ $announcement->start_date->format('d M, Y H:i') }}
                                </span>
                                <span class="badge bg-soft-secondary text-secondary">
                                    End: {{ $announcement->end_date->format('d M, Y H:i') }}
                                </span>
                            </div>
                            <div class="mb-4">
                                @if($announcement->is_draft)
                                    <span class="badge bg-warning">Draft</span>
                                @endif
                                @if($announcement->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                                <span class="badge bg-soft-info text-info ms-2">
                                    Target: {{ ucfirst($announcement->target_audience) }}
                                    @if($announcement->target_audience == 'students' && $announcement->target_year_levels)
                                        ({{ implode(', ', $announcement->target_year_levels) }})
                                    @endif
                                </span>
                            </div>
                            <div class="content mb-5">
                                {!! nl2br(e($announcement->body)) !!}
                            </div>

                            @if($announcement->attachments->count() > 0)
                                <div class="attachments mt-4">
                                    <h5 class="mb-3">Downloadable Attachments:</h5>
                                    <div class="row">
                                        @foreach($announcement->attachments as $attachment)
                                            <div class="col-md-6 mb-3">
                                                <div class="card bg-light border-0">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center overflow-hidden">
                                                                <div class="avatar-sm bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                                    <i class="feather-file fs-16"></i>
                                                                </div>
                                                                <div class="overflow-hidden">
                                                                    <h6 class="mb-0 text-truncate">{{ $attachment->file_name }}</h6>
                                                                    <small class="text-muted text-uppercase">{{ $attachment->file_type }} • {{ number_format($attachment->file_size / 1024, 2) }} KB</small>
                                                                </div>
                                                            </div>
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-3" download>
                                                                <i class="feather-download me-1"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if($announcement->image)
                                <img src="{{ Storage::url($announcement->image) }}" alt="{{ $announcement->title }}" class="img-fluid rounded">
                            @else
                                <div class="p-5 bg-light text-center rounded text-muted">
                                    No Image
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
