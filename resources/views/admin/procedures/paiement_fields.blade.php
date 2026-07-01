@php($p = $paiement ?? null)

<div class="row g-2">
    <div class="col-8">
        <label class="form-label">Montant</label>
        <input type="number" step="0.01" min="1" name="montant" class="form-control"
               value="{{ $p->montant ?? '' }}" required>
    </div>
    <div class="col-4">
        <label class="form-label">Devise</label>
        <input type="text" name="devise" class="form-control" value="{{ $p->devise ?? 'XAF' }}" required>
    </div>
</div>

<div class="mb-3 mt-2">
    <label class="form-label">Nom de la personne qui verse l'argent</label>
    <input type="text" name="nom_payeur" class="form-control"
           value="{{ $p->nom_payeur ?? '' }}" placeholder="Laisser vide si c'est le client lui-même">
</div>

<div class="row g-2">
    <div class="col-6">
        <label class="form-label">Mode de paiement</label>
        <select name="mode" class="form-select">
            <option value="especes" @selected(($p->mode ?? '')=='especes')>Espèces</option>
            <option value="virement" @selected(($p->mode ?? '')=='virement')>Virement</option>
            <option value="mobile_money" @selected(($p->mode ?? '')=='mobile_money')>Mobile Money</option>
            <option value="carte" @selected(($p->mode ?? '')=='carte')>Carte bancaire</option>
            <option value="autre" @selected(($p->mode ?? '')=='autre')>Autre</option>
        </select>
    </div>
    <div class="col-6">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select">
            <option value="recu" @selected(($p->statut ?? 'recu')=='recu')>Reçu</option>
            <option value="en_attente" @selected(($p->statut ?? '')=='en_attente')>En attente</option>
            <option value="annule" @selected(($p->statut ?? '')=='annule')>Annulé</option>
        </select>
    </div>
</div>

<div class="mb-3 mt-2">
    <label class="form-label">Date du versement</label>
    <input type="date" name="date_paiement" class="form-control"
           value="{{ $p->date_paiement?->format('Y-m-d') ?? now()->format('Y-m-d') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Référence (reçu / transaction)</label>
    <input type="text" name="reference" class="form-control" value="{{ $p->reference ?? '' }}">
</div>

<div class="mb-3">
    <label class="form-label">Note</label>
    <textarea name="note" class="form-control" rows="2">{{ $p->note ?? '' }}</textarea>
</div>