@if(session('success'))
<div class="alert alert-success">
    <i class="fa fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <strong>There were some problems:</strong>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
