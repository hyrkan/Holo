@extends('layouts.admin')

@section('title', 'Event Participants || Holo Board')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    .avatar-image {
        width: 40px;
        height: 40px;
        overflow: hidden;
        border-radius: 50%;
    }
    .avatar-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Participants for: {{ $event->name }}</h5>
                        <p class="text-muted small mb-0">Total Registered: {{ $participants->count() }} / {{ $event->capacity ?: 'Unlimited' }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-light btn-sm">
                            <i class="feather-arrow-left me-2"></i> Back to Event
                        </a>
                        <button class="btn btn-primary btn-sm" id="export-excel">
                            <i class="feather-download me-2"></i> Export CSV
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs nav-tabs-custom px-4 pt-3 border-bottom-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.events.participants', $event) }}">
                                <i class="feather-users me-2"></i>Registration List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.events.attendance', $event) }}">
                                <i class="feather-check-square me-2"></i>Attendance Sheet
                            </a>
                        </li>
                    </ul>
                    
                    <div class="p-4 pt-2">
                        <div class="table-responsive">
                        <table id="participants-table" class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Student Number</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                    <th>Registration Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($participants as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-text bg-soft-primary text-primary rounded-circle me-3">
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</h6>
                                                    <small class="text-muted">{{ $student->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->student_number }}</td>
                                        <td>{{ $student->program }}</td>
                                        <td>{{ $student->year_level }}</td>
                                        <td>{{ $student->pivot->created_at ? $student->pivot->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-soft-{{ $student->pivot->status === 'registered' ? 'success' : 'danger' }} text-{{ $student->pivot->status === 'registered' ? 'success' : 'danger' }}">
                                                {{ ucfirst($student->pivot->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                                    <i class="feather-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('admin.students.show', $student) }}" class="dropdown-item">
                                                        <i class="feather-eye me-2"></i> View Profile
                                                    </a>
                                                    {{-- Add more actions if needed, e.g., cancel registration --}}
                                                </div>
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
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#participants-table').DataTable({
            "order": [[4, "desc"]], // Order by registration date
            "pageLength": 25,
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search participants..."
            }
        });

        // Simple CSV Export logic
        $('#export-excel').click(function() {
            let csv = [];
            let rows = document.querySelectorAll("table tr");
            
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll("td, th");
                
                for (let j = 0; j < cols.length - 1; j++) { // Skip actions column
                    // Clean text content
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                    row.push('"' + text + '"');
                }
                csv.push(row.join(","));
            }

            let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
            let downloadLink = document.createElement("a");
            downloadLink.download = "participants-{{ Str::slug($event->name) }}.csv";
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
        });
    });
</script>
@endpush
