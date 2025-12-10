@extends('layouts.navigation')

@section('content')
@section('module-content')
<div class="module-header">
    <h2>✏️ Edit Course: {{ $course->Title }}</h2>
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
    <form action="{{ route('admin.courses.update', $course->CourseID) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Course Title</label>
            <input type="text" name="Title" class="form-control" value="{{ old('Title', $course->Title) }}">
        </div>
        <div class="mb-3">
            <label>Teacher</label>
            <select name="teachersID" class="form-select">
                @foreach($teachers as $teacher)
                <option value="{{ $teacher->teachersID }}" {{ $teacher->teachersID == $course->teachersID ? 'selected' : '' }}>{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Days</label>
            <select name="days[]" class="form-select" multiple>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday'] as $day)
                <option value="{{ $day }}" {{ in_array($day, $selectedDays ?? []) ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Start Time</label>
            <input type="time" name="Start_time" class="form-control" value="{{ $course->Start_time }}">
        </div>
        <div class="mb-3">
            <label>End Time</label>
            <input type="time" name="end_time" class="form-control" value="{{ $course->end_time }}">
        </div>
        <div class="mb-3">
            <label>Students</label>
            <select name="student_ids[]" class="form-select" multiple>
                @foreach($students as $student)
                <option value="{{ $student->studentID }}" {{ in_array($student->studentID, $selectedStudents ?? []) ? 'selected' : '' }}>
                    {{ $student->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $course->description) }}</textarea>
        </div>

        <button class="btn btn-admin">💾 Update</button>
    </form>
</div>
@endsection
@endsection

