@extends('layouts.admin')

@section('title', 'Dashboard - HoloBoard')

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
        <!-- [ Stats Cards ] start -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div class="d-flex gap-4 align-items-center">
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded">
                                <i class="feather-users"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $stats['total_students'] }}</span></div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Total Students</h3>
                            </div>
                        </div>
                        @if($stats['pending_students'] > 0)
                        <a href="{{ route('admin.students.index', ['status' => 'pending']) }}" class="badge bg-soft-warning text-warning">{{ $stats['pending_students'] }} Pending</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div class="d-flex gap-4 align-items-center">
                            <div class="avatar-text avatar-lg bg-soft-success text-success rounded">
                                <i class="feather-calendar"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $stats['total_events'] }}</span></div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Total Events</h3>
                            </div>
                        </div>
                        <span class="badge bg-soft-success text-success">{{ $stats['active_events'] }} Active</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div class="d-flex gap-4 align-items-center">
                            <div class="avatar-text avatar-lg bg-soft-danger text-danger rounded">
                                <i class="feather-archive"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $stats['total_lost'] + $stats['total_found'] }}</span></div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Lost & Found</h3>
                            </div>
                        </div>
                        <span class="badge bg-soft-info text-info">{{ $stats['resolved_lost_found'] }} Resolved</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div class="d-flex gap-4 align-items-center">
                            <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded">
                                <i class="feather-percent"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $attendanceRate }}</span>%</div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Attendance Rate</h3>
                            </div>
                        </div>
                        <span class="badge bg-soft-primary text-primary">Overall</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Stats Cards ] end -->

        <!-- [ Charts ] start -->
        <div class="col-xxl-8 col-lg-7">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Registration Trends</h5>
                    <div class="card-header-action">
                        <span class="badge bg-soft-primary text-primary">{{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="registration-trend-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-lg-5">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Event Scheduling Overview</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="p-3 bg-soft-primary rounded d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 text-primary">Scheduled This Week</h6>
                                <small class="text-muted">Unique Events</small>
                            </div>
                            <h3 class="fw-bold mb-0 text-primary">{{ $schedulingStats['this_week'] }}</h3>
                        </div>
                        <div class="p-3 bg-soft-success rounded d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 text-success">Scheduled This Month</h6>
                                <small class="text-muted">Overall count</small>
                            </div>
                            <h3 class="fw-bold mb-0 text-success">{{ $schedulingStats['this_month'] }}</h3>
                        </div>
                        <div class="p-3 bg-soft-warning rounded d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 text-warning">Scheduled This Year</h6>
                                <small class="text-muted">Annual summary</small>
                            </div>
                            <h3 class="fw-bold mb-0 text-warning">{{ $schedulingStats['this_year'] }}</h3>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="card-title mb-3">Lost & Found Status</h6>
                    <div id="lost-found-chart"></div>
                </div>
            </div>
        </div>
        <!-- [ Charts ] end -->

        <!-- [ Tables ] start -->
        <div class="col-xxl-7 col-lg-6">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Recent Student Registrations</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-light">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Program</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentStudents as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text avatar-md bg-soft-primary text-primary">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $student->full_name }}</div>
                                                <div class="fs-12 text-muted">{{ $student->student_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->program }}</td>
                                    <td>
                                        @if($student->status == 'approved')
                                            <span class="badge bg-soft-success text-success">Approved</span>
                                        @elseif($student->status == 'pending')
                                            <span class="badge bg-soft-warning text-warning">Pending</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger">{{ ucfirst($student->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-5 col-lg-6">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Popular Events</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-light">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Registrations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularEvents as $event)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $event->name }}</div>
                                        <div class="fs-12 text-muted">{{ $event->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress w-100" style="height: 6px;">
                                                @php
                                                    $maxReg = $popularEvents->max('registrations_count');
                                                    $percent = $maxReg > 0 ? ($event->registrations_count / $maxReg) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="fw-bold text-dark">{{ $event->registrations_count }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Recent Lost & Found Reports</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.lost-and-found.index') }}" class="btn btn-sm btn-light">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Date Reported</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLostFound as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->item_name }}</div>
                                        <div class="fs-12 text-muted">{{ Str::limit($item->description, 50) }}</div>
                                    </td>
                                    <td>
                                        @if($item->type == 'lost')
                                            <span class="badge bg-soft-danger text-danger">Lost</span>
                                        @else
                                            <span class="badge bg-soft-success text-success">Found</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->location }}</td>
                                    <td>
                                        @if($item->status == 'resolved')
                                            <span class="badge bg-soft-success text-success">Resolved</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning">Active</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->date_reported->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Tables ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="{{ asset('assets/vendors/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/js/circle-progress.min.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Registration Trend Chart
            const registrationTrendOptions = {
                series: [{
                    name: 'Registrations',
                    data: @json($registrationTrends->pluck('count'))
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: @json($registrationTrends->pluck('date')),
                    labels: {
                        formatter: function(value) {
                            return new Date(value).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        }
                    }
                },
                colors: ['#3454d1'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
            };

            const registrationTrendChart = new ApexCharts(document.querySelector("#registration-trend-chart"), registrationTrendOptions);
            registrationTrendChart.render();

            // Lost & Found Status Chart
            const lostFoundOptions = {
                series: [
                    {{ $lostAndFoundStats['lost_active'] }}, 
                    {{ $lostAndFoundStats['lost_resolved'] }}, 
                    {{ $lostAndFoundStats['found_active'] }}, 
                    {{ $lostAndFoundStats['found_resolved'] }}
                ],
                chart: {
                    height: 300,
                    type: 'donut',
                },
                labels: ['Lost (Active)', 'Lost (Resolved)', 'Found (Active)', 'Found (Resolved)'],
                colors: ['#ea4d4d', '#f78e8e', '#25b76e', '#79dcb1'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%'
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const lostFoundChart = new ApexCharts(document.querySelector("#lost-found-chart"), lostFoundOptions);
            lostFoundChart.render();

            // Date Range Picker
            var start = moment('{{ $startDate->toDateTimeString() }}');
            var end = moment('{{ $endDate->toDateTimeString() }}');

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end) {
                cb(start, end);
                window.location.href = "{{ route('admin.dashboard') }}?start_date=" + start.format('YYYY-MM-DD') + "&end_date=" + end.format('YYYY-MM-DD');
            });

            cb(start, end);
        });
    </script>
@endpush
