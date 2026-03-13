﻿@extends('layouts.admin')

@section('title', 'Students List || Holo Board')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="card-title mb-0">Students</h5>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <select id="statusFilter" class="form-select form-select-sm">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="denied">Denied</option>
                                <option value="expired">Expired</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <select id="typeFilter" class="form-select form-select-sm">
                                <option value="all">All Types</option>
                                <option value="regular">Regular</option>
                                <option value="guest">Guest</option>
                            </select>
                        </div>
                        <div>
                            <input id="programFilter" type="text" class="form-control form-control-sm" placeholder="Filter Program">
                        </div>
                        <div>
                            <a id="exportCsvBtn" href="{{ route('admin.students.export') }}" class="btn btn-sm btn-light">
                                <i class="feather-download me-1"></i> Download CSV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table id="students-table" class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Name</th>
                                    <th>Student Number</th>
                                    <th>Program</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Joined At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <a href="{{ route('admin.students.show', $student) }}" class="d-block fw-bold text-dark">{{ $student->first_name }} {{ $student->last_name }}</a>
                                                <span class="fs-12 d-block fw-normal text-muted">{{ $student->user->email ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->student_number }}</td>
                                    <td>{{ $student->program ?? 'Not Assigned' }}</td>
                                    <td>
                                        <span class="badge bg-soft-info text-info">{{ ucfirst($student->student_type) }}</span>
                                    </td>
                                    <td>
                                        @if($student->status === 'pending')
                                            <span class="badge bg-soft-warning text-warning">Pending</span>
                                        @elseif($student->status === 'approved')
                                            <span class="badge bg-soft-success text-success">Approved</span>
                                        @elseif($student->status === 'denied')
                                            <span class="badge bg-soft-danger text-danger">Denied</span>
                                        @elseif($student->status === 'expired')
                                            <span class="badge bg-soft-secondary text-secondary">Expired</span>
                                        @elseif($student->status === 'inactive')
                                            <span class="badge bg-soft-dark text-dark">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->created_at->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a href="javascript:void(0);" class="btn btn-light btn-icon btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="feather-more-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @if($student->status === 'pending')
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#approveModal{{ $student->id }}">
                                                        <i class="feather-check-circle me-2"></i> Approve
                                                    </button>
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#denyModal{{ $student->id }}">
                                                        <i class="feather-x-circle me-2"></i> Deny
                                                    </button>
                                                    <div class="dropdown-divider"></div>
                                                @endif
                                                <a href="{{ route('admin.students.edit', $student) }}" class="dropdown-item">
                                                    <i class="feather-edit-2 me-2"></i> Edit
                                                </a>
                                                <a href="{{ route('admin.students.show', $student) }}" class="dropdown-item">
                                                    <i class="feather-eye me-2"></i> View
                                                </a>
                                                @if($student->status !== 'inactive')
                                                    <div class="dropdown-divider"></div>
                                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal{{ $student->id }}">
                                                        <i class="feather-trash-2 me-2"></i> Set Inactive
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="feather-users fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No students found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('modals')
@foreach ($students as $student)
    @if($student->status === 'pending')
        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal{{ $student->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.students.approve', $student) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="approveModalLabel{{ $student->id }}">Approve Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <p>Assign a program and year level for <strong>{{ $student->full_name }}</strong>:</p>
                            <div class="mb-3">
                                <label class="form-label">Program</label>
                                <input type="text" name="program" class="form-control" placeholder="e.g. BSIT" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Year Level</label>
                                <select name="year_level" class="form-control" required>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve & Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Deny Modal -->
        <div class="modal fade" id="denyModal{{ $student->id }}" tabindex="-1" aria-labelledby="denyModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.students.deny', $student) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="denyModalLabel{{ $student->id }}">Deny Registration</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <p>Are you sure you want to deny <strong>{{ $student->full_name }}</strong>?</p>
                            <div class="mb-3">
                                <label class="form-label">Reason (Optional)</label>
                                <textarea name="reason" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Confirm Deny</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@foreach ($students as $student)
    @if($student->status !== 'inactive')
        <div class="modal fade" id="deactivateModal{{ $student->id }}" tabindex="-1" aria-labelledby="deactivateModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="deactivateModalLabel{{ $student->id }}">Set Account Inactive</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <p>Set <strong>{{ $student->full_name }}</strong>'s account to Inactive? The user will be unable to log in.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#students-table').DataTable({
            order: [[5, 'desc']],
            pageLength: 10,
            dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
            language: {
                search: "",
                searchPlaceholder: "Search students...",
                lengthMenu: "_MENU_ per page",
            }
        });

        // Style search box
        $('.dataTables_filter input').addClass('form-control form-control-sm').css({
            width: '250px',
            display: 'inline-block',
            marginLeft: '0'
        });
        $('.dataTables_filter label').addClass('mb-0');

        // Initialize filters from current request if present
        var initStatus = "{{ request('status', 'all') }}";
        $('#statusFilter').val(initStatus);

        // Status filter (column index 4)
        $('#statusFilter').on('change', function() {
            var val = $(this).val();
            table.column(4).search(val === 'all' ? '' : val, true, false).draw();
        }).trigger('change');

        $('#exportCsvBtn').on('click', function(e) {
            e.preventDefault();
            var status = $('#statusFilter').val();
            var url = $(this).attr('href');
            if (status && status !== 'all') {
                url += (url.indexOf('?') === -1 ? '?' : '&') + 'status=' + encodeURIComponent(status);
            }
            window.location.href = url;
        });

        // Type filter (column index 3)
        $('#typeFilter').on('change', function() {
            var val = $(this).val();
            table.column(3).search(val === 'all' ? '' : val, true, false).draw();
        });

        // Program filter (column index 2, plain text)
        var programDebounce;
        $('#programFilter').on('input', function() {
            clearTimeout(programDebounce);
            var val = this.value;
            programDebounce = setTimeout(function(){
                table.column(2).search(val).draw();
            }, 250);
        });
        // Auto-set Classification when Year Level changes (index page modals)
        document.querySelectorAll('.year-level-sel').forEach(function(sel) {
            var studentId = sel.getAttribute('data-student');
            sel.addEventListener('change', function () {
                var classSel = document.getElementById('classification_' + studentId);
                if (classSel && this.value === '1st Year') {
                    classSel.value = 'freshie';
                }
            });
            // Init
            var classSel = document.getElementById('classification_' + sel.getAttribute('data-student'));
            if (classSel && sel.value === '1st Year') {
                classSel.value = 'freshie';
            }
        });
    });
</script>
@endpush
