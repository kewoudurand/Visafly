{{-- resources/views/student/results/show.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Résultats - ' . $langue->nom)

@push('styles')
<style>
.header-examen {
    background: linear-gradient(135deg, {{ $langue->couleur }}, {{ $langue->couleur }}dd);
    border-radius: 14px;
    padding: 28px;
    color: #fff;
    margin-bottom: 28px;
    box-shadow: 0 4px 16px rgba(27,58,107,.12);
}

.header-examen h1 {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 8px;
}

.header-examen p {
    font-size: 14px;
    opacity: .9;
    margin: 0;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
    margin-top: 20px;
}

.stat-box {
    background: rgba(255,255,255,.15);
    border-radius: 10px;
    padding: 14px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.stat-box-num {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-box-lbl {
    font-size: 11px;
    font-weight: 600;
    opacity: .85;
    text-transform: uppercase;
}

.discipline-card {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #eee;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(27,58,107,.04);
}

.discipline-header {
    background: linear-gradient(135deg, {{ $langue->couleur }}15, {{ $langue->couleur }}08);
    border-bottom: 1.5px solid #eee;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.discipline-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: {{ $langue->couleur }};
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.discipline-title {
    font-size: 15px;
    font-weight: 700;
    color: #1B3A6B;
}

.discipline-body {
    padding: 20px;
}

.passage-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid #f5f5f5;
}

.passage-item:last-child {
    border-bottom: none;
}

.passage-info {
    flex: 1;
}

.passage-serie {
    font-size: 13px;
    font-weight: 600;
    color: #333;
    margin-bottom: 3px;
}

.passage-date {
    font-size: 11px;
    color: #888;
}

.passage-score {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.score-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
}

.score-h {
    background: rgba(27,58,107,.1);
    color: #1B3A6B;
}
.score-m {
    background: rgba(28,200,138,.1);
    color: #0f6e56;
}
.score-l {
    background: rgba(245,166,35,.1);
    color: #633806;
}
.score-vl {
    background: rgba(226,75,74,.08);
    color: #a32d2d;
}

.niveau-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    background: rgba(27,58,107,.1);
    color: #1B3A6B;
}

.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: #fff;
    color: #1B3A6B;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
}

.btn-detail:hover {
    border-color: #F5A623;
    color: #F5A623;
}

.progression-box {
    background: #f8f9fb;
    border-radius: 12px;
    padding: 16px;
    margin-top: 20px;
    border-left: 4px solid {{ $langue->couleur }};
}

.progression-label {
    font-size: 11px;
    font-weight: 700;
    color: #888;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.progression-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.progression-arrow {
    font-size: 18px;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')

{{-- Header avec examen --}}
<div class="header-examen">
  <a href="{{ route('student.results.index') }}" 
     style="display:inline-flex;align-items:center;gap:6px;color:#fff;
            text-decoration:none;font-size:12px;margin-bottom:12px;opacity:.8;">
    <i class="bi bi-arrow-left"></i> Retour
  </a>
  
  <h1>{{ $langue->nom }}</h1>
  <p>Vérifiez vos scores par discipline</p>

  {{-- Stats header --}}
  <div class="stats-row">
    <div class="stat-box">
      <div class="stat-box-num">{{ $stats['total_passages'] }}</div>
      <div class="stat-box-lbl">Passages</div>
    </div>
    <div class="stat-box">
      <div class="stat-box-num">{{ $stats['score_moyen'] }}%</div>
      <div class="stat-box-lbl">Score moyen</div>
    </div>
    <div class="stat-box">
      <div class="stat-box-num">{{ $stats['meilleur_score'] }}%</div>
      <div class="stat-box-lbl">Meilleur score</div>
    </div>
    <div class="stat-box">
      <div class="stat-box-num">{{ $stats['pire_score'] }}%</div>
      <div class="stat-box-lbl">Plus bas score</div>
    </div>
  </div>

  {{-- Progression --}}
  @if($stats['progression'])
  <div class="progression-box" style="margin-top:16px;background:rgba(255,255,255,.15);border-left-color:rgba(255,255,255,.4);">
    <div class="progression-label" style="color:rgba(255,255,255,.7);">Votre progression</div>
    <div class="progression-content" style="color:#fff;">
      <div style="text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;">{{ $stats['progression']['score_initial'] }}%</div>
        <div style="font-size:10px;opacity:.8;">Initial</div>
      </div>
      <div class="progression-arrow">
        @if($stats['progression']['positif'])
          <i class="bi bi-arrow-up-right" style="color:#1cc88a;"></i>
        @else
          <i class="bi bi-arrow-down-right" style="color:#E24B4A;"></i>
        @endif
      </div>
      <div style="text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;">{{ $stats['progression']['score_final'] }}%</div>
        <div style="font-size:10px;opacity:.8;">Actuel</div>
      </div>
      <div style="margin-left:auto;text-align:right;">
        <div style="font-size:1.4rem;font-weight:800;">{{ ($stats['progression']['positif'] ? '+' : '') . $stats['progression']['pourcentage'] }}%</div>
        <div style="font-size:10px;opacity:.8;">Différence</div>
      </div>
    </div>
  </div>
  @endif
</div>

{{-- Résultats par discipline --}}
<div>
  @forelse($resultats as $disciplineId => $data)
  <div class="discipline-card">
    <div class="discipline-header">
      <div class="discipline-icon">
        @php
          $icons = [
            'expression' => 'bi-chat-left-dots',
            'comprehension' => 'bi-ear',
            'ecrite' => 'bi-pencil',
            'orale' => 'bi-mic',
          ];
          $code = strtolower($data['discipline']->code ?? '');
          $icon = collect($icons)->first(fn($v, $k) => str_contains($code, $k), 'bi-book');
        @endphp
        <i class="bi {{ $icon }}"></i>
      </div>
      <div class="discipline-title">{{ $data['discipline']->nom }}</div>
    </div>

    <div class="discipline-body">
      @forelse($data['passages'] as $passage)
      <div class="passage-item">
        <div class="passage-info">
          <div class="passage-serie">{{ $passage['serie']->titre ?? $passage['serie']->nom }}</div>
          <div class="passage-date">{{ $passage['date']->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="passage-score">
          @php
            $score = $passage['score'];
            $scoreClass = $score >= 80 ? 'score-h' : ($score >= 60 ? 'score-m' : ($score >= 40 ? 'score-l' : 'score-vl'));
          @endphp
          <span class="score-badge {{ $scoreClass }}">{{ $score }}%</span>
          <a href="{{ route('student.results.detail', ['passage' => 'PLACEHOLDER']) }}" 
             class="btn-detail"
             onclick="alert('Détails du passage'); return false;">
            <i class="bi bi-eye"></i>
            Voir
          </a>
        </div>
      </div>
      @empty
      <div style="text-align:center;color:#aaa;padding:20px;">
        Aucun passage pour cette discipline
      </div>
      @endforelse
    </div>
  </div>
  @empty
  <div style="text-align:center;padding:40px;background:#f8f9fb;border-radius:14px;color:#aaa;">
    <i class="bi bi-journal" style="font-size:32px;display:block;margin-bottom:12px;opacity:.3;"></i>
    Aucun résultat disponible
  </div>
  @endforelse
</div>

@endsection