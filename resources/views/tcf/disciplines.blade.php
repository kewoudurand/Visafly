{{-- resources/views/tcf/disciplines.blade.php --}}
@extends('layouts.app')

@section('title', 'Série '.$serie->nom.' — Choisir une discipline')

@section('content')
<div class="container py-4">

  {{-- Retour --}}
  <div class="mb-3">
    <a href="{{ route('tcf.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
      <i class="bi bi-arrow-left me-1"></i> Retour aux séries
    </a>
  </div>

  {{-- En-tête --}}
  <div class="text-center mb-4">
    <p class="text-muted mb-1">Vous êtes sur le point de commencer la série</p>
    <h2 class="fw-bold" style="color:#1B3A6B;">
      {{ $serie->nom }} — <span style="color:#F5A623;">{{ $serie->type }}</span> nouveau format
    </h2>
    <p class="text-muted">Choisissez une discipline pour débuter le test, bon apprentissage !</p>
  </div>

  {{-- Cartes disciplines --}}
  <div class="row g-4 justify-content-center">
    @foreach($disciplines as $discipline)
    <div class="col-lg-3 col-md-6">

      {{-- Carte cliquable --}}
      <div class="card h-100 border rounded-3 text-center p-3 discipline-card"
           style="cursor:pointer;transition:all .2s;border:1px solid #e0e0e0 !important;"
           onclick="confirmerDiscipline('{{ $serie->code }}','{{ $discipline->code }}','{{ $discipline->nom }}','{{ $discipline->duree_minutes }}','{{ $discipline->nb_questions }}','{{ $discipline->type_questions }}')">

        {{-- Icône --}}
        <div class="d-flex justify-content-center mb-3">
          <div style="width:64px;height:64px;background:rgba(27,58,107,.08);border-radius:16px;display:flex;align-items:center;justify-content:center;">
            @switch($discipline->code)
              @case('comprehension_ecrite')
                <i class="bi bi-book" style="font-size:28px;color:#1B3A6B;"></i>
                @break
              @case('comprehension_orale')
                <i class="bi bi-volume-up" style="font-size:28px;color:#1B3A6B;"></i>
                @break
              @case('expression_ecrite')
                <i class="bi bi-pencil-square" style="font-size:28px;color:#1B3A6B;"></i>
                @break
              @case('expression_orale')
                <i class="bi bi-mic" style="font-size:28px;color:#1B3A6B;"></i>
                @break
              @default
                <i class="bi bi-journal-text" style="font-size:28px;color:#1B3A6B;"></i>
            @endswitch
          </div>
        </div>

        <h5 class="fw-semibold mb-3" style="color:#1B3A6B;font-size:14px;">{{ $discipline->nom }}</h5>

        <div class="d-flex flex-column gap-1 align-items-start text-start">
          <small class="text-muted d-flex align-items-center gap-1">
            <i class="bi bi-clock"></i> {{ $discipline->duree_minutes }} minutes
          </small>
          <small class="text-muted d-flex align-items-center gap-1">
            <i class="bi bi-question-circle"></i>
            {{ $discipline->nb_questions }}
            {{ $discipline->type_questions === 'qcm' ? 'questions' : 'tâches' }}
          </small>
        </div>

      </div>
    </div>
    @endforeach
  </div>

</div>

{{-- ── Modal confirmation ── --}}
<div class="modal fade" id="modalConfirm" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 border-0 shadow">

      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-semibold" style="color:#1B3A6B;">Début du test</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-2">
        <p class="text-muted" id="modal-desc" style="font-size:14px;"></p>

        <div class="rounded-3 p-3 mb-3"
             style="background:rgba(245,166,35,.1);border-left:3px solid #F5A623;">
          <small style="color:#633806;">
            Prenez la peine de bien lire les questions et les consignes avant de répondre.
          </small>
        </div>

        <p class="text-center fw-semibold" style="color:#1B3A6B;">
          Êtes-vous prêt à commencer ?
        </p>
      </div>

      <div class="modal-footer border-0 pt-0 gap-2">
        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                data-bs-dismiss="modal">Annuler</button>
        <form id="formDemarrer" method="POST">
          @csrf
          <button type="submit" class="btn rounded-pill px-4 fw-semibold"
                  style="background:#1B3A6B;color:#fff;min-width:140px;">
            Commencer
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

@push('styles')
<style>
  .discipline-card:hover {
    border-color: #1B3A6B !important;
    background: rgba(27,58,107,.03);
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(27,58,107,.12);
  }
  .discipline-card:hover div[style*="rgba(27,58,107,.08)"] {
    background: linear-gradient(135deg, #F5A623, #e09610) !important;
  }
  .discipline-card:hover i { color: #fff !important; }
</style>
@endpush

@push('scripts')
<script>
function confirmerDiscipline(
    serieCode,
    disciplineCode,
    nom,
    duree,
    nbQ,
    type
) {

    const taches = type === 'qcm'
        ? nbQ + ' questions'
        : nbQ + ' tâches';

    document.getElementById('modal-desc').innerHTML =
        `Vous êtes sur le point de débuter un test de <strong>${nom}</strong> type examen TCF Canada.<br>
        Il comporte <strong>${taches}</strong> et dure <strong>${duree} minutes</strong> exactement.`;

    const url = "{{ route('tcf.demarrer', ['serie' => '__SERIE__', 'discipline' => '__DISC__']) }}";

    document.getElementById('formDemarrer').action =
        url.replace('__SERIE__', serieCode)
          .replace('__DISC__', disciplineCode);

    const modal = new bootstrap.Modal(
        document.getElementById('modalConfirm')
    );

    modal.show();
}
</script>
@endpush

@endsection
