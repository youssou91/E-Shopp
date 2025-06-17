<?php
include 'header.php';

// Vérifiez si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    echo '<script>window.location.href = "index.php";</script>';
    exit;
}

// Traitement des actions des commandes
if (isset($_POST['action'])) {
    $orderId = $_POST['order_id']; // Assurez-vous que le nom du champ correspond
    $action = $_POST['action'];
    switch ($action) {
        case 'traiter':
            update_commandeOrderstatut($orderId, 'En traitement');
            break;
        case 'expedier':
            update_commandeOrderstatut($orderId, 'En expedition');
            break;
        case 'annuler':
            update_commandeOrderstatut($orderId, 'Annulee');
            break;
    }
    echo '<script>window.location.href = "commandesAdmin.php";</script>';
}

// Récupérer la liste des commandes
$orders = getAllcommandes();
$index = 1;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liste des commandes</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center text-primary">Liste des commandes</h1>
            <table id="ordersTable" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateurs</th>
                        <th>Date</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $index++; ?></td>
                            <td><?php echo htmlspecialchars($order['prenom'] . ' ' . htmlspecialchars($order['nom_utilisateur'])); ?></td>
                            <td><?php echo htmlspecialchars($order['date_commande']); ?></td>
                            <td><?php echo '$ '.htmlspecialchars($order['prix_total']); ?></td>
                            <td><?php echo htmlspecialchars($order['statut']); ?></td>
                            <td>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id_commande']); ?>">
                                    <button type="submit" name="action" value="traiter" class="btn btn-success btn-sm" 
                                        <?php echo ($order['statut'] == 'Annulee' || $order['statut'] == 'En expedition') ? 'disabled' : ''; ?>>
                                        Traiter
                                    </button>
                                    <button type="submit" name="action" value="expedier" class="btn btn-warning btn-sm" <?php echo ($order['statut'] == 'Annulee') ? 'disabled' : ''; ?>>Expédier</button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalAnnulerCommande<?php echo htmlspecialchars($order['id_commande']); ?>" <?php echo ($order['statut'] == 'Annulee') ? 'disabled' : ''; ?>>
                                        Annuler
                                    </button>
                                </form> 
                            </td>
                        </tr>

                        <!-- Modal pour annuler la commande -->
                        <div class="modal fade" id="modalAnnulerCommande<?php echo htmlspecialchars($order['id_commande']); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Annulation de commande</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Voulez-vous vraiment annuler cette commande ?</p>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id_commande']); ?>">
                                            <input type="hidden" name="action" value="annuler">
                                            <button type="submit" name="action" value="annuler" class="btn btn-primary">Confirmer l'annulation</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
        <script>
            $(document).ready(function() {
                $('#ordersTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "lengthMenu": [10, 25, 50, 100],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/French.json"
                    }
                });
            });
        </script>
    </body>
    <?php include 'footer.php';?>
</html>
