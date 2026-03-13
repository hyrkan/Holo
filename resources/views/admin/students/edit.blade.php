@extends('layouts.admin')

@section('title', 'Edit Student || Holo Board')

@section('content')
<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Edit Student</h5>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-light btn-sm">Back</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.students.update', $student) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="csrf_token_edit" value="{{ csrf_token() }}">
                        <div class="mb-3">
                            <label class="form-label">Student</label>
                            <input type="text" class="form-control" value="{{ $student->first_name }} {{ $student->last_name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $student->user->email ?? 'N/A' }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program</label>
                            <select name="program" id="edit_program" class="form-select">
                                <option value="">-- Select Program --</option>
                                @foreach(($programs ?? collect())->where('is_active', true) as $prog)
                                    @php $pname = strtoupper($prog->name); @endphp
                                    <option value="{{ $pname }}" {{ old('program', $student->program) === $pname ? 'selected' : '' }}>{{ $pname }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <button id="manageProgramsBtnEdit" class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#manageProgramsEdit" aria-expanded="false" aria-controls="manageProgramsEdit" data-list-url="{{ route('admin.programs.index') }}">
                                    <i class="feather-settings me-1"></i> Manage Programs
                                </button>
                            </div>
                            <div class="collapse mt-3" id="manageProgramsEdit">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-2">Add Program</h6>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-4">
                                            <input id="program_add_name_edit" type="text" class="form-control" placeholder="Program name">
                                        </div>
                                        <div class="col-md-6">
                                            <input id="program_add_desc_edit" type="text" class="form-control" placeholder="Description (optional)">
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button id="program_add_btn_edit" type="button" class="btn btn-primary btn-sm" data-url="{{ route('admin.programs.store') }}">Add</button>
                                        </div>
                                    </div>
                                    <h6 class="mb-2">Programs</h6>
                                    <div id="programs_list_edit" class="list-group">
                                        @foreach(($programs ?? collect()) as $prog)
                                            <div class="list-group-item">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-4">
                                                        <input id="program_name_edit_{{ $prog->id }}" type="text" class="form-control form-control-sm" value="{{ strtoupper($prog->name) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input id="program_desc_edit_{{ $prog->id }}" type="text" class="form-control form-control-sm" value="{{ $prog->description }}">
                                                    </div>
                                                    <div class="col-md-2 d-flex justify-content-end align-items-center">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm me-2 program-update-btn-edit" data-url="{{ route('admin.programs.update', $prog) }}" data-id="{{ $prog->id }}">Update</button>
                                                        @if($prog->is_active)
                                                            <button type="button" class="btn btn-outline-danger btn-sm program-archive-btn-edit" data-url="{{ route('admin.programs.archive', $prog) }}">Archive</button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-primary btn-sm program-restore-btn-edit" data-url="{{ route('admin.programs.restore', $prog) }}">Restore</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year Level</label>
                            <select name="year_level" id="edit_year_level" class="form-select">
                                <option value="">Select Year Level</option>
                                @php
                                    $years = ['1st Year','2nd Year','3rd Year','4th Year','N/A'];
                                @endphp
                                @foreach($years as $year)
                                <option value="{{ $year }}" {{ old('year_level', $student->year_level) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Enrollment Status</label>
                            <select name="enrollment_status" id="edit_enrollment_status" class="form-select">
                                <option value="">-- Select Status --</option>
                                @foreach(($statuses ?? collect())->where('is_active', true) as $status)
                                    <option value="{{ $status->name }}" {{ old('enrollment_status', $student->enrollment_status) === $status->name ? 'selected' : '' }}>{{ $status->label }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <button id="manageStatusesBtnEdit" class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#manageStatusesEdit" aria-expanded="false" aria-controls="manageStatusesEdit" data-list-url="{{ route('admin.enrollment-statuses.index') }}">
                                    <i class="feather-settings me-1"></i> Manage Enrollment Statuses
                                </button>
                            </div>
                            <div class="collapse mt-3" id="manageStatusesEdit">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-2">Add Status</h6>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <input id="status_add_name_edit" type="text" class="form-control" placeholder="slug (e.g. enrolled)">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="status_add_label_edit" type="text" class="form-control" placeholder="Label (e.g. Enrolled)">
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button id="status_add_btn_edit" type="button" class="btn btn-primary btn-sm" data-url="{{ route('admin.enrollment-statuses.store') }}">Add</button>
                                        </div>
                                    </div>
                                    <h6 class="mb-2">Statuses</h6>
                                    <div id="statuses_list_edit" class="list-group">
                                        @foreach(($statuses ?? collect()) as $status)
                                            <div class="list-group-item">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-3">
                                                        <input id="status_name_edit_{{ $status->id }}" type="text" class="form-control form-control-sm" value="{{ $status->name }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input id="status_label_edit_{{ $status->id }}" type="text" class="form-control form-control-sm" value="{{ $status->label }}">
                                                    </div>
                                                    <div class="col-md-5 d-flex justify-content-end align-items-center">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm me-2 status-update-btn-edit" data-url="{{ route('admin.enrollment-statuses.update', $status) }}" data-id="{{ $status->id }}">Update</button>
                                                        @if($status->is_active)
                                                            <button type="button" class="btn btn-outline-danger btn-sm status-archive-btn-edit" data-url="{{ route('admin.enrollment-statuses.archive', $status) }}">Archive</button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-primary btn-sm status-restore-btn-edit" data-url="{{ route('admin.enrollment-statuses.restore', $status) }}">Restore</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Classification</label>
                            <select id="edit_classification" name="classification" class="form-select">
                                <option value="">-- Select Classification --</option>
                                @foreach(($classifications ?? collect())->where('is_active', true) as $cls)
                                    <option value="{{ $cls->name }}" {{ old('classification', $student->classification) === $cls->name ? 'selected' : '' }}>{{ $cls->label }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <button id="manageClassificationsBtnEdit" class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#manageClassificationsEdit" aria-expanded="false" aria-controls="manageClassificationsEdit" data-list-url="{{ route('admin.classifications.index') }}">
                                    <i class="feather-settings me-1"></i> Manage Classifications
                                </button>
                            </div>
                            <div class="collapse mt-3" id="manageClassificationsEdit">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-2">Add Classification</h6>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <input id="classification_add_name_edit" type="text" class="form-control" placeholder="slug (e.g. freshie)">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="classification_add_label_edit" type="text" class="form-control" placeholder="Label (e.g. Freshie)">
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button id="classification_add_btn_edit" type="button" class="btn btn-primary btn-sm" data-url="{{ route('admin.classifications.store') }}">Add</button>
                                        </div>
                                    </div>
                                    <h6 class="mb-2">Classifications</h6>
                                    <div id="classifications_list_edit" class="list-group">
                                        @foreach(($classifications ?? collect()) as $cls)
                                            <div class="list-group-item">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-3">
                                                        <input id="classification_name_edit_{{ $cls->id }}" type="text" class="form-control form-control-sm" value="{{ $cls->name }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input id="classification_label_edit_{{ $cls->id }}" type="text" class="form-control form-control-sm" value="{{ $cls->label }}">
                                                    </div>
                                                    <div class="col-md-5 d-flex justify-content-end align-items-center">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm me-2 classification-update-btn-edit" data-url="{{ route('admin.classifications.update', $cls) }}" data-id="{{ $cls->id }}">Update</button>
                                                        @if($cls->is_active)
                                                            <button type="button" class="btn btn-outline-danger btn-sm classification-archive-btn-edit" data-url="{{ route('admin.classifications.archive', $cls) }}">Archive</button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-primary btn-sm classification-restore-btn-edit" data-url="{{ route('admin.classifications.restore', $cls) }}">Restore</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">Auto-set to Freshie when Year Level is 1st Year.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                @php
                                    $statuses = ['pending' => 'Pending', 'approved' => 'Approved', 'denied' => 'Denied', 'expired' => 'Expired', 'inactive' => 'Inactive'];
                                @endphp
                                @foreach($statuses as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $student->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var csrf = document.getElementById('csrf_token_edit') ? document.getElementById('csrf_token_edit').value : '';
    function post(url, data) {
      return fetch(url, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: new URLSearchParams(data || {})
      });
    }
    function loadPrograms() {
      var btn = document.getElementById('manageProgramsBtnEdit');
      var url = btn ? btn.getAttribute('data-list-url') : '';
      if (!url) return;
      fetch(url, { headers: { 'Accept': 'application/json' }})
        .then(function(res){ return res.json(); })
        .then(function(json){
          var programs = json.programs || [];
          var list = document.getElementById('programs_list_edit');
          var sel = document.getElementById('edit_program');
          if (list) list.innerHTML = '';
          var keep = sel ? sel.value : '';
          programs.forEach(function(p){
            var item = document.createElement('div');
            item.className = 'list-group-item';
            item.innerHTML = '<div class="row g-2 align-items-center"><div class="col-md-4"><input id="program_name_edit_' + p.id + '" type="text" class="form-control form-control-sm" value="' + (p.name || '').toUpperCase() + '"></div><div class="col-md-6"><input id="program_desc_edit_' + p.id + '" type="text" class="form-control form-control-sm" value="' + (p.description || '') + '"></div><div class="col-md-2 d-flex justify-content-end align-items-center"><button type="button" class="btn btn-outline-secondary btn-sm me-2 program-update-btn-edit" data-url="/admin/programs/' + p.id + '" data-id="' + p.id + '">Update</button>' + (p.is_active ? '<button type="button" class="btn btn-outline-danger btn-sm program-archive-btn-edit" data-url="/admin/programs/' + p.id + '/archive">Archive</button>' : '<button type="button" class="btn btn-outline-primary btn-sm program-restore-btn-edit" data-url="/admin/programs/' + p.id + '/restore">Restore</button>') + '</div></div>';
            if (list) list.appendChild(item);
            if (sel) {
              var opt = document.createElement('option');
              opt.value = (p.name || '').toUpperCase();
              opt.textContent = (p.name || '').toUpperCase();
              if (p.is_active) sel.appendChild(opt);
            }
          });
          if (sel && keep) sel.value = keep;
        });
    }
    function loadStatuses() {
      var btn = document.getElementById('manageStatusesBtnEdit');
      var url = btn ? btn.getAttribute('data-list-url') : '';
      if (!url) return;
      fetch(url, { headers: { 'Accept': 'application/json' }})
        .then(function(res){ return res.json(); })
        .then(function(json){
          var statuses = json.statuses || [];
          var list = document.getElementById('statuses_list_edit');
          var sel = document.getElementById('edit_enrollment_status');
          if (list) list.innerHTML = '';
          var keep = sel ? sel.value : '';
          statuses.forEach(function(s){
            var item = document.createElement('div');
            item.className = 'list-group-item';
            item.innerHTML = '<div class="row g-2 align-items-center"><div class="col-md-3"><input id="status_name_edit_' + s.id + '" type="text" class="form-control form-control-sm" value="' + (s.name || '') + '"></div><div class="col-md-4"><input id="status_label_edit_' + s.id + '" type="text" class="form-control form-control-sm" value="' + (s.label || '') + '"></div><div class="col-md-5 d-flex justify-content-end align-items-center"><button type="button" class="btn btn-outline-secondary btn-sm me-2 status-update-btn-edit" data-url="/admin/enrollment-statuses/' + s.id + '" data-id="' + s.id + '">Update</button>' + (s.is_active ? '<button type="button" class="btn btn-outline-danger btn-sm status-archive-btn-edit" data-url="/admin/enrollment-statuses/' + s.id + '/archive">Archive</button>' : '<button type="button" class="btn btn-outline-primary btn-sm status-restore-btn-edit" data-url="/admin/enrollment-statuses/' + s.id + '/restore">Restore</button>') + '</div></div>';
            if (list) list.appendChild(item);
            if (sel && s.is_active) {
              var opt = document.createElement('option');
              opt.value = s.name;
              opt.textContent = s.label;
              sel.appendChild(opt);
            }
          });
          if (sel && keep) sel.value = keep;
        });
    }
    function loadClassifications() {
      var btn = document.getElementById('manageClassificationsBtnEdit');
      var url = btn ? btn.getAttribute('data-list-url') : '';
      if (!url) return;
      fetch(url, { headers: { 'Accept': 'application/json' }})
        .then(function(res){ return res.json(); })
        .then(function(json){
          var classifications = json.classifications || [];
          var list = document.getElementById('classifications_list_edit');
          var sel = document.getElementById('edit_classification');
          if (list) list.innerHTML = '';
          var keep = sel ? sel.value : '';
          classifications.forEach(function(c){
            var item = document.createElement('div');
            item.className = 'list-group-item';
            item.innerHTML = '<div class="row g-2 align-items-center"><div class="col-md-3"><input id="classification_name_edit_' + c.id + '" type="text" class="form-control form-control-sm" value="' + (c.name || '') + '"></div><div class="col-md-4"><input id="classification_label_edit_' + c.id + '" type="text" class="form-control form-control-sm" value="' + (c.label || '') + '"></div><div class="col-md-5 d-flex justify-content-end align-items-center"><button type="button" class="btn btn-outline-secondary btn-sm me-2 classification-update-btn-edit" data-url="/admin/classifications/' + c.id + '" data-id="' + c.id + '">Update</button>' + (c.is_active ? '<button type="button" class="btn btn-outline-danger btn-sm classification-archive-btn-edit" data-url="/admin/classifications/' + c.id + '/archive">Archive</button>' : '<button type="button" class="btn btn-outline-primary btn-sm classification-restore-btn-edit" data-url="/admin/classifications/' + c.id + '/restore">Restore</button>') + '</div></div>';
            if (list) list.appendChild(item);
            if (sel && c.is_active) {
              var opt = document.createElement('option');
              opt.value = c.name;
              opt.textContent = c.label;
              sel.appendChild(opt);
            }
          });
          if (sel && keep) sel.value = keep;
        });
    }
    var collapseProg = document.getElementById('manageProgramsEdit');
    var collapseStat = document.getElementById('manageStatusesEdit');
    var collapseCls  = document.getElementById('manageClassificationsEdit');
    if (collapseProg) collapseProg.addEventListener('show.bs.collapse', loadPrograms);
    if (collapseStat) collapseStat.addEventListener('show.bs.collapse', loadStatuses);
    if (collapseCls)  collapseCls.addEventListener('show.bs.collapse', loadClassifications);
    var addProgBtn = document.getElementById('program_add_btn_edit');
    if (addProgBtn) {
      addProgBtn.addEventListener('click', function(){
        var name = document.getElementById('program_add_name_edit').value.trim();
        var desc = document.getElementById('program_add_desc_edit').value.trim();
        if (!name) return;
        post(this.getAttribute('data-url'), { name: name, description: desc })
          .then(function(){ loadPrograms(); });
      });
    }
    var addStatusBtn = document.getElementById('status_add_btn_edit');
    if (addStatusBtn) {
      addStatusBtn.addEventListener('click', function(){
        var name = document.getElementById('status_add_name_edit').value.trim();
        var label = document.getElementById('status_add_label_edit').value.trim();
        if (!name || !label) return;
        post(this.getAttribute('data-url'), { name: name, label: label })
          .then(function(){ loadStatuses(); });
      });
    }
    var addClassificationBtn = document.getElementById('classification_add_btn_edit');
    if (addClassificationBtn) {
      addClassificationBtn.addEventListener('click', function(){
        var name = document.getElementById('classification_add_name_edit').value.trim();
        var label = document.getElementById('classification_add_label_edit').value.trim();
        if (!name || !label) return;
        post(this.getAttribute('data-url'), { name: name, label: label })
          .then(function(){ loadClassifications(); });
      });
    }
    var yearSel = document.getElementById('edit_year_level');
    var classSel = document.getElementById('edit_classification');
    function autoSelectClassificationEdit() {
      if (!yearSel || !classSel) return;
      if (yearSel.value === '1st Year') {
        var opts = Array.from(classSel.options || []);
        var freshieOpt = opts.find(function(o){ return o.value === 'freshie'; }) || opts.find(function(o){ return (o.textContent || '').trim().toLowerCase() === 'freshie'; });
        if (freshieOpt) classSel.value = freshieOpt.value;
      }
    }
    if (yearSel) {
      yearSel.addEventListener('change', autoSelectClassificationEdit);
      autoSelectClassificationEdit();
    }
  });
</script>
@endpush
@endsection
