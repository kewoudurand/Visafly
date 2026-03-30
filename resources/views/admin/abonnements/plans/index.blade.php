{{-- resources/views/admin/abonnements/plans/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Plans d\'abonnement')

@push('styles')
<style>
.kpi{background:#fff;border-radius:14px;border:1px solid #eee;padding:20px;
     box-shadow:0 2px 8px rgba(27,58,107,.04);}
.kpi-num{font-size:2rem;font-weight:800;line-height:1;margin-bottom:4px;}
.kpi-lbl{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}
.plan-card{background:#fff;border-radius:16px;border:1px solid #eee;overflow:hidden;
           box-shadow:0 2px 12px rgba(27,58,107,.05);transition:all .2s;}
.plan-card:hover{box-shadow:0 6px 24px rgba(27,58,107,.1);}
.plan-card-header{padding:18px 20px;color:#fff;display:flex;align-items:center;gap:14px;}
.plan-icon-badge{width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);
                 display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.plan-nom{font-size:17px;font-weight:800;}
.plan-prix{font-size:24px;font-weight:900;margin-left:auto;white-space:nowrap;}
.plan-body{padding:18px 20px;}
.point-line{display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#444;margin-bottom:8px;}
.point-line i{font-size:14px;flex-shrink:0;margin-top:1px;}
.popular-badge{position:absolute;top:12px;right:12px;padding:3px 12px;border-radius:20px;
               background:#F5A623;color:#1B3A6B;font-size:10px;font-weight:800;
               text-transform:uppercase;letter-spacing:.5px;}
.act-btn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;
         border-radius:8px;border:1px solid #e8e8e8;background:#fff;color:#666;
         text-decoration:none;cursor:pointer;font-size:12px;transition:all .15s;}
.act-btn:hover{border-color:#1B3A6B;color:#1B3A6B;}
.act-btn.red:hover{border-color:#E24B4A;color:#E24B4A;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Plans d'abonnement</h2>
        <p class="text-muted mb-0" style="font-size:13px;">Gérez les offres et les avantages</p>
    </div>
    <a href="{{ route('admin.abonnements.plans.create') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;
              background:#1B3A6B;color:#fff;border-radius:20px;font-size:13px;
              font-weight:700;text-decoration:none;">
        <i class="bi bi-plus-circle"></i>Nouveau plan
    </a>
</div>

@if(session('success'))
<div class="alert rounded-3 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="kpi">
            <div class="kpi-num" style="color:#1cc88a;">{{ $stats['total_abonnes'] }}</div>
            <div class="kpi-lbl">Abonnés actifs</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi">
            <div class="kpi-num" style="color:#F5A623;">
                {{ number_format($stats['revenus_mois'], 0, ',', ' ') }}
                <small style="font-size:12px;font-weight:400;color:#888;">XAF</small>
            </div>
            <div class="kpi-lbl">Revenus ce mois</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi" style="background:#1B3A6B;">
            <div class="kpi-num" style="color:#F5A623;">
                {{ number_format($stats['revenus_total'], 0, ',', ' ') }}
                <small style="font-size:12px;font-weight:400;color:rgba(255,255,255,.5);">XAF</small>
            </div>
            <div class="kpi-lbl" style="color:rgba(255,255,255,.6);">Revenus total</div>
        </div>
    </div>
</div>

{{-- Plans --}}
<div class="row g-4">
    @forelse($plans as $plan)
    <div class="col-md-4">
        <div class="plan-card" style="position:relative;">

            @if($plan->populaire)
            <div class="popular-badge">⭐ Populaire</div>
            @endif

            {{-- Header --}}
            <div class="plan-card-header" style="background:{{ $plan->couleur }};">
                <div class="plan-icon-badge">
                    <i class="bi {{ $plan->icone }}"></i>
                </div>
                <div>
                    <div class="plan-nom">{{ $plan->nom }}</div>
                    <div style="font-size:11px;opacity:.7;">{{ $plan->description }}</div>
                </div>
                <div class="plan-prix">
                    {{ number_format($plan->prix, 0, ',', ' ') }}
                    <small style="font-size:12px;font-weight:400;opacity:.8;">{{ $plan->devise }}</small>
                </div>
            </div>

            {{-- Body --}}
            <div class="plan-body">
                {{-- Stats abonnés --}}
                <div style="display:flex;gap:16px;margin-bottom:14px;padding-bottom:14px;
                            border-bottom:1px solid #f5f5f5;">
                    <div style="text-align:center;">
                        <div style="font-size:18px;font-weight:800;color:#1B3A6B;">
                            {{ $plan->total_abonnements }}
                        </div>
                        <div style="font-size:10px;color:#888;">Total</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:18px;font-weight:800;color:#1cc88a;">
                            {{ $plan->abonnements_actifs }}
                        </div>
                        <div style="font-size:10px;color:#888;">Actifs</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:18px;font-weight:800;color:#1B3A6B;">
                            {{ $plan->duree_jours }} j
                        </div>
                        <div style="font-size:10px;color:#888;">Durée</div>
                    </div>
                    <div style="margin-left:auto;display:flex;align-items:center;">
                        <span style="font-size:11px;padding:3px 10px;border-radius:10px;
                                     background:{{ $plan->actif ? 'rgba(28,200,138,.1)' : 'rgba(226,75,74,.08)' }};
                                     color:{{ $plan->actif ? '#0f6e56' : '#a32d2d' }};font-weight:600;">
                            {{ $plan->actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>

                {{-- Points --}}
                @foreach($plan->points ?? [] as $pt)
                <div class="point-line">
                    <i class="bi {{ $pt['icone'] }}" style="color:{{ $pt['couleur'] }};"></i>
                    {{ $pt['texte'] }}
                </div>
                @endforeach

                {{-- Actions --}}
                <div style="display:flex;gap:6px;margin-top:14px;padding-top:12px;
                            border-top:1px solid #f5f5f5;justify-content:flex-end;">
                    <a href="{{ route('admin.abonnements.plans.edit', $plan) }}"
                       class="act-btn" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.abonnements.plans.toggle', $plan) }}">
                        @csrf
                        <button type="submit" class="act-btn" title="{{ $plan->actif ? 'Désactiver' : 'Activer' }}">
                            <i class="bi bi-{{ $plan->actif ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.abonnements.plans.destroy', $plan) }}"
                          onsubmit="return confirm('Supprimer le plan « {{ $plan->nom }} » ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="act-btn red" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    @empty
    <div class="col-12">
        <div style="text-align:center;padding:60px;background:#f8f9fb;border-radius:14px;border:1.5px dashed #ddd;">
            <i class="bi bi-credit-card" style="font-size:36px;color:#ccc;display:block;margin-bottom:12px;"></i>
            <div style="font-size:14px;color:#888;margin-bottom:16px;">Aucun plan créé.</div>
            <a href="{{ route('admin.abonnements.plans.create') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:10px 24px;
                      background:#1B3A6B;color:#fff;border-radius:20px;font-size:13px;
                      font-weight:700;text-decoration:none;">
                <i class="bi bi-plus-circle"></i>Créer le premier plan
            </a>
        </div>
    </div>
    @endforelse
</div>

@endsection