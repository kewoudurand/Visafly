{{-- resources/views/instructeur/courses/create.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Nouveau cours')

@section('content')
<div class="container-fluid" style="max-width:900px">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold" style="color:#1B3A6B">Nouveau cours</h4>
    </div>
    @include('instructor.courses._form', [
        'cours'       => null,
        'routeAction' => route('instructor.courses.store'),
        'method'      => 'POST',
    ])
</div>
@endsection