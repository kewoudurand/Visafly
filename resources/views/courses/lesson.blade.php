{{-- resources/views/cours/lecon.blade.php --}}
@extends('layouts.app')
@section('title', $lecon->titre.' — '.$cours->titre)

@push('styles')
<style>
.lecon-wrap { max-width: 820px; margin: 0 auto; padding: 24px 20px 80px; }

/* Navigation haut */
.lecon-nav-top {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 28px;
}
.back-btn {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: #fff;
    border: 1px solid #e8e8e8;
    display: flex; align-items: center; justify-content: center;
    color: #1B3A6B;
    text-decoration: none;
    transition: all .2s;
    flex-shrink: 0;
}
.back-btn:hover { background: #1B3A6B; color: #fff; }

/* En-tête leçon */
.lecon-header {
    background: {{ $cours->couleur }};
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    color: #fff;
}
.lecon-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.18);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
}
.lecon-titre-h { font-size: 1.5rem; font-weight: 800; margin-bottom: 6px; }
.lecon-meta { font-size: 12px; opacity: .75; display: flex; gap: 16px; }

/* Vocabulaire */
.mots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 12px;
    margin-bottom: 28px;
}
.mot-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #eee;
    padding: 16px;
    box-shadow: 0 2px 8px rgba(27,58,107,.05);
    transition: all .2s;
}
.mot-card:hover {
    border-color: {{ $cours->couleur }};
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(27,58,107,.1);
}
.mot-de {
    font-size: 18px;
    font-weight: 800;
    color: #1B3A6B;
    margin-bottom: 2px;
}
.mot-phonetique {
    font-size: 12px;
    color: #999;
    font-style: italic;
    margin-bottom: 4px;
}
.mot-fr {
    font-size: 13px;
    color: #555;
    font-weight: 600;
    margin-bottom: 6px;
}
.mot-exemple {
    font-size: 12px;
    color: #888;
    background: #f8f9fb;
    border-left: 3px solid {{ $cours->couleur }};
    padding: 6px 10px;
    border-radius: 0 8px 8px 0;
    line-height: 1.5;
}

/* Exercices */
.exercice-block {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #eee;
    padding: 22px;
    margin-bottom: 14px;
    box-shadow: 0 2px 8px rgba(27,58,107,.04);
}
.ex-num {
    font-size: 11px;
    font-weight: 700;
    color: {{ $cours->couleur }};
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-bottom: 8px;
}
.ex-question {
    font-size: 15px;
    font-weight: 700;
    color: #1B3A6B;
    margin-bottom: 14px;
}

/* Choix QCM */
.choix-list { display: flex; flex-direction: column; gap: 8px; }
.choix-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: #f8f9fb;
    border: 1.5px solid #e8e8e8;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
    color: #333;
    transition: all .15s;
    text-align: left;
    width: 100%;
}
.choix-btn:hover { border-color: {{ $cours->couleur }}; background: rgba(27,58,107,.04); }
.choix-btn.selected { border-color: {{ $cours->couleur }}; background: rgba(27,58,107,.06); }
.choix-btn.correct { border-color: #1cc88a; background: rgba(28,200,138,.08); color: #0f6e56; }
.choix-btn.wrong   { border-color: #E24B4A; background: rgba(226,75,74,.06); color: #a32d2d; }

.choix-letter {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: #e8e8e8;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
    transition: all .15s;
}
.choix-btn.selected .choix-letter { background: {{ $cours->couleur }}; color: #fff; }

/* Input texte libre */
.libre-input {
    width: 100%;
    border: 1.5px solid #e8e8e8;
    border-radius: 10px;
    padding: 11px 14px;
    font-size: 14px;
    outline: none;
    transition: all .2s;
    background: #fafafa;
}
.libre-input:focus { border-color: {{ $cours->couleur }}; background: #fff; }

/* Explication */
.explication-box {
    display: none;
    margin-top: 10px;
    background: rgba(245,166,35,.08);
    border-left: 3px solid #F5A623;
    border-radius: 0 10px 10px 0;
    padding: 10px 14px;
    font-size: 13px;
    color: #633806;
    line-height: 1.6;
}

/* Bouton valider */
.btn-valider {
    width: 100%;
    padding: 14px;
    background: {{ $cours->couleur }};
    color: #fff;
    border: none;
    border-radius: 25px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 24px;
}
.btn-valider:hover { filter: brightness(1.08); transform: translateY(-2px); }
.btn-valider:disabled { opacity: .5; cursor: not-allowed; transform: none; }

/* Résultat */
.result-box {
    display: none;
    background: #fff;
    border-radius: 14px;
    padding: 28px;
    text-align: center;
    border: 1px solid #eee;
    box-shadow: 0 4px 20px rgba(27,58,107,.08);
    margin-top: 20px;
}
.result-score {
    font-size: 3rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 8px;
}
.result-emoji { font-size: 2.5rem; margin-bottom: 12px; }

/* Navigation bas */
.lecon-nav-bottom {
    display: flex;
    gap: 12px;
    margin-top: 28px;
}
.nav-btn {
    padding: 11px 22px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all .2s;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
}
.nav-btn:hover { transform: translateY(-1px); text-decoration: none; }
</style>
@endpush

@section('content')

<div class="lecon-wrap">

    {{-- Navigation --}}
    <div class="lecon-nav-top">
        <a href="{{ route('cours.allemand.show', $cours->slug) }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <div style="font-size:11px;color:#888;margin-bottom:2px;">
                <span style="color:{{ $cours->couleur }};font-weight:700;">{{ $cours->niveau }}</span>
                · {{ $cours->titre }}
            </div>
            <div style="font-size:15px;font-weight:700;color:#1B3A6B;">{{ $lecon->titre }}</div>
        </div>
    </div>

    {{-- Header --}}
    <div class="lecon-header">
        <div class="lecon-type-badge">
            <i class="bi {{ $lecon->typeIcon() }}"></i>
            {{ $lecon->typeLabel() }}
        </div>
        <div class="lecon-titre-h">{{ $lecon->titre }}</div>
        <div class="lecon-meta">
            <span><i class="bi bi-clock me-1"></i>{{ $lecon->duree_minutes }} min</span>
            <span><i class="bi bi-star me-1"></i>{{ $lecon->points_recompense }} points</span>
            @if($lecon->estTermineePar(auth()->id()))
            <span style="color:#F5A623;">
                <i class="bi bi-check-circle-fill me-1"></i>Terminée
            </span>
            @endif
        </div>
    </div>

    {{-- Contenu texte --}}
    @if($lecon->contenu)
    <div style="background:#fff;border-radius:14px;border:1px solid #eee;
                padding:22px;margin-bottom:24px;font-size:14px;line-height:1.8;color:#333;">
        {!! nl2br(e($lecon->contenu)) !!}
    </div>
    @endif

    {{-- Vocabulaire --}}
    @if($lecon->mots && count($lecon->mots))
    <div style="margin-bottom:28px;">
        <h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:14px;
                   text-transform:uppercase;letter-spacing:.6px;">
            <i class="bi bi-alphabet me-2" style="color:{{ $cours->couleur }};"></i>
            Vocabulaire ({{ count($lecon->mots) }} mots)
        </h3>
        <div class="mots-grid">
            @foreach($lecon->mots as $mot)
            <div class="mot-card">
                <div class="mot-de">{{ $mot['de'] }}</div>
                @if(!empty($mot['phonetique']))
                <div class="mot-phonetique">/ {{ $mot['phonetique'] }} /</div>
                @endif
                <div class="mot-fr">🇫🇷 {{ $mot['fr'] }}</div>
                @if(!empty($mot['exemple']))
                <div class="mot-exemple">
                    <i class="bi bi-chat-quote" style="font-size:10px;color:{{ $cours->couleur }};margin-right:4px;"></i>
                    {{ $mot['exemple'] }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Exercices --}}
    @if($lecon->exercices && count($lecon->exercices))
    <div id="exercicesZone">
        <h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:14px;
                   text-transform:uppercase;letter-spacing:.6px;">
            <i class="bi bi-check2-circle me-2" style="color:{{ $cours->couleur }};"></i>
            Exercices ({{ count($lecon->exercices) }} questions)
        </h3>

        @foreach($lecon->exercices as $idx => $ex)
        <div class="exercice-block" id="ex-{{ $idx }}">
            <div class="ex-num">Question {{ $idx + 1 }}</div>
            <div class="ex-question">{{ $ex['question'] }}</div>

            @if($ex['type'] === 'qcm')
            <div class="choix-list" data-idx="{{ $idx }}" data-reponse="{{ $ex['reponse'] }}">
                @foreach($ex['choix'] as $ci => $choix)
                <button class="choix-btn" onclick="selectChoix({{ $idx }}, this, '{{ addslashes($choix) }}')"
                        data-value="{{ $choix }}">
                    <div class="choix-letter">{{ chr(65+$ci) }}</div>
                    <span>{{ $choix }}</span>
                </button>
                @endforeach
            </div>
            @else
            <input type="text"
                   class="libre-input"
                   id="libre-{{ $idx }}"
                   data-idx="{{ $idx }}"
                   data-reponse="{{ $ex['reponse'] }}"
                   placeholder="Votre réponse...">
            @endif

            <div class="explication-box" id="expl-{{ $idx }}">
                <strong>💡 Explication :</strong> {{ $ex['explication'] ?? '' }}
            </div>
        </div>
        @endforeach

        <button class="btn-valider" id="btnValider" onclick="validerExercices()">
            <i class="bi bi-check-circle-fill"></i>
            Valider mes réponses
        </button>

        {{-- Résultat final --}}
        <div class="result-box" id="resultBox">
            <div class="result-emoji" id="resultEmoji">🎉</div>
            <div class="result-score" id="resultScore">100%</div>
            <div style="font-size:15px;color:#666;margin-bottom:8px;" id="resultMsg"></div>
            <div style="font-size:13px;color:#F5A623;font-weight:700;" id="resultPoints"></div>
        </div>
    </div>
    @else
    {{-- Pas d'exercices : marquer terminée directement --}}
    @auth
    <form method="POST" action="{{ route('cours.allemand.valider', $lecon->id) }}" id="formTerminer">
        @csrf
        <input type="hidden" name="reponses" value="[]">
        <button type="submit" class="btn-valider">
            <i class="bi bi-check-circle-fill"></i>
            Marquer comme terminée
        </button>
    </form>
    @endauth
    @endif

    {{-- Navigation leçons --}}
    <div class="lecon-nav-bottom">
        @if($precedente)
        <a href="{{ route('cours.allemand.lecon', [$cours->slug, $precedente->slug]) }}"
           class="nav-btn"
           style="background:#f0f0f0;color:#555;flex:1;justify-content:center;">
            <i class="bi bi-arrow-left"></i> Précédente
        </a>
        @endif
        @if($suivante)
        <a href="{{ route('cours.allemand.lecon', [$cours->slug, $suivante->slug]) }}"
           class="nav-btn"
           style="background:{{ $cours->couleur }};color:#fff;flex:1;justify-content:center;">
            Suivante <i class="bi bi-arrow-right"></i>
        </a>
        @endif
    </div>

</div>

@push('scripts')
<script>
const LECON_ID   = {{ $lecon->id }};
const VALIDER_URL = '{{ route("cours.allemand.valider", $lecon->id) }}';
const CSRF        = '{{ csrf_token() }}';
const NB_EXERCICES= {{ count($lecon->exercices ?? []) }};

const reponses  = {};   // { idx: valeur }
const corrects  = {};   // { idx: true/false }

// ── Sélection QCM ──────────────────────────────────────
function selectChoix(idx, btn, valeur) {
    const list = btn.closest('.choix-list');
    list.querySelectorAll('.choix-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    reponses[idx] = valeur;
}

// ── Valider tous les exercices ──────────────────────────
async function validerExercices() {
    // Collecter les réponses texte libre
    document.querySelectorAll('.libre-input').forEach(input => {
        const idx = input.dataset.idx;
        reponses[idx] = input.value.trim();
    });

    if (Object.keys(reponses).length < NB_EXERCICES) {
        alert('Veuillez répondre à toutes les questions avant de valider.');
        return;
    }

    // Calculer le score localement
    let correct = 0;
    document.querySelectorAll('.exercice-block').forEach((block, idx) => {
        const list = block.querySelector('.choix-list');
        const libre = block.querySelector('.libre-input');
        const rep    = reponses[idx] ?? '';
        let bonneRep;

        if (list) {
            bonneRep = list.dataset.reponse;
        } else if (libre) {
            bonneRep = libre.dataset.reponse;
        }

        const isOk = rep.toLowerCase().trim() === bonneRep.toLowerCase().trim();
        corrects[idx] = isOk;
        if (isOk) correct++;

        // Colorier les boutons
        if (list) {
            list.querySelectorAll('.choix-btn').forEach(btn => {
                btn.disabled = true;
                if (btn.dataset.value === bonneRep) btn.classList.add('correct');
                else if (btn.classList.contains('selected') && !isOk) btn.classList.add('wrong');
            });
        } else if (libre) {
            libre.disabled = true;
            libre.style.borderColor = isOk ? '#1cc88a' : '#E24B4A';
        }

        // Afficher l'explication
        document.getElementById('expl-' + idx).style.display = 'block';
    });

    const score = Math.round((correct / NB_EXERCICES) * 100);

    // Afficher le résultat
    const resultBox = document.getElementById('resultBox');
    resultBox.style.display = 'block';
    document.getElementById('resultScore').textContent = score + '%';
    document.getElementById('resultScore').style.color =
        score >= 70 ? '#1cc88a' : (score >= 50 ? '#F5A623' : '#E24B4A');
    document.getElementById('resultEmoji').textContent =
        score >= 90 ? '🏆' : (score >= 70 ? '🎉' : (score >= 50 ? '💪' : '📚'));
    document.getElementById('resultMsg').textContent =
        score >= 70 ? 'Excellent ! Leçon validée !' : 'Continuez à pratiquer !';

    document.getElementById('btnValider').disabled = true;

    // Envoyer au serveur
    try {
        const res = await fetch(VALIDER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({ reponses }),
        });
        const data = await res.json();
        if (data.points_gagnes) {
            document.getElementById('resultPoints').textContent =
                '+' + data.points_gagnes + ' points gagnés !';
        }
    } catch (e) {
        console.error('Erreur de sauvegarde:', e);
    }

    resultBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
@endpush

@endsection