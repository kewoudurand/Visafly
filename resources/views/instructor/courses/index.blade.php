{{-- resources/views/instructeur/courses/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Mes cours')

@push('styles')
<style>
    .cours-card {
        border: none; border-radius: 14px;
        box-shadow: 0 2px 10px rgba(27,58,107,.08);
        overflow: hidden; transition: transform .2s, box-shadow .2s;
    }
    .cours-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(27,58,107,.13); }
    .cours-card .top-bar { height: 6px; }
    .stat-chip {
        background: #f8f9fa; border-radius: 6px;
        padding: .25rem .6rem; font-size: .76rem; color: #495057;
        display: inline-flex; align-items: center; gap: .3rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:#1B3A6B">Mes cours</h4>
            <p class="text-muted small mb-0">{{ $cours->total() }} cours au total</p>
        </div>
        <a href="{{ route('instructeur.cours.create') }}"
           class="btn fw-bold px-4" style="background:#1B3A6B;color:#fff;border-color:#1B3A6B">
            <i class="bi bi-plus-lg me-2"></i>Nouveau cours
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cours->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:3.5rem">📚</div>
            <h5 class="mt-3 fw-bold">Aucun cours pour l'instant</h5>
            <p class="text-muted">Créez votre premier cours pour commencer à ajouter des leçons.</p>
            <a href="{{ route('instructeur.cours.create') }}" class="btn btn-primary px-4">Créer mon premier cours</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($cours as $c)
            <div class="col-md-6 col-xl-4">
                <div class="cours-card card h-100">
                    <div class="top-bar" style="background:{{ $c->couleur ?? '#1B3A6B' }}"></div>
                    <div class="card-body d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="badge fw-bold" style="background:{{ $c->couleur ?? '#1B3A6B' }};color:#fff">{{ $c->niveau }}</span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li><a class="dropdown-item" href="{{ route('instructeur.cours.lessons.index', $c) }}">
                                        <i class="bi bi-list-ul me-2 text-primary"></i>Gérer les leçons
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('instructeur.cours.edit', $c) }}">
                                        <i class="bi bi-pencil me-2 text-warning"></i>Modifier
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('instructeur.cours.destroy', $c) }}" method="POST"
                                              onsubmit="return confirm('Supprimer ce cours ?')">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>Supprimer
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $c->titre }}</h6>
                            @if($c->sous_titre)
                                <p class="text-muted small mb-0">{{ $c->sous_titre }}</p>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="stat-chip"><i class="bi bi-journals"></i>{{ $c->lessons_count }} leçon(s)</span>
                            @if($c->gratuit)<span class="stat-chip" style="background:#e8f8f0;color:#198754"><i class="bi bi-unlock-fill"></i>Gratuit</span>@endif
                            @if(!$c->publie)<span class="stat-chip" style="background:#f8d7da;color:#842029"><i class="bi bi-eye-slash"></i>Brouillon</span>@endif
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('instructeur.cours.lessons.index', $c) }}"
                               class="btn btn-sm w-100 fw-semibold" style="background:#f0f4ff;color:#1B3A6B">
                                <i class="bi bi-list-ul me-1"></i>Gérer les leçons
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $cours->links() }}</div>
    @endif
</div>
@endsection


{{-- ──────────────────────────────────────────────────────────────── --}}
{{-- resources/views/instructeur/courses/create.blade.php            --}}
{{-- ──────────────────────────────────────────────────────────────── --}}

{{--
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
--}}

{{-- ──────────────────────────────────────────────────────────────── --}}
{{-- resources/views/instructeur/courses/edit.blade.php              --}}
{{-- ──────────────────────────────────────────────────────────────── --}}

{{--
@extends('layouts.instructeur')
@section('title', 'Modifier — ' . $cours->titre)

@section('content')
<div class="container-fluid" style="max-width:900px">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('instructeur.cours.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold" style="color:#1B3A6B">Modifier le cours</h4>
    </div>

    @include('instructeur.courses._form', [
        'cours'       => $cours,
        'routeAction' => route('instructeur.cours.update', $cours),
        'method'      => 'PUT',
    ])
</div>
@endsection
--}}