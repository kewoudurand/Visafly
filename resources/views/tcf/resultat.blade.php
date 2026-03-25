{{-- resources/views/tcf/resultat.blade.php --}}
@extends('layouts.app')

@section('title', 'Résultats — '.$passage->discipline->nom)

@section('content')
<div class="container py-4">

  {{-- En-tête résultat --}}
  <div class="text-center mb-4">
    <div class="mb-3">
      @if($passage->score >= 70)
        <div style="width:80px;height:80px;background:rgba(28,200,138,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;">
          <i class="bi bi-patch-check-fill" style="font-size:36px;color:#1cc88a;"></i>
        </div>
      @else
        <div style="width:80px;height:80px;background:rgba(245,166,35,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;">
          <i class="bi bi-clipboard-data" style="font-size:36px;color:#F5A623;"></i>
        </div>
      @endif
    </div>
    <h2 class="fw-bold" style="color:#1B3A6B;">Résultats de votre épreuve</h2>
    <p class="text-muted">{{ $passage->discipline->serie->nom }} — {{ $passage->discipline->nom }}</p>
  </div>

  {{-- Cartes score --}}
  <div class="row g-3 mb-4 justify-content-center">
    <div class="col-md-3 col-6">
      <div class="text-center p-3 rounded-3" style="background:rgba(27,58,107,.05);border:1px solid rgba(27,58,107,.1);">
        <div style="font-size:32px;font-weight:800;color:#1B3A6B;">{{ $passage->score }}<small style="font-size:16px;">%</small></div>
        <div style="font-size:12px;color:#888;margin-top:4px;">Score global</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="text-center p-3 rounded-3" style="background:rgba(28,200,138,.05);border:1px solid rgba(28,200,138,.2);">
        <div style="font-size:32px;font-weight:800;color:#1cc88a;">{{ $passage->nb_correctes }}</div>
        <div style="font-size:12px;color:#888;margin-top:4px;">Bonnes réponses</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="text-center p-3 rounded-3" style="background:rgba(226,75,74,.05);border:1px solid rgba(226,75,74,.2);">
        <div style="font-size:32px;font-weight:800;color:#E24B4A;">
          {{ $passage->discipline->nb_questions - $passage->nb_correctes }}
        </div>
        <div style="font-size:12px;color:#888;margin-top:4px;">Mauvaises réponses</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="text-center p-3 rounded-3" style="background:rgba(27,58,107,.05);border:1px solid rgba(27,58,107,.1);">
        <div style="font-size:32px;font-weight:800;color:#1B3A6B;">
          {{ gmdate('i:s', $passage->temps_utilise ?? 0) }}
        </div>
        <div style="font-size:12px;color:#888;margin-top:4px;">Temps utilisé</div>
      </div>
    </div>
  </div>

  {{-- Détail des réponses --}}
  <div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4">
      <h5 class="mb-0 fw-semibold" style="color:#1B3A6B;">Détail des réponses</h5>
    </div>
    <div class="card-body p-0">
      @foreach($passage->passageReponses as $pr)
        <div class="p-4 border-bottom d-flex gap-3 align-items-start">

          {{-- Numéro --}}
          <div style="width:36px;height:36px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;
                      background:{{ $pr->est_correcte ? 'rgba(28,200,138,.15)' : 'rgba(226,75,74,.12)' }};
                      color:{{ $pr->est_correcte ? '#0f6e56' : '#a32d2d' }};">
            {{ $pr->question->numero }}
          </div>

          <div class="flex-grow-1">
            <p style="font-size:14px;color:#333;margin-bottom:8px;">{{ $pr->question->enonce }}</p>

            {{-- Réponses --}}
            <div class="d-flex flex-wrap gap-2">
              @foreach($pr->question->reponses as $rep)
                <span class="px-3 py-1 rounded-pill"
                      style="font-size:12px;
                        @if($rep->est_correcte) background:rgba(28,200,138,.15);color:#0f6e56;border:1px solid rgba(28,200,138,.3);
                        @elseif($pr->reponse_id == $rep->id && !$rep->est_correcte) background:rgba(226,75,74,.12);color:#a32d2d;border:1px solid rgba(226,75,74,.3);
                        @else background:#f5f5f5;color:#888;border:1px solid #e8e8e8;
                        @endif">
                  {{ $rep->lettre }}. {{ $rep->texte }}
                  @if($rep->est_correcte) <i class="bi bi-check-circle-fill ms-1"></i> @endif
                  @if($pr->reponse_id == $rep->id && !$rep->est_correcte) <i class="bi bi-x-circle-fill ms-1"></i> @endif
                </span>
              @endforeach
            </div>
          </div>

          {{-- Icône résultat --}}
          <div style="flex-shrink:0;">
            @if($pr->est_correcte)
              <i class="bi bi-check-circle-fill" style="font-size:20px;color:#1cc88a;"></i>
            @else
              <i class="bi bi-x-circle-fill" style="font-size:20px;color:#E24B4A;"></i>
            @endif
          </div>

        </div>
      @endforeach
    </div>
  </div>

  {{-- Boutons d'action --}}
  <div class="d-flex gap-3 justify-content-center flex-wrap">
    <a href="{{ route('tcf.disciplines', $passage->discipline->serie) }}"
       class="btn rounded-pill px-4 fw-semibold"
       style="background:#1B3A6B;color:#fff;">
      <i class="bi bi-arrow-repeat me-1"></i> Recommencer
    </a>
    <a href="{{ route('tcf.index') }}"
       class="btn btn-outline-secondary rounded-pill px-4">
      <i class="bi bi-grid me-1"></i> Toutes les séries
    </a>
  </div>

</div>
@endsection
