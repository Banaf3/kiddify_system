@extends('layouts.kiddify')
@section('title','Create Course')

@section('content')

<div class="content-box mx-auto" style="max-width: 700px;">

    <h3 class="fw-bold">Create Course</h3>
    <hr>

    <form action="{{ route('courses.store') }}" method="POST">
        @csrf

        <label class="fw-bold">Course Title</label>
        <input type="text" name="Title" class="form-control mb-3" placeholder="Eg: Mathematics">

        <label class="fw-bold">Assign Teacher</label>
        <select name="teachersID" class="form-select mb-3">
            @foreach($teachers as $t)
            <option value="{{ $t->teachersID }}">{{ $t->name }}</option>
            @endforeach
        </select>

        <div class="row mb-3">
            <div class="col">
                <label class="fw-bold">Start Time</label>
                <input type="time" name="Start_time" class="form-control">
            </div>

            <div class="col">
                <label class="fw-bold">End Time</label>
                <input type="time" name="end_time" class="form-control">
            </div>
        </div>

        <label class="fw-bold">Days</label>
        <select name="days[]" multiple class="form-select mb-3">
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
        </select>

        <label class="fw-bold">Select Students</label>
        <select name="student_ids[]" multiple class="form-select mb-4" size="5">
            @foreach($students as $s)
            <option value="{{ $s->student_id }}">{{ $s->name }}</option>
            @endforeach
        </select>

        <div class="text-center">
            <button class="btn btn-success px-4">Save</button>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary px-4">Cancel</a>
        </div>

    </form>
</div>

@endsection
