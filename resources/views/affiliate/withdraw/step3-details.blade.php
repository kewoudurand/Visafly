{{-- FILE: resources/views/affiliate/withdraw/step3-details.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="px-4 py-4 border-bottom bg-white">
                    <h4 class="fw-bold text-dark mb-1">
                        {{ $instructions['title'] }}
                    </h4>
                    <p class="text-muted small mb-0">
                        Étape 3 sur 3 — Confirmation des informations
                    </p>
                </div>

                <div class="card-body p-4">

                    {{-- Résumé --}}
                    <div class="row g-3 mb-4">

                        <div class="col-lg-4">
                            <div class="rounded-4 p-4"
                                 style="background:#f8fafc;border:1px solid #eef2f7;">
                                <div class="text-muted small mb-2">
                                    Montant à retirer
                                </div>

                                <h3 class="fw-bold" style="color:#0d6efd;">
                                    {{ number_format($amount, 0) }} F
                                </h3>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="rounded-4 p-4 h-100"
                                 style="background:#f8fafc;border:1px solid #eef2f7;">
                                <div class="text-muted small mb-2">
                                    Frais appliqués
                                </div>

                                <h3 class="fw-bold mb-0" style="color:#198754;">
                                    {{ $instructions['fees'] }}
                                </h3>
                            </div>
                        </div>

                    </div>

                    {{-- Formulaire --}}
                    <form action="{{ route('affiliate.withdraw.submit') }}" method="POST">
                        @csrf

                        <input type="hidden" name="method" value="{{ $method }}">

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">
                                {{ $instructions['field_label'] }}
                            </label>

                            <input
                                type="text"
                                name="reference"
                                class="form-control form-control-lg border-0 shadow-sm @error('reference') is-invalid @enderror"
                                placeholder="{{ $instructions['field_placeholder'] }}"
                                value="{{ old('reference') }}"
                                style="background:#f8fafc;height:56px;"
                                required>

                            <small class="text-muted d-block mt-2">
                                {{ $instructions['field_hint'] }}
                            </small>

                            @error('reference')
                                <div class="invalid-feedback d-block mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Confirmation --}}
                        <div class="rounded-4 p-3 mb-4"
                             style="background:#f8fafc;border:1px solid #eef2f7;">

                            <div class="form-check m-0">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="confirm"
                                    required>

                                <label class="form-check-label text-dark" for="confirm">
                                    Je confirme que les informations saisies sont exactes
                                </label>
                            </div>

                        </div>

                        {{-- Actions --}}   
                        <div class="row g-2 mt-1">

                            <div class="col-md-4">
                                <button type="submit"
                                        class="btn btn-success w-100 rounded-3 fw-semibold py-3">
                                    Confirmer
                                </button>
                            </div>

                            <div class="col-md-4">
                                <a href="{{ route('affiliate.withdraw.show-method') }}"
                                class="btn btn-light border w-100 rounded-3 py-3">
                                    Retour
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="{{ route('affiliate.dashboard') }}"
                                class="btn btn-outline-danger w-100 rounded-3 py-3">
                                    Annuler
                                </a>
                            </div>

                        </div>

                    </form>

                    {{-- Avertissement --}}
                    <div class="mt-4 rounded-4 p-4 small"
                         style="background:#fff8e6;border:1px solid #ffe7a3;">

                        <div class="fw-semibold text-dark mb-2">
                            Vérification importante
                        </div>

                        <ul class="mb-0 ps-3 text-muted">
                            <li>Vérifiez votre numéro ou compte avant validation</li>
                            <li>Une erreur de saisie peut entraîner un paiement incorrect</li>
                            <li>Traitement estimé : 24 à 48 heures</li>
                        </ul>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection