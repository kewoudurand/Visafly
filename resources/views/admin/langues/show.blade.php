{{-- resources/views/admin/langues/show.blade.php --}}
@extends('layouts.dashboard')
@section('title', $langue->nom.' — Séries')

@push('styles')
<style>
/* ─── Onglets disciplines ─── */
.disc-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;}
.disc-tab-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;
              border-radius:10px;border:1.5px solid #e8e8e8;background:#fff;
              font-size:13px;font-weight:600;color:#666;cursor:pointer;transition:all .2s;}
.disc-tab-btn:hover{border-color:#ddd;background:#f8f9fb;}
.disc-tab-btn.active{color:#fff;border-color:transparent;}
.disc-tab-btn .nb{font-size:10px;padding:2px 7px;border-radius:8px;
                  background:rgba(255,255,255,.25);font-weight:700;}

/* ─── Info bande discipline ─── */
.disc-info-bar{display:flex;align-items:center;justify-content:space-between;
               flex-wrap:wrap;gap:12px;padding:14px 18px;
               background:#fff;border-radius:12px;border:1px solid #eee;
               margin-bottom:14px;}
.disc-meta{display:flex;gap:16px;flex-wrap:wrap;}
.disc-meta-item{display:flex;align-items:center;gap:5px;font-size:12px;color:#666;}
.disc-meta-item i{color:#F5A623;font-size:13px;}

/* ─── Lignes de séries ─── */
.serie-row{display:flex;align-items:center;justify-content:space-between;
           padding:14px 16px;border-radius:10px;background:#f8f9fb;
           margin-bottom:8px;border:1px solid #eee;transition:all .2s;}
.serie-row:hover{background:#fff;border-color:#ddd;box-shadow:0 2px 8px rgba(27,58,107,.06);}

.serie-num{width:36px;height:36px;border-radius:9px;flex-shrink:0;
           display:flex;align-items:center;justify-content:center;
           font-size:13px;font-weight:800;}
.serie-titre{font-size:13px;font-weight:700;color:#1B3A6B;}
.serie-meta{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:3px;}
.serie-meta span{font-size:11px;color:#888;display:flex;align-items:center;gap:3px;}

/* Badges niveau + gratuite */
.badge-niv{padding:2px 9px;border-radius:8px;font-size:10px;font-weight:700;}
.niv-1{background:rgba(28,200,138,.1);color:#0f6e56;}
.niv-2{background:rgba(245,166,35,.1);color:#633806;}
.niv-3{background:rgba(226,75,74,.1);color:#a32d2d;}
.badge-gratuit{padding:2px 9px;border-radius:8px;font-size:10px;font-weight:700;
               background:rgba(28,200,138,.1);color:#0f6e56;}
.badge-premium{padding:2px 9px;border-radius:8px;font-size:10px;font-weight:700;
               background:rgba(245,166,35,.1);color:#633806;}
.badge-inactive{padding:2px 9px;border-radius:8px;font-size:10px;font-weight:700;
                background:#f0f0f0;color:#999;}

/* Actions série */
.s-actions{display:flex;gap:4px;flex-shrink:0;}
.s-btn{width:30px;height:30px;border-radius:8px;border:1px solid #e8e8e8;
       background:#fff;display:inline-flex;align-items:center;justify-content:center;
       font-size:12px;color:#666;text-decoration:none;cursor:pointer;transition:all .15s;}
.s-btn:hover{border-color:#1B3A6B;color:#1B3A6B;}
.s-btn.red:hover{border-color:#E24B4A;color:#E24B4A;}

/* Zone vide */
.empty-zone{text-align:center;padding:40px 24px;background:#f8f9fb;
            border-radius:12px;border:1.5px dashed #ddd;}
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.langues.index') }}"
       style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;
              color:#1B3A6B;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:44px;height:44px;border-radius:11px;background:{{ $langue->couleur }};
                    display:flex;align-items:center;justify-content:center;
                    font-size:14px;font-weight:900;color:#fff;">
            {{ strtoupper($langue->code) }}
        </div>
        <div>
            <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.3rem;">
                {{ $langue->nom }}
            </h2>
            <p class="text-muted mb-0" style="font-size:12px;">{{ $langue->organisme }}</p>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

{{-- Onglets disciplines --}}
<div class="disc-tabs" id="discTabs">
    @foreach($langue->disciplines as $i => $disc)
    <button class="disc-tab-btn {{ $i === 0 ? 'active' : '' }}"
            id="tab-{{ $disc->id }}"
            onclick="switchDisc({{ $disc->id }}, this)"
            style="{{ $i === 0 ? 'background:'.$langue->couleur.';border-color:'.$langue->couleur.';' : '' }}">
        <i class="bi {{ $disc->typeIcon() }}"></i>
        {{ $disc->nom_court ?? strtoupper($disc->code) }}
        <span style="font-size:11px;opacity:.75;font-weight:400;">{{ $disc->nom }}</span>
        <span class="nb">{{ $disc->series->count() }}</span>
    </button>
    @endforeach
</div>

{{-- Panneaux --}}
@foreach($langue->disciplines as $i => $disc)
<div id="panel-{{ $disc->id }}" style="{{ $i !== 0 ? 'display:none;' : '' }}">

    {{-- Bande info discipline --}}
    <div class="disc-info-bar">
        <div class="disc-meta">
            <div class="disc-meta-item">
                <i class="bi bi-clock"></i>{{ $disc->duree_minutes }} min
            </div>
            <div class="disc-meta-item">
                <i class="bi bi-tag"></i>{{ $disc->typeLabel() }}
            </div>
            @if($disc->has_audio)
            <div class="disc-meta-item">
                <i class="bi bi-headphones"></i>Audio
            </div>
            @endif
            @if($disc->has_image)
            <div class="disc-meta-item">
                <i class="bi bi-image"></i>Images
            </div>
            @endif
            <div class="disc-meta-item">
                <i class="bi bi-collection"></i>{{ $disc->series->count() }} série(s)
            </div>
        </div>
        <a href="{{ route('admin.series.create', $disc) }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;
                  background:{{ $langue->couleur }};color:#fff;border-radius:20px;
                  font-size:12px;font-weight:700;text-decoration:none;">
            <i class="bi bi-plus-circle"></i>Nouvelle série
        </a>
    </div>

    {{-- Liste séries --}}
    @forelse($disc->series as $serie)
    <div class="serie-row">
        {{-- Icône nb questions --}}
        <div class="serie-num"
             style="background:{{ $serie->active ? 'rgba(27,58,107,.08)' : '#f0f0f0' }};
                    color:{{ $serie->active ? '#1B3A6B' : '#aaa' }};">
            {{ $serie->nombre_questions }}
        </div>

        {{-- Infos --}}
        <div style="flex:1;min-width:0;margin:0 14px;">
            <div class="serie-titre">{{ $serie->titre }}</div>
            <div class="serie-meta">
                <span class="badge-niv niv-{{ $serie->niveau }}">{{ $serie->niveauLabel() }}</span>
                <span class="{{ $serie->gratuite ? 'badge-gratuit' : 'badge-premium' }}">
                    <i class="bi bi-{{ $serie->gratuite ? 'unlock' : 'lock' }}"></i>
                    {{ $serie->gratuite ? 'Gratuite' : 'Premium' }}
                </span>
                @if(!$serie->active)
                <span class="badge-inactive">Inactive</span>
                @endif
                <span><i class="bi bi-clock"></i>{{ $serie->duree_minutes }} min</span>
                <span><i class="bi bi-question-circle"></i>{{ $serie->nombre_questions }} question(s)</span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="s-actions">
            <a href="{{ route('admin.series.show', $serie) }}"
               class="s-btn" title="Voir les questions">
                <i class="bi bi-list-check"></i>
            </a>
            <a href="{{ route('admin.series.edit', $serie) }}"
               class="s-btn" title="Modifier la série">
                <i class="bi bi-pencil"></i>
            </a>
            <form method="POST"
                  action="{{ route('admin.series.destroy', $serie) }}"
                  onsubmit="return confirm('Supprimer « {{ $serie->titre }} » et ses {{ $serie->nombre_questions }} questions ?')">
                @csrf @method('DELETE')
                <button type="submit" class="s-btn red" title="Supprimer">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-zone">
        <i class="bi bi-collection" style="font-size:30px;color:#ccc;display:block;margin-bottom:10px;"></i>
        <div style="font-size:13px;color:#888;margin-bottom:14px;">Aucune série pour cette discipline.</div>
        <a href="{{ route('admin.langues.series.create', $disc) }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:9px 20px;
                  background:{{ $langue->couleur }};color:#fff;border-radius:20px;
                  font-size:12px;font-weight:700;text-decoration:none;">
            <i class="bi bi-plus-circle"></i>Créer la première série
        </a>
    </div>
    @endforelse

</div>
@endforeach

@push('scripts')
<script>
const color = '{{ $langue->couleur }}';

function switchDisc(id, btn) {
    document.querySelectorAll('[id^="panel-"]').forEach(p => p.style.display = 'none');
    document.getElementById('panel-' + id).style.display = 'block';

    document.querySelectorAll('.disc-tab-btn').forEach(b => {
        b.classList.remove('active');
        b.style.background  = '#fff';
        b.style.color       = '#666';
        b.style.borderColor = '#e8e8e8';
    });

    btn.classList.add('active');
    btn.style.background  = color;
    btn.style.color       = '#fff';
    btn.style.borderColor = color;
}
</script>
@endpush

@endsection