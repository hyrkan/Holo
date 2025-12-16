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
                            </div>
                            <div class="content">
                                {!! nl2br(e($announcement->body)) !!}
                            </div>
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
