<?php
include 'header.php';

// Vérifier si l'ID de la commande est bien envoyé via POST
if (isset($_POST['id_commande'])) {
    $order_id = $_POST['id_commande']; // Récupérer l'ID de la commande
    $prix_total = getOrderTotal($order_id); 

    if ($prix_total === null || $prix_total === false) {
        die("Erreur lors de la récupération du prix total.");
    }
} else {
    die("Aucune commande spécifiée.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement PayPal</title>
    
    <script src="https://www.paypal.com/sdk/js?client-id=AWW6GZJg_ShlBU7L34BaliLIpxsvWrKKEVzKCOUBKUXMX2wapM7rcA-SlpYwQ4Nr5i7-aliEssT-gF4N&components=buttons"></script>

    <style>
        #paypal-button-container {
            display: flex;
            justify-content: center;
        }
    </style>
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
                        value: '<?= $prix_total ?>' // Utilise le prix total dynamique
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Transaction completed by ' + details.payer.name.given_name);
                window.location.href = "order_confirmation.php?orderID=" + data.orderID;
            });
        },
        onCancel: function (data) {
            alert('Transaction annulée');
        },
        onError: function (err) {
            console.error('Erreur lors du paiement', err);
        }
    }).render('#paypal-button-container');
</script>
</body>
</html>
