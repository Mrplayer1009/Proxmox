<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Stripe - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow max-w-md w-full">
        <h1 class="text-2xl font-bold text-orange-600 mb-6">Paiement Stripe (Test)</h1>
        <form id="payment-form">
            <div id="card-element" class="mb-4"></div>
            <button id="submit" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 transition w-full">Payer</button>
            <div id="payment-message" class="mt-4 text-center text-green-600"></div>
        </form>
    </div>
    <script>
        const stripe = Stripe('pk_test_51RhbUH4QDOqmsz8c2euOgNCcnjhElGUt7pEpdDzA2X6FJwi3ghVp8Hkjjp0cNdFodc2QO2Lbz1ltdBQ2vxPjva6700oth9g4Vo'); // Mets ta clé publique Stripe ici
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const {paymentMethod, error} = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });
            if (error) {
                document.getElementById('payment-message').textContent = error.message;
            } else {
                document.getElementById('payment-message').textContent = 'Paiement Stripe simulé (aucun débit réel).';
            }
        });
    </script>
</body>
</html> 