@extends('layouts.dashboard')
@section('title', 'Gestion des tests — VisaFly')

@push('styles')
<style>
.test-card{background:#fff;border-radius:14px;border:1px solid #eee;padding:18px 20px;
           margin-bottom:12px;display:flex;align-items:center;gap:16px;
           box-shadow:0 1px 4px rgba(27,58,107,.04);transition:box-shadow .2s;}
.test-card:hover{box-shadow:0 4px 16px rgba(27,58,107,.08);}
.test-icon{width:46px;height:46px;border-radius:11px;display:flex;align-items:center;
           justify-content:center;font-size:14px;font-weight:900;color:#fff;flex-shrink:0;}
.badge-niveau{font-size:10px;font-weight:700;padding:2px 8px;border-radius:8px;
              background:rgba(27,58,107,.08);color:#1B3A6B;}
.badge-gratuit{font-size:10px;font-weight:700;padding:2px 8px;border-radius:8px;
               background:rgba(28,200,138,.1);color:#0f6e56;}
.badge-inactif{font-size:10px;font-weight:700;padding:2px 8px;border-radius:8px;
               background:rgba(226,75,74,.08);color:#a32d2d;}
.filter-select{border:1.5px solid #eee;border-radius:10px;padding:8px 14px;font-size:13px;color:#1B3A6B;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Gestion des tests</h2>
        <p class="text-muted mb-0" style="font-size:13px;">Créez et gérez les séries d'entraînement par langue</p>
    </div>
    <a href="{{ route('admin.tests.create') }}"
       style="background:#1B3A6B;color:#fff;padding:10px 20px;border-radius:20px;
              font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;
              align-items:center;gap:6px;">
        <i class="bi bi-plus-lg"></i> Nouveau test
    </a>
</div>

@if(session('success'))
<div class="alert rounded-3 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

{{-- ── Filtres ── --}}
<form method="GET" class="d-flex gap-2 mb-4 flex-wrap">
    <select name="langue" class="filter-select" onchange="this.form.submit()">
        <option value="">Toutes les langues</option>
        @foreach($langues as $l)
        <option value="{{ $l->code }}" {{ request('langue') === $l->code ? 'selected' : '' }}>
            {{ $l->nom }}
        </option>
        @endforeach
    </select>

    @if(request('langue') && $disciplines->count())
    <select name="discipline" class="filter-select" onchange="this.form.submit()">
        <option value="">Toutes les disciplines</option>
        @foreach($disciplines as $d)
        <option value="{{ $d->id }}" {{ (string) request('discipline') === (string) $d->id ? 'selected' : '' }}>
            {{ $d->nom }}
        </option>
        @endforeach
    </select>
    @endif

    @if(request('langue') || request('discipline'))
    <a href="{{ route('admin.tests.index') }}" class="filter-select text-decoration-none">
        <i class="bi bi-x-lg me-1"></i>Réinitialiser
    </a>
    @endif
</form>

{{-- ── Liste des tests ── --}}
@forelse($series as $serie)
<div class="test-card">
    <div class="test-icon" style="background:{{ $serie->discipline?->langue?->couleur ?? '#999' }};">
        {{ strtoupper($serie->discipline?->langue?->code ?? '??') }}
    </div>
    <div style="flex:1;">
        <div style="font-size:14px;font-weight:700;color:#1B3A6B;">
            {{ $serie->titre }}
        </div>
        <div style="font-size:12px;color:#888;margin-top:2px;">
            {{ $serie->discipline?->langue?->nom ?? '—' }} · {{ $serie->discipline?->nom ?? '—' }}
            · {{ $serie->questions_count }} question(s)
        </div>
        <div class="d-flex gap-2 mt-2">
            <span class="badge-niveau">Niveau {{ $serie->niveau }}</span>
            @if($serie->gratuite)
                <span class="badge-gratuit">Gratuite</span>
            @endif
            @if(!$serie->active)
                <span class="badge-inactif">Inactive</span>
            @endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tests.edit', $serie) }}"
           style="padding:8px 16px;border-radius:18px;background:rgba(27,58,107,.08);
                  color:#1B3A6B;font-size:12px;font-weight:600;text-decoration:none;">
            <i class="bi bi-pencil-square me-1"></i>Modifier
        </a>
        <form method="POST" action="{{ route('admin.tests.destroy', $serie) }}"
              onsubmit="return confirm('Supprimer ce test et toutes ses questions ?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding:8px 16px;border-radius:18px;border:none;
                     background:rgba(226,75,74,.08);color:#a32d2d;font-size:12px;font-weight:600;">
                <i class="bi bi-trash me-1"></i>Supprimer
            </button>
        </form>
    </div>
</div>
@empty
<div class="text-center py-5" style="color:#aaa;">
    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
    Aucun test trouvé.
</div>
@endforelse

<div class="mt-3">
    {{ $series->links('pagination::bootstrap-5') }}
</div>

@endsection