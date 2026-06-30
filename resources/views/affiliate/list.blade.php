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