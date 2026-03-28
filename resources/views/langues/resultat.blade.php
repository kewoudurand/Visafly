{{-- resources/views/langues/resultat.blade.php --}}
@extends('layouts.app')
@section('title', 'Résultats — '.$serie->titre)

@push('styles')
<style>
.res-hero{text-align:center;padding:48px 20px 32px;}
.score-circle{width:130px;height:130px;border-radius:50%;margin:0 auto 20px;
              display:flex;flex-direction:column;align-items:center;justify-content:center;
              border:6px solid {{ $score >= 60 ? '#1cc88a' : ($score >= 40 ? '#F5A623' : '#E24B4A') }};
              box-shadow:0 0 0 10px {{ $score >= 60 ? 'rgba(28,200,138,.08)' : ($score >= 40 ? 'rgba(245,166,35,.08)' : 'rgba(226,75,74,.08)') }};}
.score-num{font-size:2.4rem;font-weight:800;
           color:{{ $score >= 60 ? '#0f6e56' : ($score >= 40 ? '#633806' : '#a32d2d') }};}
.score-pct{font-size:12px;color:#888;}
.stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;
           max-width:600px;margin:0 auto 32px;}
.stat-box{background:#fff;border-radius:12px;border:1px solid #eee;padding:18px;
          text-align:center;box-shadow:0 2px 8px rgba(27,58,107,.05);}
.stat-box-num{font-size:1.6rem;font-weight:800;color:#1B3A6B;}
.stat-box-lbl{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-top:4px;}

.q-recap{max-width:700px;margin:0 auto;padding:0 20px 60px;}
.q-recap-item{background:#fff;border-radius:12px;border:1px solid #eee;
              padding:16px;margin-bottom:10px;}
.q-recap-header{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.q-badge{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;
         justify-content:center;font-size:11px;font-weight:800;flex-shrink:0;}
.correct{background:rgba(28,200,138,.15);color:#0f6e56;}
.wrong  {background:rgba(226,75,74,.12); color:#a32d2d;}
.q-text {font-size:13px;font-weight:600;color:#1B3A6B;flex:1;}
.rep-list{display:flex;flex-direction:column;gap:5px;}
.rep-result{display:flex;align-items:center;gap:8px;padding:8px 12px;
            border-radius:8px;font-size:13px;}
.rep-result.correct-ans{background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;}
.rep-result.wrong-ans  {background:rgba(226,75,74,.06);border:1px solid rgba(226,75,74,.2);color:#a32d2d;}
.rep-result.neutral    {background:#f8f9fb;border:1px solid #eee;color:#555;}
</style>
@endpush

@section('content')

<div class="res-hero">
  <div style="font-size:12px;color:#888;margin-bottom:20px;text-transform:uppercase;letter-spacing:.6px;">
    {{ $langue->nom }} · {{ $serie->titre }} · {{ $discipline->nom }}
  </div>

  {{-- Score --}}
  <div class="score-circle">
    <div class="score-num">{{ $score }}<span style="font-size:1rem;">%</span></div>
    <div class="score-pct">Score</div>
  </div>

  <h2 style="font-size:1.5rem;font-weight:800;color:#1B3A6B;margin-bottom:8px;">
    @if($score >= 60)
      Excellent travail ! 🎉
    @elseif($score >= 40)
      Bon effort, continuez ! 💪
    @else
      Continuez à pratiquer ! 📚
    @endif
  </h2>
  <p style="font-size:14px;color:#666;margin-bottom:32px;">
    Vous avez obtenu {{ $bonnes }} bonne(s) réponse(s) sur {{ $totalQuestions }}
    en {{ $tempsPasse }} minutes.
  </p>

  {{-- Stats --}}
  <div class="stat-grid">
    <div class="stat-box">
      <div class="stat-box-num" style="color:#1cc88a;">{{ $bonnes }}</div>
      <div class="stat-box-lbl">Correctes</div>
    </div>
    <div class="stat-box">
      <div class="stat-box-num" style="color:#E24B4A;">{{ $mauvaises }}</div>
      <div class="stat-box-lbl">Incorrectes</div>
    </div>
    <div class="stat-box">
      <div class="stat-box-num" style="color:#888;">{{ $nonRepondues }}</div>
      <div class="stat-box-lbl">Sans réponse</div>
    </div>
  </div>

  {{-- Actions --}}
  <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-bottom:40px;">
    <a href="{{ route('langues.disciplines', [$langue->code, $serie->id]) }}"
       style="padding:11px 24px;background:#1B3A6B;color:#fff;border-radius:25px;
              font-size:13px;font-weight:600;text-decoration:none;">
      <i class="bi bi-arrow-repeat me-2"></i>Recommencer
    </a>
    <a href="{{ route('langues.series', $langue->code) }}"
       style="padding:11px 24px;border:1.5px solid #1B3A6B;color:#1B3A6B;border-radius:25px;
              font-size:13px;font-weight:600;text-decoration:none;">
      <i class="bi bi-grid me-2"></i>Autres séries
    </a>
    <a href="{{ route('dashboard') }}"
       style="padding:11px 24px;border:1.5px solid #ddd;color:#666;border-radius:25px;
              font-size:13px;font-weight:600;text-decoration:none;">
      <i class="bi bi-house me-2"></i>Tableau de bord
    </a>
  </div>
</div>

{{-- Récapitulatif des réponses --}}
<div class="q-recap">
  <h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:16px;
             text-transform:uppercase;letter-spacing:.6px;">
    <i class="bi bi-list-check me-2" style="color:#F5A623;"></i>Correction détaillée
  </h3>

  @foreach($corrections as $idx => $item)
  <div class="q-recap-item">
    <div class="q-recap-header">
      <div class="q-badge {{ $item['correct'] ? 'correct' : 'wrong' }}">
        @if($item['correct'])<i class="bi bi-check-lg"></i>
        @else<i class="bi bi-x-lg"></i>@endif
      </div>
      <div class="q-text">{{ $idx + 1 }}. {{ $item['question']->enonce }}</div>
      <div style="font-size:11px;color:#888;">{{ $item['question']->points }} pt(s)</div>
    </div>

    {{-- Image si présente --}}
    @if($item['question']->image)
    <img src="{{ asset('storage/'.$item['question']->image) }}"
         style="max-height:80px;border-radius:8px;margin-bottom:8px;border:1px solid #eee;">
    @endif

    <div class="rep-list">
      @foreach($item['reponses'] as $rep)
      <div class="rep-result
        {{ $rep->correcte ? 'correct-ans' : ($rep->id == $item['repondue'] && !$rep->correcte ? 'wrong-ans' : 'neutral') }}">
        @if($rep->correcte)<i class="bi bi-check-circle-fill"></i>
        @elseif($rep->id == $item['repondue'])<i class="bi bi-x-circle-fill"></i>
        @else<i class="bi bi-circle" style="opacity:.3;"></i>@endif
        {{ $rep->texte }}
      </div>
      @endforeach
    </div>

    @if($item['question']->explication && !$item['correct'])
    <div style="margin-top:10px;font-size:12px;color:#888;font-style:italic;
                background:#fffbf0;border-radius:8px;padding:8px 12px;
                border-left:3px solid #F5A623;">
      <i class="bi bi-lightbulb me-1" style="color:#F5A623;"></i>
      {{ $item['question']->explication }}
    </div>
    @endif
  </div>
  @endforeach
</div>

@endsection