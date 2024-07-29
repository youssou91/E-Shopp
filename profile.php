<?php
include 'header.php';

if (!isset($_SESSION['id_utilisateur'])) {
    echo '<script>window.location.href = "connexion.php";</script>';
    exit;
}

$userId = $_SESSION['id_utilisateur'];
$userInfo = getUserInfo($userId);
$userOrders = getUserCommandWithStatus($userId);

// Affichage des commandes avec leur état
// Traitement des actions des commandes
if (isset($_POST['action'])) {
    $orderId = $_POST['order_id']; // Assurez-vous que le nom du champ correspond
    $action = $_POST['action'];
    switch ($action) {
        case 'traiter':
            update_commandeOrderstatut($orderId, 'En traitement');
            break;
        case 'expédier':
            update_commandeOrderstatut($orderId, 'En expedition');
            break;
        case 'annuler':
            update_commandeOrderstatut($orderId, 'Annulee');
            break;
    }
    echo '<script>window.location.href = "commandesAdmin.php";</script>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .status-pending {
            background-color: #FFFFE0; /* Jaune clair pour "En attente" */
        }
        .status-processing {
            background-color: #FFCC00; /* Jaune pour "En traitement" */
        }
        .status-shipped {
            background-color: #ADD8E6; /* Bleu clair pour "Expédiée" */
        }
        .status-delivered {
            background-color: #90EE90; /* Vert clair pour "Livrée" */
        }
        .status-cancelled {
            background-color: #FF6347; /* Rouge tomate pour "Annulée" */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Mon Profil</h1>
        <div class="row">
            <div class="col-md-5">
                <h3>Informations personnelles</h3>
                <p><strong>Nom :</strong> <?= htmlspecialchars($userInfo['nom_utilisateur']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($userInfo['prenom']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($userInfo['couriel']) ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($userInfo['telephone']) ?></p>
                <h4>Adresse</h4>
                <p><strong>Rue :</strong> <?= htmlspecialchars($userInfo['numero']).' '.htmlspecialchars($userInfo['rue'])  ?></p>
                <p><strong>Code Postal :</strong> <?= htmlspecialchars($userInfo['code_postal']) ?></p>
                <p><strong>Ville :</strong> <?=htmlspecialchars($userInfo['ville']).', '. htmlspecialchars($userInfo['province']) ?></p>
                <p><strong>Pays :</strong> <?= htmlspecialchars($userInfo['pays']) ?></p>
                <a href="modifier_profil.php" class="btn btn-warning">Modifier les informations</a>
                <a href="modifier_mot_de_passe.php" class="btn btn-danger">Modifier le mot de passe</a>
            </div>
            <div class="col-md-7">
                <h3>Mes Commandes</h3>
                <?php if (count($userOrders) > 0): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Détails</th>
                                <th>Annuler</th>
                                <th>Paiement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; ?>
                            <?php foreach ($userOrders as $order): ?>
                                <tr>
                                    <td><?= $index++; ?></td>
                                    <td><?= htmlspecialchars($order['date_commande']) ?></td>
                                    <td>$ <?= htmlspecialchars($order['prix_total']) ?></td>
                                    <td><?= htmlspecialchars($order['statut']) ?></td>
                                    <td><a href="details_commande.php?id_commande=<?= htmlspecialchars($order['id_commande']) ?>" class="btn btn-info">Détails</a></td>
                                    <td>
                                        <form action="annuler_commande.php" method="post">
                                            <input type="hidden" name="id_commande" value="<?= htmlspecialchars($order['id_commande']) ?>">
                                            <button type="submit" name="action" value="annuler" class="btn btn-danger" <?= ($order['statut'] == 'Annulee') ? 'disabled' : '' ?>>Annuler</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="paiement_commande.php" method="post">
                                            <input type="hidden" name="id_commande" value="<?= htmlspecialchars($order['id_commande']) ?>">
                                            <button type="submit" class="btn btn-success" <?= ($order['statut'] == 'Annulee') ? 'disabled' : '' ?>>Payer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune commande trouvée.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

<?php include 'footer.php';?>
</html>
