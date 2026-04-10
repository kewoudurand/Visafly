{{-- resources/views/cours/list.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    /* On garde ton Hero qui est très beau */
    .cours-show-hero {
        background: #fff;;
        padding: 60px 24px 100px;
        position: relative;
        text-align: center;
    }
    .hero-inner { max-width: 800px; margin: 0 auto; position: relative; z-index: 1; }
    .hero-titre { font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 10px; }
    .hero-sous-titre { font-size: 18px; color: rgba(255,255,255,0.8); }

    /* Conteneur des deux boutons centrés */
    .actions-container {
        max-width: 900px;
        margin: -50px auto 100px;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        position: relative;
        z-index: 10;
    }

    /* Style des grandes cartes boutons */
    .action-card {
        background: #fff;
        border-radius: 24px;
        padding: 40px 30px;
        text-align: center;
        text-decoration: none !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .action-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 45px rgba(0,0,0,0.15);
        border-color: #fff;;
    }

    .action-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin-bottom: 20px;
        background: rgba(0,0,0,0.04);
        color: #fff;;
    }

    .action-title {
        font-size: 22px;
        font-weight: 800;
        color: #1B3A6B;
        margin-bottom: 10px;
    }

    .action-desc {
        font-size: 14px;
        color: #777;
        line-height: 1.5;
    }

    /* Mobile Responsive */
    @media(max-width: 768px) {
        .actions-container {
            grid-template-columns: 1fr;
            margin-top: -30px;
        }
        .hero-titre { font-size: 1.8rem; }
    }
</style>
@endpush

@section('content')


<div class="actions-container">
    

    <a href="{{route('cours.allemand.index') }}" class="action-card">
        <div class="action-icon" style="background: #fff5;">
            <i class="bi bi-book-half"></i>
        </div>
        <div class="action-title">Lire le cours</div>
        <div class="action-desc">
            Accédez aux leçons détaillées, au vocabulaire et à la grammaire de ce module.
        </div>
        <div style="margin-top:20px; font-weight:700; color:#fff;">
            Démarrer <i class="bi bi-arrow-right"></i>
        </div>
    </a>

    {{-- BOUTON 2 : PASSER UN TEST --}}
    <a href="{{ route('langues.series', 'goethe') }}" class="action-card">
        <div class="action-icon" style="background: #1cc88a15; color: #1cc88a;">
            <i class="bi bi-patch-check"></i>
        </div>
        <div class="action-title">Passer un test</div>
        <div class="action-desc">
            Évaluez vos connaissances et gagnez des points pour valider votre niveau A1.
        </div>
        <div style="margin-top:20px; font-weight:700; color:#1cc88a;">
            Commencer l'examen <i class="bi bi-arrow-right"></i>
        </div>
    </a>

</div>

@endsection