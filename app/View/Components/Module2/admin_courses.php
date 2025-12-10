@extends('layouts.kiddify')
@section('title','Manage Courses')

@section('content')

<div class="content-box">

    <h3 class="fw-bold">Manage Course Registration</h3>
    <hr>

    <div class="d-flex justify-content-between mb-3">
        <div>
            <input type="text" class="form-control" placeholder="Search Course by Name">
        </div>

        <a href="{{ route('courses.create') }}" class="yellow-btn">
            + Create Course
        </a>
    </div>

    <table class="table table-hover">
        <thead class="table-warning">
            <tr>
                <th>Course</th>
                <th>Assigned Teacher</th>
                <th>Schedule</th>
                <th>Days</th>
                <th>Students</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->Title }}</td>
                <td>{{ $course->teacher->name ?? 'Not Assigned' }}</td>
                <td>{{ $course->Start_time }} - {{ $course->end_time }}</td>
                <td>{{ implode(', ', json_decode($course->days)) }}</td>
                <td>{{ $course->students->count() }}</td>

                <td>
                    <a href="{{ route('courses.edit', $course->CourseID) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Edit Course">
                        Edit
                    </a>
                    <a href="{{ route('courses.viewStudents', $course->CourseID) }}" class="btn btn-sm btn-info">
                        View Students
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

@endsection
