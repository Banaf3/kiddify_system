@extends('layouts.navigation')

@section('content')
@section('module-content')
<div class="module-header">
    <h2>👥 Students in {{ $course->Title }}</h2>
</div>

<div class="card card-custom p-3">
    @if($students->isEmpty())
        <div class="alert alert-info">No students enrolled in this course yet.</div>
    @else
    <table class="table table-hover align-middle">
        <thead style="background: var(--admin-accent);">
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone_number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
@endsection
