{{-- resources/views/Module2/teacher/teacher_show.blade.php --}}
@extends('layouts.navigation')

@section('content')
<div class="container mt-5">

    <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary mb-3">⬅ Back</a>

    <h2 class="fw-bold">{{ $course->Title }}</h2>

    <div class="mb-3">
        <span class="badge bg-success">Teacher: {{ $course->teacher->T_name }}</span>
        <span class="badge bg-primary">Time: {{ $course->Start_time }} - {{ $course->end_time }}</span>
    </div>

    <h4 class="mt-4">👥 Enrolled Students</h4>

    <table class="table table-bordered table-hover shadow-sm">
        <thead class="table-warning">
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($course->students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
