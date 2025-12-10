@extends('layouts.admin_module2')

@section('module-content')

<div class="module-header">
    <h3>➕ Create New Course</h3>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Validation Errors Found</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li>⚠️ {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.courses.store') }}" class="card card-custom p-4">
    @csrf

    <div class="row mb-3">
        <label class="col-md-3 col-form-label">Course Title</label>
        <div class="col-md-9">
            <input type="text" name="Title" class="form-control" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-md-3 col-form-label">Teacher</label>
        <div class="col-md-9">
            <select name="teachersID" class="form-control" required>
                <option value="">Select Teacher</option>
                @foreach($teachers as $teacher)
                <option value="{{ $teacher->teachersID }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-md-3 col-form-label">Description</label>
        <div class="col-md-9">
            <textarea name="description" class="form-control"></textarea>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-md-3 col-form-label">Days</label>
        <div class="col-md-9">
            <select name="days[]" class="form-control" multiple required>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
            </select>
            <small class="text-secondary">Hold CTRL to select multiple</small>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-md-3 col-form-label">Start Time</label>
        <div class="col-md-3">
            <input type="time" name="Start_time" class="form-control" required>
        </div>

        <label class="col-md-3 col-form-label">End Time</label>
        <div class="col-md-3">
            <input type="time" name="end_time" class="form-control" required>
        </div>
    </div>

    <div class="row mb-4">
        <label class="col-md-3 col-form-label">Enroll Students</label>
        <div class="col-md-9">
            <select name="student_ids[]" multiple class="form-control" required>
                @foreach($students as $student)
                <option value="{{ $student->studentID }}">{{ $student->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <button class="btn btn-admin">Create Course</button>
    </div>
</form>

@endsection
