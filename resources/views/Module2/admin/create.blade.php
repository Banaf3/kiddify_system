@extends('layouts.navigation')

@section('content')
@section('module-content')
<div class="module-header">
    <h2>➕ Add New Course</h2>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card card-custom p-4">
    <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Course Title</label>
            <input type="text" name="Title" class="form-control" value="{{ old('Title') }}">
        </div>
        <div class="mb-3">
            <label>Teacher</label>
            <select name="teachersID" class="form-select">
                @foreach($teachers as $teacher)
                <option value="{{ $teacher->teachersID }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Days</label>
            <select name="days[]" class="form-select" multiple>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday'] as $day)
                <option value="{{ $day }}">{{ $day }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Start Time</label>
            <input type="time" name="Start_time" class="form-control">
        </div>
        <div class="mb-3">
            <label>End Time</label>
            <input type="time" name="end_time" class="form-control">
        </div>
        <div class="mb-3">
            <label>Students</label>
            <select name="student_ids[]" class="form-select" multiple>
                @foreach($students as $student)
                <option value="{{ $student->studentID }}">{{ $student->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button class="btn btn-admin">💾 Save</button>
    </form>
</div>
@endsection
@endsection

