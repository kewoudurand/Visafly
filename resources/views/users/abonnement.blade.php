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
.plan-card.active-plan{border-color:#1B3A6B;}
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

{{-- ── Abonnement actuel ── --}}
@if($abonnement)
<div class="abo-current" style="background:{{ $abonnement->plan?->couleur ?? '#1B3A6B' }};">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;position:relative;z-index:1;">
        <div>
            <div style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;
                        letter-spacing:.7px;margin-bottom:8px;">
                <span class="abo-status-dot"></span>Abonnement actif
            </div>
            <div style="font-size:24px;font-weight:800;margin-bottom:4px;">
                {{ $abonnement->plan?->nom ?? ucfirst($abonnement->forfait) }}
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

{{-- Accès aux examens --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:12px;">
            <i class="bi bi-unlock-fill me-2" style="color:#F5A623;"></i>
            Mes accès aux examens
        </h3>
        @foreach($langues as $langue)
        <div class="exam-access">
            <div class="exam-access-icon" style="background:{{ $langue->couleur }};">
                {{ strtoupper($langue->code) }}
            </div>
            <div style="flex:1;">
                <div style="font-size:13px;font-weight:700;color:#1B3A6B;">{{ $langue->nom }}</div>
                <div style="font-size:11px;color:#888;margin-top:2px;">{{ $langue->organisme }}</div>
            </div>
            <div>
                <span class="access-badge access-full">
                    <i class="bi bi-check-circle-fill me-1"></i>Accès complet
                </span>
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
    </div>
</div>

@else
{{-- Pas d'abonnement --}}
<div style="background:rgba(245,166,35,.06);border:1.5px solid rgba(245,166,35,.3);
            border-radius:16px;padding:24px;margin-bottom:28px;text-align:center;">
    <i class="bi bi-lock-fill" style="font-size:32px;color:#F5A623;display:block;margin-bottom:10px;"></i>
    <h3 style="font-size:1.1rem;font-weight:700;color:#1B3A6B;margin-bottom:6px;">
        Vous n'avez pas d'abonnement actif
    </h3>
    <p style="font-size:13px;color:#666;margin-bottom:0;">
        Souscrivez à un plan pour accéder à toutes les séries d'entraînement.
    </p>
</div>

{{-- Accès limités sans abonnement --}}
<h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin-bottom:12px;">
    <i class="bi bi-lock me-2" style="color:#E24B4A;"></i>Accès sans abonnement
</h3>
@foreach($langues as $langue)
<div class="exam-access" style="opacity:.7;">
    <div class="exam-access-icon" style="background:{{ $langue->couleur }};">
        {{ strtoupper($langue->code) }}
    </div>
    <div style="flex:1;">
        <div style="font-size:13px;font-weight:700;color:#1B3A6B;">{{ $langue->nom }}</div>
        <div style="font-size:11px;color:#888;margin-top:2px;">Séries gratuites uniquement</div>
    </div>
    <span class="access-badge access-partial">
        <i class="bi bi-unlock me-1"></i>Limité
    </span>
</div>
@endforeach

@endif

{{-- ── Plans disponibles ── --}}
<h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin:28px 0 16px;">
    <i class="bi bi-grid me-2" style="color:#F5A623;"></i>
    {{ $abonnement ? 'Changer de plan' : 'Choisir un plan' }}
</h3>

<div class="row g-3">
    @foreach($plans as $plan)
    <div class="col-md-4">
        <div class="plan-card {{ $abonnement && $abonnement->forfait === $plan->code ? 'active-plan' : '' }}">

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

            @if($abonnement && $abonnement->forfait === $plan->code)
            <button class="btn-subscribe"
                    style="background:rgba(27,58,107,.08);color:#1B3A6B;cursor:default;">
                <i class="bi bi-check-circle-fill me-2"></i>Plan actuel
            </button>
            @else
            <form method="POST" action="{{ route('abonnement.souscrire', $plan) }}">
                @csrf
                <button type="submit" class="btn-subscribe"
                        style="background:{{ $plan->couleur }};color:#fff;">
                    <i class="bi bi-lightning-charge-fill me-2"></i>S'abonner
                </button>
            </form>
            @endif

        </div>
    </div>
    @endforeach
</div>

{{-- Historique --}}
@if($historique->count())
<h3 style="font-size:14px;font-weight:700;color:#1B3A6B;margin:28px 0 12px;">
    <i class="bi bi-clock-history me-2" style="color:#F5A623;"></i>Historique
</h3>
<div style="background:#fff;border-radius:14px;border:1px solid #eee;padding:16px 20px;">
    @foreach($historique as $h)
    <div class="histo-row">
        <div>
            <div style="font-size:13px;font-weight:600;color:#1B3A6B;">{{ ucfirst($h->forfait) }}</div>
            <div style="font-size:11px;color:#888;">
                {{ $h->debut_at->format('d/m/Y') }} → {{ $h->fin_at->format('d/m/Y') }}
            </div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:13px;font-weight:700;color:#1B3A6B;">
                {{ number_format($h->montant, 0, ',', ' ') }} {{ $h->devise }}
            </div>
            @php $actifHisto = $h->actif && $h->fin_at >= now(); @endphp
            <span style="font-size:10px;padding:2px 8px;border-radius:8px;
                         background:{{ $actifHisto ? 'rgba(28,200,138,.1)' : '#f0f0f0' }};
                         color:{{ $actifHisto ? '#0f6e56' : '#999' }};">
                {{ $actifHisto ? 'Actif' : 'Expiré' }}
            </span>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection