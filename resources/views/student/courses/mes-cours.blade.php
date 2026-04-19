{{-- resources/views/student/courses/mes-cours.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Mes cours & Progression')

@push('styles')
<style>
/* ── KPIs ── */
.kpi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;}
@media(max-width:600px){.kpi-grid{grid-template-columns:repeat(3,1fr);gap:8px;}}
.kpi{background:#fff;border-radius:12px;border:1px solid #eee;padding:16px;text-align:center;
     position:relative;overflow:hidden;box-shadow:0 2px 8px rgba(27,58,107,.05);}
.kpi::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--c,#1B3A6B);}
.kpi-n{font-size:1.8rem;font-weight:800;color:var(--c,#1B3A6B);line-height:1;margin-bottom:3px;}
.kpi-l{font-size:10px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.5px;}

/* ── Section tabs ── */
.tab-nav{display:flex;gap:4px;background:#f0f4f8;border-radius:12px;padding:4px;margin-bottom:20px;}
.tab-btn{flex:1;padding:9px;border:none;background:none;border-radius:9px;font-size:13px;
         font-weight:600;color:#666;cursor:pointer;transition:all .2s;}
.tab-btn.active{background:#fff;color:#1B3A6B;box-shadow:0 2px 8px rgba(27,58,107,.1);}

/* ── Carte cours en cours ── */
.course-card{background:#fff;border-radius:14px;border:1px solid #eee;
             padding:16px 18px;margin-bottom:10px;box-shadow:0 2px 8px rgba(27,58,107,.04);
             transition:all .2s;display:flex;align-items:center;gap:14px;}
.course-card:hover{box-shadow:0 4px 16px rgba(27,58,107,.08);}
.course-icon{width:46px;height:46px;border-radius:11px;display:flex;align-items:center;
             justify-content:center;font-size:13px;font-weight:900;color:#fff;flex-shrink:0;}
.course-info{flex:1;min-width:0;}
.course-titre{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:3px;
              white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.course-sub{font-size:11px;color:#888;display:flex;align-items:center;gap:8px;flex-wrap:wrap;}

/* Barre de progression */
.prog-bar{height:6px;border-radius:3px;background:#f0f0f0;overflow:hidden;margin-top:7px;}
.prog-bar-fill{height:100%;border-radius:3px;transition:width .8s;}

/* ── Carte examen (série) ── */
.exam-row{display:flex;align-items:center;gap:12px;padding:12px 14px;
          background:#f8f9fb;border-radius:10px;border:1px solid #eee;margin-bottom:7px;}
.exam-badge{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;
            justify-content:center;font-size:11px;font-weight:900;color:#fff;flex-shrink:0;}
.score-pill{padding:3px 9px;border-radius:10px;font-size:11px;font-weight:700;flex-shrink:0;}
.sg{background:rgba(28,200,138,.1);color:#0f6e56;}
.sm{background:rgba(245,166,35,.12);color:#633806;}
.sb{background:rgba(226,75,74,.08);color:#a32d2d;}
.sn{background:#f0f0f0;color:#888;}

/* ── Cours disponibles ── */
.avail-card{background:#fff;border-radius:12px;border:1.5px dashed #e0e0e0;
            padding:14px 16px;display:flex;align-items:center;gap:12px;
            margin-bottom:8px;text-decoration:none;transition:all .2s;}
.avail-card:hover{border-color:#1B3A6B;background:rgba(27,58,107,.02);}

/* Section header */
.sec-title{font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:12px;
           display:flex;align-items:center;gap:8px;}
.sec-title i{color:#F5A623;}
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Ma progression</h2>
    <p class="text-muted mb-0" style="font-size:13px;">Cours commencés, terminés et séries d'examens</p>
  </div>
  <a href="{{ route('langues.index') }}"
     style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:#F5A623;
            color:#1B3A6B;border-radius:20px;font-size:13px;font-weight:700;text-decoration:none;">
    <i class="bi bi-play-fill"></i>Commencer un examen
  </a>
</div>

{{-- KPIs --}}
<div class="kpi-grid mb-4">
  <div class="kpi" style="--c:#F5A623;">
    <div class="kpi-n">{{ $stats['cours_en_cours'] + $stats['series_en_cours'] }}</div>
    <div class="kpi-l">En cours</div>
  </div>
  <div class="kpi" style="--c:#1cc88a;">
    <div class="kpi-n">{{ $stats['cours_termines'] + $stats['series_terminees'] }}</div>
    <div class="kpi-l">Terminés</div>
  </div>
  <div class="kpi" style="--c:#1B3A6B;">
    <div class="kpi-n">{{ $stats['score_moyen'] }}<span style="font-size:.9rem;">%</span></div>
    <div class="kpi-l">Score moy.</div>
  </div>
</div>

{{-- Onglets --}}
<div class="tab-nav mb-3">
  <button class="tab-btn active" onclick="showTab('en-cours',this)">
    En cours <span style="background:rgba(245,166,35,.15);color:#633806;padding:1px 7px;border-radius:8px;font-size:10px;margin-left:4px;">{{ $stats['cours_en_cours'] + $stats['series_en_cours'] }}</span>
  </button>
  <button class="tab-btn" onclick="showTab('termines',this)">
    Terminés <span style="background:rgba(28,200,138,.1);color:#0f6e56;padding:1px 7px;border-radius:8px;font-size:10px;margin-left:4px;">{{ $stats['cours_termines'] + $stats['series_terminees'] }}</span>
  </button>
  <button class="tab-btn" onclick="showTab('disponibles',this)">
    À découvrir
  </button>
</div>

{{-- ══ ONGLET EN COURS ══ --}}
<div id="tab-en-cours">

  <div class="row g-4">
    <div class="col-lg-6">

      {{-- Cours instructeurs en cours --}}
      @if($coursEnCours->isNotEmpty())
      <div class="sec-title"><i class="bi bi-book-half"></i>Mes cours en cours</div>
      @foreach($coursEnCours as $p)
      @php $course = $p->course; $lang = $course->langue; @endphp
      <div class="course-card">
        <div class="course-icon" style="background:{{ $lang?->couleur ?? '#1B3A6B' }};">
          {{ $lang ? strtoupper($lang->code) : substr($course->titre,0,2) }}
        </div>
        <div class="course-info">
          <div class="course-titre">{{ $course->titre }}</div>
          <div class="course-sub">
            <span style="color:{{ $course->niveauColor() }};font-weight:700;">{{ $course->niveau }}</span>
            <span>{{ $p->lecons_terminees }}/{{ $p->lecons_total }} leçons</span>
            <span>{{ $p->derniere_activite_at?->diffForHumans() ?? 'Jamais' }}</span>
          </div>
          <div class="prog-bar">
            <div class="prog-bar-fill"
                 style="width:{{ $p->progression_pct }}%;background:{{ $lang?->couleur ?? '#1B3A6B' }};"></div>
          </div>
        </div>
        <a href="{{ route('student.course.start', $course) }}"
           style="display:inline-flex;align-items:center;gap:4px;padding:7px 14px;
                  background:#1B3A6B;color:#fff;border-radius:14px;font-size:11px;
                  font-weight:700;text-decoration:none;white-space:nowrap;flex-shrink:0;">
          <i class="bi bi-play-fill"></i>Continuer
        </a>
      </div>
      @endforeach
      @endif

      {{-- Séries d'examens en cours --}}
      @if($passagesEnCours->isNotEmpty())
      <div class="sec-title mt-3"><i class="bi bi-journal-check"></i>Séries d'examens en cours</div>
      @foreach($passagesEnCours as $p)
      @php $lang = $p->langue; @endphp
      <div class="exam-row">
        <div class="exam-badge" style="background:{{ $lang?->couleur ?? '#888' }};">
          {{ $lang ? strtoupper($lang->code) : '?' }}
        </div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:13px;font-weight:600;color:#1B3A6B;">{{ $p->serie?->titre ?? '—' }}</div>
          <div style="font-size:11px;color:#888;">{{ $p->discipline?->nom ?? '' }} · {{ $p->created_at->diffForHumans() }}</div>
        </div>
        <span class="score-pill sn">En cours</span>
      </div>
      @endforeach
      @endif

      @if($coursEnCours->isEmpty() && $passagesEnCours->isEmpty())
      <div style="text-align:center;padding:36px;background:#f8f9fb;border-radius:12px;border:1.5px dashed #ddd;">
        <i class="bi bi-book" style="font-size:30px;color:#ccc;display:block;margin-bottom:8px;"></i>
        <div style="font-size:13px;color:#888;margin-bottom:12px;">Vous n'avez rien en cours.</div>
        <a href="{{ route('langues.index') }}" style="padding:8px 18px;background:#1B3A6B;color:#fff;border-radius:20px;font-size:12px;font-weight:600;text-decoration:none;">Commencer maintenant</a>
      </div>
      @endif

    </div>

    {{-- Progression examens par langue --}}
    <div class="col-lg-6">
      <div class="sec-title"><i class="bi bi-translate"></i>Progression par examen</div>
      @forelse($progressionExamens as $item)
      @php $l = $item['langue']; @endphp
      <div style="background:#fff;border-radius:12px;border:1px solid #eee;padding:14px 16px;margin-bottom:10px;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
          <div style="width:36px;height:36px;border-radius:9px;background:{{ $l->couleur }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#fff;flex-shrink:0;">
            {{ strtoupper($l->code) }}
          </div>
          <div style="flex:1;">
            <div style="font-size:13px;font-weight:700;color:#1B3A6B;">{{ $l->nom }}</div>
            <div style="font-size:11px;color:#888;">{{ $item['termines'] }}/{{ $item['total'] }} séries · Score moy. {{ $item['score_moyen'] }}%</div>
          </div>
          <span style="font-size:13px;font-weight:800;color:{{ $l->couleur }};">{{ $item['progression'] }}%</span>
        </div>
        <div class="prog-bar">
          <div class="prog-bar-fill" style="width:{{ $item['progression'] }}%;background:{{ $l->couleur }};"></div>
        </div>
      </div>
      @empty
      <div style="font-size:13px;color:#aaa;text-align:center;padding:20px;">Aucune série commencée</div>
      @endforelse
    </div>
  </div>
</div>

{{-- ══ ONGLET TERMINÉS ══ --}}
<div id="tab-termines" style="display:none;">
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="sec-title"><i class="bi bi-patch-check-fill"></i>Cours terminés</div>
      @forelse($coursTermines as $p)
      @php $course = $p->course; $lang = $course->langue; @endphp
      <div class="course-card" style="border-color:rgba(28,200,138,.3);">
        <div class="course-icon" style="background:{{ $lang?->couleur ?? '#1cc88a' }};">
          {{ $lang ? strtoupper($lang->code) : substr($course->titre,0,2) }}
        </div>
        <div class="course-info">
          <div class="course-titre">{{ $course->titre }}</div>
          <div class="course-sub">
            <span style="color:{{ $course->niveauColor() }};font-weight:700;">{{ $course->niveau }}</span>
            <span style="color:#1cc88a;font-weight:600;"><i class="bi bi-check-circle-fill"></i> Terminé</span>
            @if($p->fin_at)<span>{{ $p->fin_at->format('d/m/Y') }}</span>@endif
          </div>
          <div class="prog-bar">
            <div class="prog-bar-fill" style="width:100%;background:#1cc88a;"></div>
          </div>
        </div>
        @if($p->score_quiz_moyen)
        <span class="score-pill sg">{{ $p->score_quiz_moyen }}%</span>
        @endif
      </div>
      @empty
      <div style="font-size:13px;color:#aaa;text-align:center;padding:20px;">Aucun cours terminé</div>
      @endforelse
    </div>

    <div class="col-lg-6">
      <div class="sec-title"><i class="bi bi-trophy-fill"></i>Séries d'examens terminées</div>
      @forelse($passagesTermines as $p)
      @php
        $lang = $p->langue;
        $sc = $p->score ?? null;
        $cls = $sc >= 60 ? 'sg' : ($sc >= 40 ? 'sm' : ($sc !== null ? 'sb' : 'sn'));
      @endphp
      <div class="exam-row" style="border-color:rgba(28,200,138,.2);">
        <div class="exam-badge" style="background:{{ $lang?->couleur ?? '#888' }};">
          {{ $lang ? strtoupper($lang->code) : '?' }}
        </div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:13px;font-weight:600;color:#1B3A6B;">{{ $p->serie?->titre ?? '—' }}</div>
          <div style="font-size:11px;color:#888;">{{ $p->discipline?->nom ?? '' }} · {{ $p->created_at->format('d/m/Y') }}</div>
        </div>
        <span class="score-pill {{ $cls }}">{{ $sc !== null ? $sc.'%' : '—' }}</span>
      </div>
      @empty
      <div style="font-size:13px;color:#aaa;text-align:center;padding:20px;">Aucune série terminée</div>
      @endforelse
    </div>
  </div>
</div>

{{-- ══ ONGLET DISPONIBLES ══ --}}
<div id="tab-disponibles" style="display:none;">
  <div class="sec-title"><i class="bi bi-stars"></i>Cours disponibles à découvrir</div>
  @forelse($coursDisponibles as $course)
  @php $lang = $course->langue; @endphp
  <a href="{{ route('student.course.start', $course) }}" class="avail-card d-block">
    <div class="course-icon" style="background:{{ $lang?->couleur ?? '#1B3A6B' }};width:42px;height:42px;border-radius:10px;">
      {{ $lang ? strtoupper($lang->code) : '📚' }}
    </div>
    <div style="flex:1;min-width:0;">
      <div style="font-size:13px;font-weight:700;color:#1B3A6B;">{{ $course->titre }}</div>
      <div style="font-size:11px;color:#888;margin-top:2px;">
        <span style="color:{{ $course->niveauColor() }};font-weight:700;">{{ $course->niveau }}</span>
        · {{ $course->lessons_count }} leçon(s)
        @if($course->duree_estimee_minutes > 0) · {{ $course->duree_estimee_minutes }}min @endif
        · Par {{ $course->instructor?->first_name ?? 'VisaFly' }}
      </div>
    </div>
    <i class="bi bi-arrow-right-circle" style="color:#ccc;font-size:20px;flex-shrink:0;"></i>
  </a>
  @empty
  <div style="text-align:center;padding:40px;background:#f8f9fb;border-radius:12px;border:1.5px dashed #ddd;">
    <i class="bi bi-stars" style="font-size:28px;color:#ccc;display:block;margin-bottom:8px;"></i>
    <div style="font-size:13px;color:#888;">Aucun cours disponible pour le moment.</div>
  </div>
  @endforelse
</div>

<script>
function showTab(name, btn) {
  ['en-cours','termines','disponibles'].forEach(t => {
    document.getElementById('tab-'+t).style.display = t === name ? 'block' : 'none';
  });
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}
</script>

@endsection