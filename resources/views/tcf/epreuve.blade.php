<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $passage->discipline->serie->nom }} — {{ $passage->discipline->nom }}</title>
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: system-ui, sans-serif; background: #f8f9fb; }

    /* ── Top bar ── */
    .topbar {
      background: #fff;
      border-bottom: 1px solid #e0e0e0;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      gap: 14px;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    /* Timers */
    .timer-global { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .timer-global .icon { color: #1B3A6B; font-size: 16px; }
    .timer-global .val {
      font-size: 15px; font-weight: 700; color: #1B3A6B;
      min-width: 52px; font-variant-numeric: tabular-nums;
    }
    .bar-wrap { flex: 1; height: 8px; background: #e8e8e8; border-radius: 4px; overflow: hidden; }
    .bar-fill { height: 100%; border-radius: 4px; background: #1cc88a; transition: width 1s linear; }

    .timer-question {
      display: flex; align-items: center; gap: 6px; flex-shrink: 0;
      background: rgba(27,58,107,.06); border-radius: 8px; padding: 4px 10px;
    }
    .timer-question .icon { color: #F5A623; font-size: 13px; }
    .timer-question .val {
      font-size: 13px; font-weight: 700; color: #1B3A6B;
      min-width: 38px; font-variant-numeric: tabular-nums;
    }
    .timer-question.warning .val { color: #E24B4A; }

    .exam-label { font-size: 12px; color: #888; white-space: nowrap; }
    .btn-fin {
      background: #E24B4A; color: #fff; border: none;
      border-radius: 8px; padding: 6px 18px;
      font-size: 13px; font-weight: 600; cursor: pointer; flex-shrink: 0;
    }

    /* ── Layout ── */
    .exam-layout { display: flex; height: calc(100vh - 57px); overflow: hidden; }
    .exam-main {
      flex: 1; overflow-y: auto; padding: 16px;
      display: flex; flex-direction: column; gap: 14px;
      padding-bottom: 80px;
    }

    /* ── Document ── */
    .doc-zone {
      background: #fff; border: 1px solid #e0e0e0;
      border-radius: 12px; padding: 18px;
      font-size: 14px; line-height: 1.75; color: #333;
    }

    /* ── Question ── */
    .question-card { background: #fff; border-radius: 12px; border: 1px solid #e0e0e0; overflow: hidden; }
    .q-header {
      background: #1B3A6B; padding: 12px 18px;
      display: flex; align-items: center; gap: 12px;
    }
    .q-num {
      width: 32px; height: 32px; background: rgba(255,255,255,.2);
      border-radius: 8px; display: flex; align-items: center; justify-content: center;
      font-size: 14px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .q-text { font-size: 14px; color: #fff; }

    /* ── Options ── */
    .options { padding: 10px; }
    .option-label {
      display: flex; align-items: center; gap: 12px;
      padding: 12px 14px; border-radius: 8px; cursor: pointer;
      border: 1px solid transparent; transition: all .15s; margin-bottom: 6px;
    }
    .option-label:hover { background: rgba(27,58,107,.05); border-color: rgba(27,58,107,.2); }
    .option-label.selected { background: rgba(27,58,107,.08); border-color: #1B3A6B; }
    .option-radio { display: none; }
    .opt-circle {
      width: 32px; height: 32px; border-radius: 50%;
      background: #f0f0f0; border: 1px solid #ccc;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 600; color: #666;
      flex-shrink: 0; transition: all .15s;
    }
    .option-label.selected .opt-circle { background: #1B3A6B; border-color: #1B3A6B; color: #fff; }
    .opt-text { font-size: 14px; color: #333; }

    /* ── Grille numéros ── */
    .exam-grid {
      width: 140px; flex-shrink: 0;
      border-left: 1px solid #e0e0e0; background: #fff;
      padding: 14px 10px; overflow-y: auto;
    }
    .grid-title {
      font-size: 10px; font-weight: 600; text-transform: uppercase;
      letter-spacing: .6px; color: #aaa; text-align: center; margin-bottom: 10px;
    }
    .grid-nums { display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; }
    .gnum {
      height: 32px; border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 600; cursor: pointer;
      border: 1px solid #e0e0e0; color: #888; background: #fff;
      text-decoration: none; transition: all .15s;
    }
    .gnum:hover { border-color: #1B3A6B; color: #1B3A6B; }
    .gnum.answered { background: #1cc88a; border-color: #1cc88a; color: #fff; }
    .gnum.current  { background: #1B3A6B; border-color: #1B3A6B; color: #fff; }

    /* ── Footer navigation ── */
    .exam-footer {
      position: fixed; bottom: 0;
      left: 0; right: 140px;
      background: #fff; border-top: 1px solid #e0e0e0;
      padding: 12px 20px;
      display: flex; justify-content: space-between; align-items: center;
      z-index: 50;
    }
    .btn-nav {
      padding: 9px 22px; border-radius: 20px;
      font-size: 13px; font-weight: 500; cursor: pointer; border: none;
    }
    .btn-prev { background: #f0f0f0; color: #555; border: 1px solid #ddd !important; }
    .btn-next { background: #1B3A6B; color: #fff; }
    .btn-next:hover { background: #F5A623; color: #1B3A6B; }
    .btn-terminer { background: #1cc88a; color: #fff; }
  </style>
</head>
<body>

{{-- ── TOP BAR ── --}}
<div class="topbar">

  {{-- Timer global (série entière) --}}
  <div class="timer-global">
    <i class="bi bi-clock icon"></i>
    <span class="val" id="timerGlobal">--:--</span>
  </div>

  {{-- Barre progression globale --}}
  <div class="bar-wrap">
    <div class="bar-fill" id="barGlobal" style="width:100%;"></div>
  </div>

  {{-- Timer par question --}}
  <div class="timer-question" id="timerQWrap">
    <i class="bi bi-hourglass-split icon"></i>
    <span class="val" id="timerQuestion">60</span>
    <span style="font-size:11px;color:#888;">s / question</span>
  </div>

  <span class="exam-label">
    {{ $passage->discipline->serie->type }} :
    {{ $passage->discipline->serie->nom }} ||
    {{ $passage->discipline->nom }}
  </span>

  <form method="GET" action="{{ route('tcf.epreuve.terminer', [
    'serie'      => $passage->discipline->serie->code,
    'discipline' => $passage->discipline->code,
]); }}"
        onsubmit="return confirm('Terminer l\'épreuve ?')">
    <button type="submit" class="btn-fin">Fin</button>
  </form>
</div>

{{-- ── LAYOUT ── --}}
<div class="exam-layout">

  {{-- Zone principale --}}
  <div class="exam-main">

    {{-- Support document --}}
    @if($question->consigne)
    <div class="doc-zone">
      @if($question->type_support === 'image' && $question->fichier_support)
        <strong>Document :</strong>
        <img src="{{ asset('storage/'.$question->fichier_support) }}" alt="Support" style="max-width:100%;border-radius:8px;margin-top:8px;">
      @elseif($question->type_support === 'audio' && $question->fichier_support)
        <div style="background:#f0f4ff;border-radius:10px;padding:14px;display:flex;align-items:center;gap:12px;">
          <i class="bi bi-play-circle-fill" style="font-size:32px;color:#1B3A6B;"></i>
          <audio controls style="width:100%;max-width:400px;">
            <source src="{{ asset('storage/'.$question->fichier_support) }}">
          </audio>
        </div>
      @else
        {!! nl2br(e($question->consigne)) !!}
      @endif
    </div>
    @endif

    {{-- Question + options --}}
    <form method="POST" action="{{ route('tcf.epreuve.repondre', [
    'serie'      => $passage->discipline->serie->code,
    'discipline' => $passage->discipline->code,
]) }}" id="formReponse">
      @csrf
      <input type="hidden" name="question_id" value="{{ $question->id }}">
      <input type="hidden" name="numero" value="{{ $numero }}">

      <div class="question-card">
        <div class="q-header">
          <div class="q-num">{{ $question->numero }}</div>
          <div class="q-text">{{ $question->enonce }}</div>
        </div>
        <div class="options">
          @foreach($question->reponses as $rep)
            @php $checked = isset($reponsesDonnees[$question->id]) && $reponsesDonnees[$question->id] == $rep->id; @endphp
            <label class="option-label {{ $checked ? 'selected' : '' }}" onclick="selectOption(this)">
              <input class="option-radio" type="radio" name="reponse_id"
                     value="{{ $rep->id }}" {{ $checked ? 'checked' : '' }}>
              <div class="opt-circle">{{ $rep->lettre }}</div>
              <span class="opt-text">{{ $rep->texte }}</span>
            </label>
          @endforeach
        </div>
      </div>
    </form>

  </div>

  {{-- Grille numéros --}}
  <div class="exam-grid">
    <div class="grid-title">Questions</div>
    <div class="grid-nums">
      @for($i = 1; $i <= $totalQ; $i++)
        @php
          $qId     = $questions->firstWhere('numero', $i)?->id;
          $answered = $qId && in_array($qId, $questionsRepondues);
          $current  = $i == $numero;
        @endphp
        <a href="{{ route('tcf.epreuve.show', [
            'serie'      => $passage->discipline->serie->code,
            'discipline' => $passage->discipline->code,
            'question'   => $i,
            'passage'    => $passage->id
        ]) }}"
           class="gnum {{ $current ? 'current' : ($answered ? 'answered' : '') }}">
          {{ $i }}
        </a>
      @endfor
    </div>
  </div>

</div>

{{-- ── Footer nav ── --}}
<div class="exam-footer">
  @if($numero > 1)
            <a href="{{ route('tcf.epreuve.show', [
            'serie'      => $passage->discipline->serie->code,
            'discipline' => $passage->discipline->code,
            'question'   => $i,
            'passage'    => $passage->id
        ]) }}"
       class="btn-nav btn-prev">← Précédent</a>
  @else
    <span></span>
  @endif

  <span style="font-size:12px;color:#888;">Question {{ $numero }} sur {{ $totalQ }}</span>

  @if($numero < $totalQ)
    <button class="btn-nav btn-next" onclick="soumettre()">Suivant →</button>
  @else
    <button class="btn-nav btn-terminer"
            onclick="if(confirm('Terminer et voir vos résultats ?')) soumettre()">
      Terminer
    </button>
  @endif
</div>

<script>
  // ═══════════════════════════════════════
  //  TIMERS — basés sur l'heure serveur
  // ═══════════════════════════════════════

  // Timestamps passés depuis PHP
  const debutTimestamp = {{ $debutTimestamp }};  // timestamp UNIX du début
  const dureeMax       = {{ $dureeMax }};         // secondes totales (ex: 3600)
  const autoSoumit     = false;

  // Temps restant question (reset à chaque page)
  let secsQuestion = 60;
  let soumis = false;

function pad(n) { return String(Math.floor(n)).padStart(2, '0'); }

function updateTimers() {

    // ── Timer global : calculé en temps réel depuis debut_at ──
    const maintenant  = Math.floor(Date.now() / 1000);
    const tempsEcoule = maintenant - debutTimestamp;
    const secsGlobal  = Math.max(0, dureeMax - tempsEcoule);

    // Affichage timer global
    const m = Math.floor(secsGlobal / 60);
    const s = secsGlobal % 60;
    document.getElementById('timerGlobal').textContent = pad(m) + ':' + pad(s);

    // Couleur barre globale
    const pct = Math.round((secsGlobal / dureeMax) * 100);
    const bar = document.getElementById('barGlobal');
    bar.style.width = pct + '%';
    if      (secsGlobal <= 300) bar.style.background = '#E24B4A';
    else if (secsGlobal <= 900) bar.style.background = '#F5A623';
    else                        bar.style.background = '#1cc88a';

    // Auto-soumettre si temps global écoulé
    if (secsGlobal <= 0 && !soumis) {
        soumis = true;
        document.getElementById('formReponse').submit();
        return;
    }

    // ── Timer question (60s fixe par question) ──
    document.getElementById('timerQuestion').textContent = secsQuestion;
    const wrap = document.getElementById('timerQWrap');

    if (secsQuestion <= 10) {
        wrap.classList.add('warning');
        wrap.style.background = 'rgba(226,75,74,.1)';
        document.getElementById('timerQuestion').style.color = '#E24B4A';
    } else {
        wrap.classList.remove('warning');
        wrap.style.background = 'rgba(27,58,107,.06)';
        document.getElementById('timerQuestion').style.color = '#1B3A6B';
    }

    // Auto-soumettre si temps question écoulé
    if (secsQuestion <= 0 && !soumis) {
        soumis = true;
        document.getElementById('formReponse').submit();
        return;
    }

    secsQuestion--;
}

// Démarrer immédiatement
updateTimers();
setInterval(updateTimers, 1000);

// ── Sélection option ──
function selectOption(el) {
    document.querySelectorAll('.option-label').forEach(o => {
        o.classList.remove('selected');
        o.querySelector('.opt-circle').style.cssText = '';
    });
    el.classList.add('selected');
    el.querySelector('input[type=radio]').checked = true;
}

function soumettre() {
    document.getElementById('formReponse').submit();
}
</script>

</body>
</html>