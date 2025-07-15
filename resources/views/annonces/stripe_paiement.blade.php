@extends('layouts.app')
@section('content')
<div class="container py-8">
    <h2 class="text-2xl font-bold mb-6">Paiement de l’annonce : {{ $annonce->titre }}</h2>
    <p>Montant : <strong>{{ number_format($annonce->prix,2) }} €</strong></p>
    <button id="checkout-button" class="px-4 py-2 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition">
        Payer avec Stripe
    </button>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ $publicKey }}");
    document.getElementById('checkout-button').addEventListener('click', function () {
        stripe.redirectToCheckout({ sessionId: "{{ $sessionId }}" });
    });
</script>
@endsection 