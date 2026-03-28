{{-- resources/views/langues/epreuve.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $langue->nom }} — {{ $serie->titre }} — {{ $discipline->nom }}</title>
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <style>
  *{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:system-ui,sans-serif;background:#f4f6f9;height:100vh;
       display:flex;flex-direction:column;overflow:hidden;}

  /* ── TOPBAR ── */
  .topbar{display:flex;align-items:center;gap:16px;padding:0 20px;
          height:56px;background:#fff;border-bottom:1px solid #e8e8e8;flex-shrink:0;}
  .timer-val{font-size:15px;font-weight:700;color:#1B3A6B;min-width:48px;}
  .progress-track{flex:1;height:10px;background:#e8e8e8;border-radius:5px;overflow:hidden;}
  .progress-fill{height:100%;border-radius:5px;background:#1cc88a;transition:width 1s linear;}
  .progress-fill.warn{background:#F5A623;}
  .progress-fill.danger{background:#E24B4A;}
  .topbar-title{font-size:13px;font-weight:600;color:#555;flex-shrink:0;}
  .btn-fin{padding:8px 20px;background:#E24B4A;color:#fff;border:none;
           border-radius:20px;font-size:13px;font-weight:700;cursor:pointer;}
  .btn-fin:hover{background:#c93a39;}

  /* ── BODY ── */
  .epreuve-body{display:flex;flex:1;overflow:hidden;}

  /* ── MAIN ── */
  .epreuve-main{flex:1;overflow-y:auto;padding:20px;}

  /* Image */
  .q-image-wrap{background:#fff;border:1px solid #e8e8e8;border-radius:12px;
                overflow:hidden;margin-bottom:16px;text-align:center;
                max-height:320px;display:flex;align-items:center;justify-content:center;}
  .q-image-wrap img{max-width:100%;max-height:320px;object-fit:contain;}

  /* Audio */
  .q-audio-wrap{background:#fff;border:1px solid #e8e8e8;border-radius:12px;
                padding:16px;margin-bottom:16px;display:flex;align-items:center;gap:14px;}
  .q-num-badge{width:40px;height:40px;border-radius:8px;background:{{ $langue->couleur }};
               display:flex;align-items:center;justify-content:center;
               font-size:15px;font-weight:800;color:#fff;flex-shrink:0;}
  .q-audio-wrap audio{flex:1;height:34px;}

  /* Énoncé */
  .q-enonce{background:#fff;border-radius:12px;padding:18px;margin-bottom:14px;
            font-size:14px;color:#1B3A6B;font-weight:600;line-height:1.6;
            border:1px solid #e8e8e8;}

  /* Contexte */
  .q-contexte{background:#fffbf0;border-left:4px solid #F5A623;border-radius:0 10px 10px 0;
              padding:12px 16px;margin-bottom:14px;font-size:13px;color:#555;line-height:1.6;}

  /* Réponses */
  .reponses-list{display:flex;flex-direction:column;gap:8px;}
  .rep-option{display:flex;align-items:center;gap:12px;padding:14px 16px;
              background:#fff;border:1.5px solid #e8e8e8;border-radius:10px;
              cursor:pointer;transition:all .2s;user-select:none;}
  .rep-option:hover{border-color:{{ $langue->couleur }};
                    background:rgba(27,58,107,.03);}
  .rep-option.selected{border-color:{{ $langue->couleur }};
                       background:rgba(27,58,107,.06);}
  .rep-option input{display:none;}
  .rep-letter{width:30px;height:30px;border-radius:50%;background:#f0f0f0;
              display:flex;align-items:center;justify-content:center;
              font-size:13px;font-weight:700;color:#666;flex-shrink:0;transition:all .2s;}
  .rep-option.selected .rep-letter{background:{{ $langue->couleur }};color:#fff;}
  .rep-text{font-size:14px;color:#333;font-weight:500;}

  /* Navigation questions */
  .nav-sidebar{width:130px;background:#fff;border-left:1px solid #e8e8e8;
               overflow-y:auto;padding:12px 10px;flex-shrink:0;}
  .nav-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:5px;}
  .nav-btn{aspect-ratio:1;border-radius:7px;border:none;font-size:12px;font-weight:700;
           cursor:pointer;transition:all .15s;display:flex;align-items:center;justify-content:center;}
  .nav-btn.answered{background:{{ $langue->couleur }};color:#fff;}
  .nav-btn.current{background:#1B3A6B;color:#fff;}
  .nav-btn.unanswered{background:#f0f0f0;color:#666;}
  .nav-btn:hover{filter:brightness(.9);}

  /* Actions bas de page */
  .epreuve-footer{display:flex;align-items:center;justify-content:space-between;
                  padding:12px 20px;background:#fff;border-top:1px solid #e8e8e8;
                  flex-shrink:0;}
  .btn-nav{padding:10px 24px;border-radius:20px;font-size:13px;font-weight:600;
           cursor:pointer;transition:all .2s;border:none;}
  .btn-prev{background:#f0f0f0;color:#666;}
  .btn-prev:hover{background:#e0e0e0;}
  .btn-next{background:{{ $langue->couleur }};color:#fff;}
  .btn-next:hover{filter:brightness(1.05);}
  .q-progress-text{font-size:12px;color:#888;}

  /* Modal fin */
  .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
                 z-index:1000;align-items:center;justify-content:center;}
  .modal-box{background:#fff;border-radius:18px;padding:36px;max-width:420px;
             width:90%;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.2);}

  @media(max-width:700px){
    .nav-sidebar{display:none;}
    .topbar-title{display:none;}
  }
  </style>
</head>
<body>

{{-- ════ TOPBAR ════ --}}
<div class="topbar">
  <div class="timer-val" id="timerDisplay">{{ gmdate('i:s', $discipline->duree_minutes * 60) }}</div>
  <div class="progress-track">
    <div class="progress-fill" id="progressBar" style="width:100%;"></div>
  </div>
  <div class="topbar-title">
    {{ $langue->nom }}: {{ $serie->titre }} ‖ {{ $discipline->nom }}
  </div>
  <button class="btn-fin" onclick="document.getElementById('finModal').style.display='flex'">
    Fin
  </button>
</div>

{{-- ════ BODY ════ --}}
<div class="epreuve-body">

  {{-- Main content --}}
  <div class="epreuve-main">
    <form id="epreuveForm" method="POST"
          action="{{ route('langues.epreuve.soumettre', [$langue->code, $serie->id, $discipline->id]) }}">
      @csrf

      @foreach($questions as $idx => $q)
      <div class="question-panel" id="q-{{ $idx }}"
           style="{{ $idx > 0 ? 'display:none;' : '' }}">

        {{-- Image --}}
        @if($q->image)
        <div class="q-image-wrap">
          <img src="{{ asset('storage/'.$q->image) }}" alt="Image question {{ $idx+1 }}">
        </div>
        @endif

        {{-- Audio --}}
        @if($q->audio)
        <div class="q-audio-wrap">
          <div class="q-num-badge">{{ $idx + 1 }}</div>
          <audio controls id="audio-{{ $idx }}" style="flex:1;height:34px;">
            <source src="{{ asset('storage/'.$q->audio) }}">
          </audio>
        </div>
        @endif

        {{-- Contexte --}}
        @if($q->contexte)
        <div class="q-contexte">{{ $q->contexte }}</div>
        @endif

        {{-- Énoncé --}}
        <div class="q-enonce">
          @if(!$q->image && !$q->audio)
          <span style="display:inline-flex;align-items:center;justify-content:center;
                       width:26px;height:26px;border-radius:6px;
                       background:{{ $langue->couleur }};color:#fff;
                       font-size:11px;font-weight:800;margin-right:8px;vertical-align:middle;">
            {{ $idx + 1 }}
          </span>
          @endif
          {{ $q->enonce }}
        </div>

        {{-- Réponses --}}
        <div class="reponses-list">
          @foreach($q->reponses as $ri => $rep)
          <label class="rep-option" id="rep-{{ $idx }}-{{ $ri }}"
                 onclick="selectRep({{ $idx }}, {{ $ri }}, {{ $q->id }}, {{ $rep->id }})">
            <input type="radio" name="reponses[{{ $q->id }}]" value="{{ $rep->id }}">
            <div class="rep-letter">{{ chr(65+$ri) }}</div>
            <div class="rep-text">{{ $rep->texte }}</div>
          </label>
          @endforeach
        </div>

      </div>
      @endforeach
    </form>
  </div>

  {{-- Sidebar navigation --}}
  <div class="nav-sidebar">
    <div style="font-size:10px;font-weight:700;color:#888;text-transform:uppercase;
                letter-spacing:.5px;margin-bottom:8px;text-align:center;">
      Questions
    </div>
    <div class="nav-grid" id="navGrid">
      @foreach($questions as $idx => $q)
      <button class="nav-btn {{ $idx===0 ? 'current' : 'unanswered' }}"
              id="nav-{{ $idx }}"
              onclick="goTo({{ $idx }})">
        {{ $idx + 1 }}
      </button>
      @endforeach
    </div>
  </div>

</div>

{{-- ════ FOOTER ════ --}}
<div class="epreuve-footer">
  <button class="btn-nav btn-prev" id="btnPrev" onclick="navigate(-1)" style="visibility:hidden;">
    <i class="bi bi-arrow-left me-1"></i>Précédent
  </button>
  <div class="q-progress-text">
    <span id="currentNum">1</span> / {{ count($questions) }}
  </div>
  <button class="btn-nav btn-next" id="btnNext" onclick="navigate(1)">
    Suivant <i class="bi bi-arrow-right ms-1"></i>
  </button>
</div>

{{-- Modal fin --}}
<div id="finModal" class="modal-overlay">
  <div class="modal-box">
    <div style="font-size:2rem;margin-bottom:12px;">⏱️</div>
    <h3 style="font-size:1.2rem;font-weight:800;color:#1B3A6B;margin-bottom:12px;">
      Terminer l'épreuve ?
    </h3>
    <p style="font-size:13px;color:#666;line-height:1.6;margin-bottom:6px;">
      Questions répondues : <strong id="answeredCount">0</strong> / {{ count($questions) }}
    </p>
    <p style="font-size:13px;color:#888;margin-bottom:24px;">
      Les questions sans réponse seront comptées comme incorrectes.
    </p>
    <div style="display:flex;gap:12px;">
      <button onclick="document.getElementById('finModal').style.display='none'"
              style="flex:1;padding:12px;border-radius:25px;border:1.5px solid #ddd;
                     background:#fff;color:#666;font-size:13px;font-weight:600;cursor:pointer;">
        Continuer
      </button>
      <button onclick="submitExam()"
              style="flex:1;padding:12px;border-radius:25px;background:#E24B4A;
                     color:#fff;font-size:13px;font-weight:700;border:none;cursor:pointer;">
        Terminer
      </button>
    </div>
  </div>
</div>

{{-- Modal temps écoulé --}}
<div id="timeModal" class="modal-overlay">
  <div class="modal-box">
    <div style="font-size:3rem;margin-bottom:12px;">⌛</div>
    <h3 style="font-size:1.2rem;font-weight:800;color:#E24B4A;margin-bottom:12px;">
      Temps écoulé !
    </h3>
    <p style="font-size:13px;color:#666;margin-bottom:20px;">
      Le temps imparti est écoulé. Vos réponses ont été enregistrées automatiquement.
    </p>
    <button onclick="submitExam()"
            style="width:100%;padding:12px;border-radius:25px;background:#1B3A6B;
                   color:#fff;font-size:13px;font-weight:700;border:none;cursor:pointer;">
      Voir mes résultats
    </button>
  </div>
</div>

<script>
const TOTAL_SECONDS = {{ $discipline->duree_minutes * 60 }};
const TOTAL_QS      = {{ count($questions) }};
let current         = 0;
let timeLeft        = TOTAL_SECONDS;
let answers         = {};   // { qIdx: repId }
let answered        = new Set();

// ─── Timer ───
const timerDisplay = document.getElementById('timerDisplay');
const progressBar  = document.getElementById('progressBar');

const timer = setInterval(() => {
  timeLeft--;
  const m = Math.floor(timeLeft / 60).toString().padStart(2,'0');
  const s = (timeLeft % 60).toString().padStart(2,'0');
  timerDisplay.textContent = m + ':' + s;

  const pct = (timeLeft / TOTAL_SECONDS) * 100;
  progressBar.style.width = pct + '%';
  if (pct <= 25)     progressBar.className = 'progress-fill danger';
  else if (pct <= 50) progressBar.className = 'progress-fill warn';

  if (timeLeft <= 0) {
    clearInterval(timer);
    document.getElementById('timeModal').style.display = 'flex';
    setTimeout(submitExam, 3000);
  }
}, 1000);

// ─── Navigation ───
function goTo(idx) {
  document.getElementById('q-'+current).style.display = 'none';
  document.getElementById('nav-'+current).className =
    'nav-btn ' + (answered.has(current) ? 'answered' : 'unanswered');
  current = idx;
  document.getElementById('q-'+idx).style.display = 'block';
  document.getElementById('nav-'+idx).className = 'nav-btn current';
  document.getElementById('currentNum').textContent = idx + 1;
  document.getElementById('btnPrev').style.visibility = idx > 0 ? 'visible' : 'hidden';
  document.getElementById('btnNext').textContent =
    idx === TOTAL_QS - 1 ? 'Terminer' : 'Suivant ›';

  // Auto-play audio si présent
  const audio = document.getElementById('audio-' + idx);
  if (audio) audio.play().catch(()=>{});
}

function navigate(dir) {
  if (dir === 1 && current === TOTAL_QS - 1) {
    updateAnsweredCount();
    document.getElementById('finModal').style.display = 'flex';
    return;
  }
  const next = Math.max(0, Math.min(TOTAL_QS-1, current + dir));
  goTo(next);
}

// ─── Sélection réponse ───
function selectRep(qIdx, repIdx, qId, repId) {
  // Désélectionner les autres
  document.querySelectorAll(`[id^="rep-${qIdx}-"]`).forEach(el => el.classList.remove('selected'));
  document.getElementById(`rep-${qIdx}-${repIdx}`).classList.add('selected');
  document.querySelector(`#rep-${qIdx}-${repIdx} input`).checked = true;
  answered.add(qIdx);
  document.getElementById('nav-'+qIdx).className = 'nav-btn answered';
  updateAnsweredCount();
}

function updateAnsweredCount() {
  document.getElementById('answeredCount').textContent = answered.size;
}

// ─── Soumission ───
function submitExam() {
  clearInterval(timer);
  document.getElementById('epreuveForm').submit();
}
</script>
</body>
</html>