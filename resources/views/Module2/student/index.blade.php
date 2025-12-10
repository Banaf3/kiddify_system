{{-- resources/views/Module2/student/student_index.blade.php --}}
@extends('layouts.navigation')

@section('content')
<div class="container mt-5">

    <h2 class="fw-bold mb-4">🎒 My Courses</h2>

    <div class="row g-4">
        @forelse($courses as $course)
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">

                <img src="https://via.placeholder.com/350x160?text={{ urlencode($course->Title) }}" 
                    class="card-img-top" alt="Course">

                <div class="card-body">
                    <h4 class="card-title fw-bold">{{ $course->Title }}</h4>

                    <p class="text-muted small">
                        👨‍🏫 Instructor: <strong>{{ $course->teacher->T_name }}</strong>
                    </p>

                    <p class="small">
                        <strong>Schedule:</strong><br>
                        {{ $course->Start_time }} - {{ $course->end_time }} <br>
                        @foreach(json_decode($course->days) as $day)
                            <span class="badge bg-info">{{ $day }}</span>
                        @endforeach
                    </p>

                    <a href="{{ route('student.courses.show', $course->CourseID) }}" 
                       class="btn btn-primary w-100 mt-2">
                        View Details
                    </a>
                </div>

            </div>
        </div>
        @empty
            <p>No courses registered yet.</p>
        @endforelse
    </div>

</div>
@endsection
