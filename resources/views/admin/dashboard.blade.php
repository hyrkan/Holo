@extends('layouts.admin')

@section('title', 'Dashboard - Duralux')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Dashboard</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Dashboard</li>
        </ul>
    </div>
    <div class="page-header-right ms-auto">
        <div class="page-header-right-items">
            <div class="d-flex d-md-none">
                <a href="javascript:void(0)" class="page-header-right-close-toggle">
                    <i class="feather-arrow-left me-2"></i>
                    <span>Back</span>
                </a>
            </div>
            <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                <div id="reportrange" class="reportrange-picker d-flex align-items-center">
                    <span class="reportrange-picker-field"></span>
                </div>
            </div>
        </div>
        <div class="d-md-none d-flex align-items-center">
            <a href="javascript:void(0)" class="page-header-right-open-toggle">
                <i class="feather-align-right fs-20"></i>
            </a>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->

<!-- [ Main Content ] start -->
<div class="main-content">
    <div class="row">
        {{-- Your dashboard content goes here --}}
        <!-- [Mini] start -->
        <div class="col-lg-4">
            <div class="card mb-4 stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="avatar-text">
                            <i class="feather feather-star"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Tasks Completed</div>
                            <div class="fs-12 text-muted">22/35 completed</div>
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-dark">22/35</div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between gap-4">
                    <div id="task-completed-area-chart"></div>
                    <div class="fs-12 text-muted text-nowrap">
                        <span class="fw-semibold text-primary">28% more</span><br />
                        <span>from last week</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4 stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="avatar-text">
                            <i class="feather feather-file-text"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">New Tasks</div>
                            <div class="fs-12 text-muted">0/20 tasks</div>
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-dark">5/20</div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between gap-4">
                    <div id="new-tasks-area-chart"></div>
                    <div class="fs-12 text-muted text-nowrap">
                        <span class="fw-semibold text-success">34% more</span><br />
                        <span>from last week</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4 stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="avatar-text">
                            <i class="feather feather-airplay"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Project Done</div>
                            <div class="fs-12 text-muted">20/30 project</div>
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-dark">20/30</div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between gap-4">
                    <div id="project-done-area-chart"></div>
                    <div class="fs-12 text-muted text-nowrap">
                        <span class="fw-semibold text-danger">42% more</span><br />
                        <span>from last week</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- [Mini] end !-->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@push('scripts')
{{-- Add page-specific scripts here --}}
@endpush
