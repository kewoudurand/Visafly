{{-- resources/views/admin/langues/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Gestion des langues')

@push('styles')
<style>
/* ─── Cards examens ─── */
.lg-exam-card{background:#fff;border-radius:16px;border:1px solid #eee;overflow:hidden;
              box-shadow:0 2px 12px rgba(27,58,107,.05);transition:all .2s;}
.lg-exam-card:hover{box-shadow:0 6px 28px rgba(27,58,107,.1);transform:translateY(-2px);}

.lg-header{padding:20px 22px;display:flex;align-items:center;gap:14px;}
.lg-code-badge{width:54px;height:54px;border-radius:12px;background:rgba(255,255,255,.22);
               border:1.5px solid rgba(255,255,255,.3);
               display:flex;align-items:center;justify-content:center;
               font-size:14px;font-weight:900;flex-shrink:0;color:#fff;}
.lg-header-title{font-size:18px;font-weight:800;color:#fff;}
.lg-header-sub{font-size:11px;color:rgba(255,255,255,.7);margin-top:2px;}
.lg-header-count{margin-left:auto;font-size:11px;padding:4px 12px;border-radius:12px;
                 background:rgba(255,255,255,.18);color:#fff;font-weight:600;white-space:nowrap;}

.lg-body{padding:18px 22px;}

/* Stats grille */
.lg-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:16px;}
.lg-stat{text-align:center;padding:12px 8px;border-radius:10px;background:#f8f9fb;}
.lg-stat-num{font-size:1.6rem;font-weight:800;color:#1B3A6B;line-height:1;}
.lg-stat-lbl{font-size:10px;font-weight:600;color:#888;text-transform:uppercase;
             letter-spacing:.5px;margin-top:3px;}

/* Chips disciplines */
.disc-chips{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;}
.disc-chip{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;
           border-radius:8px;font-size:11px;font-weight:600;color:#1B3A6B;
           background:rgba(27,58,107,.07);border:1px solid rgba(27,58,107,.1);}
.disc-chip i{font-size:11px;}

.lg-btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;
        border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;
        transition:all .2s;color:#fff;}
.lg-btn:hover{filter:brightness(1.1);transform:translateY(-1px);color:#fff;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">
            Gestion des langues
        </h2>
        <p class="text-muted mb-0" style="font-size:13px;">
            TCF · TEF · IELTS · Goethe — Gérez les séries et les questions
        </p>
    </div>
</div>

@if(session('success'))
<div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

<div class="row g-4">
@foreach($langues as $langue)
<div class="col-md-6">
<div class="lg-exam-card">

    {{-- Header coloré --}}
    <div class="lg-header" style="background:{{ $langue->couleur }};">
        <div class="lg-code-badge">{{ strtoupper($langue->code) }}</div>
        <div>
            <div class="lg-header-title">{{ $langue->nom }}</div>
            <div class="lg-header-sub">{{ $langue->organisme }}</div>
        </div>
        <div class="lg-header-count">
            {{ $langue->disciplines->count() }} disciplines
        </div>
    </div>

    <div class="lg-body">

        {{-- Stats --}}
        <div class="lg-stats">
            <div class="lg-stat">
                <div class="lg-stat-num">{{ $langue->series()->count() }}</div>
                <div class="lg-stat-lbl">Séries</div>
            </div>
            <div class="lg-stat">
                <div class="lg-stat-num">
                    {{ \App\Models\LangueQuestion::whereIn('serie_id',
                        $langue->series()->pluck('langue_series.id'))->count() }}
                </div>
                <div class="lg-stat-lbl">Questions</div>
            </div>
            <div class="lg-stat">
                <div class="lg-stat-num"
                     style="color:{{ $langue->actif ? '#1cc88a' : '#E24B4A' }};font-size:1.1rem;">
                    <i class="bi bi-circle-fill" style="font-size:9px;vertical-align:middle;"></i>
                    {{ $langue->actif ? 'Actif' : 'Inactif' }}
                </div>
                <div class="lg-stat-lbl">Statut</div>
            </div>
        </div>

        {{-- Chips disciplines --}}
        <div class="disc-chips">
            @foreach($langue->disciplines as $d)
            <span class="disc-chip">
                <i class="bi {{ $d->typeIcon() }}"></i>
                {{ $d->nom_court ?? strtoupper($d->code) }}
                @if($d->has_audio) <i class="bi bi-headphones" style="color:#F5A623;"></i>@endif
                @if($d->has_image) <i class="bi bi-image" style="color:#1B3A6B;"></i>@endif
            </span>
            @endforeach
        </div>

        {{-- Bouton --}}
        <a href="{{ route('admin.langues.show', $langue) }}"
           class="lg-btn" style="background:{{ $langue->couleur }};">
            <i class="bi bi-folder2-open"></i>Gérer les séries
        </a>
    </div>

</div>
</div>
@endforeach
</div>

@endsection