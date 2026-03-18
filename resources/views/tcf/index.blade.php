@extends('layouts.app')

@section('title', 'Préparation au TCF')

@push('styles')
<style>
    .tcf-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 60px 0 40px;
        text-align: center;
    }

    .tcf-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .tcf-hero p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .tcf-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.4);
        color: #fff;
        padding: 5px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .epreuve-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }

    .epreuve-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }

    .epreuve-card .card-header {
        padding: 28px 24px 20px;
        border-bottom: none;
    }

    .epreuve-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #fff;
        margin-bottom: 16px;
    }

    .epreuve-card .card-body {
        padding: 0 24px 24px;
    }

    .epreuve-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: #6c757d;
        margin-right: 16px;
    }

    .conseil-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .conseil-list li {
        font-size: 13px;
        color: #555;
        padding: 4px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .conseil-list li::before {
        content: '✓';
        font-weight: 700;
        color: #28a745;
        flex-shrink: 0;
    }

    .btn-commencer {
        border-radius: 50px;
        padding: 10px 28px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.3px;
        transition: all 0.3s;
    }

    .optionnel-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-bandeau {
        background: #f8f9ff;
        border-left: 4px solid #6777ef;
        border-radius: 0 8px 8px 0;
        padding: 16px 20px;
        margin-bottom: 40px;
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="tcf-hero">
    <div class="container">
        <div class="tcf-badge">🎓 Préparation officielle</div>
        <h1>Test de Connaissance du Français</h1>
        <p>Choisissez l'épreuve que vous souhaitez préparer et entraînez-vous avec nos exercices.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">

        {{-- Bandeau info --}}
        <div class="info-bandeau">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
                <div>
                    <strong>Le TCF comporte 3 épreuves obligatoires</strong> (Compréhension Écrite, Compréhension Orale, Maîtrise des Structures) 
                    et <strong>2 épreuves facultatives</strong> (Expression Écrite et Orale) selon votre objectif 
                    <em>(TCF Québec, TCF DAP, TCF ANF…)</em>.
                </div>
            </div>
        </div>

        {{-- Titre section --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold">Sélectionnez une épreuve</h2>
            <p class="text-muted">Cliquez sur l'épreuve de votre choix pour commencer votre entraînement</p>
        </div>

        {{-- Cartes des épreuves --}}
        <div class="row g-4">
            @foreach($epreuves as $index => $epreuve)
            <div class="col-lg-4 col-md-6">
                <div class="epreuve-card card">
                    <div class="card-header bg-white">

                        {{-- Badge optionnel --}}
                        @if(in_array($epreuve['slug'], ['expression-ecrite', 'expression-orale']))
                            <span class="optionnel-badge bg-warning text-dark mb-2 d-inline-block">
                                Facultative
                            </span>
                        @elseif($epreuve['slug'] === 'maitrise-structures')
                            <span class="optionnel-badge bg-secondary text-white mb-2 d-inline-block">
                                Optionnelle
                            </span>
                        @else
                            <span class="optionnel-badge bg-success text-white mb-2 d-inline-block">
                                Obligatoire
                            </span>
                        @endif

                        {{-- Icône --}}
                        <div class="epreuve-icon bg-{{ $epreuve['couleur'] }}">
                            <i class="bi {{ $epreuve['icon'] }}"></i>
                        </div>

                        {{-- Titre --}}
                        <h5 class="fw-bold mb-1">{{ $epreuve['titre'] }}</h5>

                        {{-- Méta --}}
                        <div class="epreuve-meta mt-2">
                            <span><i class="bi bi-clock"></i> {{ $epreuve['duree'] }}</span>
                            <span><i class="bi bi-list-check"></i> {{ $epreuve['questions'] }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Description --}}
                        <p class="text-muted small mb-3">{{ $epreuve['description'] }}</p>

                        {{-- Conseils --}}
                        <p class="fw-semibold small mb-2">💡 Conseils :</p>
                        <ul class="conseil-list mb-4">
                            @foreach($epreuve['conseils'] as $conseil)
                                <li>{{ $conseil }}</li>
                            @endforeach
                        </ul>

                        {{-- Bouton --}}
                        <a href="{{ route('tcf.epreuve', $epreuve['slug']) }}"
                           class="btn btn-{{ $epreuve['couleur'] }} btn-commencer w-100">
                            <i class="bi bi-play-fill me-1"></i>
                            Commencer l'entraînement
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Légende --}}
        <div class="d-flex flex-wrap gap-3 justify-content-center mt-5">
            <span class="d-flex align-items-center gap-2">
                <span class="optionnel-badge bg-success text-white">Obligatoire</span>
                Requise pour tous les types de TCF
            </span>
            <span class="d-flex align-items-center gap-2">
                <span class="optionnel-badge bg-warning text-dark">Facultative</span>
                Selon votre objectif (Québec, DAP, etc.)
            </span>
            <span class="d-flex align-items-center gap-2">
                <span class="optionnel-badge bg-secondary text-white">Optionnelle</span>
                Disponible selon le centre
            </span>
        </div>

    </div>
</section>

@endsection