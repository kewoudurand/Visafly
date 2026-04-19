{{-- resources/views/instructor/courses/show.blade.php --}}
@extends('layouts.dashboard')
@section('title', $course->titre)

@push('styles')
<style>
.lesson-row{display:flex;align-items:center;gap:12px;padding:14px 16px;background:#f8f9fb;
            border-radius:10px;border:1px solid #eee;margin-bottom:8px;transition:all .2s;}
.lesson-row:hover{background:#fff;border-color:#ddd;box-shadow:0 2px 8px rgba(27,58,107,.06);}
.lesson-num{width:34px;height:34px;border-radius:9px;background:rgba(27,58,107,.08);
            display:flex;align-items:center;justify-content:center;font-size:12px;
            font-weight:700;color:#1B3A6B;flex-shrink:0;}
.lesson-info{flex:1;min-width:0;}
.lesson-titre{font-size:13px;font-weight:600;color:#1B3A6B;}
.lesson-meta{display:flex;gap:8px;flex-wrap:wrap;margin-top:3px;}
.lmeta{font-size:11px;color:#888;display:flex;align-items:center;gap:3px;}
.type-badge{padding:2px 8px;border-radius:7px;font-size:10px;font-weight:700;}
.type-texte{background:rgba(27,58,107,.08);color:#1B3A6B;}
.type-audio{background:rgba(245,166,35,.12);color:#633806;}
.type-video{background:rgba(226,75,74,.08);color:#a32d2d;}
.type-mixte{background:rgba(127,119,221,.12);color:#3C3489;}
.act-btn{width:30px;height:30px;border-radius:8px;border:1px solid #e8e8e8;background:#fff;
         display:flex;align-items:center;justify-content:center;font-size:12px;color:#666;
         text-decoration:none;cursor:pointer;transition:all .15s;}
.act-btn:hover{border-color:#1B3A6B;color:#1B3A6B;}
.act-btn.red:hover{border-color:#E24B4A;color:#E24B4A;}
.stat-mini{background:#f8f9fb;border-radius:10px;padding:14px;text-align:center;}
.stat-mini-n{font-size:1.6rem;font-weight:800;color:#1B3A6B;line-height:1;}
.stat-mini-l{font-size:11px;color:#888;margin-top:3px;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
  <a href="{{ route('instructor.dashboard') }}"
     style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;display:flex;align-items:center;justify-content:center;color:#1B3A6B;text-decoration:none;">
    <i class="bi bi-arrow-left"></i>
  </a>
  <div>
    <div style="font-size:11px;color:#888;">
      <span style="color:{{ $course->getCouleurAttribute() }};font-weight:700;">{{ $course->niveau }}</span>
      @if($course->langue) · {{ $course->langue->nom }} @endif
    </div>
    <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.2rem;">{{ $course->titre }}</h2>
  </div>
  <div class="d-flex gap-2 ms-auto">
    <a href="{{ route('instructor.courses.edit', $course) }}"
       style="display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border:1.5px solid #1B3A6B;color:#1B3A6B;border-radius:20px;font-size:12px;font-weight:600;text-decoration:none;">
      <i class="bi bi-pencil"></i>Modifier
    </a>
    <a href="{{ route('instructor.lessons.create', $course) }}"
       style="display:inline-flex;align-items:center;gap:5px;padding:8px 18px;background:#1B3A6B;color:#fff;border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;">
      <i class="bi bi-plus-circle"></i>Ajouter une leçon
    </a>
  </div>
</div>

@if(session('success'))
<div class="alert rounded-3 mb-3" style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;font-size:13px;">
  <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

<div class="row g-4">
  <div class="col-lg-8">

    {{-- Statut & description --}}
    <div style="background:#fff;border-radius:14px;border:1px solid #eee;padding:18px;margin-bottom:16px;">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span style="font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;
                     background:{{ $course->publie ? 'rgba(28,200,138,.1)' : '#f0f0f0' }};
                     color:{{ $course->publie ? '#0f6e56' : '#888' }};">
          <i class="bi bi-{{ $course->publie ? 'eye' : 'eye-slash' }} me-1"></i>
          {{ $course->publie ? 'Publié' : 'Brouillon' }}
        </span>
        <span style="font-size:11px;color:#888;">
          Niveau : <strong style="color:{{ $course->getCouleurAttribute() }};">{{ $course->niveauLabel() }}</strong>
        </span>
      </div>
      @if($course->description)
      <p style="font-size:13px;color:#555;line-height:1.6;margin:0;">{{ $course->description }}</p>
      @endif
    </div>

    {{-- Liste des leçons --}}
    <div style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
      <i class="bi bi-collection" style="color:#F5A623;"></i>
      Leçons ({{ $course->lessons->count() }})
    </div>

    @forelse($course->lessons as $lesson)
    <div class="lesson-row">
      <div class="lesson-num">{{ $loop->iteration }}</div>
      <div class="lesson-info">
        <div class="lesson-titre">{{ $lesson->titre }}</div>
        <div class="lesson-meta">
          <span class="type-badge type-{{ $lesson->type }}">
            <i class="bi {{ $lesson->typeIcon() }}"></i> {{ $lesson->typeLabel() }}
          </span>
          @if($lesson->fichier_media)
          <span class="lmeta"><i class="bi bi-headphones"></i>{{ $lesson->dureeFormatee() }}</span>
          @endif
          @if($lesson->has_quiz)
          <span class="lmeta" style="color:#7F77DD;"><i class="bi bi-question-circle"></i>Quiz</span>
          @endif
          <span class="lmeta">
            <i class="bi bi-{{ $lesson->publiee ? 'eye' : 'eye-slash' }}"></i>
            {{ $lesson->publiee ? 'Visible' : 'Cachée' }}
          </span>
        </div>
      </div>
      <div style="display:flex;gap:4px;flex-shrink:0;">
       {{-- Passage des deux paramètres : le cours d'abord, la leçon ensuite --}}
      <a href="{{ route('instructor.lessons.edit', [$lesson->course_id, $lesson->id]) }}" 
        class="act-btn" 
        title="Modifier">
        <i class="bi bi-pencil"></i>
      </a>
        <form method="POST" action="{{ route('instructor.lessons.destroy', [$lesson->course_id, $lesson->id]) }}"
              onsubmit="return confirm('Supprimer « {{ $lesson->titre }} » ?')">
          @csrf @method('DELETE')
          <button type="submit" class="act-btn red" title="Supprimer">
            <i class="bi bi-trash"></i>
          </button>
        </form>
      </div>
    </div>
    @empty
    <div style="text-align:center;padding:40px;background:#f8f9fb;border-radius:12px;border:1.5px dashed #ddd;">
      <i class="bi bi-collection" style="font-size:30px;color:#ccc;display:block;margin-bottom:10px;"></i>
      <div style="font-size:13px;color:#888;margin-bottom:14px;">Aucune leçon pour ce cours.</div>
      <a href="{{ route('instructor.lessons.create', $course) }}"
         style="padding:9px 20px;background:#1B3A6B;color:#fff;border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;">
        <i class="bi bi-plus-circle me-2"></i>Ajouter la première leçon
      </a>
    </div>
    @endforelse

  </div>

  {{-- Stats --}}
  <div class="col-lg-4">
    <div style="background:#fff;border-radius:14px;border:1px solid #eee;padding:18px;">
      <div style="font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:14px;"><i class="bi bi-bar-chart me-2" style="color:#F5A623;"></i>Statistiques</div>
      <div class="row g-2">
        <div class="stat-mini-n" style="color:#F5A623;">
    {{ $stats['score_moy'] ?? 0 }}%
</div>
        <div class="col-6"><div class="stat-mini"><div class="stat-mini-n">{{ $stats['inscrits'] }}</div><div class="stat-mini-l">Inscrits</div></div></div>
        <div class="col-6"><div class="stat-mini"><div class="stat-mini-n" style="color:#1cc88a;">{{ $stats['termines'] }}</div><div class="stat-mini-l">Terminés</div></div></div>
        <div class="col-6"><div class="stat-mini"><div class="stat-mini-n" style="color:#F5A623;">{{ $stats['score_moy'] }}%</div><div class="stat-mini-l">Score moy.</div></div></div>
        <div class="col-6"><div class="stat-mini"><div class="stat-mini-n">{{ $course->duree_estimee_minutes }}min</div><div class="stat-mini-l">Durée est.</div></div></div>
      </div>
    </div>
  </div>
</div>

@endsection