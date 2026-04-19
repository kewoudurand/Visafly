{{-- resources/views/instructeur/dashboard.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Mon espace — VisaFly')

@push('styles')
<style>
    .dash-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(27,58,107,.08);
        border-left: 5px solid transparent;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .dash-card .icon-wrap {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; flex-shrink: 0;
    }
    .dash-card .stat-val { font-size: 2rem; font-weight: 800; line-height: 1; }
    .dash-card .stat-lbl { font-size: .8rem; color: #6c757d; margin-top: .2rem; }
    .welcome-banner {
        background: linear-gradient(135deg, #1B3A6B, #243f70);
        border-radius: 16px;
        padding: 2rem;
        color: #fff;
        margin-bottom: 2rem;
    }
    .welcome-banner .orb {
        width: 80px; height: 80px; border-radius: 50%;
        background: rgba(245,166,35,.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem;
    }
    .recent-lesson {
        display: flex; align-items: center; gap: .85rem;
        padding: .75rem 0; border-bottom: 1px solid #f0f0f0;
    }
    .recent-lesson:last-child { border-bottom: none; }
    .recent-lesson .type-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem; flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- ── Banner de bienvenue ──────────────────────────────── --}}
    <div class="welcome-banner">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="orb">👨‍🏫</div>
                <div>
                    <h4 class="fw-bold mb-1">Bonjour, {{ auth()->user()->name }} 👋</h4>
                    <p class="mb-0 opacity-75">Gérez vos cours et leçons depuis votre espace instructeur.</p>
                </div>
            </div>
            <a href="{{ route('instructeur.cours.create') }}"
               class="btn fw-bold px-4 py-2" style="background:#F5A623;color:#000">
                <i class="bi bi-plus-lg me-2"></i>Nouveau cours
            </a>
        </div>
    </div>

    {{-- ── Statistiques ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        @php
            $mesCours   = \App\Models\Course::deInstructeur(auth()->id())->count();
            $mesLecons  = \App\Models\Lesson::deInstructeur(auth()->id())->count();
            $publies    = \App\Models\Course::deInstructeur(auth()->id())->where('publie', true)->count();
            $etudiants  = \App\Models\LessonProgression::whereIn(
                'lesson_id',
                \App\Models\Lesson::deInstructeur(auth()->id())->pluck('id')
            )->distinct('user_id')->count();
        @endphp

        <div class="col-sm-6 col-xl-3">
            <div class="dash-card" style="border-left-color:#1B3A6B">
                <div class="icon-wrap" style="background:#f0f4ff"><i class="bi bi-journals" style="color:#1B3A6B"></i></div>
                <div>
                    <div class="stat-val" style="color:#1B3A6B">{{ $mesCours }}</div>
                    <div class="stat-lbl">Cours créés</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="dash-card" style="border-left-color:#F5A623">
                <div class="icon-wrap" style="background:#fff8e8"><i class="bi bi-book" style="color:#F5A623"></i></div>
                <div>
                    <div class="stat-val" style="color:#F5A623">{{ $mesLecons }}</div>
                    <div class="stat-lbl">Leçons créées</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="dash-card" style="border-left-color:#198754">
                <div class="icon-wrap" style="background:#e8f8f0"><i class="bi bi-globe" style="color:#198754"></i></div>
                <div>
                    <div class="stat-val" style="color:#198754">{{ $publies }}</div>
                    <div class="stat-lbl">Cours publiés</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="dash-card" style="border-left-color:#0dcaf0">
                <div class="icon-wrap" style="background:#e8f9ff"><i class="bi bi-people" style="color:#0dcaf0"></i></div>
                <div>
                    <div class="stat-val" style="color:#0dcaf0">{{ $etudiants }}</div>
                    <div class="stat-lbl">Étudiants actifs</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Dernières leçons créées ──────────────────────────── --}}
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius:14px">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center px-4 pt-4 pb-3 border-bottom">
                        <h6 class="fw-bold mb-0" style="color:#1B3A6B">Mes dernières leçons</h6>
                        <a href="{{ route('instructeur.cours.index') }}" class="btn btn-sm btn-light">Voir tout</a>
                    </div>
                    @php
                        $dernieresLecons = \App\Models\Lesson::deInstructeur(auth()->id())
                            ->with('cours')
                            ->latest()
                            ->take(6)
                            ->get();
                    @endphp
                    <div class="px-4 py-2">
                        @forelse($dernieresLecons as $l)
                        <div class="recent-lesson">
                            <div class="type-icon" style="background:{{ match($l->type) {
                                'vocabulaire' => '#e8f8f0', 'dialogue' => '#e8f0ff',
                                'grammaire' => '#fff8e8', 'audio' => '#e8f9ff', default => '#f8f9fa'
                            } }}">
                                <i class="bi {{ $l->iconeType() }}" style="color:{{ match($l->type) {
                                    'vocabulaire' => '#198754', 'dialogue' => '#0d6efd',
                                    'grammaire' => '#856404', 'audio' => '#0dcaf0', default => '#6c757d'
                                } }}"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <p class="mb-0 fw-semibold text-truncate small">{{ $l->titre }}</p>
                                <p class="mb-0 text-muted" style="font-size:.75rem">{{ $l->cours->titre ?? '—' }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                @if(!$l->publiee)
                                    <span class="badge bg-secondary-subtle text-secondary">Brouillon</span>
                                @endif
                                <a href="{{ route('instructeur.cours.lessons.edit', [$l->cours, $l]) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center py-4">Aucune leçon créée pour l'instant.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius:14px">
                <div class="card-body p-0">
                    <div class="px-4 pt-4 pb-3 border-bottom">
                        <h6 class="fw-bold mb-0" style="color:#1B3A6B">Mes cours</h6>
                    </div>
                    @php
                        $mesCoursList = \App\Models\Course::deInstructeur(auth()->id())
                            ->withCount('lessons')
                            ->orderBy('ordre')
                            ->take(5)
                            ->get();
                    @endphp
                    <div class="px-4 py-2">
                        @forelse($mesCoursList as $c)
                        <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                            <span class="badge fw-bold px-2 py-1" style="background:{{ $c->couleur ?? '#1B3A6B' }};color:#fff;border-radius:6px;font-size:.7rem">
                                {{ $c->niveau }}
                            </span>
                            <div class="flex-grow-1 min-w-0">
                                <p class="mb-0 fw-semibold small text-truncate">{{ $c->titre }}</p>
                                <p class="mb-0 text-muted" style="font-size:.73rem">{{ $c->lessons_count }} leçon(s)</p>
                            </div>
                            <a href="{{ route('instructeur.cours.lessons.index', $c) }}"
                               class="btn btn-sm btn-light py-0 px-2" title="Gérer les leçons">
                                <i class="bi bi-list-ul"></i>
                            </a>
                        </div>
                        @empty
                        <p class="text-muted text-center py-4 small">Aucun cours créé.</p>
                        @endforelse
                        @if($mesCours > 5)
                        <div class="text-center pt-2 pb-1">
                            <a href="{{ route('instructeur.cours.index') }}" class="btn btn-sm btn-light">
                                Voir tous mes cours ({{ $mesCours }})
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection