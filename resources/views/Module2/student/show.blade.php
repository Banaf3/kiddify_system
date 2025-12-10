@extends('layouts.navigation')

@section('content')
@section('module-content')
<div class="module-header">
    <h2>📖 Course Details: {{ $course->Title }}</h2>
</div>

<div class="card card-custom p-4">
    <p><strong>Teacher:</strong> {{ $course->teacher->name ?? 'N/A' }}</p>
    <p><strong>Days:</strong> {{ implode(', ', json_decode($course->days, true)) }}</p>
    <p><strong>Time:</strong> {{ $course->Start_time }} - {{ $course->end_time }}</p>
    <p><strong>Description:</strong></p>
    <p>{{ $course->description ?? '-' }}</p>

    <hr>
    <h5>Enrolled Students ({{ $course->students->count() }})</h5>
    <ul>
        @foreach($course->students as $student)
        <li>{{ $student->name }} ({{ $student->email }})</li>
        @endforeach
    </ul>
</div>
@endsection
@endsection
