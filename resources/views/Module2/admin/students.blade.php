@extends('layouts.navigation')

@section('content')
<div class="container py-4">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-purple text-white rounded-top-4">
            <h4 class="mb-0">
                <i class="bi bi-people-fill me-2"></i> Students Enrolled — {{ $course->Title }}
            </h4>
        </div>

        <div class="card-body p-4">

            @if($students->count() == 0)
                <div class="alert alert-info text-center">
                    No students are currently assigned to this course.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        <tr>
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->studentID }}</td>
                            <td>{{ $student->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Courses
                </a>
            </div>

        </div>
    </div>

</div>

<style>
    .bg-purple { background-color: #6A0DAD !important; }
</style>
@endsection
