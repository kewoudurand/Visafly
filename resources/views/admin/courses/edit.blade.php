@extends('layouts.dashboard')
@section('title', 'Modifier — ' . $cours->titre)

@section('content')
<div class="container-fluid" style="max-width:900px">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.cours.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold" style="color:#1B3A6B">Modifier le cours</h4>
    </div>

    @include('admin.courses._form', [
        'cours'       => $cours,
        'routeAction' => route('admin.cours.update', ['cour' => $cours->id]),
        'method'      => 'PUT',
    ])
</div>
@endsection