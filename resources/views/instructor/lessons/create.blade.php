{{-- resources/views/instructeur/lessons/create.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Nouvelle leçon — ' . $cours->titre)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('instructor.cours.lessons.index', $cours) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold" style="color:#1B3A6B">Nouvelle leçon</h4>
            <small class="text-muted">
                <span class="badge me-1" style="background:{{ $cours->couleur ?? '#1B3A6B' }};color:#fff">{{ $cours->niveau }}</span>
                {{ $cours->titre }}
            </small>
        </div>
    </div>

    @include('shared.lessons._form_lecon', [
        'cours'                 => $cours,
        'lesson'                => null,
        'routeAction'           => route('instructeur.cours.lessons.store', $cours),
        'method'                => 'POST',
        'showInstructeurSelect' => false,   // ← instructeur ne peut pas changer l'assignation
        'instructeurs'          => collect(),
    ])
</div>
@endsection