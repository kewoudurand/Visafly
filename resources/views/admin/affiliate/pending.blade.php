{{-- FILE: resources/views/admin/affiliate/pending.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    {{-- Header --}}
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2" style="color: #1B3A6B;">📋 Parrainages En Attente</h1>
        <p class="text-muted">{{ $referrals->total() }} parrainage(s) en attente de validation</p>
    </div>

    {{-- Messages --}}
    @if($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-left: 5px solid #4CAF50;">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tableau --}}
    <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
        <div class="card-header bg-white border-0" style="border-bottom: 2px solid #FFA726;">
            <h5 class="mb-0 fw-bold" style="color: #1B3A6B;">⚠️ À Valider</h5>
        </div>
        <div class="card-body p-0">
            @if($referrals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr style="border-bottom: 2px solid #FFA726;">
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">#</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Parrain</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Code</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Parrainé</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">💰 Montant</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">📅 Date</th>
                                <th style="color: #1B3A6B; font-weight: 600; padding: 1.25rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referrals as $referral)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 1.25rem; color: #999;"><small>#{{ $referral->id }}</small></td>
                                    <td style="padding: 1.25rem;">
                                        <strong style="color: #1B3A6B;">{{ $referral->referrer->first_name ?? $referral->referrer->name }}</strong><br>
                                        <small class="text-muted">{{ $referral->referrer->email }}</small>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <code style="background-color: #f0f0f0; padding: 0.25rem 0.5rem;">{{ $referral->referrer->referral_code }}</code>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <small style="color: #1B3A6B; font-weight: 600;">{{ $referral->referred->first_name ?? $referral->referred->name }}</small>
                                    </td>
                                    <td style="padding: 1.25rem; color: #FFA726; font-weight: 700; font-size: 1.1rem;">
                                        {{ number_format($referral->commission, 0) }} F
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <small class="text-muted">{{ $referral->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <form method="POST" action="{{ route('admin.affiliate.referrals.complete', $referral) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background-color: #4CAF50; color: white; font-weight: 600;">
                                                ✅ Compléter
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $referral->id }}" style="font-weight: 600;">
                                            ❌ Rejeter
                                        </button>

                                        {{-- Modal Rejet --}}
                                        <div class="modal fade" id="rejectModal{{ $referral->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.affiliate.referrals.reject', $referral) }}">
                                                        @csrf
                                                        <div class="modal-header" style="border-bottom: 2px solid #F5A623;">
                                                            <h5 style="color: #1B3A6B;">❌ Rejeter le parrainage</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold" style="color: #1B3A6B;">Raison du rejet</label>
                                                                <textarea name="reason" class="form-control" rows="4" required 
                                                                          style="border-color: #F5A623;"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-danger" style="font-weight: 600;">Rejeter</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
                    <div style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;">✅</div>
                    <h5 style="color: #1B3A6B;">Aucun parrainage en attente!</h5>
                    <p class="text-muted">Tous les parrainages ont été validés</p>
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


