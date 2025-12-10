{{-- resources/views/Module2/student/student_show.blade.php --}}
@extends('layouts.navigation')

@section('content')
<div class="container mt-5">

    <a href="{{ route('student.courses.index') }}" class="btn btn-secondary mb-3">⬅ Back</a>

    <div class="card shadow border-0">
        <img src="https://via.placeholder.com/1000x300?text={{ urlencode($course->Title) }}"
             class="card-img-top" alt="Course Image">

        <div class="card-body">
            <h2 class="fw-bold">{{ $course->Title }}</h2>

            <p class="text-muted mt-2">
                Instructor: <strong>{{ $course->teacher->T_name }}</strong>
            </p>

            <h5 class="mt-3">🕒 Time</h5>
            <p>{{ $course->Start_time }} - {{ $course->end_time }}</p>

            <h5 class="mt-3">📅 Days</h5>
            @foreach(json_decode($course->days) as $day)
                <span class="badge bg-primary">{{ $day }}</span>
            @endforeach

            <h5 class="mt-4">📘 Course Description</h5>
            <p>{{ $course->description ?? 'No description available.' }}</p>

        </div>
    </div>

</div>
@endsection
