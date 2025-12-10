{{-- resources/views/Module2/teacher/teacher_index.blade.php --}}
@extends('layouts.navigation')

@section('content')
<div class="container mt-5">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="fw-bold mb-4">📘 Assigned Courses</h2>

    <div class="row g-4">
        @forelse($courses as $course)
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <img src="https://via.placeholder.com/300x150?text={{ urlencode($course->Title) }}" 
                     class="card-img-top" alt="Course Image">

                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $course->Title }}</h5>

                    <p class="card-text small text-muted">
                        ⏰ {{ $course->Start_time }} - {{ $course->end_time }}
                    </p>

                    <p class="card-text">
                        <strong>Days:</strong>
                        @foreach(json_decode($course->days) as $day)
                            <span class="badge bg-primary">{{ $day }}</span>
                        @endforeach
                    </p>

                    <a href="{{ route('teacher.courses.show', $course->CourseID) }}" 
                       class="btn btn-warning w-100">
                        View Students
                    </a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted">No courses assigned yet.</p>
        @endforelse
    </div>

</div>
@endsection
