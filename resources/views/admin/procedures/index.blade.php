@extends('layouts/dashboard')

@section('content')
<div class="container-fluid py-4" style="--marine:#1B3A6B; --or:#F5A623;">

    {{-- ── En-tête ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 fw-bold mb-0" style="color:var(--marine)">Paiements des procédures</h1>
        <button type="button" class="btn fw-semibold" style="background:var(--or); color:#fff"
                data-bs-toggle="modal" data-bs-target="#modalAttribuer">
            + Attribuer une procédure
        </button>
    </div>

    {{-- ── Stats ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total attributions</div>
                    <div class="h4 fw-bold mb-0" style="color:var(--marine)">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">En cours</div>
                    <div class="h4 fw-bold mb-0" style="color:var(--or)">{{ $stats['en_cours'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Montant total dû</div>
                    <div class="h5 fw-bold mb-0">{{ number_format($stats['montant_total'], 0, ',', ' ') }} XAF</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Montant total versé</div>
                    <div class="h5 fw-bold mb-0 text-success">{{ number_format($stats['montant_verse'], 0, ',', ' ') }} XAF</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filtres ── --}}
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small">Recherche (client / pays de destination)</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nom, email, pays...">
            </div>
            <div class="col-md-4">
                <label class="form-label small">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="en_cours" @selected(request('statut')=='en_cours')>En cours</option>
                    <option value="terminee" @selected(request('statut')=='terminee')>Terminée</option>
                    <option value="annulee" @selected(request('statut')=='annulee')>Annulée</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-secondary">Filtrer</button>
            </div>
        </div>
    </form>

    {{-- ── Tableau ── --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background:var(--marine); color:#fff">
                    <tr>
                        <th>Client (procédure)</th>
                        <th>Pays / Procédure</th>
                        <th>Montant total</th>
                        <th>Total versé</th>
                        <th>Reste à payer</th>
                        <th>Statut</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attributions as $cp)
                        @php
                            $totalVerse = $cp->totalVerse();
                            $reste = $cp->resteAPayer();
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $cp->client->name ?? '—' }}</div>
                                <div class="small text-muted">{{ $cp->client->email ?? '' }}</div>
                            </td>
                            <td>{{ $cp->intitule() }}</td>
                            <td>{{ number_format($cp->prix_total, 0, ',', ' ') }} {{ $cp->devise }}</td>
                            <td class="text-success fw-semibold">{{ number_format($totalVerse, 0, ',', ' ') }} {{ $cp->devise }}</td>
                            <td class="{{ $reste > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">
                                {{ number_format($reste, 0, ',', ' ') }} {{ $cp->devise }}
                            </td>
                            <td>
                                <span class="badge {{ $cp->statutClass() }}">{{ $cp->statutLabel() }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.procedures.show', $cp) }}" class="btn btn-sm btn-outline-primary">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucune procédure attribuée pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $attributions->links() }}
        </div>
    </div>
</div>

{{-- ── Modal : attribuer une procédure ── --}}
<div class="modal fade" id="modalAttribuer" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.procedures.store') }}" class="modal-content">
            @csrf
            <div class="modal-header" style="background:#1B3A6B; color:#fff">
                <h5 class="modal-title">Attribuer une procédure</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Client</label>
                    <select name="user_id" id="clientSelect" class="form-select" required>
                        <option value="">Sélectionner...</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}">{{ $c->first_name }} ({{ $c->email }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Message d'état de la recherche de consultation --}}
                <div id="consultationStatus" class="small mb-3" style="display:none;"></div>

                <input type="hidden" name="consultation_id" id="consultationIdInput">

                <div class="mb-3">
                    <label class="form-label">Pays de destination / Procédure</label>
                    <input type="text" name="destination_country" id="destinationInput" class="form-control"
                           placeholder="Sera rempli automatiquement si une consultation existe, sinon saisir manuellement" required>
                </div>

                <div class="row g-2">
                    <div class="col-8">
                        <label class="form-label">Prix total (modifiable)</label>
                        <input type="number" step="0.01" min="0" name="prix_total" id="prixTotalInput" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Devise</label>
                        <input type="text" name="devise" id="deviseInput" class="form-control" value="XAF" required>
                    </div>
                </div>
                <div class="mb-3 mt-2">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn fw-semibold" style="background:#F5A623; color:#fff">Attribuer</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const clientSelect      = document.getElementById('clientSelect');
        const statusBox         = document.getElementById('consultationStatus');
        const consultationInput = document.getElementById('consultationIdInput');
        const destinationInput  = document.getElementById('destinationInput');
        const prixTotalInput    = document.getElementById('prixTotalInput');
        const deviseInput       = document.getElementById('deviseInput');

        // Base URL générée côté serveur (sans l'id, on le remplace en JS)
        const baseUrl = "{{ url('/admin/procedures/client') }}";

        clientSelect?.addEventListener('change', function () {
            const userId = this.value;

            // Réinitialise les champs avant chaque recherche
            consultationInput.value = '';
            destinationInput.value  = '';
            prixTotalInput.value    = '';
            deviseInput.value       = 'XAF';
            statusBox.style.display = 'none';

            if (!userId) return;

            statusBox.style.display = 'block';
            statusBox.className = 'small mb-3 text-muted';
            statusBox.textContent = 'Recherche d\'une consultation en cours...';

            fetch(`${baseUrl}/${userId}/consultation`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(r => r.json())
                .then(data => {
                    if (data.found) {
                        consultationInput.value = data.consultation_id;
                        destinationInput.value  = data.destination_country || '';
                        prixTotalInput.value    = data.montant_total || '';
                        deviseInput.value       = data.devise || 'XAF';

                        statusBox.className = 'small mb-3 text-success';
                        statusBox.textContent = '✓ Consultation en cours trouvée — pays et montant pré-remplis automatiquement.';
                    } else {
                        statusBox.className = 'small mb-3 text-warning';
                        statusBox.textContent = '⚠ Aucune consultation en cours pour ce client — saisissez le pays et le montant manuellement.';
                    }
                })
                .catch(() => {
                    statusBox.className = 'small mb-3 text-danger';
                    statusBox.textContent = 'Erreur lors de la récupération de la consultation. Saisie manuelle possible.';
                });
        });
    })();
</script>
@endsection