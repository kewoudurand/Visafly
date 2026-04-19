{{-- resources/views/instructeur/lessons/index.blade.php --}}
@extends('layouts.instructeur')
@section('title', 'Leçons — ' . $cours->titre)

@push('styles')
<style>
    .lecon-row {
        background: #fff; border-radius: 12px; padding: 1rem 1.25rem;
        margin-bottom: .6rem; border: 1px solid #e9ecef;
        display: flex; align-items: center; gap: 1rem;
        transition: box-shadow .15s;
    }
    .lecon-row:hover { box-shadow: 0 3px 12px rgba(27,58,107,.1); }
    .lecon-row.sortable-ghost { opacity: .4; background: #f0f4ff; }
    .drag-handle { cursor: grab; color: #adb5bd; font-size: 1.1rem; }
    .drag-handle:active { cursor: grabbing; }
    .type-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .cours-header {
        background: linear-gradient(135deg, #1B3A6B, #243f70);
        border-radius: 14px; padding: 1.5rem 2rem;
        color: #fff; margin-bottom: 1.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- ── Header du cours ──────────────────────────────── --}}
    <div class="cours-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('instructeur.cours.index') }}"
                   class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <div class="mb-1">
                        <span class="badge fw-bold px-3" style="background:#F5A623;color:#000">{{ $cours->niveau }}</span>
                        @if(!$cours->publie)<span class="badge bg-secondary ms-1">Brouillon</span>@endif
                    </div>
                    <h4 class="fw-bold mb-0">{{ $cours->titre }}</h4>
                    <p class="mb-0 opacity-75 small">{{ $lecons->count() }} leçon(s)</p>
                </div>
            </div>
            <a href="{{ route('instructeur.cours.lessons.create', $cours) }}"
               class="btn fw-bold px-4" style="background:#F5A623;color:#000">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle leçon
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Liste leçons ─────────────────────────────────── --}}
    @if($lecons->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:3rem">📝</div>
            <p class="text-muted mt-2 mb-3">Aucune leçon dans ce cours.</p>
            <a href="{{ route('instructeur.cours.lessons.create', $cours) }}" class="btn btn-primary">
                Créer la première leçon
            </a>
        </div>
    @else
        <div id="sortable-lessons">
            @foreach($lecons as $lecon)
            <div class="lecon-row" data-id="{{ $lecon->id }}">
                <i class="bi bi-grip-vertical drag-handle" title="Réordonner"></i>

                @php
                    $dot = match($lecon->type) {
                        'vocabulaire' => '#198754', 'dialogue' => '#0d6efd',
                        'grammaire' => '#ffc107', 'audio' => '#0dcaf0', default => '#adb5bd'
                    };
                @endphp
                <div class="type-dot" style="background:{{ $dot }}" title="{{ ucfirst($lecon->type) }}"></div>

                <div class="flex-grow-1 min-w-0">
                    <p class="mb-0 fw-semibold text-truncate" style="font-size:.95rem">
                        {{ $lecon->ordre }}. {{ $lecon->titre }}
                    </p>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        <span class="text-muted" style="font-size:.76rem">
                            <i class="bi bi-tag me-1"></i>{{ ucfirst($lecon->type) }}
                        </span>
                        @if($lecon->nombreMots())
                            <span class="text-muted" style="font-size:.76rem">
                                <i class="bi bi-alphabet me-1"></i>{{ $lecon->nombreMots() }} mots
                            </span>
                        @endif
                        @if($lecon->nombreExercices())
                            <span class="text-muted" style="font-size:.76rem">
                                <i class="bi bi-pencil me-1"></i>{{ $lecon->nombreExercices() }} ex.
                            </span>
                        @endif
                    </div>
                </div>

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

                <div class="d-flex gap-1 flex-shrink-0">
                    <a href="{{ route('instructeur.cours.lessons.edit', [$cours, $lecon]) }}"
                       class="btn btn-sm btn-outline-primary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('instructeur.cours.lessons.destroy', [$cours, $lecon]) }}"
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
        <p class="text-muted small mt-2">
            <i class="bi bi-grip-vertical me-1"></i>Glissez pour réordonner.
        </p>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const el = document.getElementById('sortable-lessons');
if (el) {
    Sortable.create(el, {
        handle: '.drag-handle', animation: 150, ghostClass: 'sortable-ghost',
        onEnd() {
            const ordre = [...el.querySelectorAll('.lecon-row')].map(r => r.dataset.id);
            fetch("{{ route('instructeur.cours.lessons.reordonner', $cours) }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ordre })
            });
        }
    });
}
</script>
@endpush