<?php
include 'header.php';
// Assurez-vous que vous avez bien inclus les fichiers d'entête nécessaires

// Configuration PayPal
$clientId = 'YOUR_PAYPAL_CLIENT_ID';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $clientId; ?>&currency=USD"></script>
</head>
<body>
<div class="container">
    <h1 class="text-center">Paiement PayPal</h1>
    <div id="paypal-button-container"></div>
</div>

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '99.99' // Remplacez par le montant dynamique de la commande
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Transaction completed by ' + details.payer.name.given_name);
                // Vous pouvez rediriger l'utilisateur ou traiter les informations du paiement ici
                window.location.href = "order_confirmation.php?orderID=" + data.orderID;
            });
        },
        onCancel: function (data) {
            alert('Transaction annulée');
            // Gestion des cas où l'utilisateur annule le paiement
        },
        onError: function (err) {
            console.error('Erreur lors du paiement', err);
            // Gestion des erreurs lors du paiement
        }
    }).render('#paypal-button-container');
</script>
</body>
</html>
