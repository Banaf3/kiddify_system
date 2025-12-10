@extends('layouts.navigation')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

@section('module-content')
<div class="module-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0">📚 Course Management</h2>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-admin">➕ Add New Course</a>
</div>

@if (session('success'))
<div class="alert alert-success">
    <strong>Success!</strong> {{ session('success') }}
</div>
@endif

<div class="card card-custom p-3">
    <table class="table table-hover align-middle">
        <thead style="background: var(--admin-accent);">
            <tr>
                <th>Course Title</th>
                <th>Teacher</th>
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
                <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                <td>{{ implode(', ', json_decode($course->days, true)) }}</td>
                <td>{{ $course->Start_time }} - {{ $course->end_time }}</td>
                <td>{{ $course->students->count() }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.courses.edit', $course->CourseID) }}" class="btn btn-sm btn-warning">✏️ Edit</a>
                    <a href="{{ route('admin.courses.students', $course->CourseID) }}" class="btn btn-sm btn-info text-white">👥 Students</a>
                    <form action="{{ route('admin.courses.destroy', $course->CourseID) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this course?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">🗑️ Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@endsection
