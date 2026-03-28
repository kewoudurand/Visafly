{{-- resources/views/langues/disciplines.blade.php --}}
@extends('layouts.app')
@section('title', 'Choisissez une discipline — '.$serie->titre)

@push('styles')
<style>
.disc-hero{text-align:center;padding:48px 20px 36px;}
.disc-hero h2{font-size:1.4rem;font-weight:800;color:#1B3A6B;}
.disc-hero h2 strong{color:{{ $langue->couleur }};}
.disc-hero p{font-size:14px;color:#666;margin-top:10px;}

.disc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;
           max-width:900px;margin:0 auto;padding:0 20px 60px;}
@media(max-width:860px){.disc-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.disc-grid{grid-template-columns:1fr;}}

.disc-card{background:#fff;border:1.5px solid #e8e8e8;border-radius:16px;
           padding:28px 20px 24px;text-align:center;cursor:pointer;
           transition:all .25s;display:flex;flex-direction:column;align-items:center;gap:12px;}
.disc-card:hover{border-color:{{ $langue->couleur }};
                 box-shadow:0 8px 28px rgba(27,58,107,.12);transform:translateY(-3px);}
.disc-card-icon{width:64px;height:64px;border-radius:50%;
                display:flex;align-items:center;justify-content:center;}
.disc-card-icon svg,.disc-card-icon i{font-size:32px;color:{{ $langue->couleur }};}
.disc-card-name{font-size:14px;font-weight:700;color:#1B3A6B;}
.disc-card-info{display:flex;flex-direction:column;gap:5px;width:100%;}
.disc-card-row{display:flex;align-items:center;gap:6px;font-size:12px;color:#888;
               justify-content:center;}

/* Modal confirmation */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);
               z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.modal-box{background:#fff;border-radius:18px;padding:36px;max-width:480px;width:100%;
           box-shadow:0 24px 60px rgba(0,0,0,.2);position:relative;}
.modal-close{position:absolute;top:14px;right:16px;background:none;border:none;
             font-size:20px;color:#aaa;cursor:pointer;}
.modal-close:hover{color:#333;}
</style>
@endpush

@section('content')

<div class="disc-hero">
  <div style="display:inline-block;margin-bottom:16px;">
    <a href="{{ route('langues.series', $langue->code) }}"
       style="display:inline-flex;align-items:center;gap:6px;width:34px;height:34px;
              border-radius:8px;background:#f0f0f0;color:#1B3A6B;text-decoration:none;
              justify-content:center;font-size:14px;">
      <i class="bi bi-arrow-left"></i>
    </a>
  </div>
  <h2>Vous êtes sur le point de commencer la série
    <strong>{{ $serie->titre }}</strong>
    du {{ $langue->nom }}
  </h2>
  <p>Vous devez choisir une discipline pour débuter le test, bon apprentissage !</p>
</div>

{{-- Grille des 4 disciplines --}}
<div class="disc-grid">
  @foreach($disciplines as $disc)
  <div class="disc-card" onclick="openModal({{ $disc->id }})">
    <div class="disc-card-icon">
      {{-- Icône SVG selon le type --}}
      @if(in_array($disc->code, ['ce','reading','lesen']))
        {{-- Livre ouvert --}}
        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="56" height="56">
          <path d="M8 14C8 14 20 10 32 14V54C20 50 8 54 8 54V14Z" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linejoin="round"/>
          <path d="M56 14C56 14 44 10 32 14V54C44 50 56 54 56 54V14Z" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linejoin="round"/>
        </svg>
      @elseif(in_array($disc->code, ['co','listening','horen']))
        {{-- Haut-parleur --}}
        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="56" height="56">
          <path d="M14 22H22L34 12V52L22 42H14V22Z" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linejoin="round"/>
          <path d="M42 22C46 26 46 38 42 42" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linecap="round"/>
          <path d="M48 16C54 22 54 42 48 48" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linecap="round"/>
        </svg>
      @elseif(in_array($disc->code, ['ee','pe','writing','schreiben']))
        {{-- Crayon --}}
        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="56" height="56">
          <path d="M12 52L16 36L44 8L56 20L28 48L12 52Z" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linejoin="round"/>
          <path d="M36 16L48 28" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linecap="round"/>
          <path d="M12 52L16 36" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linecap="round"/>
        </svg>
      @else
        {{-- Micro --}}
        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="56" height="56">
          <rect x="22" y="8" width="20" height="30" rx="10" stroke="{{ $langue->couleur }}" stroke-width="3"/>
          <path d="M14 32C14 42.5 50 42.5 50 32" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linecap="round"/>
          <path d="M32 44V56" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linecap="round"/>
          <path d="M24 56H40" stroke="{{ $langue->couleur }}" stroke-width="3" stroke-linecap="round"/>
        </svg>
      @endif
    </div>
    <div class="disc-card-name">{{ $disc->nom }}</div>
    <div class="disc-card-info">
      <div class="disc-card-row">
        <i class="bi bi-clock" style="font-size:11px;"></i>
        {{ $disc->duree_minutes }} minutes
      </div>
      <div class="disc-card-row">
        <i class="bi bi-list-check" style="font-size:11px;"></i>
        @php
          $nbQ = \App\Models\LangueQuestion::whereIn('serie_id',
            \App\Models\LangueSerie::where('discipline_id', $disc->id)->pluck('id')
          )->count();
        @endphp
        {{ $nbQ > 0 ? $nbQ.' questions' : 'Questions à venir' }}
      </div>
      @if($disc->has_audio)
      <div class="disc-card-row" style="color:#F5A623;">
        <i class="bi bi-headphones" style="font-size:11px;"></i>Audio inclus
      </div>
      @endif
    </div>
  </div>

  {{-- Modal de cette discipline --}}
  <div id="modal-{{ $disc->id }}" class="modal-overlay">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal({{ $disc->id }})">
        <i class="bi bi-x-lg"></i>
      </button>
      <h3 style="font-size:1.2rem;font-weight:800;color:#1B3A6B;margin-bottom:16px;">
        Début du test
      </h3>
      <p style="font-size:14px;color:#1B3A6B;font-weight:500;line-height:1.7;margin-bottom:12px;text-align:center;">
        Vous êtes sur le point de débuter un test de
        <strong>{{ $disc->nom }}</strong> type examen {{ $langue->nom }}.
        Il comporte <strong>{{ $nbQ }}</strong> questions et dure
        <strong>{{ $disc->duree_minutes }} minutes</strong> exactement.
      </p>
      <p style="font-size:13px;color:#555;text-align:center;margin-bottom:20px;">
        Prenez la peine de bien lire les questions et les consignes avant de répondre.
      </p>
      <p style="font-size:14px;font-weight:800;color:#1B3A6B;text-align:center;margin-bottom:24px;">
        ÊTES-VOUS PRÊT À COMMENCER ?
      </p>
      <div style="display:flex;gap:12px;">
        <button onclick="closeModal({{ $disc->id }})"
                style="flex:1;padding:12px;border-radius:25px;border:1.5px solid #ddd;
                       background:#fff;color:#666;font-size:13px;font-weight:600;cursor:pointer;">
          Annuler
        </button>
        <a href="{{ route('langues.epreuve', [$langue->code, $serie->id, $disc->id]) }}"
           style="flex:1;padding:12px;border-radius:25px;background:{{ $langue->couleur }};
                  color:{{ in_array($langue->code,['tcf','tef']) ? '#fff' : '#fff' }};
                  font-size:13px;font-weight:700;text-decoration:none;
                  display:flex;align-items:center;justify-content:center;">
          Commencer
        </a>
      </div>
    </div>
  </div>
  @endforeach
</div>

<script>
function openModal(id)  { document.getElementById('modal-'+id).style.display='flex'; }
function closeModal(id) { document.getElementById('modal-'+id).style.display='none'; }
document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if(e.target===m) m.style.display='none'; });
});
</script>

@endsection