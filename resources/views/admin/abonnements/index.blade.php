{{-- resources/views/admin/abonnements/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Gestion des abonnements')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Abonnements</h2>
        <p class="text-muted mb-0" style="font-size:13px;">Suivi de tous les abonnements VisaFly</p>
    </div>
</div>

{{-- Stats cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="p-4 rounded-3"
             style="background:#fff;border:1px solid rgba(27,58,107,.1);">
            <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                        letter-spacing:.6px;margin-bottom:8px;">Actifs</div>
            <div style="font-size:2rem;font-weight:800;color:#1cc88a;">{{ $stats['actifs'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-4 rounded-3"
             style="background:#fff;border:1px solid rgba(27,58,107,.1);">
            <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                        letter-spacing:.6px;margin-bottom:8px;">Expirés</div>
            <div style="font-size:2rem;font-weight:800;color:#E24B4A;">{{ $stats['expires'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-4 rounded-3"
             style="background:#fff;border:1px solid rgba(27,58,107,.1);">
            <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                        letter-spacing:.6px;margin-bottom:8px;">Revenus ce mois</div>
            <div style="font-size:1.8rem;font-weight:800;color:#1B3A6B;">
                {{ number_format($stats['revenus_mois'], 0, ',', ' ') }}
                <small style="font-size:12px;font-weight:400;color:#888;">XAF</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-4 rounded-3"
             style="background:#1B3A6B;">
            <div style="font-size:11px;font-weight:600;color:rgba(255,255,255,.6);text-transform:uppercase;
                        letter-spacing:.6px;margin-bottom:8px;">Revenus total</div>
            <div style="font-size:1.8rem;font-weight:800;color:#F5A623;">
                {{ number_format($stats['revenus_total'], 0, ',', ' ') }}
                <small style="font-size:12px;font-weight:400;color:rgba(255,255,255,.5);">XAF</small>
            </div>
        </div>
    </div>
</div>

{{-- Tableau abonnements --}}
<div class="rounded-3 overflow-hidden"
     style="background:#fff;border:1px solid #eee;box-shadow:0 2px 8px rgba(27,58,107,.05);">
    <table class="table mb-0">
        <thead>
            <tr style="background:#f8f9fb;">
                <th style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;border:none;padding:12px 16px;">
                    Utilisateur
                </th>
                <th style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;border:none;padding:12px 16px;">
                    Forfait
                </th>
                <th style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;border:none;padding:12px 16px;">
                    Période
                </th>
                <th style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;border:none;padding:12px 16px;">
                    Montant
                </th>
                <th style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;border:none;padding:12px 16px;">
                    Statut
                </th>
                <th style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;border:none;padding:12px 16px;text-align:right;">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($abonnements as $abo)
            @php $isActif = $abo->actif && $abo->fin_at >= now(); @endphp
            <tr>
                <td style="padding:14px 16px;vertical-align:middle;border-bottom:1px solid #f5f5f5;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;border-radius:50%;background:#1B3A6B;
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:11px;font-weight:700;color:#F5A623;flex-shrink:0;">
                            {{ strtoupper(substr($abo->user->first_name ?? '?', 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#1B3A6B;">
                                {{ $abo->user->first_name ?? 'Supprimé' }} {{ $abo->user->last_name ?? '' }}
                            </div>
                            <div style="font-size:11px;color:#888;">{{ $abo->user->email ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td style="padding:14px 16px;vertical-align:middle;border-bottom:1px solid #f5f5f5;">
                    <span style="font-size:13px;font-weight:600;color:#333;">
                        {{ ucfirst($abo->forfait) }}
                    </span>
                </td>
                <td style="padding:14px 16px;vertical-align:middle;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;">
                    {{ $abo->debut_at->format('d/m/Y') }} → {{ $abo->fin_at->format('d/m/Y') }}
                </td>
                <td style="padding:14px 16px;vertical-align:middle;border-bottom:1px solid #f5f5f5;font-size:13px;font-weight:600;color:#1B3A6B;">
                    {{ number_format($abo->montant, 0, ',', ' ') }} {{ $abo->devise }}
                </td>
                <td style="padding:14px 16px;vertical-align:middle;border-bottom:1px solid #f5f5f5;">
                    @if($isActif)
                    <span style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;
                                 background:rgba(28,200,138,.1);color:#0f6e56;">
                        <i class="bi bi-check-circle me-1"></i>Actif
                    </span>
                    @else
                    <span style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;
                                 background:rgba(226,75,74,.08);color:#a32d2d;">
                        <i class="bi bi-x-circle me-1"></i>Expiré
                    </span>
                    @endif
                </td>
                <td style="padding:14px 16px;vertical-align:middle;border-bottom:1px solid #f5f5f5;text-align:right;">
                    <a href="{{ route('admin.users.show', $abo->user_id) }}"
                       style="width:30px;height:30px;border-radius:7px;border:1px solid #e8e8e8;
                              background:#fff;display:inline-flex;align-items:center;justify-content:center;
                              color:#666;font-size:12px;text-decoration:none;">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5" style="color:#888;">
                    <i class="bi bi-credit-card" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px;"></i>
                    Aucun abonnement enregistré
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($abonnements->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $abonnements->links() }}
</div>
@endif

@endsection
