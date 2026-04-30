{{-- FILE: resources/views/admin/affiliate/index.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    {{-- Header --}}
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2" style="color: #1B3A6B;">💰 Gestion des Affiliations</h1>
        <p class="text-muted">Contrôlez l'ensemble de votre système de parrainage</p>
    </div>

    {{-- Statistiques principales --}}
    <div class="row mb-5">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #1B3A6B; border-radius: 0.75rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2">👥 Utilisateurs Parrainés</p>
                            <h2 class="mb-1" style="color: #1B3A6B;">{{ $stats['affiliated_users'] }}</h2>
                            <small class="text-success fw-600">
                                {{ round(($stats['affiliated_users']/$stats['total_users'])*100, 1) }}% de {{ $stats['total_users'] }}
                            </small>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.15;">👥</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #FFA726; border-radius: 0.75rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2">⏳ Commissions En Attente</p>
                            <h2 class="mb-1" style="color: #FFA726;">{{ number_format($stats['pending_commissions'], 0) }} F</h2>
                            <small class="text-warning">À valider</small>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.15;">⏳</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #4CAF50; border-radius: 0.75rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2">✅ Commissions Complétées</p>
                            <h2 class="mb-1" style="color: #4CAF50;">{{ number_format($stats['completed_commissions'], 0) }} F</h2>
                            <small class="text-success">Validées</small>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.15;">✅</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1B3A6B 0%, #F5A623 100%); color: white; border-radius: 0.75rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-2 opacity-75 small">💳 Soldes Disponibles</p>
                            <h2 class="mb-1">{{ number_format($stats['total_wallets'], 0) }} F</h2>
                            <small class="opacity-75">Total wallets</small>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.25;">💳</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 0.75rem;">
        <div class="card-header bg-white border-0" style="border-bottom: 2px solid #F5A623;">
            <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">⚡ Actions Rapides</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.affiliate.referrals.pending') }}" class="btn" 
                   style="background-color: #1B3A6B; color: white; font-weight: 600; border-radius: 0.5rem;">
                    📋 Parrainages En Attente
                </a>
                <form method="POST" action="{{ route('admin.affiliate.referrals.complete-all') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn" 
                            style="background-color: #4CAF50; color: white; font-weight: 600; border-radius: 0.5rem;"
                            onclick="return confirm('✅ Valider TOUS les parrainages en attente?')">
                        ✅ Compléter Tous
                    </button>
                </form>
                <a href="{{ route('admin.affiliate.affiliates.list') }}" class="btn" 
                   style="background-color: #2196F3; color: white; font-weight: 600; border-radius: 0.5rem;">
                    👥 Gestion des Affiliés
                </a>
                <a href="{{ route('admin.affiliate.withdrawals') }}" class="btn" 
                   style="background-color: #FF9800; color: white; font-weight: 600; border-radius: 0.5rem;">
                    🏦 Retraits
                </a>
                <a href="{{ route('admin.affiliate.export') }}" class="btn btn-outline-secondary" style="font-weight: 600; border-radius: 0.5rem;">
                    📊 Exporter CSV
                </a>
            </div>
        </div>
    </div>

    {{-- Top affiliés & Parrainages récents --}}
    <div class="row">
        {{-- Top 10 Affiliés --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0" style="border-bottom: 2px solid #F5A623;">
                    <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">🏆 Top 10 Affiliés</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topAffiliates as $index => $user)
                            <div class="list-group-item px-4 py-3" style="border-bottom: 1px solid #eee;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3" style="min-width: 40px;">
                                            @if($index === 0)
                                                <span style="font-size: 1.5rem;">🥇</span>
                                            @elseif($index === 1)
                                                <span style="font-size: 1.5rem;">🥈</span>
                                            @elseif($index === 2)
                                                <span style="font-size: 1.5rem;">🥉</span>
                                            @else
                                                <span style="color: #1B3A6B; font-weight: 700; font-size: 1.2rem;">#{{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold" style="color: #1B3A6B;">{{ $user->first_name ?? $user->name }}</p>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-0"><span class="badge" style="background-color: #1B3A6B;">{{ $user->referrals_count }}</span></p>
                                        <small class="text-muted">parrainés</small>
                                    </div>
                                </div>
                                <a href="{{ route('admin.affiliate.affiliates.detail', $user) }}" class="btn btn-sm btn-link mt-2">
                                    Voir le détail →
                                </a>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                Aucun affilié
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Parrainages récents --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0" style="border-bottom: 2px solid #F5A623;">
                    <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">📅 Parrainages Récents</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentReferrals as $referral)
                            <div class="list-group-item px-4 py-3" style="border-bottom: 1px solid #eee;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1 fw-bold" style="color: #1B3A6B;">
                                            {{ $referral->referrer->first_name ?? $referral->referrer->name }} 
                                            <small class="text-muted">→</small>
                                            {{ $referral->referred->first_name ?? $referral->referred->name }}
                                        </p>
                                        <small class="text-muted">{{ $referral->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="mb-1" style="color: #4CAF50;">{{ number_format($referral->commission, 0) }} F</h6>
                                        @if($referral->status === 'pending')
                                            <span class="badge" style="background-color: #FFA726;">⏳ Attente</span>
                                        @elseif($referral->status === 'completed')
                                            <span class="badge" style="background-color: #4CAF50;">✅ Complétée</span>
                                        @else
                                            <span class="badge" style="background-color: #2196F3;">🏦 Retiré</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                Aucun parrainage
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection

