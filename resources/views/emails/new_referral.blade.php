<!DOCTYPE html>
<h2>Bonjour {{ $referrer->first_name }},</h2>
<p>Bonne nouvelle ! Une nouvelle personne vient de s'inscrire en utilisant votre code de parrainage.</p>
<ul>
    <li><strong>Nom du filleul :</strong> {{ $referral->first_name }} {{ $referral->last_name }}</li>
    <li><strong>Date :</strong> {{ now()->format('d/m/Y H:i') }}</li>
</ul>
<p>Continuez à partager votre code pour augmenter vos revenus d'affiliation !</p>
<a href="{{ route('affiliate.dashboard') }}">Voir mon tableau de bord</a>