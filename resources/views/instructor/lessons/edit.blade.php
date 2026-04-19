{{-- resources/views/instructeur/lessons/edit.blade.php --}}
@extends('layouts.instructeur')
@section('title', 'Modifier — ' . $lesson->titre)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('instructeur.cours.lessons.index', $cours) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold" style="color:#1B3A6B">Modifier la leçon</h4>
            <small class="text-muted">
                <span class="badge me-1" style="background:{{ $cours->couleur ?? '#1B3A6B' }};color:#fff">{{ $cours->niveau }}</span>
                {{ $cours->titre }} — {{ $lesson->titre }}
            </small>
        </div>
    </div>

    @include('shared.lessons._form_lecon', [
        'cours'                 => $cours,
        'lesson'                => $lesson,
        'routeAction'           => route('instructeur.cours.lessons.update', [$cours, $lesson]),
        'method'                => 'PUT',
        'showInstructeurSelect' => false,
        'instructeurs'          => collect(),
    ])
</div>
@endsection