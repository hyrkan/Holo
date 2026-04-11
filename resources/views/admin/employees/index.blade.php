@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Holo Tech</h5>
                    <div class="card-header-action">
                        <form action="{{ route('admin.employees.index') }}" method="GET" class="me-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search name..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit"><i class="feather-search"></i></button>
                            </div>
                        </form>
                        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Add Holo Tech
                        </a>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Name</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Joined At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">

                                            <a href="{{ route('admin.employees.show', $employee) }}">
                                                <span class="d-block">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                                                <span class="fs-12 d-block fw-normal text-muted">{{ $employee->user->email ?? 'N/A' }}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-info text-info text-uppercase">{{ $employee->user->roles->first()?->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{Str::limit($employee->address, 30) }}</td>
                                    <td>{{ $employee->created_at->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <div class="hstack gap-3 justify-content-end">
                                            <a href="{{ route('admin.employees.edit', $employee) }}" class="text-secondary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="feather-edit-3 fs-16"></i>
                                            </a>
                                            <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="d-inline">
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
                                    <td colspan="5" class="text-center py-5">
                                        <i class="feather-users fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No holo tech found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
