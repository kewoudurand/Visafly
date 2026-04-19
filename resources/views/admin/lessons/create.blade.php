{{-- resources/views/admin/lessons/create.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Nouvelle leçon — ' . $cour->titre)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.cours.lessons.index', $cour) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold" style="color:#1B3A6B">Nouvelle leçon</h4>
            <small class="text-muted">
                <span class="badge me-1" style="background:{{ $cour->couleur ?? '#1B3A6B' }};color:#fff">{{ $cour->niveau }}</span>
                {{ $cour->titre }}
            </small>
        </div>
    </div>

    @include('shared.lessons._form_lecon', [
        'cours'                 => $cour,
        'lesson'                => null,
        'routeAction'           => route('admin.cours.lessons.store', $cour),
        'method'                => 'POST',
        'showInstructeurSelect' => true,
        'instructeurs'          => $instructeurs,
    ])
</div>
@endsection