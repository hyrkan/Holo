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
    .registration-row.selected {
        background-color: rgba(110, 98, 255, 0.05);
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
                        <!-- Bulk Actions -->
                        <div id="bulk-actions" class="mb-3 d-none">
                            <form action="{{ route('admin.events.certificate.bulk', $event) }}" method="POST" id="bulk-form" class="d-flex align-items-center gap-2 bg-light p-3 rounded">
                                @csrf
                                <span class="fw-bold text-dark"><span id="selected-count">0</span> Selected:</span>
                                <input type="hidden" name="action" id="bulk-action-type">
                                <div id="selected-ids-container"></div>
                                <button type="button" class="btn btn-success btn-sm" onclick="submitBulk('award')">
                                    <i class="feather-award me-1"></i> Award Certificate
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="submitBulk('revoke')">
                                    <i class="feather-x-circle me-1"></i> Revoke Certificate
                                </button>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table id="participants-table" class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="40"><input type="checkbox" id="select-all" class="form-check-input"></th>
                                        <th>Student</th>
                                        <th>Student Number</th>
                                        <th>Program</th>
                                        <th>Registration Date</th>
                                        <th>Certificate</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($participants as $student)
                                        <tr class="registration-row">
                                            <td>
                                                <input type="checkbox" class="form-check-input participant-checkbox" value="{{ $student->id }}">
                                            </td>
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
                                            <td>{{ $student->pivot->created_at ? $student->pivot->created_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($student->pivot->is_eligible_for_certificate)
                                                    <span class="badge bg-soft-success text-success">
                                                        <i class="feather-award me-1"></i> Eligible
                                                    </span>
                                                @else
                                                    <span class="badge bg-soft-secondary text-secondary">
                                                        Not Awarded
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);" class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                                        <i class="feather-more-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <form action="{{ route('admin.events.certificate.toggle', [$event, $student]) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="feather-{{ $student->pivot->is_eligible_for_certificate ? 'x-circle' : 'award' }} me-2"></i>
                                                                {{ $student->pivot->is_eligible_for_certificate ? 'Revoke Certificate' : 'Award Certificate' }}
                                                            </button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="{{ route('admin.students.show', $student) }}" class="dropdown-item">
                                                            <i class="feather-eye me-2"></i> View Profile
                                                        </a>
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
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#participants-table').DataTable({
            "order": [[4, "desc"]],
            "pageLength": 50,
            "columnDefs": [
                { "orderable": false, "targets": [0, 6] }
            ],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search participants..."
            }
        });

        // Bulk Selection Logic
        const selectAll = $('#select-all');
        const checkboxes = $('.participant-checkbox');
        const bulkActions = $('#bulk-actions');
        const selectedCount = $('#selected-count');

        function updateBulkUI() {
            const checked = $('.participant-checkbox:checked');
            const count = checked.length;
            
            if (count > 0) {
                bulkActions.removeClass('d-none');
                selectedCount.text(count);
            } else {
                bulkActions.addClass('d-none');
            }

            $('.registration-row').each(function() {
                if ($(this).find('.participant-checkbox').is(':checked')) {
                    $(this).addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            });
        }

        selectAll.on('change', function() {
            $('.participant-checkbox', table.rows({ search: 'applied' }).nodes()).prop('checked', this.checked);
            updateBulkUI();
        });

        $('#participants-table tbody').on('change', '.participant-checkbox', function() {
            updateBulkUI();
            if(!this.checked) {
                selectAll.prop('checked', false);
            }
            if($('.participant-checkbox:checked').length == $('.participant-checkbox').length) {
                selectAll.prop('checked', true);
            }
        });

        window.submitBulk = function(action) {
            if (confirm(`Are you sure you want to ${action} certificates for the selected participants?`)) {
                const container = $('#selected-ids-container');
                container.empty();
                
                $('.participant-checkbox:checked').each(function() {
                    container.append(`<input type="hidden" name="student_ids[]" value="${$(this).val()}">`);
                });

                $('#bulk-action-type').val(action);
                $('#bulk-form').submit();
            }
        };

        // CSV Export
        $('#export-excel').click(function() {
            let csv = [];
            let rows = document.querySelectorAll("table tr");
            
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll("td, th");
                for (let j = 1; j < cols.length - 1; j++) { // Skip checkbox and actions
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                    row.push('"' + text + '"');
                }
                if (row.length > 0) csv.push(row.join(","));
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
