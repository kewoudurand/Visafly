{{-- resources/views/student/results/detail.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Détail passage - ' . $passage->langue->nom)

@push('styles')
<style>
.header-passage {
    background: linear-gradient(135deg, {{ $passage->langue->couleur }}, {{ $passage->langue->couleur }}dd);
    border-radius: 14px;
    padding: 28px;
    color: #fff;
    margin-bottom: 28px;
}

.header-passage h1 {
    font-size: 1.8rem;
    font-weight: 800;
    margin: 0 0 8px;
}

.header-passage-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
    margin-top: 18px;
}

.meta-item {
    background: rgba(255,255,255,.15);
    border-radius: 10px;
    padding: 12px;
}

.meta-lbl {
    font-size: 10px;
    text-transform: uppercase;
    font-weight: 700;
    opacity: .85;
    margin-bottom: 4px;
}

.meta-val {
    font-size: 15px;
    font-weight: 700;
    line-height: 1;
}

.score-grande {
    background: #fff;
    border-radius: 14px;
    padding: 32px;
    text-align: center;
    margin-bottom: 28px;
    box-shadow: 0 2px 8px rgba(27,58,107,.04);
}

.score-grande-num {
    font-size: 4rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 8px;
    background: linear-gradient(135deg, {{ $passage->langue->couleur }}, {{ $passage->langue->couleur }}cc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.score-grande-lbl {
    font-size: 13px;
    color: #888;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 16px;
}

.score-grande-niveau {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    background: rgba(27,58,107,.1);
    color: #1B3A6B;
    font-weight: 700;
    font-size: 13px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.info-box {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #eee;
    padding: 18px;
}

.info-box-titre {
    font-size: 12px;
    color: #888;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.info-box-contenu {
    font-size: 16px;
    font-weight: 700;
    color: #1B3A6B;
}

.reponses-section {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #eee;
    padding: 24px;
    margin-bottom: 28px;
}

.reponses-titre {
    font-size: 14px;
    font-weight: 700;
    color: #1B3A6B;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 2px solid {{ $passage->langue->couleur }};
}

.reponse-item {
    padding: 14px;
    margin-bottom: 10px;
    border-radius: 8px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.reponse-item.correcte {
    background: rgba(28,200,138,.08);
    border-left: 4px solid #1cc88a;
}

.reponse-item.incorrecte {
    background: rgba(226,75,74,.08);
    border-left: 4px solid #E24B4A;
}

.reponse-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 14px;
}

.reponse-item.correcte .reponse-icon {
    background: rgba(28,200,138,.2);
    color: #0f6e56;
}

.reponse-item.incorrecte .reponse-icon {
    background: rgba(226,75,74,.15);
    color: #a32d2d;
}

.reponse-text {
    flex: 1;
    font-size: 13px;
    line-height: 1.4;
}

.reponse-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.reponse-contenu {
    color: #333;
}

.stats-reponses {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid rgba(0,0,0,.08);
}

.stat-resp {
    text-align: center;
}

.stat-resp-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1B3A6B;
    line-height: 1;
}

.stat-resp-lbl {
    font-size: 11px;
    color: #888;
    font-weight: 600;
    margin-top: 4px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: #f8f9fb;
    color: #1B3A6B;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all .2s;
}

.btn-back:hover {
    background: #eef0f5;
    color: #F5A623;
}
</style>
@endpush

@section('content')

{{-- Header --}}
<div style="margin-bottom:24px;">
  <a href="{{ route('student.results.show', $passage->langue->id) }}" class="btn-back">
    <i class="bi bi-arrow-left"></i>
    Retour aux résultats
  </a>
</div>

<div class="header-passage">
  <h1>{{ $passage->langue->nom }}</h1>
  
  <div class="header-passage-meta">
    <div class="meta-item">
      <div class="meta-lbl">Discipline</div>
      <div class="meta-val">{{ $passage->discipline->nom }}</div>
    </div>
    <div class="meta-item">
      <div class="meta-lbl">Série</div>
      <div class="meta-val">{{ $passage->serie->titre ?? $passage->serie->nom }}</div>
    </div>
    <div class="meta-item">
      <div class="meta-lbl">Date</div>
      <div class="meta-val">{{ $passage->created_at->format('d/m/Y') }}</div>
    </div>
    <div class="meta-item">
      <div class="meta-lbl">Heure</div>
      <div class="meta-val">{{ $passage->created_at->format('H:i') }}</div>
    </div>
  </div>
</div>

{{-- Score principal --}}
<div class="score-grande">
  <div class="score-grande-num">{{ $passage->score }}%</div>
  <div class="score-grande-lbl">Score obtenu</div>
  <span class="score-grande-niveau">Niveau: <strong>{{ $niveau }}</strong></span>
</div>

{{-- Infos supplémentaires --}}
<div class="info-grid">
  <div class="info-box">
    <div class="info-box-titre">Statut</div>
    <div class="info-box-contenu" style="color: {{ $passage->statut === 'termine' ? '#1cc88a' : '#F5A623' }};">
      {{ ucfirst($passage->statut) }}
    </div>
  </div>
  
  <div class="info-box">
    <div class="info-box-titre">Langue</div>
    <div class="info-box-contenu">
      <span style="display:inline-block;padding:4px 10px;border-radius:6px;
                   background:{{ $passage->langue->couleur }};color:#fff;font-size:12px;">
        {{ $passage->langue->code }}
      </span>
    </div>
  </div>
</div>

{{-- Détails des réponses --}}
@if($passage->reponses && $passage->reponses->count() > 0)
<div class="reponses-section">
  <div class="reponses-titre">
    <i class="bi bi-check-circle me-2"></i>Détail des réponses
  </div>

  <div class="stats-reponses">
    <div class="stat-resp">
      <div class="stat-resp-num" style="color:#1cc88a;">{{ $passage->bonnes_reponses ?? 0 }}</div>
      <div class="stat-resp-lbl">Correctes</div>
    </div>
    <div class="stat-resp">
      <div class="stat-resp-num" style="color:#E24B4A;">{{ $passage->mauvaises_reponses ?? 0 }}</div>
      <div class="stat-resp-lbl">Incorrectes</div>
    </div>
    <div class="stat-resp">
      <div class="stat-resp-num" style="color:#F5A623;">{{ $passage->non_repondues ?? 0 }}</div>
      <div class="stat-resp-lbl">Non répondues</div>
    </div>
    <div class="stat-resp">
      <div class="stat-resp-num" style="color:#1B3A6B;">{{ $passage->total_questions ?? 0 }}</div>
      <div class="stat-resp-lbl">Total</div>
    </div>
  </div>
</div>

{{-- Listing des réponses --}}
<div class="reponses-section">
  <div class="reponses-titre">
    Vos réponses
  </div>

  @forelse($passage->reponses as $i => $reponse)
  <div class="reponse-item {{ $reponse->correcte ? 'correcte' : 'incorrecte' }}">
    <div class="reponse-icon">
      @if($reponse->correcte)
        <i class="bi bi-check-lg"></i>
      @else
        <i class="bi bi-x-lg"></i>
      @endif
    </div>
    <div class="reponse-text">
      <div class="reponse-label">Question {{ $i + 1 }}</div>
      <div class="reponse-contenu">
        <strong>Votre réponse:</strong> {{ $reponse->reponse_donnee ?? 'Non répondue' }}
        @if($reponse->correcte)
        <div style="color:#0f6e56;margin-top:4px;">
          <i class="bi bi-check-circle-fill"></i> Correct !
        </div>
        @else
        <div style="color:#a32d2d;margin-top:4px;">
          <i class="bi bi-x-circle-fill"></i> Incorrect
        </div>
        @endif
      </div>
    </div>
  </div>
  @empty
  <div style="text-align:center;color:#aaa;padding:20px;">
    Aucun détail de réponses disponible
  </div>
  @endforelse
</div>
@endif

{{-- Conseils --}}
<div style="background:#f8f9fb;border-radius:12px;padding:20px;border-left:4px solid {{ $passage->langue->couleur }};margin-bottom:28px;">
  <div style="font-weight:700;color:#1B3A6B;margin-bottom:8px;">
    💡 Conseil pour progresser
  </div>
  <div style="font-size:13px;color:#555;line-height:1.6;">
    @php
      $conseil = match(true) {
        $passage->score >= 80 => "Excellent ! Vous maîtrisez bien cette section. Continuez vos entraînements pour maintenir ce niveau.",
        $passage->score >= 60 => "Bon travail ! Il y a encore de la marge pour progresser. Revoyez les questions où vous avez échoué.",
        $passage->score >= 40 => "Vous avancez bien, mais un travail régulier est nécessaire. Pratiquez quotidiennement et révisez les points faibles.",
        default => "Continuez vos efforts ! Ne découragez pas et pratiquez davantage. Chaque passage est une occasion d'apprendre.",
      };
    @endphp
    {{ $conseil }}
  </div>
</div>

@endsection