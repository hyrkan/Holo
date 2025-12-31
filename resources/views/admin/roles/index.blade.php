@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Roles</h5>
                    <div class="card-header-action">
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Add Role
                        </a>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Name</th>
                                    <th>Permissions</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                <tr>
                                    <td>{{ ucfirst($role->name) }}</td>
                                    <td>
                                        @foreach($role->permissions as $permission)
                                            <span class="badge bg-soft-primary text-primary">{{ $permission->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-end">
                                        <div class="hstack gap-3 justify-content-end">
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="text-secondary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="feather-edit-3 fs-16"></i>
                                            </a>
                                            @if($role->name !== 'admin')
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger border-0 bg-transparent p-0" onclick="return confirm('Are you sure?')" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="feather-trash-2 fs-16"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <i class="feather-shield fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No roles found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
