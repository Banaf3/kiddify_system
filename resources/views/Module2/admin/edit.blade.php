@extends('layouts.navigation')

@section('content')
<div class="container py-4">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error!</strong> Please fix the issues below:
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-purple text-white rounded-top-4">
            <h4 class="mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Course
            </h4>
        </div>

        <div class="card-body p-4">

            <form method="POST" action="{{ route('admin.courses.update', $course->CourseID) }}">
                @csrf
                @method('PUT')

                {{-- COURSE TITLE --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Course Title</label>
                    <input type="text" name="Title" class="form-control" value="{{ $course->Title }}" required>
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $course->description }}</textarea>
                </div>

                {{-- TEACHER --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Assigned Teacher</label>
                    <select name="teachersID" class="form-select" required>
                        @foreach($teachers as $t)
                            <option value="{{ $t->teachersID }}"
                                {{ $course->teachersID == $t->teachersID ? 'selected' : '' }}>
                                {{ $t->T_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- START TIME --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Start Time</label>
                    <input type="time" name="Start_time" class="form-control" value="{{ $course->Start_time }}" required>
                </div>

                {{-- END TIME --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">End Time</label>
                    <input type="time" name="end_time" class="form-control" value="{{ $course->end_time }}" required>
                </div>

                {{-- DAYS --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Days</label><br>

                    @php
                        $selectedDays = json_decode($course->days, true);
                    @endphp

                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="days[]" class="form-check-input"
                               value="{{ $day }}"
                               {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $day }}</label>
                    </div>
                    @endforeach
                </div>

                {{-- MAX STUDENT --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Max Students</label>
                    <input type="number" name="maxStudent" class="form-control"
                           value="{{ $course->maxStudent }}" required>
                </div>

                {{-- STUDENT CHECKBOX --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Assign Students</label>
                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: scroll;">

                        @foreach($students as $student)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="student_ids[]"
                                   value="{{ $student->studentID }}"
                                   {{ in_array($student->studentID, $selectedStudents) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                {{ $student->name }} ({{ $student->studentID }})
                            </label>
                        </div>
                        @endforeach

                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-purple px-4">
                        <i class="bi bi-check-circle me-1"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>

{{-- CUSTOM PURPLE BUTTON STYLE --}}
<style>
    .bg-purple { background-color: #6A0DAD !important; }
    .btn-purple { background-color: #6A0DAD; color:white; }
    .btn-purple:hover { background-color: #580a91; }
</style>
@endsection
