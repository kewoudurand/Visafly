@extends('layouts.dashboard')

@push('styles')
<style>
  /* ════════════════════════════════════════════════════════════
    ADMIN — TABLEAU SUIVI ÉTUDIANTS
  ════════════════════════════════════════════════════════════ */

  /* KPIs */
  .kpi-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
  @media(max-width:900px){.kpi-row{grid-template-columns:repeat(2,1fr);}}
  @media(max-width:480px){.kpi-row{grid-template-columns:repeat(2,1fr);}}

  .kpi{background:#fff;border-radius:14px;border:1px solid #eee;padding:18px 20px;
      position:relative;overflow:hidden;box-shadow:0 2px 8px rgba(27,58,107,.05);}
  .kpi::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
              background:var(--c,#1B3A6B);}
  .kpi-n{font-size:2rem;font-weight:800;color:var(--c,#1B3A6B);line-height:1;margin-bottom:4px;}
  .kpi-l{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}

  @media(max-width:768px){
    .hide-mobile{display:none!important;}
    .prog-table td,.prog-table th{padding:10px 10px;}
  }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4">📊 Tableau de Bord d'Affiliation</h1>
        </div>
    </div>

    {{-- Actions --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <a href="{{ route('affiliate.list') }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;
                background:#1B3A6B;color:#fff;border-radius:20px;font-size:13px;
                font-weight:700;text-decoration:none;">
                    👥 Mes Affiliés
                </a>
                <a href="{{ route('affiliate.history') }}" class="mx-2" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;
                background:#F5A623;color:#fff;border-radius:20px;font-size:13px;
                font-weight:700;text-decoration:none;">
                    📋 Historique
                </a>
                @if($stats['wallet']['amount'] > 0)
                    {{-- <button 
                        class="btn btn-success" 
                        data-bs-toggle="modal" 
                        data-bs-target="#withdrawModal">
                        🏦 Retirer des Fonds
                    </button> --}}
                    <a href="{{ route('affiliate.withdraw.show-form') }}" class="mx-2" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;
                background:#23f562;color:#fff;border-radius:20px;font-size:13px;
                font-weight:700;text-decoration:none;">
                        🏦 Retirer des Fonds
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Lien d'affiliation --}}
    <div class="card mb-4" style="border-left: 4px solid #F5A623;">
        <div class="card-body">
            <h5 class="card-title">🔗 Votre Lien d'Affiliation</h5>
            <div class="input-group">
                <input 
                    type="text" 
                    class="form-control" 
                    id="affiliateLink"
                    value="{{ $stats['affiliate_link'] }}"
                    readonly>
                <button 
                    class="btn btn-outline-secondary" 
                    type="button"
                    onclick="copyToClipboard('affiliateLink')">
                    📋 Copier
                </button>
            </div>
            <small class="text-muted d-block mt-2">Code: <code>{{ $stats['referral_code'] }}</code></small>
        </div>
    </div>

    {{-- Statistiques Principales --}}
    <div class="kpi-row">
        {{-- Parrainage --}}
        <div class="kpi" style="--c:#1B3A6B;">
            <div class="kpi-l">👥 Total Parrainés</div>
            <div class="kpi-n">                    
                <h2 class="text-primary">{{ $stats['referrals']['total'] }}</h2>
            </div>
            <small class="text-success">{{ $stats['referrals']['active'] }} actifs</small>
        </div>
        {{-- Commissions Complétées --}}
        <div class="kpi" style="--c:#1cc88a;">
            <div class="kpi-l">✅ Complétées</div>
            <div class="kpi-n"><h2 class="text-success">{{ number_format($stats['commissions']['completed'], 0) }} F</h2></div>
            <small>{{ $stats['commissions']['completed'] > 0 ? '💰 À disposition' : '' }}</small>
        </div>
        {{-- Commissions En Attente --}}
        <div class="kpi" style="--c:#F5A623;">
            <div class="kpi-l">⏳ En Attente</div>
            <div class="kpi-n"><h2 class="text-warning">{{ number_format($stats['commissions']['pending'], 0) }} F</h2></div>
            <small>À valider</small>
        </div>
        {{-- Solde Wallet --}}
        <div class="kpi" style="--c:#7F77DD;">
            <div class="kpi-l">💳 Solde</div>
            <div class="kpi-n"><h2>{{ number_format($stats['wallet']['amount'], 0) }} F</h2></div>
            <small>Disponible</small>
        </div>
    </div>

    {{-- Détail des Gains --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-bold">💰 Détail de vos Gains</h5>
                </div>

                <div class="card-body p-4">
                    <div class="row text-center g-0">

                        <div class="col-md-4 pb-3 pb-md-0 border-end">
                            <h6 class="text-muted mb-2">Total Gagné</h6>
                            <h3 class="text-primary fw-bold">
                                {{ number_format($stats['wallet']['total_earned'], 0) }} F
                            </h3>
                        </div>

                        <div class="col-md-4 pb-3 pb-md-0 border-end">
                            <h6 class="text-muted mb-2">Total Retiré</h6>
                            <h3 class="text-danger fw-bold">
                                {{ number_format($stats['wallet']['total_withdrawn'], 0) }} F
                            </h3>
                        </div>

                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Solde Net</h6>
                            <h3 class="text-success fw-bold">
                                {{ number_format(
                                    $stats['wallet']['total_earned'] - $stats['wallet']['total_withdrawn'],
                                    0
                                ) }} F
                            </h3>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    
    {{-- Utilisation du Lien --}}
    <div class="card col-lg-8 mt-5 bg-light">
        <div class="card-body">
            <h5>📖 Comment Partager?</h5>
            <ul class="mb-0">
                <li>Copiez votre lien d'affiliation ci-dessus</li>
                <li>Partagez-le avec vos contacts (Email, WhatsApp, Facebook, etc)</li>
                <li>Chaque inscription via votre lien = <strong>Commission</strong> pour vous</li>
                <li>Les commissions sont validées après l'action de l'utilisateur parrainé</li>
            </ul>
        </div>
    </div>
</div>

{{-- Modal de Retrait --}}
<div class="modal fade" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Retirer des Fonds</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="withdrawForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Montant à retirer</label>
                        <div class="input-group">
                            <input 
                                type="number" 
                                class="form-control" 
                                id="withdrawAmount"
                                placeholder="Montant"
                                step="1000"
                                max="{{ $stats['wallet']['amount'] }}"
                                required>
                            <span class="input-group-text">F</span>
                        </div>
                        <div class="input-group">
                            <label>Mode de paiement</label>
                            <select name="method" id="paymentMethod" class="form-control" onchange="toggleInstructions()">
                                <option value="mtn_money">MTN Mobile Money</option>
                                <option value="orange_money">Orange Money</option>
                                <option value="bank_transfer">Virement Bancaire</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label id="detailLabel">Numéro de téléphone</label>
                            <input type="text" name="payment_details" class="form-control" placeholder="6xx xxx xxx" required>
                        </div>

                        <div id="orangeInstructions" class="alert alert-info d-none">
                            <strong>Instructions Orange Money :</strong>
                            <p>Après avoir validé, composez le <strong>#150*50#</strong> sur votre téléphone pour valider le retrait avec votre code secret.</p>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Solde disponible: {{ number_format($stats['wallet']['amount'], 0) }} F
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Confirmer le Retrait</button>
                </div>
            </form>
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

    function toggleInstructions() {
        const method = document.getElementById('paymentMethod').value;
        const instructions = document.getElementById('orangeInstructions');
        const label = document.getElementById('detailLabel');

        // Afficher instructions si Orange
        if (method === 'orange_money') {
            instructions.classList.remove('d-none');
        } else {
            instructions.classList.add('d-none');
        }

        // Changer le label si virement bancaire
        label.innerText = (method === 'bank_transfer') ? "RIB / Informations Bancaires" : "Numéro de téléphone";
    }

    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const amount = document.getElementById('withdrawAmount').value;

        fetch('{{ route("affiliate.withdraw") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('✅ Retrait effectué avec succès!');
                location.reload();
            } else {
                alert('❌ Erreur: ' + data.message);
            }
        });
    });
</script>

@endsection