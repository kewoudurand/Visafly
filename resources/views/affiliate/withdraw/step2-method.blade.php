{{-- FILE: resources/views/affiliate/withdraw/step2-method.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="px-4 py-4 border-bottom bg-white">
                    <h4 class="fw-bold text-dark mb-1">
                        Demande de retrait
                    </h4>
                    <p class="text-muted small mb-0">
                        Étape 2 sur 3 — Choisissez votre moyen de paiement
                    </p>
                </div>

                <div class="card-body p-4">

                    {{-- Montant --}}
                    <div class="rounded-4 p-4 mb-4"
                         style="background:#f8fafc;border:1px solid #eef2f7;">
                        <div class="text-muted small mb-2">
                            Montant sélectionné
                        </div>

                        <h2 class="fw-bold mb-0" style="color:#198754;">
                            {{ number_format($amount, 0) }} F
                        </h2>
                    </div>

                    <p class="text-muted mb-4">
                        Sélectionnez le moyen de paiement qui vous convient.
                    </p>

                    {{-- Méthodes --}}
                    <div class="row g-4">
                        @foreach($methods as $key => $method)

                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 method-card">

                                <div class="card-body p-4 d-flex flex-column">

                                    <div class="mb-3">
                                        <h5 class="fw-bold text-dark mb-2">
                                            {{ $method['title'] }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            {{ $method['description'] }}
                                        </p>
                                    </div>

                                    <div class="rounded-4 p-3 mb-4"
                                         style="background:#f8fafc;border:1px solid #eef2f7;">

                                        <div class="small d-flex justify-content-between mb-2">
                                            <span class="text-muted">Délai</span>
                                            <strong>{{ $method['processing_time'] }}</strong>
                                        </div>

                                        <div class="small d-flex justify-content-between mb-2">
                                            <span class="text-muted">Frais</span>
                                            <strong>{{ $method['fees'] }}</strong>
                                        </div>

                                        <div class="small d-flex justify-content-between">
                                            <span class="text-muted">Limites</span>
                                            <strong>
                                                {{ number_format($method['min_amount'], 0) }}
                                                -
                                                {{ number_format($method['max_amount'], 0) }} F
                                            </strong>
                                        </div>

                                    </div>

                                    <div class="mt-auto d-grid gap-2">

                                        <a href="{{ route('affiliate.withdraw.method-details', $key) }}"
                                           class="btn btn-primary rounded-3 py-3 fw-semibold">
                                            Choisir {{ $method['title'] }}
                                        </a>

                                        <a href="{{ route('affiliate.withdraw.show-method', $key) }}"
                                           class="btn btn-light border rounded-3">
                                            Voir détails
                                        </a>

                                    </div>

                                </div>
                            </div>
                        </div>

                        @endforeach
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex flex-wrap gap-2 mt-4">

                        <a href="{{ route('affiliate.withdraw.show-form') }}"
                           class="btn btn-light border rounded-3 px-4">
                            Retour
                        </a>

                        <a href="{{ route('affiliate.dashboard') }}"
                           class="btn btn-outline-danger rounded-3 px-4">
                            Annuler
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
.method-card{
    transition:all .25s ease;
}

.method-card:hover{
    transform:translateY(-4px);
    box-shadow:0 1rem 2rem rgba(0,0,0,.08)!important;
}
</style>
@endsection