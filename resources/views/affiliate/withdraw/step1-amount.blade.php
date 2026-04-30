{{-- FILE: resources/views/affiliate/withdraw/step1-amount.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="px-4 py-4 border-bottom bg-white">
                    <h4 class="mb-1 fw-bold text-dark">
                        Demande de retrait
                    </h4>
                    <p class="text-muted mb-0 small">
                        Étape 1 sur 3 — Saisissez le montant à retirer
                    </p>
                </div>

                <div class="card-body p-4">

                    {{-- Solde --}}
                    <div class="rounded-4 p-1 mb-2"
                         style="background:#f8fafc;border:1px solid #eef2f7;">
                        <div class="text-muted small mb-2">
                            Solde disponible
                        </div>

                        <h2 class="fw-bold mb-0" style="color:#0d6efd;">
                            {{ number_format($balance, 0) }} F
                        </h2>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('affiliate.withdraw.validate-amount') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">
                                Montant à retirer
                            </label>

                            <div class="input-group">
                                <input
                                    type="number"
                                    name="amount"
                                    class="form-control form-control-lg border-0 shadow-sm @error('amount') is-invalid @enderror"
                                    placeholder="10 000"
                                    step="1000"
                                    min="10000"
                                    max="{{ $balance }}"
                                    value="{{ old('amount') }}"
                                    style="background:#f8fafc;height:56px;"
                                    required>

                                <span class="input-group-text border-0 px-4"
                                      style="background:#eef2f7;">
                                    F
                                </span>
                            </div>

                            @error('amount')
                                <div class="invalid-feedback d-block mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted d-block mt-2">
                                Minimum : 10 000 F • Maximum : {{ number_format($balance, 0) }} F
                            </small>
                        </div>

                        {{-- Actions --}}
                        <div class="d-grid gap-2">
                            <button type="submit"
                                    class="btn btn-primary btn-lg rounded-3 fw-semibold py-3">
                                Continuer
                            </button>

                            <a href="{{ route('affiliate.dashboard') }}"
                               class="btn btn-light border rounded-3 py-3">
                                Annuler
                            </a>
                        </div>
                    </form>

                    {{-- Infos --}}
                    <div class="mt-4 rounded-4 p-3 small"
                         style="background:#fff8e6;border:1px solid #ffe7a3;">
                        <div class="fw-semibold text-dark mb-2">
                            Informations utiles
                        </div>

                        <ul class="mb-0 ps-3 text-muted">
                            <li>Montant minimum : 10 000 F</li>
                            <li>Des frais peuvent s’appliquer selon le moyen choisi</li>
                            <li>Délai de traitement estimé : 24 à 48 h</li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection