@extends('layouts.navigation')

@section('content')
@section('module-content')
<div class="module-header d-flex justify-content-between align-items-center">
    <h2>📚 My Courses</h2>
</div>

<div class="card card-custom p-3">
    @if($courses->isEmpty())
        <div class="alert alert-info">You are not assigned to any courses yet.</div>
    @else
    <table class="table table-hover align-middle">
        <thead style="background: var(--admin-accent);">
            <tr>
                <th>Course Title</th>
                <th>Days</th>
                <th>Time</th>
                <th>Total Students</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->Title }}</td>
                <td>{{ implode(', ', json_decode($course->days, true)) }}</td>
                <td>{{ $course->Start_time }} - {{ $course->end_time }}</td>
                <td>{{ $course->students->count() }}</td>
                <td class="text-end">
                    <a href="{{ route('teacher.courses.show', $course->CourseID) }}" class="btn btn-sm btn-info text-white">👀 View Students</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
@endsection

