@extends('layouts.admin')

@section('title', 'Event Participants || Holo Board')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #e2e5e9;
        border-radius: 4px;
        min-height: 31px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #6e62ff;
        border: 1px solid #6e62ff;
        color: white;
        border-radius: 3px;
        padding: 0 5px;
        margin-top: 3px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent;
        color: #ddd;
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
                            <form action="{{ route('admin.events.certificate.bulk', $event) }}" method="POST" id="bulk-form" class="bg-light p-3 rounded">
                                @csrf
                                <div class="row align-items-end g-3">
                                    <div class="col-md-3">
                                        <span class="fw-bold text-dark d-block mb-1"><span id="selected-count">0</span> Selected Participants</span>
                                        <div id="selected-ids-container"></div>
                                        <input type="hidden" name="action" id="bulk-action-type">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small fw-bold">Select Certificates</label>
                                        <select name="certificate_ids[]" class="form-select select2-multiple" multiple data-placeholder="Choose certificates...">
                                            @foreach($event->certificates as $cert)
                                                <option value="{{ $cert->id }}">{{ $cert->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex gap-2">
                                        <button type="button" class="btn btn-success btn-sm flex-grow-1" onclick="submitBulk('award')">
                                            <i class="feather-award me-1"></i> Award
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm flex-grow-1" onclick="submitBulk('revoke')">
                                            <i class="feather-x-circle me-1"></i> Revoke
                                        </button>
                                    </div>
                                </div>
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
                                        <th>Awarded Certificates</th>
                                        <th class="text-end" width="100">Actions</th>
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
                                            <td>
                                                <select class="form-select select2-multiple student-eligibility" 
                                                        multiple 
                                                        data-student-id="{{ $student->id }}"
                                                        data-url="{{ route('admin.events.certificate.update-eligibility', [$event, $student]) }}"
                                                        data-placeholder="Award certificates...">
                                                    @foreach($event->certificates as $cert)
                                                        @php
                                                            $isAwarded = $student->certificates->contains($cert->id);
                                                        @endphp
                                                        <option value="{{ $cert->id }}" {{ $isAwarded ? 'selected' : '' }}>
                                                            {{ $cert->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2-multiple').select2({
            width: '100%'
        });

        const table = $('#participants-table').DataTable({
            "order": [[1, "asc"]],
            "pageLength": 50,
            "columnDefs": [
                { "orderable": false, "targets": [0, 4, 5] }
            ],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search participants..."
            }
        });

        // Individual Eligibility Update
        $('.student-eligibility').on('change', function() {
            const url = $(this).data('url');
            const certificateIds = $(this).val();
            
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    certificate_ids: certificateIds
                },
                success: function(response) {
                    // Optional: Show toast
                }
            });
        });

        // Bulk Selection Logic
        const selectAll = $('#select-all');
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
            const certIds = $('#bulk-form select').val();
            if (!certIds || certIds.length === 0) {
                alert('Please select at least one certificate type.');
                return;
            }

            if (confirm(`Are you sure you want to ${action} selected certificates for the selected participants?`)) {
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
