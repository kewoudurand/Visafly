{{-- resources/views/instructeur/courses/create.blade.php --}}
@extends('layouts.instructeur')
@section('title', 'Nouveau cours')

@section('content')
<div class="container-fluid" style="max-width:900px">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('instructeur.cours.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold" style="color:#1B3A6B">Nouveau cours</h4>
    </div>
    @include('instructeur.courses._form', [
        'cours'       => null,
        'routeAction' => route('instructeur.cours.store'),
        'method'      => 'POST',
    ])
</div>
@endsection