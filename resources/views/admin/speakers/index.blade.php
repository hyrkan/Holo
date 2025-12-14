@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Speakers</h5>
                    <div class="card-header-action">
                        <form action="{{ route('admin.speakers.index') }}" method="GET" class="me-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search speaker..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit"><i class="feather-search"></i></button>
                            </div>
                        </form>
                        <a href="{{ route('admin.speakers.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Create Speaker
                        </a>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Speaker Info</th>
                                    <th>Work</th>
                                    <th>Socials</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($speakers as $speaker)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-image">
                                                <img src="{{ $speaker->image ? asset('storage/'.$speaker->image) : asset('assets/images/no-image.png') }}" alt="" class="img-fluid" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                            </div>
                                            <a href="{{ route('admin.speakers.show', $speaker) }}">
                                                <span class="d-block fw-bold">{{ $speaker->first_name }} {{ $speaker->last_name }}</span>
                                                <span class="fs-12 d-block fw-normal text-muted">{{ $speaker->email }}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $speaker->title ?? 'N/A' }}</span>
                                            <span class="text-muted fs-12">{{ $speaker->company ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2">
                                            @if($speaker->website)
                                                <a href="{{ $speaker->website }}" target="_blank" class="text-muted" data-bs-toggle="tooltip" title="Website"><i class="feather-globe"></i></a>
                                            @endif
                                            @if($speaker->linkedin)
                                                <a href="{{ $speaker->linkedin }}" target="_blank" class="text-muted" data-bs-toggle="tooltip" title="LinkedIn"><i class="feather-linkedin"></i></a>
                                            @endif
                                            @if($speaker->twitter)
                                                <a href="{{ $speaker->twitter }}" target="_blank" class="text-muted" data-bs-toggle="tooltip" title="Twitter"><i class="feather-twitter"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="hstack gap-3 justify-content-end">
                                            <a href="{{ route('admin.speakers.edit', $speaker) }}" class="text-secondary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="feather-edit-3 fs-16"></i>
                                            </a>
                                            <form action="{{ route('admin.speakers.destroy', $speaker) }}" method="POST" class="d-inline">
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
                                    <td colspan="4" class="text-center py-5">
                                        <i class="feather-mic fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No speakers found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $speakers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
