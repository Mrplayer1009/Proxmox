@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8 max-w-lg">
    <h2 class="text-2xl font-bold mb-6">Paiement du panier</h2>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-semibold mb-4">Récapitulatif</h3>
        <ul class="mb-4">
            @foreach($panier as $item)
                <li>{{ $item['nom'] }} x {{ $item['quantite'] }} = {{ number_format($item['prix'] * $item['quantite'], 2) }} €</li>
            @endforeach
        </ul>
        <div class="mb-4 font-bold">Total : {{ number_format($total, 2) }} €</div>
        <form id="payment-form">
            @csrf
            <div id="card-element" class="mb-4"></div>
            <button id="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">Payer</button>
            <div id="payment-message" class="mt-4 text-center text-green-600"></div>
        </form>
    </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('stripe.key') }}');
    let elements = stripe.elements();
    let cardElement = elements.create('card');
    cardElement.mount('#card-element');
    let clientSecret = null;
    fetch("{{ route('panier.payment_intent') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => { clientSecret = data.clientSecret; });
    document.getElementById('payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!clientSecret) {
            document.getElementById('payment-message').textContent = 'Erreur de paiement, veuillez réessayer.';
            return;
        }
        const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
            }
        });
        if (error) {
            document.getElementById('payment-message').textContent = error.message;
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
            // Paiement réussi, on finalise la commande côté serveur
            fetch("{{ route('panier.payer') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({})
            }).then(() => {
                window.location.href = "{{ route('panier.afficher') }}";
            });
        }
    });
</script>
@endsection 