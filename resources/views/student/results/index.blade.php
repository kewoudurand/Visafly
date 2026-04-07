{{-- resources/views/student/results/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Mes résultats')

@push('styles')
<style>
.result-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #eee;
    padding: 22px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(27,58,107,.04);
    transition: all .3s;
}
.result-card:hover {
    border-color: #F5A623;
    box-shadow: 0 4px 16px rgba(245,166,35,.12);
}

.exam-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 12px;
    color: #fff;
    font-weight: 800;
    font-size: 12px;
    flex-shrink: 0;
}

.stat-item {
    text-align: center;
}
.stat-num {
    font-size: 1.6rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 4px;
}
.stat-lbl {
    font-size: 11px;
    color: #888;
    font-weight: 600;
    text-transform: uppercase;
}

.btn-voir-details {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: #1B3A6B;
    color: #fff;
    border-radius: 20px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all .2s;
}
.btn-voir-details:hover {
    background: #152d54;
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 60px 40px;
    background: #f8f9fb;
    border-radius: 14px;
    border: 2px dashed #ddd;
}
.empty-state i {
    font-size: 48px;
    color: #ccc;
    display: block;
    margin-bottom: 16px;
}
.empty-state p {
    font-size: 14px;
    color: #888;
    margin: 0;
}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Mes résultats</h2>
    <p class="text-muted mb-0" style="font-size:13px;">Consultez vos scores et votre progression</p>
  </div>
</div>

{{-- Message de succès --}}
@if(session('success'))
<div class="alert rounded-3 mb-4" style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
  <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

{{-- Message d'erreur --}}
@if(session('error'))
<div class="alert rounded-3 mb-4" style="background:rgba(226,75,74,.08);border:1px solid rgba(226,75,74,.3);color:#a32d2d;">
  <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
</div>
@endif

@if($statsParExamen)

{{-- État vide --}}
<div class="empty-state">
  <i class="bi bi-journal"></i>
  <p>Vous n'avez encore passé aucun examen.</p>
  <a href="{{ route('langues.index') }}" 
     style="display:inline-block;margin-top:16px;color:#1B3A6B;font-weight:600;text-decoration:none;">
    Passer un examen →
  </a>
</div>

@else

{{-- Liste des examens passés --}}
<div class="row g-3">
  @foreach($statsParExamen as $examen)
  <div class="col-lg-6">
    <div class="result-card">
      <div style="display:flex;align-items:flex-start;gap:16px;">
        
        {{-- Badge examen --}}
        <div class="exam-badge" style="background:{{ $examen['langue']->couleur }};">
          {{ strtoupper(substr($examen['langue']->code, 0, 3)) }}
        </div>

        {{-- Infos --}}
        <div style="flex:1;min-width:0;">
          <div style="font-size:18px;font-weight:700;color:#1B3A6B;margin-bottom:4px;">
            {{ $examen['langue']->nom }}
          </div>
          <div style="font-size:12px;color:#888;margin-bottom:12px;">
            Dernier passage: {{ $examen['dernier_passage']->created_at->format('d/m/Y à H:i') }}
          </div>

          {{-- Stats --}}
          <div class="row g-3" style="margin-bottom:12px;">
            <div class="col-6">
              <div class="stat-item">
                <div class="stat-num" style="color:{{ $examen['dernier_passage']->score >= 60 ? '#1cc88a' : '#F5A623' }};">
                  {{ $examen['dernier_passage']->score }}%
                </div>
                <div class="stat-lbl">Dernier score</div>
              </div>
            </div>
            <div class="col-6">
              <div class="stat-item">
                <div class="stat-num" style="color:#1B3A6B;">
                  {{ $examen['score_moyen'] }}%
                </div>
                <div class="stat-lbl">Score moyen</div>
              </div>
            </div>
          </div>

          {{-- Plus de stats --}}
          <div style="font-size:11px;color:#888;display:flex;gap:16px;margin-bottom:14px;">
            <div>
              <strong style="color:#333;">{{ $examen['nb_passages'] }}</strong> passage(s)
            </div>
            <div>
              Meilleur: <strong style="color:#1cc88a;">{{ $examen['meilleur_score'] }}%</strong>
            </div>
          </div>

          {{-- Bouton --}}
          <a href="{{ route('student.results.show', $examen['langue']->id) }}" 
             class="btn-voir-details">
            <i class="bi bi-arrow-right"></i>
            Voir détails
          </a>
        </div>

      </div>
    </div>
  </div>
  @endforeach
</div>

@endif

@endsection