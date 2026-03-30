{{-- resources/views/admin/langues/series/show.blade.php --}}
@extends('layouts.dashboard')
@section('title', $serie->titre.' — Questions')

@push('styles')
<style>
/* ─── Entête série ─── */
.serie-header{background:#fff;border-radius:14px;border:1px solid #eee;
              padding:20px 22px;margin-bottom:20px;display:flex;
              align-items:center;justify-content:space-between;
              flex-wrap:wrap;gap:14px;box-shadow:0 2px 8px rgba(27,58,107,.04);}
.serie-meta-chips{display:flex;gap:8px;flex-wrap:wrap;}
.meta-chip{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;
           border-radius:8px;font-size:12px;font-weight:500;background:#f0f4f8;color:#555;}
.meta-chip i{font-size:12px;color:#F5A623;}

/* ─── Cartes questions ─── */
.q-card{background:#fff;border-radius:12px;border:1px solid #eee;padding:16px 18px;
        margin-bottom:10px;box-shadow:0 1px 4px rgba(27,58,107,.04);transition:all .2s;}
.q-card:hover{border-color:#ddd;box-shadow:0 3px 12px rgba(27,58,107,.07);}

.q-header{display:flex;align-items:flex-start;gap:12px;}
.q-num{width:34px;height:34px;border-radius:9px;background:rgba(27,58,107,.08);
       display:flex;align-items:center;justify-content:center;
       font-size:12px;font-weight:800;color:#1B3A6B;flex-shrink:0;}
.q-body{flex:1;min-width:0;}
.q-type-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;
              border-radius:8px;font-size:10px;font-weight:700;
              background:rgba(27,58,107,.08);color:#1B3A6B;margin-bottom:6px;}
.q-enonce{font-size:13px;font-weight:600;color:#333;line-height:1.5;margin-bottom:10px;}
.q-contexte{font-size:12px;color:#555;background:#fffbf0;border-left:3px solid #F5A623;
            padding:8px 12px;border-radius:0 8px 8px 0;margin-bottom:10px;line-height:1.5;}

/* Médias miniatures */
.q-medias{display:flex;align-items:center;gap:8px;margin-bottom:10px;}
.q-img-thumb{width:72px;height:54px;border-radius:7px;object-fit:cover;
             border:1px solid #eee;}
.q-audio-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 10px;
               border-radius:8px;background:rgba(245,166,35,.1);color:#633806;font-size:11px;}

/* Réponses */
.q-reponses{display:flex;flex-wrap:wrap;gap:6px;}
.rep-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;
          border-radius:8px;font-size:12px;}
.rep-pill.correcte{background:rgba(28,200,138,.1);border:1px solid rgba(28,200,138,.3);color:#0f6e56;}
.rep-pill.mauvaise{background:#f8f9fb;border:1px solid #eee;color:#666;}

/* Explication */
.q-explication{margin-top:8px;font-size:11px;color:#888;font-style:italic;
               background:#fafafa;border-radius:7px;padding:6px 10px;}

/* Actions */
.q-actions{display:flex;flex-direction:column;gap:4px;flex-shrink:0;}
.qa-btn{width:30px;height:30px;border-radius:8px;border:1px solid #e8e8e8;
        background:#fff;display:flex;align-items:center;justify-content:center;
        font-size:12px;color:#666;text-decoration:none;cursor:pointer;transition:all .15s;}
.qa-btn:hover{border-color:#1B3A6B;color:#1B3A6B;}
.qa-btn.red:hover{border-color:#E24B4A;color:#E24B4A;}

/* Zone vide */
.empty-zone{text-align:center;padding:56px 24px;background:#f8f9fb;
            border-radius:14px;border:1.5px dashed #ddd;}
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.langues.show', $serie->discipline->langue_id) }}"
       style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;
              color:#1B3A6B;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:11px;color:#888;margin-bottom:2px;">
            <span style="color:{{ $serie->discipline->langue->couleur }};font-weight:700;">
                {{ $serie->discipline->langue->nom }}
            </span>
            <span style="margin:0 6px;">·</span>
            {{ $serie->discipline->nom }}
        </div>
        <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.2rem;">
            {{ $serie->titre }}
        </h2>
    </div>
</div>

@if(session('success'))
<div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

{{-- Header série --}}
<div class="serie-header">
    <div class="serie-meta-chips">
        <span class="meta-chip">
            <i class="bi bi-bar-chart-steps"></i>
            <span style="color:{{ $serie->niveauColor() }};font-weight:700;">{{ $serie->niveauLabel() }}</span>
        </span>
        <span class="meta-chip"><i class="bi bi-clock"></i>{{ $serie->duree_minutes }} min</span>
        <span class="meta-chip">
            <i class="bi bi-{{ $serie->gratuite ? 'unlock' : 'lock' }}"></i>
            {{ $serie->gratuite ? 'Gratuite' : 'Premium' }}
        </span>
        <span class="meta-chip">
            <i class="bi bi-question-circle"></i>
            {{ $serie->questions->count() }} question(s)
        </span>
        <span class="meta-chip" style="color:{{ $serie->active ? '#1cc88a' : '#E24B4A' }};">
            <i class="bi bi-circle-fill" style="font-size:7px;"></i>
            {{ $serie->active ? 'Active' : 'Inactive' }}
        </span>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.series.edit', $serie) }}"
           style="display:inline-flex;align-items:center;gap:5px;padding:8px 16px;
                  border:1.5px solid #1B3A6B;color:#1B3A6B;border-radius:20px;
                  font-size:12px;font-weight:600;text-decoration:none;">
            <i class="bi bi-pencil"></i>Modifier la série
        </a>
        <a href="{{ route('admin.questions.create', $serie) }}"
           style="display:inline-flex;align-items:center;gap:5px;padding:8px 18px;
                  background:#1B3A6B;color:#fff;border-radius:20px;
                  font-size:12px;font-weight:700;text-decoration:none;">
            <i class="bi bi-plus-circle"></i>Ajouter une question
        </a>
    </div>
</div>

{{-- Questions --}}
@forelse($serie->questions as $q)
<div class="q-card">
    <div class="q-header">
        <div class="q-num">{{ $q->ordre + 1 }}</div>
        <div class="q-body">

            {{-- Type + méta --}}
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px;">
                <span class="q-type-badge">
                    {{ $q->typeLabel() }}
                </span>
                <span style="font-size:11px;color:#aaa;">
                    <i class="bi bi-star me-1"></i>{{ $q->points }} pt ·
                    <i class="bi bi-clock ms-1 me-1"></i>{{ $q->duree_secondes }}s
                </span>
            </div>

            {{-- Médias --}}
            @if($q->image || $q->audio)
            <div class="q-medias">
                @if($q->image)
                <img src="{{ asset('storage/'.$q->image) }}" class="q-img-thumb" alt="image">
                @endif
                @if($q->audio)
                <div class="q-audio-badge">
                    <i class="bi bi-headphones"></i>
                    <audio controls style="height:26px;max-width:160px;">
                        <source src="{{ asset('storage/'.$q->audio) }}">
                    </audio>
                </div>
                @endif
            </div>
            @endif

            {{-- Contexte --}}
            @if($q->contexte)
            <div class="q-contexte">{{ Str::limit($q->contexte, 150) }}</div>
            @endif

            {{-- Énoncé --}}
            <div class="q-enonce">{{ $q->enonce }}</div>

            {{-- Réponses --}}
            @if($q->reponses->count())
            <div class="q-reponses">
                @foreach($q->reponses as $rep)
                <span class="rep-pill {{ $rep->correcte ? 'correcte' : 'mauvaise' }}">
                    @if($rep->correcte)<i class="bi bi-check-circle-fill"></i>@endif
                    {{ $rep->texte }}
                </span>
                @endforeach
            </div>
            @endif

            {{-- Explication --}}
            @if($q->explication)
            <div class="q-explication">
                <i class="bi bi-lightbulb me-1" style="color:#F5A623;"></i>
                {{ $q->explication }}
            </div>
            @endif

        </div>

        {{-- Actions --}}
        <div class="q-actions">
            <a href="{{ route('admin.questions.edit', $q) }}"
               class="qa-btn" title="Modifier">
                <i class="bi bi-pencil"></i>
            </a>
            <form method="POST"
                  action="{{ route('admin.questions.destroy', $q) }}"
                  onsubmit="return confirm('Supprimer cette question ?')">
                @csrf @method('DELETE')
                <button type="submit" class="qa-btn red" title="Supprimer">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@empty
<div class="empty-zone">
    <i class="bi bi-patch-question" style="font-size:36px;color:#ccc;display:block;margin-bottom:12px;"></i>
    <div style="font-size:14px;color:#888;margin-bottom:16px;">
        Aucune question dans cette série.
    </div>
    <a href="{{ route('admin.questions.create', $serie) }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:10px 24px;
              background:#1B3A6B;color:#fff;border-radius:20px;
              font-size:13px;font-weight:700;text-decoration:none;">
        <i class="bi bi-plus-circle"></i>Ajouter la première question
    </a>
</div>
@endforelse

@endsection