{{-- ════════════════════════════════════════════════════════════════ --}}

{{-- FILE: resources/views/admin/affiliate/affiliates-list.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    {{-- Header --}}
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2" style="color: #1B3A6B;">👥 Gestion des Affiliés</h1>
        <p class="text-muted">{{ $affiliates->total() }} affilié(s) actif(s)</p>
    </div>

    {{-- Messages --}}
    @if($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-left: 5px solid #4CAF50;">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tableau refactorisé --}}
    <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
        <div class="card-header bg-white border-0" style="border-bottom: 2px solid #F5A623;">
            <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">📋 Liste des Affiliés</h5>
        </div>
        <div class="card-body p-0">
            @if($affiliates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border: none;">
                        <thead style="background-color: #f8f9fa;">
                            <tr style="border-bottom: 2px solid #F5A623;">
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Nom</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Email</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Code</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">👥 Parrainés</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">✅ Commissions</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">💳 Solde</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Status</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affiliates as $affiliate)
                                <tr style="border-bottom: 1px solid #eee; transition: background-color 0.2s;">
                                    <td style="padding: 1.25rem;">
                                        <div style="font-weight: 600; color: #1B3A6B;">
                                            {{ $affiliate->first_name ?? $affiliate->name }} {{ $affiliate->last_name ?? '' }}
                                        </div>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <a href="mailto:{{ $affiliate->email }}" style="color: #F5A623; text-decoration: none;">
                                            {{ $affiliate->email }}
                                        </a>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <code style="background-color: #f0f0f0; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                            {{ $affiliate->referral_code }}
                                        </code>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <span style="background-color: #1B3A6B; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-weight: 600; font-size: 0.9rem;">
                                            {{ $affiliate->referrals_count }}
                                        </span>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        @php
                                            $completed = $affiliate->affiliateActivity()
                                                                ->where('status', 'completed')
                                                                ->sum('commission');
                                        @endphp
                                        <span style="color: #4CAF50; font-weight: 700;">{{ number_format($completed, 0) }} F</span>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <span style="color: #2196F3; font-weight: 700;">{{ number_format($affiliate->affiliateWallet->balance, 0) }} F</span>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        @if($affiliate->is_active_affiliate)
                                            <span class="badge" style="background-color: #4CAF50;">✅ Actif</span>
                                        @else
                                            <span class="badge" style="background-color: #f44336;">🚫 Inactif</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <a href="{{ route('admin.affiliate.affiliates.detail', $affiliate) }}" 
                                           class="btn btn-sm" style="background-color: #1B3A6B; color: white; font-weight: 600; border-radius: 0.25rem;">
                                            👁️ Détail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center p-4">
                    {{ $affiliates->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;">👥</div>
                    <h5 style="color: #1B3A6B;">Aucun affilié trouvé</h5>
                    <p class="text-muted">Commencez à inviter des utilisateurs!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    tr:hover {
        background-color: #f8f9fa !important;
    }

    .btn {
        transition: all 0.2s;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection
