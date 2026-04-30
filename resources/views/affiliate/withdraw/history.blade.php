{{-- FILE: resources/views/affiliate/withdraw/history.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1>📋 Historique de mes Retraits</h1>
            <p class="text-muted">Suivi de vos demandes de retrait</p>
        </div>
    </div>

    {{-- Statistiques rapides --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6>⏳ En Attente</h6>
                    <h3 class="text-warning">
                        {{ $withdrawals->where('status', 'pending')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6>✅ Approuvés</h6>
                    <h3 class="text-success">
                        {{ $withdrawals->where('status', 'approved')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6>🏦 Complétés</h6>
                    <h3 class="text-info">
                        {{ $withdrawals->where('status', 'completed')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6>❌ Rejetés</h6>
                    <h3 class="text-danger">
                        {{ $withdrawals->where('status', 'failed')->count() }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Montant</th>
                            <th>Moyen</th>
                            <th>Statut</th>
                            <th>Date de Demande</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td><small>#{{ $withdrawal->id }}</small></td>
                                <td><strong class="text-info">{{ number_format($withdrawal->amount, 0) }} F</strong></td>
                                <td>
                                    @if($withdrawal->method === 'orange_money')
                                        🟠 Orange Money
                                    @elseif($withdrawal->method === 'mtn')
                                        🔴 MTN Money
                                    @elseif($withdrawal->method === 'bank_transfer')
                                        🏦 Virement Bancaire
                                    @else
                                        ❓ Autre
                                    @endif
                                </td>
                                <td>
                                    @if($withdrawal->status === 'pending')
                                        <span class="badge bg-warning">⏳ En Attente</span>
                                    @elseif($withdrawal->status === 'approved')
                                        <span class="badge bg-success">✅ Approuvé</span>
                                    @elseif($withdrawal->status === 'completed')
                                        <span class="badge bg-info">🏦 Complété</span>
                                    @elseif($withdrawal->status === 'failed')
                                        <span class="badge bg-danger">❌ Rejeté</span>
                                    @endif
                                </td>
                                <td><small>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    @if($withdrawal->status === 'pending')
                                        <form method="POST" action="{{ route('affiliate.withdraw.cancel', $withdrawal) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Annuler ce retrait?')">
                                                ❌ Annuler
                                            </button>
                                        </form>
                                    @elseif($withdrawal->status === 'failed' && $withdrawal->notes)
                                        <small class="text-danger">{{ $withdrawal->notes }}</small>
                                    @else
                                        <small class="text-muted">Aucune action</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">Aucun retrait pour le moment</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $withdrawals->links() }}
    </div>

    {{-- Boutons --}}
    <div class="mt-4">
        <a href="{{ route('affiliate.withdraw.show-form') }}" class="btn btn-primary btn-lg">
            💰 Demander un Retrait
        </a>
        <a href="{{ route('affiliate.dashboard') }}" class="btn btn-outline-secondary btn-lg">
            ← Retour au Dashboard
        </a>
    </div>
</div>
@endsection