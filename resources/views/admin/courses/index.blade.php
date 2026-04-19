{{-- resources/views/admin/courses/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Gestion des cours')

@push('styles')
<style>
    .cours-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(27,58,107,.08);
        transition: transform .2s, box-shadow .2s;
    }
    .cours-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(27,58,107,.15); }
    .cours-card .niveau-badge {
        font-size: .7rem; font-weight: 700; letter-spacing: .08em;
        padding: .3rem .75rem; border-radius: 20px;
    }
    .cours-card .instructeur-chip {
        background: #f0f4ff; color: #1B3A6B;
        border-radius: 20px; padding: .2rem .7rem;
        font-size: .78rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .cours-card .card-accent {
        height: 5px;
        background: var(--accent);
    }
    .stat-pill {
        background: #f8f9fa; border-radius: 8px;
        padding: .3rem .65rem; font-size: .78rem;
        display: inline-flex; align-items: center; gap: .3rem;
    }
    .filter-bar { background: #fff; border-radius: 12px; padding: 1rem 1.25rem; box-shadow: 0 1px 6px rgba(0,0,0,.06); }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- ── Titre page ─────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:#1B3A6B">Cours de langues</h4>
            <p class="text-muted small mb-0">{{ $cours->total() }} cours au total</p>
        </div>
        <a href="{{ route('admin.cours.create') }}" class="btn btn-primary fw-bold px-4" style="background:#1B3A6B;border-color:#1B3A6B">
            <i class="bi bi-plus-lg me-2"></i>Nouveau cours
        </a>
    </div>

    {{-- ── Filtres ─────────────────────────────────────────── --}}
    <div class="filter-bar d-flex flex-wrap gap-3 align-items-center mb-4">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center w-100">
            <select name="niveau" class="form-select form-select-sm" style="width:120px" onchange="this.form.submit()">
                <option value="">Tous niveaux</option>
                @foreach(['A1','A2','B1','B2','C1','C2'] as $n)
                    <option value="{{ $n }}" {{ request('niveau') === $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
            <select name="instructeur_id" class="form-select form-select-sm" style="width:190px" onchange="this.form.submit()">
                <option value="">Tous les instructeurs</option>
                @foreach($instructeurs as $inst)
                    <option value="{{ $inst->id }}" {{ request('instructor_id') == $inst->id ? 'selected' : '' }}>
                        {{ $inst->first_name }}
                    </option>
                @endforeach
            </select>
            <select name="publie" class="form-select form-select-sm" style="width:140px" onchange="this.form.submit()">
                <option value="">Tous statuts</option>
                <option value="1" {{ request('publie') === '1' ? 'selected' : '' }}>Publiés</option>
                <option value="0" {{ request('publie') === '0' ? 'selected' : '' }}>Brouillons</option>
            </select>
            @if(request()->hasAny(['niveau','instructor_id','publie']))
                <a href="{{ route('admin.cours.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- ── Grille de cours ─────────────────────────────────── --}}
    @if($cours->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-journal-x fs-1 text-muted"></i>
            <p class="text-muted mt-2">Aucun cours trouvé.</p>
            <a href="{{ route('admin.cours.create') }}" class="btn btn-primary mt-1">Créer le premier cours</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($cours as $c)
            <div class="col-md-6 col-xl-4">
                <div class="cours-card card h-100" style="--accent: {{ $c->couleur ?? '#1B3A6B' }}">
                    <div class="card-accent"></div>
                    <div class="card-body d-flex flex-column gap-3">

                        {{-- Header --}}
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center gap-2">
                                <span class="niveau-badge text-white" style="background:{{ $c->couleur ?? '#1B3A6B' }}">
                                    {{ $c->niveau }}
                                </span>
                                @if(!$c->publie)
                                    <span class="badge bg-secondary-subtle text-secondary">Brouillon</span>
                                @endif
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.cours.lessons.index', $c) }}">
                                            <i class="bi bi-list-ul me-2 text-primary"></i>Leçons
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.cours.edit', $c) }}">
                                            <i class="bi bi-pencil me-2 text-warning"></i>Modifier
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.cours.destroy', $c) }}" method="POST"
                                              onsubmit="return confirm('Supprimer ce cours et toutes ses leçons ?')">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>Supprimer
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Titre --}}
                        <div>
                            <h6 class="fw-bold mb-1 lh-sm">{{ $c->titre }}</h6>
                            @if($c->sous_titre)
                                <p class="text-muted small mb-0">{{ $c->sous_titre }}</p>
                            @endif
                        </div>

                        {{-- Stats --}}
                        <div class="d-flex flex-wrap gap-2">
                            <span class="stat-pill text-muted">
                                <i class="bi bi-journals text-primary"></i>
                                {{ $c->lessons_count ?? $c->lecons()->count() }} leçons
                            </span>
                            @if($c->duree_estimee_minutes)
                            <span class="stat-pill text-muted">
                                <i class="bi bi-clock text-warning"></i>
                                {{ $c->duree_estimee_minutes }} min
                            </span>
                            @endif
                            @if($c->gratuit)
                            <span class="stat-pill" style="background:#e8f8f0;color:#198754">
                                <i class="bi bi-unlock-fill"></i> Gratuit
                            </span>
                            @endif
                        </div>

                        {{-- Instructeur --}}
                        <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                            <span class="instructeur-chip">
                                <i class="bi bi-person-badge-fill"></i>
                                {{ $c->nomInstructeur() }}
                            </span>
                            <a href="{{ route('admin.cours.lessons.index', $c) }}"
                               class="btn btn-sm fw-semibold"
                               style="background:#f0f4ff;color:#1B3A6B">
                                Gérer les leçons <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $cours->withQueryString()->links() }}</div>
    @endif

</div>
@endsection