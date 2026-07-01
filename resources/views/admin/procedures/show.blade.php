@extends('layouts/dashboard')

@section('content')
<div class="container-fluid py-4" style="--marine:#1B3A6B; --or:#F5A623;">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.procedures.index') }}" class="small text-decoration-none">&larr; Retour à la liste</a>
            <h1 class="h3 fw-bold mb-0" style="color:var(--marine)">
                {{ $clientProcedure->procedure->nom ?? 'Procédure' }}
            </h1>
        </div>
        <span class="badge fs-6 {{ $clientProcedure->statutClass() }}">{{ $clientProcedure->statutLabel() }}</span>
    </div>

    <div class="row g-4">
        {{-- ── Colonne gauche : infos + paiements ── --}}
        <div class="col-lg-8">

            {{-- Infos client / procédure --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold" style="background:var(--marine); color:#fff">
                    Informations
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Client (fait la procédure)</div>
                            <div class="fw-semibold">{{ $clientProcedure->client->name ?? '—' }}</div>
                            <div class="small text-muted">{{ $clientProcedure->client->email ?? '' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Attribuée par</div>
                            <div class="fw-semibold">{{ $clientProcedure->assignePar->name ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Date de début</div>
                            <div class="fw-semibold">{{ $clientProcedure->date_debut?->format('d/m/Y') ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Montant total de la procédure</div>
                            <div class="fw-semibold fs-5" style="color:var(--marine)">
                                {{ number_format($clientProcedure->prix_total, 0, ',', ' ') }} {{ $clientProcedure->devise }}
                            </div>
                        </div>
                        @if($clientProcedure->note)
                            <div class="col-12">
                                <div class="text-muted small">Note</div>
                                <div>{{ $clientProcedure->note }}</div>
                            </div>
                        @endif
                    </div>

                    <hr>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalModifier">
                        Modifier (prix / statut)
                    </button>
                </div>
            </div>

            {{-- Liste des versements --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" style="background:var(--marine); color:#fff">
                    <span class="fw-semibold">Versements</span>
                    <button type="button" class="btn btn-sm fw-semibold" style="background:var(--or); color:#fff"
                            data-bs-toggle="modal" data-bs-target="#modalAjouterPaiement">
                        + Ajouter un versement
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Versé par</th>
                                <th>Montant</th>
                                <th>Mode</th>
                                <th>Enregistré par</th>
                                <th>Statut</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientProcedure->paiements as $paiement)
                                <tr>
                                    <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                                    <td>{{ $paiement->nomPayeurAffiche() }}</td>
                                    <td class="fw-semibold">{{ $paiement->montantFormate() }}</td>
                                    <td>{{ $paiement->modeLabel() }}</td>
                                    <td>{{ $paiement->enregistrePar->name ?? '—' }}</td>
                                    <td><span class="badge {{ $paiement->statutClass() }}">{{ $paiement->statutLabel() }}</span></td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal" data-bs-target="#modalEditPaiement{{ $paiement->id }}">
                                            Modifier
                                        </button>
                                        <form action="{{ route('admin.procedures.paiements.destroy', [$clientProcedure, $paiement]) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer ce versement ?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Suppr.</button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Modal édition de ce versement --}}
                                <div class="modal fade" id="modalEditPaiement{{ $paiement->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('admin.procedures.paiements.update', [$clientProcedure, $paiement]) }}" class="modal-content">
                                            @csrf @method('PUT')
                                            <div class="modal-header" style="background:#1B3A6B; color:#fff">
                                                <h5 class="modal-title">Modifier le versement</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @include('admin.procedures._paiement_fields', ['paiement' => $paiement])
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn fw-semibold" style="background:#F5A623; color:#fff">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">Aucun versement enregistré.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Colonne droite : résumé financier ── --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header fw-semibold" style="background:var(--marine); color:#fff">
                    Résumé financier
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Montant total</span>
                        <span class="fw-semibold">{{ number_format($clientProcedure->prix_total, 0, ',', ' ') }} {{ $clientProcedure->devise }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total versé</span>
                        <span class="fw-semibold text-success">{{ number_format($totalVerse, 0, ',', ' ') }} {{ $clientProcedure->devise }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Reste à payer</span>
                        <span class="fw-semibold {{ $resteAPayer > 0 ? 'text-danger' : 'text-muted' }}">
                            {{ number_format($resteAPayer, 0, ',', ' ') }} {{ $clientProcedure->devise }}
                        </span>
                    </div>

                    <div class="progress" style="height:10px;">
                        <div class="progress-bar" role="progressbar" style="width:{{ $pourcentagePaye }}%; background:var(--or);"></div>
                    </div>
                    <div class="text-center small text-muted mt-1">{{ $pourcentagePaye }}% payé</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal : ajouter un versement ── --}}
<div class="modal fade" id="modalAjouterPaiement" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.procedures.paiements.store', $clientProcedure) }}" class="modal-content">
            @csrf
            <div class="modal-header" style="background:#1B3A6B; color:#fff">
                <h5 class="modal-title">Ajouter un versement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('admin.procedures._paiement_fields')
            </div>
            <div class="modal-footer">
                <button class="btn fw-semibold" style="background:#F5A623; color:#fff">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal : modifier l'attribution (prix / statut) ── --}}
<div class="modal fade" id="modalModifier" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.procedures.update', $clientProcedure) }}" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header" style="background:#1B3A6B; color:#fff">
                <h5 class="modal-title">Modifier la procédure</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-8">
                        <label class="form-label">Prix total</label>
                        <input type="number" step="0.01" min="0" name="prix_total" class="form-control"
                               value="{{ $clientProcedure->prix_total }}" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Devise</label>
                        <input type="text" name="devise" class="form-control" value="{{ $clientProcedure->devise }}" required>
                    </div>
                </div>
                <div class="mb-3 mt-2">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="en_cours" @selected($clientProcedure->statut=='en_cours')>En cours</option>
                        <option value="terminee" @selected($clientProcedure->statut=='terminee')>Terminée</option>
                        <option value="annulee" @selected($clientProcedure->statut=='annulee')>Annulée</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" class="form-control"
                           value="{{ $clientProcedure->date_debut?->format('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-control" rows="2">{{ $clientProcedure->note }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn fw-semibold" style="background:#F5A623; color:#fff">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection