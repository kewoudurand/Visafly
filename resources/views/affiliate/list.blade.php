{{-- FILE: resources/views/affiliate/list.blade.php - VERSION ÉPURÉE --}}
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    {{-- Header --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 fw-bold" style="color: #1B3A6B;">👥 Mes Affiliés</h1>
                <p class="text-muted small mt-1">
                    Suivi de tous les utilisateurs que vous avez parrainés
                </p>
            </div>
            <a href="{{ route('affiliate.dashboard') }}" class="btn btn-outline-secondary">
                ← Retour au Dashboard
            </a>
        </div>
    </div>

    {{-- Stats rapides --}}
    @if($affiliates->count() > 0)
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-top: 4px solid #F5A623;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 2rem; color: #F5A623;" class="me-3">👥</div>
                            <div>
                                <p class="text-muted small mb-0">Total Parrainés</p>
                                <h3 class="mb-0" style="color: #1B3A6B;">{{ $affiliates->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-top: 4px solid #4CAF50;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 2rem; color: #4CAF50;" class="me-3">✅</div>
                            <div>
                                <p class="text-muted small mb-0">Commissions</p>
                                <h3 class="mb-0" style="color: #1B3A6B;">
                                    @php
                                        $total = $affiliates->sum(fn($r) => $r->status === 'completed' ? $r->commission : 0);
                                    @endphp
                                    {{ number_format($total, 0) }} F
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-top: 4px solid #2196F3;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 2rem; color: #2196F3;" class="me-3">⏳</div>
                            <div>
                                <p class="text-muted small mb-0">En Attente</p>
                                <h3 class="mb-0" style="color: #1B3A6B;">
                                    @php
                                        $pending = $affiliates->sum(fn($r) => $r->status === 'pending' ? $r->commission : 0);
                                    @endphp
                                    {{ number_format($pending, 0) }} F
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Tableau des affiliés --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom" style="border-bottom: 2px solid #F5A623 !important;">
            <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">📋 Liste de vos Affiliés</h5>
        </div>
        <div class="card-body p-0">
            @if($affiliates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border: none;">
                        <thead style="background-color: #f8f9fa;">
                            <tr style="border-bottom: 2px solid #F5A623;">
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">👤 Nom</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">📧 Email</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">📅 Inscription</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">💰 Commission</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">📊 Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affiliates as $referral)
                                <tr style="border-bottom: 1px solid #eee; transition: background-color 0.2s;">
                                    <td style="padding: 1.25rem; vertical-align: middle;">
                                        <div style="font-weight: 600; color: #1B3A6B;">
                                            {{ $referral->referred->first_name ?? $referral->referred->name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $referral->referred->last_name ?? '' }}
                                        </small>
                                    </td>
                                    <td style="padding: 1.25rem; vertical-align: middle;">
                                        <a href="mailto:{{ $referral->referred->email }}" 
                                           style="color: #F5A623; text-decoration: none;">
                                            {{ $referral->referred->email }}
                                        </a>
                                    </td>
                                    <td style="padding: 1.25rem; vertical-align: middle;">
                                        <small class="text-muted">
                                            {{ $referral->created_at->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td style="padding: 1.25rem; vertical-align: middle;">
                                        <span style="font-weight: 700; color: #4CAF50; font-size: 1.1rem;">
                                            {{ number_format($referral->commission, 0) }} F
                                        </span>
                                    </td>
                                    <td style="padding: 1.25rem; vertical-align: middle;">
                                        @if($referral->status === 'pending')
                                            <span class="badge" style="background-color: #FFA726; color: white;">
                                                ⏳ En attente
                                            </span>
                                        @elseif($referral->status === 'completed')
                                            <span class="badge" style="background-color: #4CAF50; color: white;">
                                                ✅ Complétée
                                            </span>
                                        @else
                                            <span class="badge" style="background-color: #2196F3; color: white;">
                                                🏦 Retiré
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4 pb-3">
                    {{ $affiliates->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;">📭</div>
                    <h5 style="color: #1B3A6B;">Aucun affilié pour le moment</h5>
                    <p class="text-muted mb-3">
                        Partagez votre lien d'affiliation pour commencer à parrainer!
                    </p>
                    <a href="{{ route('affiliate.dashboard') }}" class="btn btn-sm" 
                       style="background-color: #F5A623; color: white; border: none;">
                        Voir mon Lien d'Affiliation →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    tr:hover {
        background-color: #f8f9fa !important;
    }

    .table-responsive {
        border-radius: 0.5rem;
    }

    a {
        transition: color 0.2s;
    }

    a:hover {
        color: #E89A00 !important;
    }
</style>
@endsection

{{-- ════════════════════════════════════════════════════════════════ --}}

{{-- FILE: resources/views/affiliate/dashboard.blade.php - VERSION ÉPURÉE --}}
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    {{-- Header principal --}}
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2" style="color: #1B3A6B;">💰 Tableau de Bord d'Affiliation</h1>
        <p class="text-muted">Gérez vos parrainages et suivez vos commissions</p>
    </div>

    {{-- Lien d'affiliation premium --}}
    <div class="card border-0 shadow-sm mb-5" style="background: linear-gradient(135deg, #1B3A6B 0%, #2D5C9C 100%); color: white; border-radius: 0.75rem;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-3 fw-bold">🔗 Votre Lien d'Affiliation Unique</h5>
                    <div class="input-group input-group-lg">
                        <input 
                            type="text" 
                            class="form-control" 
                            id="affiliateLink"
                            value="{{ $stats['affiliate_link'] }}"
                            readonly
                            style="background-color: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.3);">
                        <button 
                            class="btn" 
                            type="button"
                            onclick="copyToClipboard('affiliateLink')"
                            style="background-color: #F5A623; color: white; border: none; font-weight: 600;">
                            📋 Copier
                        </button>
                    </div>
                    <small class="d-block mt-3 opacity-75">
                        Code: <code style="background-color: rgba(255,255,255,0.1); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">{{ $stats['referral_code'] }}</code>
                    </small>
                </div>
                <div class="col-md-4 text-center">
                    <div style="font-size: 3rem; opacity: 0.3;">🎯</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques principales --}}
    <div class="row mb-5">
        {{-- Parrainés --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #F5A623; border-radius: 0.75rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2 fw-500">👥 Total Parrainés</p>
                            <h2 class="mb-1" style="color: #1B3A6B; font-weight: 700;">
                                {{ $stats['referrals']['total'] }}
                            </h2>
                            <small class="text-success fw-600">
                                {{ $stats['referrals']['active'] }} actifs
                            </small>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.2;">👥</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Complétées --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #4CAF50; border-radius: 0.75rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2 fw-500">✅ Complétées</p>
                            <h2 class="mb-1" style="color: #4CAF50; font-weight: 700;">
                                {{ number_format($stats['commissions']['completed'], 0) }} F
                            </h2>
                            <small class="text-success">À disposition</small>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.2;">💚</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- En Attente --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #FFA726; border-radius: 0.75rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2 fw-500">⏳ En Attente</p>
                            <h2 class="mb-1" style="color: #FFA726; font-weight: 700;">
                                {{ number_format($stats['commissions']['pending'], 0) }} F
                            </h2>
                            <small class="text-warning">À valider</small>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.2;">⏳</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Solde --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1B3A6B 0%, #F5A623 100%); color: white; border-radius: 0.75rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-2 fw-500 opacity-75">💳 Solde Disponible</p>
                            <h2 class="mb-1" style="font-weight: 700;">
                                {{ number_format($stats['wallet']['balance'], 0) }} F
                            </h2>
                            <small class="opacity-75">Prêt à retirer</small>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">💰</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Détail des gains --}}
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0" style="border-bottom: 2px solid #F5A623;">
                    <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">📊 Détail de vos Gains</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 py-4">
                            <div style="color: #1B3A6B; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                                Total Gagné
                            </div>
                            <div style="color: #4CAF50; font-size: 2rem; font-weight: 700;">
                                {{ number_format($stats['wallet']['total_earned'], 0) }} F
                            </div>
                        </div>
                        <div class="col-md-4 py-4" style="border-left: 1px solid #eee; border-right: 1px solid #eee;">
                            <div style="color: #1B3A6B; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                                Total Retiré
                            </div>
                            <div style="color: #F5A623; font-size: 2rem; font-weight: 700;">
                                {{ number_format($stats['wallet']['total_withdrawn'], 0) }} F
                            </div>
                        </div>
                        <div class="col-md-4 py-4">
                            <div style="color: #1B3A6B; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                                Solde Net
                            </div>
                            <div style="color: #2196F3; font-size: 2rem; font-weight: 700;">
                                {{ number_format($stats['wallet']['total_earned'] - $stats['wallet']['total_withdrawn'], 0) }} F
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('affiliate.list') }}" class="btn btn-lg" 
                   style="background-color: #1B3A6B; color: white; border: none; font-weight: 600; border-radius: 0.5rem;">
                    👥 Mes Affiliés
                </a>
                <a href="{{ route('affiliate.withdraw.show-form') }}" class="btn btn-lg" 
                   style="background-color: #F5A623; color: white; border: none; font-weight: 600; border-radius: 0.5rem;">
                    💳 Retirer des Fonds
                </a>
                <a href="{{ route('affiliate.history') }}" class="btn btn-lg btn-outline-secondary"
                   style="font-weight: 600; border-radius: 0.5rem;">
                    📋 Historique
                </a>
            </div>
        </div>
    </div>

    {{-- Info Footer --}}
    <div class="card bg-light border-0 mt-5" style="border-radius: 0.75rem;">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3" style="color: #1B3A6B;">📖 Comment Ça Marche?</h6>
            <ul class="mb-0" style="color: #555;">
                <li>✅ Copiez votre lien d'affiliation ci-dessus</li>
                <li>✅ Partagez-le avec vos contacts (Email, WhatsApp, Facebook, etc)</li>
                <li>✅ Chaque inscription via votre lien = <strong>Commission</strong> pour vous</li>
                <li>✅ Les commissions sont validées après l'action de l'utilisateur parrainé</li>
            </ul>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        document.execCommand('copy');
        alert('✅ Lien copié au presse-papiers!');
    }
</script>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }

    .btn {
        transition: all 0.2s;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .fw-500 {
        font-weight: 500;
    }
</style>
@endsection