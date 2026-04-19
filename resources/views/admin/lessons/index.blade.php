{{-- resources/views/admin/lessons/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Leçons — ' . $cour->titre)

@push('styles')
<style>
    .lecon-row {
        background: #fff;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: .6rem;
        border: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: box-shadow .15s;
    }
    .lecon-row:hover { box-shadow: 0 3px 12px rgba(27,58,107,.1); }
    .lecon-row.sortable-ghost { opacity: .4; background: #f0f4ff; }
    .drag-handle { cursor: grab; color: #adb5bd; font-size: 1.1rem; }
    .drag-handle:active { cursor: grabbing; }
    .type-dot {
        width: 10px; height: 10px; border-radius: 50%;
        flex-shrink: 0;
    }
    .lecon-title { font-weight: 600; color: #212529; font-size: .95rem; }
    .lecon-meta span {
        font-size: .78rem; color: #6c757d;
        display: inline-flex; align-items: center; gap: .3rem;
    }
    .instructeur-chip {
        background: #f0f4ff; color: #1B3A6B;
        border-radius: 20px; padding: .2rem .65rem;
        font-size: .75rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: .3rem;
        white-space: nowrap;
    }
    .cours-header {
        background: linear-gradient(135deg, #1B3A6B 0%, #243f70 100%);
        border-radius: 14px;
        padding: 1.5rem 2rem;
        color: #fff;
        margin-bottom: 1.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- ── En-tête cours ────────────────────────────────── --}}
    <div class="cours-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.cours.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge text-white fw-bold px-3" style="background:#F5A623;color:#000!important">
                            {{ $cour->niveau }}
                        </span>
                        @if(!$cour->publie)
                            <span class="badge bg-secondary">Brouillon</span>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-1">{{ $cour->titre }}</h4>
                    <p class="mb-0 opacity-75 small">
                        <i class="bi bi-person-badge me-1"></i>{{ $cour->nomInstructeur() }}
                        · {{ $lecons->count() }} leçon(s)
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.cours.lessons.create', ['cour' => $cour->id]) }}"
               class="btn fw-bold px-4" style="background:#F5A623;color:#000">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle leçon
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Filtre instructeur ───────────────────────────── --}}
    @if($instructeurs->count() > 1)
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <span class="text-muted small align-self-center">Filtrer :</span>
        @foreach($instructeurs as $inst)
        <a href="{{ request()->fullUrlWithQuery(['instructeur' => $inst->id]) }}"
           class="btn btn-sm {{ request('instructeur') == $inst->id ? 'btn-primary' : 'btn-outline-secondary' }}"
           style="{{ request('instructeur') == $inst->id ? 'background:#1B3A6B;border-color:#1B3A6B' : '' }}">
            {{ $inst->name }}
        </a>
        @endforeach
        @if(request('instructeur'))
            <a href="{{ route('admin.cours.lessons.index', $cour) }}" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-x"></i> Tout afficher
            </a>
        @endif
    </div>
    @endif

    {{-- ── Liste leçons (drag & drop) ─────────────────── --}}
    @if($lecons->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-journals fs-1 text-muted"></i>
            <p class="text-muted mt-2 mb-3">Aucune leçon dans ce cours.</p>
            <a href="{{ route('admin.cours.lessons.create', $cour) }}" class="btn btn-primary">
                Créer la première leçon
            </a>
        </div>
    @else
        <div id="sortable-lessons">
            @foreach($lecons as $lecon)
            <div class="lecon-row" data-id="{{ $lecon->id }}">
                <i class="bi bi-grip-vertical drag-handle"></i>

                {{-- Type dot --}}
                @php
                    $dot = match($lecon->type) {
                        'vocabulaire' => '#198754',
                        'dialogue'    => '#0d6efd',
                        'grammaire'   => '#ffc107',
                        'audio'       => '#0dcaf0',
                        'lecture'     => '#6c757d',
                        default       => '#adb5bd'
                    };
                @endphp
                <div class="type-dot" style="background:{{ $dot }}" title="{{ ucfirst($lecon->type) }}"></div>

                {{-- Infos --}}
                <div class="flex-grow-1 min-w-0">
                    <div class="lecon-title text-truncate">
                        {{ $lecon->ordre }}. {{ $lecon->titre }}
                    </div>
                    <div class="lecon-meta d-flex flex-wrap gap-2 mt-1">
                        <span><i class="bi bi-tag"></i>{{ ucfirst($lecon->type) }}</span>
                        @if($lecon->nombreMots())
                            <span><i class="bi bi-alphabet"></i>{{ $lecon->nombreMots() }} mots</span>
                        @endif
                        @if($lecon->nombreExercices())
                            <span><i class="bi bi-pencil"></i>{{ $lecon->nombreExercices() }} ex.</span>
                        @endif
                        @if($lecon->duree_estimee_minutes)
                            <span><i class="bi bi-clock"></i>{{ $lecon->duree_estimee_minutes }} min</span>
                        @endif
                        <span class="instructeur-chip">
                            <i class="bi bi-person"></i>{{ $lecon->nomInstructeur() }}
                        </span>
                    </div>
                </div>

                {{-- Badges état --}}
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    @if($lecon->gratuite)
                        <span class="badge" style="background:#e8f8f0;color:#198754">Gratuite</span>
                    @endif
                    @if(!$lecon->publiee)
                        <span class="badge bg-secondary-subtle text-secondary">Brouillon</span>
                    @else
                        <span class="badge" style="background:#e8f8f0;color:#198754">Publiée</span>
                    @endif
                    <span class="badge" style="background:#fff8e8;color:#856404">
                        <i class="bi bi-trophy me-1"></i>{{ $lecon->points_recompense }} pts
                    </span>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-1 flex-shrink-0">
                    <a href="{{ route('admin.cours.lessons.edit', [$cour, $lecon]) }}"
                       class="btn btn-sm btn-outline-primary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.cours.lessons.destroy', [$cour, $lecon]) }}"
                          method="POST"
                          onsubmit="return confirm('Supprimer « {{ $lecon->titre }} » ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <p class="text-muted small mt-3">
            <i class="bi bi-grip-vertical me-1"></i>
            Glissez-déposez les lignes pour réordonner les leçons.
        </p>
    @endif

</div>
@endsection

@push('scripts')
{{-- Sortable.js depuis CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const el = document.getElementById('sortable-lessons');
if (el) {
    Sortable.create(el, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd() {
            const ordre = [...el.querySelectorAll('.lecon-row')].map(r => r.dataset.id);
            fetch("{{ route('admin.cours.lessons.reordonner', $cour) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ordre })
            });
        }
    });
}
</script>
@endpush