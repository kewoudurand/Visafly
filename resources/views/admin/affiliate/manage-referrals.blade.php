
{{-- ════════════════════════════════════════════════════════════════ --}}

{{-- FILE: resources/views/admin/affiliate/manage-referrals.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    {{-- Header --}}
    <div class="mb-5 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bold mb-1" style="color: #1B3A6B;">📋 Parrainages de {{ $user->first_name ?? $user->name }}</h1>
        </div>
        <a href="{{ route('admin.affiliate.affiliates.list') }}" class="btn btn-outline-secondary" style="font-weight: 600;">
            ← Retour
        </a>
    </div>

    {{-- Stats rapides --}}
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #1B3A6B; border-radius: 0.75rem;">
                <div class="card-body">
                    <p class="text-muted small mb-2">Total</p>
                    <h2 style="color: #1B3A6B;">{{ $referrals->total() }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #FFA726; border-radius: 0.75rem;">
                <div class="card-body">
                    <p class="text-muted small mb-2">⏳ En Attente</p>
                    <h2 style="color: #FFA726;">
                        @php
                            $pending = $user->affiliateActivity()->where('status', 'pending')->count();
                        @endphp
                        {{ $pending }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #4CAF50; border-radius: 0.75rem;">
                <div class="card-body">
                    <p class="text-muted small mb-2">✅ Complétés</p>
                    <h2 style="color: #4CAF50;">
                        @php
                            $completed = $user->affiliateActivity()->where('status', 'completed')->count();
                        @endphp
                        {{ $completed }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #2196F3; border-radius: 0.75rem;">
                <div class="card-body">
                    <p class="text-muted small mb-2">🏦 Retirés</p>
                    <h2 style="color: #2196F3;">
                        @php
                            $withdrawn = $user->affiliateActivity()->where('status', 'withdrawn')->count();
                        @endphp
                        {{ $withdrawn }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
        <div class="card-header bg-white border-0" style="border-bottom: 2px solid #F5A623;">
            <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">Détail des Parrainages</h5>
        </div>
        <div class="card-body p-0">
            @if($referrals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr style="border-bottom: 2px solid #F5A623;">
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">#</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Parrainé</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Email</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Montant</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Status</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Date</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referrals as $referral)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 1.25rem;"><small style="color: #999;">#{{ $referral->id }}</small></td>
                                    <td style="padding: 1.25rem; font-weight: 600; color: #1B3A6B;">
                                        {{ $referral->referred->first_name ?? $referral->referred->name }}
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <small><a href="mailto:{{ $referral->referred->email }}" style="color: #F5A623;">
                                            {{ $referral->referred->email }}
                                        </a></small>
                                    </td>
                                    <td style="padding: 1.25rem; color: #FFA726; font-weight: 700;">
                                        {{ number_format($referral->commission, 0) }} F
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        @if($referral->status === 'pending')
                                            <span class="badge" style="background-color: #FFA726;">⏳ Attente</span>
                                        @elseif($referral->status === 'completed')
                                            <span class="badge" style="background-color: #4CAF50;">✅ Complétée</span>
                                        @elseif($referral->status === 'withdrawn')
                                            <span class="badge" style="background-color: #2196F3;">🏦 Retiré</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1.25rem; color: #999;">
                                        <small>{{ $referral->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        @if($referral->status === 'pending')
                                            <form method="POST" action="{{ route('admin.affiliate.referrals.complete', $referral) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" style="font-weight: 600;">✅</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center p-4">
                    {{ $referrals->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">Aucun parrainage</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    tr:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endsection