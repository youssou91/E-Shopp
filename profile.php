<?php
include 'header.php';

if (!isset($_SESSION['id_utilisateur'])) {
    echo '<script>window.location.href = "connexion.php";</script>';
    exit;
}

$userId = $_SESSION['id_utilisateur'];
$userInfo = getUserInfo($userId);
$userOrders = getUserCommandWithStatus($userId);
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
                <p><strong>Date de naissance :</strong> <?= htmlspecialchars($userInfo['date_naissance']) ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($userInfo['telephone']) ?></p>
                <a href="modifier_profil.php" class="btn btn-warning">Modifier les informations</a>
                <a href="modifier_mot_de_passe.php" class="btn btn-danger">Modifier le mot de passe</a>
            </div>

            <!-- <div class="col-md-5">
                <h3>Informations personnelles</h3>
                <p><strong>Nom :</strong> <?= htmlspecialchars($userInfo['nom_utilisateur']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($userInfo['prenom']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($userInfo['couriel']) ?></p>
                <p><strong>Date de naissance :</strong> <?= htmlspecialchars($userInfo['date_naissance']) ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($userInfo['telephone']) ?></p>
            </div> -->
            <div class="col-md-7">
                <h3>Mes Commandes</h3>
                <?php if (count($userOrders) > 0): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Commande</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Status</th>
                                <th>Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userOrders as $order): ?>
                                <tr class="<?= getStatusClass($order['statut_description']) ?>">
                                    <td><?= htmlspecialchars($order['id_commande']) ?></td>
                                    <td><?= htmlspecialchars($order['date_commande']) ?></td>
                                    <td> $<?= htmlspecialchars($order['prix_total']) ?></td>
                                    <td><?= htmlspecialchars($order['statut_description']) ?></td>
                                    <td><a href="details_commande.php?id_commande=<?= htmlspecialchars($order['id_commande'])?>" class="btn btn-info">Détails</a></td>
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
</html>
