{{-- ════════════════════════════════════════════════════════════════ --}}

{{-- FILE: resources/views/admin/affiliate/withdrawals.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    {{-- Header --}}
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2" style="color: #1B3A6B;">🏦 Gestion des Retraits</h1>
    </div>

    {{-- Statistiques --}}
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #4CAF50; border-radius: 0.75rem;">
                <div class="card-body">
                    <p class="text-muted small mb-2">Total Retiré</p>
                    <h2 style="color: #4CAF50;">{{ number_format($totalWithdrawn, 0) }} F</h2>
                    <small class="text-success">Toutes les transactions</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #FFA726; border-radius: 0.75rem;">
                <div class="card-body">
                    <p class="text-muted small mb-2">Encore à Retirer</p>
                    <h2 style="color: #FFA726;">{{ number_format($pendingWithdraw, 0) }} F</h2>
                    <small class="text-warning">Soldes disponibles</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    @if($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-left: 5px solid #4CAF50;">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tableau des retraits --}}
    <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
        <div class="card-header bg-white border-0" style="border-bottom: 2px solid #F5A623;">
            <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">📋 Transactions Retirées</h5>
        </div>
        <div class="card-body p-0">
            @if($withdrawals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr style="border-bottom: 2px solid #F5A623;">
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Affilié</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Email</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">💰 Montant</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Moyen</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Numéro</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">📅 Date</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 1.25rem; font-weight: 600; color: #1B3A6B;">
                                        {{ $withdrawal->user->first_name ?? $withdrawal->user->name }}
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <a href="mailto:{{ $withdrawal->user->email }}" style="color: #F5A623;">
                                            {{ $withdrawal->user->email }}
                                        </a>
                                    </td>
                                    <td style="padding: 1.25rem; color: #2196F3; font-weight: 700; font-size: 1.1rem;">
                                        {{ number_format($withdrawal->amount, 0) }} F
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        @if($withdrawal->method === 'orange_money')
                                            🟠 Orange Money
                                        @elseif($withdrawal->method === 'mtn')
                                            🔴 MTN Money
                                        @elseif($withdrawal->method === 'bank_transfer')
                                            🏦 Virement
                                        @else
                                            ❓ Autre
                                        @endif
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        {{ $withdrawal->reference ?? 'N/A' }} 
                                    </td>
                                    <td style="padding: 1.25rem; color: #999;">
                                        <small>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        @if($withdrawal->status === 'pending')
                                            {{-- Bouton Actif si en attente --}}
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $withdrawal->id }}"
                                                    style="font-weight: 600;">
                                                ✅ Approuver
                                            </button>
                                        @else
                                            {{-- Bouton Grisé si déjà terminé --}}
                                            <button class="btn btn-sm btn-secondary disabled" style="font-weight: 600; opacity: 0.7;">
                                                🏁 Terminé
                                            </button>
                                        @endif

                                        {{-- Le Modal reste ici, il ne s'ouvrira que si le bouton "Approuver" est cliquable --}}
                                        @if($withdrawal->status === 'pending')
                                            <div class="modal fade" id="approveModal{{ $withdrawal->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('admin.affiliate.withdrawals.approve') }}">
                                                            @csrf
                                                            <input type="hidden" name="withdrawal_id" value="{{ $withdrawal->id }}">
                                                            
                                                            <div class="modal-header" style="border-bottom: 2px solid #F5A623;">
                                                                <h5 style="color: #1B3A6B;">✅ Approuver le retrait</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert" style="background-color: #f0f0f0; border-left: 5px solid #2196F3;">
                                                                    <p class="mb-1"><strong style="color: #1B3A6B;">{{ $withdrawal->user->first_name ?? $withdrawal->user->name }}</strong></p>
                                                                    <p class="mb-0"><strong style="color: #2196F3; font-size: 1.2rem;">{{ number_format($withdrawal->amount, 0) }} F</strong></p>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold" style="color: #1B3A6B;">Moyen de paiement</label>
                                                                    <select name="method" class="form-control" required style="border-color: #F5A623;">
                                                                        <option value="">-- Sélectionner --</option>
                                                                        <option value="orange_money" {{ $withdrawal->method == 'orange_money' ? 'selected' : '' }}>🟠 Orange Money</option>
                                                                        <option value="mtn" {{ $withdrawal->method == 'mtn' ? 'selected' : '' }}>🔴 MTN Mobile Money</option>
                                                                        <option value="bank_transfer" {{ $withdrawal->method == 'bank_transfer' ? 'selected' : '' }}>🏦 Virement Bancaire</option>
                                                                        <option value="other" {{ $withdrawal->method == 'other' ? 'selected' : '' }}>❓ Autre</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold" style="color: #1B3A6B;">Référence de transaction</label>
                                                                    <input type="text" name="reference" class="form-control" 
                                                                        value="{{ $withdrawal->reference }}"
                                                                        placeholder="Ex: Numéro de transaction ou ID" 
                                                                        required style="border-color: #F5A623;">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-success" style="font-weight: 600;">✅ Confirmer l'envoi</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center p-4">
                    {{ $withdrawals->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;">✅</div>
                    <h5 style="color: #1B3A6B;">Aucun retrait en attente!</h5>
                    <p class="text-muted">Tous les retraits ont été traités</p>
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