{{-- resources/views/users/abonnement.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Mon abonnement — VisaFly')

@push('styles')
<style>
.abo-current{border-radius:18px;padding:28px;color:#fff;margin-bottom:24px;
             background:#1B3A6B;position:relative;overflow:hidden;}
.abo-current::before{content:'';position:absolute;top:-40px;right:-40px;
                     width:160px;height:160px;border-radius:50%;
                     background:rgba(255,255,255,.05);}
.abo-current::after{content:'';position:absolute;bottom:-50px;right:40px;
                    width:100px;height:100px;border-radius:50%;
                    background:rgba(255,255,255,.04);}
.abo-status-dot{width:10px;height:10px;border-radius:50%;background:#1cc88a;
                display:inline-block;margin-right:6px;
                box-shadow:0 0 0 3px rgba(28,200,138,.3);}
.progress-abo{height:6px;border-radius:3px;background:rgba(255,255,255,.15);margin:10px 0;}
.progress-abo-fill{height:100%;border-radius:3px;background:#F5A623;transition:width .5s;}

.plan-card{background:#fff;border-radius:16px;border:2px solid #eee;padding:24px;
           transition:all .25s;cursor:pointer;position:relative;overflow:hidden;}
.plan-card:hover{border-color:#1B3A6B;box-shadow:0 8px 28px rgba(27,58,107,.12);
                 transform:translateY(-2px);}
.plan-card .popular-badge{position:absolute;top:0;right:0;padding:5px 14px;
                           background:#F5A623;color:#1B3A6B;font-size:10px;
                           font-weight:800;border-bottom-left-radius:10px;}
.plan-header{text-align:center;margin-bottom:20px;}
.plan-price{font-size:2.4rem;font-weight:900;color:#1B3A6B;line-height:1;}
.plan-period{font-size:12px;color:#888;margin-top:4px;}
.plan-point{display:flex;align-items:flex-start;gap:8px;font-size:13px;
            color:#444;margin-bottom:8px;line-height:1.4;}
.plan-point i{font-size:14px;flex-shrink:0;margin-top:1px;}
.btn-subscribe{width:100%;padding:13px;border-radius:25px;font-size:14px;
               font-weight:700;border:none;cursor:pointer;transition:all .2s;margin-top:16px;}
.btn-subscribe:hover{filter:brightness(1.05);transform:translateY(-1px);}

/* Accès examens */
.exam-access{background:#fff;border-radius:14px;border:1px solid #eee;
             padding:16px 20px;margin-bottom:10px;display:flex;
             align-items:center;gap:14px;box-shadow:0 1px 4px rgba(27,58,107,.04);}
.exam-access-icon{width:44px;height:44px;border-radius:11px;display:flex;
                  align-items:center;justify-content:center;font-size:16px;
                  font-weight:900;flex-shrink:0;color:#fff;}
.access-badge{padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;}
.access-full{background:rgba(28,200,138,.1);color:#0f6e56;}
.access-partial{background:rgba(245,166,35,.1);color:#633806;}
.access-none{background:rgba(226,75,74,.08);color:#a32d2d;}

/* Historique */
.histo-row{display:flex;align-items:center;justify-content:space-between;
           padding:12px 0;border-bottom:1px solid #f5f5f5;font-size:13px;}
.histo-row:last-child{border-bottom:none;}

/* Modal — choix de langue */
.modal-langue .form-check-langue{
    display:flex;align-items:center;gap:8px;padding:12px;
    border:1.5px solid #eee;border-radius:12px;cursor:pointer;
    transition:border-color .2s,background .2s;
}
.modal-langue .form-check-langue:hover{border-color:#1B3A6B;background:rgba(27,58,107,.03);}
.modal-langue input[type="radio"]:checked ~ span{font-weight:800;}
.modal-langue .form-check-langue.disabled{opacity:.4;cursor:not-allowed;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Mon abonnement</h2>
        <p class="text-muted mb-0" style="font-size:13px;">Gérez votre accès aux examens VisaFly</p>
    </div>
</div>

@if(session('success'))
<div class="alert rounded-3 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert rounded-3 mb-3"
     style="background:rgba(226,75,74,.08);border:1px solid rgba(226,75,74,.3);color:#a32d2d;">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
</div>
@endif

@php
    // ✅ Ensemble des codes d'examens réellement accessibles (calculé une seule fois),
    // basé sur les abonnements ACTIFS de l'utilisateur — plus de "tout ou rien".
    $codesAccessibles = $abonnementsActifs->pluck('langue.code')->filter()->values()->toArray();
@endphp

{{-- ── Abonnement(s) actif(s) ── --}}
@if($abonnement)
<div class="abo-current" style="background:{{ $abonnement->plan?->couleur ?? '#1B3A6B' }};">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;position:relative;z-index:1;">
        <div>
            <div style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;
                        letter-spacing:.7px;margin-bottom:8px;">
                <span class="abo-status-dot"></span>Abonnement actif
            </div>
            <div style="font-size:24px;font-weight:800;margin-bottom:4px;">
                {{ $abonnement->plan?->nom ?? '—' }} — {{ $abonnement->langue?->nom ?? '—' }}
            </div>
            <div style="font-size:13px;opacity:.75;">
                {{ $abonnement->plan?->description }}
            </div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:2rem;font-weight:900;color:#F5A623;">
                {{ number_format($abonnement->montant, 0, ',', ' ') }}
                <small style="font-size:13px;opacity:.7;">{{ $abonnement->devise }}</small>
            </div>
            <div style="font-size:12px;opacity:.7;margin-top:2px;">
                Expire le {{ $abonnement->fin_at->format('d/m/Y') }}
            </div>
        </div>
    </div>
    {{-- Barre de progression --}}
    @php
        $debut  = \Carbon\Carbon::parse($abonnement->debut_at);
        $fin    = \Carbon\Carbon::parse($abonnement->fin_at);
        $total  = $debut->diffInDays($fin);
        $reste  = now()->diffInDays($fin, false);
        $pct    = $total > 0 ? max(0, min(100, round(($reste / $total) * 100))) : 0;
        $joursRestants = max(0, (int)$reste);
    @endphp
    <div style="margin-top:16px;position:relative;z-index:1;">
        <div style="display:flex;justify-content:space-between;font-size:11px;
                    opacity:.75;margin-bottom:6px;">
            <span>Progression</span>
            <span>{{ $joursRestants }} jour(s) restant(s)</span>
        </div>
        <div class="progress-abo">
            <div class="progress-abo-fill" style="width:{{ $pct }}%;"></div>
        </div>
    </div>
</div>

@if($abonnementsActifs->count() > 1)
<div style="font-size:12px;color:#666;margin-bottom:20px;">
    <i class="bi bi-info-circle me-1"></i>
    Vous avez {{ $abonnementsActifs->count() }} abonnements actifs simultanés
    ({{ $abonnementsActifs->pluck('langue.nom')->join(', ') }}).
</div>
@endif
@endif

{{-- ── Accès aux examens — TOUJOURS affiché, avec état par examen ── --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:12px;">
            <i class="bi bi-unlock-fill me-2" style="color:#F5A623;"></i>
            Mes accès aux examens
        </h3>
        @foreach($langues as $langue)
            @php
                // ✅ Vérification INDIVIDUELLE par examen — plus de badge "Accès complet" généralisé
                $aAcces = in_array($langue->code, $codesAccessibles, true);
            @endphp
            <div class="exam-access" @if(!$aAcces) style="opacity:.7;" @endif>
                <div class="exam-access-icon" style="background:{{ $langue->couleur }};">
                    {{ strtoupper($langue->code) }}
                </div>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:700;color:#1B3A6B;">{{ $langue->nom }}</div>
                    <div style="font-size:11px;color:#888;margin-top:2px;">
                        {{ $aAcces ? ($langue->organisme ?? 'Accès actif') : 'Séries gratuites uniquement' }}
                    </div>
                </div>
                <div>
                    @if($aAcces)
                        <span class="access-badge access-full">
                            <i class="bi bi-check-circle-fill me-1"></i>Accès complet
                        </span>
                    @else
                        <span class="access-badge access-partial">
                            <i class="bi bi-unlock me-1"></i>Limité
                        </span>
                    @endif
                </div>
                <a href="{{ route('langues.series', $langue->code) }}"
                   style="display:inline-flex;align-items:center;gap:5px;padding:7px 14px;
                          background:{{ $langue->couleur }};color:#fff;border-radius:20px;
                          font-size:12px;font-weight:600;text-decoration:none;white-space:nowrap;">
                    Commencer <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @endforeach
    </div>

    <div class="col-lg-4">
        @if($abonnement)
        {{-- Résumé abonnement --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #eee;padding:20px;">
            <div style="font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:14px;">
                <i class="bi bi-list-check me-2" style="color:#F5A623;"></i>Avantages inclus
            </div>
            @foreach($abonnement->plan?->points ?? [] as $pt)
            <div style="display:flex;align-items:flex-start;gap:8px;
                        font-size:13px;color:#444;margin-bottom:8px;">
                <i class="bi {{ $pt['icone'] }}"
                   style="color:{{ $pt['couleur'] }};font-size:14px;flex-shrink:0;margin-top:1px;"></i>
                {{ $pt['texte'] }}
            </div>
            @endforeach
        </div>
        @else
        <div style="background:rgba(245,166,35,.06);border:1.5px solid rgba(245,166,35,.3);
                    border-radius:16px;padding:24px;text-align:center;">
            <i class="bi bi-lock-fill" style="font-size:32px;color:#F5A623;display:block;margin-bottom:10px;"></i>
            <h3 style="font-size:1rem;font-weight:700;color:#1B3A6B;margin-bottom:6px;">
                Aucun abonnement actif
            </h3>
            <p style="font-size:13px;color:#666;margin-bottom:0;">
                Souscrivez à un plan pour débloquer un examen.
            </p>
        </div>
        @endif
    </div>
</div>

{{-- ── Plans disponibles ── --}}
<h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin:28px 0 16px;">
    <i class="bi bi-grid me-2" style="color:#F5A623;"></i>
    Choisir un plan
</h3>

<div class="row g-3">
    @foreach($plans as $plan)
    <div class="col-md-4">
        <div class="plan-card">

            @if($plan->populaire)
            <div class="popular-badge">⭐ Populaire</div>
            @endif

            <div class="plan-header">
                <div style="font-size:28px;color:{{ $plan->couleur }};margin-bottom:8px;">
                    <i class="bi {{ $plan->icone }}"></i>
                </div>
                <div style="font-size:16px;font-weight:800;color:#1B3A6B;">{{ $plan->nom }}</div>
                <div style="font-size:11px;color:#888;margin-top:2px;">{{ $plan->description }}</div>
                <div class="plan-price" style="margin-top:12px;">
                    {{ number_format($plan->prix, 0, ',', ' ') }}
                    <small style="font-size:12px;font-weight:400;color:#888;">{{ $plan->devise }}</small>
                </div>
                <div class="plan-period">/ {{ $plan->duree_jours }} jours</div>
            </div>

            @foreach($plan->points ?? [] as $pt)
            <div class="plan-point">
                <i class="bi {{ $pt['icone'] }}" style="color:{{ $pt['couleur'] }};"></i>
                {{ $pt['texte'] }}
            </div>
            @endforeach

            <button type="button" class="btn-subscribe"
                    style="background:{{ $plan->couleur }};color:#fff;"
                    data-bs-toggle="modal"
                    data-bs-target="#modalLangue-{{ $plan->id }}">
                <i class="bi bi-lightning-charge-fill me-2"></i>S'abonner
            </button>

        </div>
    </div>
    @endforeach
</div>

{{-- ══════════════════════════════════════════════════════════════
     Modals de choix d'examen — sorties de .plan-card pour éviter
     le conflit position:fixed / transform (clignotement).
     Les examens déjà actifs sont désactivés dans le radio pour
     empêcher un rachat inutile du même examen.
     ══════════════════════════════════════════════════════════════ --}}
@foreach($plans as $plan)
    <div class="modal fade modal-langue" id="modalLangue-{{ $plan->id }}" tabindex="-1"
        aria-labelledby="modalLangueLabel-{{ $plan->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;">
                <div class="modal-header" style="border-bottom:1px solid #f0f0f0;">
                    <h5 class="modal-title" id="modalLangueLabel-{{ $plan->id }}" style="color:#1B3A6B;font-weight:800;">
                        Choisissez votre examen
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form method="POST" action="{{ route('abonnement.souscrire', $plan) }}">
                    @csrf
                    <div class="modal-body">
                        <p style="font-size:13px;color:#666;margin-bottom:16px;">
                            Le plan <strong>{{ $plan->nom }}</strong> donne accès à
                            <strong>un seul examen au choix</strong> parmi TCF, TEF, IELTS et Goethe.
                            Sélectionnez celui que vous souhaitez préparer.
                        </p>
                        <div class="row g-2">
                            @foreach($langues as $langue)
                                @php $dejaActif = in_array($langue->code, $codesAccessibles, true); @endphp
                                <div class="col-6">
                                    <label class="form-check-langue {{ $dejaActif ? 'disabled' : '' }}">
                                        <input type="radio" name="langue_id" value="{{ $langue->id }}"
                                               {{ $dejaActif ? 'disabled' : 'required' }}
                                               style="accent-color:{{ $langue->couleur }};">
                                        <span style="font-size:13px;font-weight:600;color:#1B3A6B;">
                                            {{ $langue->nom }}
                                            @if($dejaActif) <small>(déjà actif)</small> @endif
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('langue_id')
                        <div style="color:#a32d2d;font-size:12px;margin-top:8px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="tarif-btn tarif-btn-primary" style="width:auto;padding:10px 24px;">
                            Continuer vers le paiement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- Historique --}}
@if($historique->count())
<h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin:28px 0 12px;">
    <i class="bi bi-clock-history me-2" style="color:#F5A623;"></i>Historique
</h3>
<div style="background:#fff;border-radius:14px;border:1px solid #eee;padding:16px 20px;">
    @foreach($historique as $h)
    <div class="histo-row">
        <div>
            <div style="font-size:13px;font-weight:600;color:#1B3A6B;">
                {{ $h->plan?->nom ?? '—' }} — {{ $h->langue?->nom ?? '—' }}
            </div>
            <div style="font-size:11px;color:#888;">
                @if($h->debut_at && $h->fin_at)
                    {{ $h->debut_at->format('d/m/Y') }} → {{ $h->fin_at->format('d/m/Y') }}
                @else
                    Non activé pour le moment
                @endif
            </div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:13px;font-weight:700;color:#1B3A6B;">
                {{ number_format($h->montant, 0, ',', ' ') }} {{ $h->devise }}
            </div>
            @php
                $actifHisto = $h->statut === 'actif' && $h->fin_at && $h->fin_at >= now();
                $labels = [
                    'actif'      => ['Actif', 'rgba(28,200,138,.1)', '#0f6e56'],
                    'en_attente' => ['En attente de paiement', 'rgba(245,166,35,.1)', '#633806'],
                    'expire'     => ['Expiré', '#f0f0f0', '#999'],
                    'annule'     => ['Annulé', 'rgba(226,75,74,.08)', '#a32d2d'],
                ];
                [$label, $bg, $color] = $actifHisto
                    ? ['Actif', 'rgba(28,200,138,.1)', '#0f6e56']
                    : ($labels[$h->statut] ?? ['—', '#f0f0f0', '#999']);
            @endphp
            <span style="font-size:10px;padding:2px 8px;border-radius:8px;
                         background:{{ $bg }};color:{{ $color }};">
                {{ $label }}
            </span>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection