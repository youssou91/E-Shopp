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
        case 'expédier':
            update_commandeOrderstatut($orderId, 'En expédition');
            break;
        case 'annuler':
            update_commandeOrderstatut($orderId, 'Annulee');
            break;
    }
    echo '<script>window.location.href = "commandesAdmin.php";</script>';
}

// Récupérer la liste des commandes
$orders = getAllcommandes();
?>
<div class="container">
    <h1 class="text-center text-primary">Liste des commandes</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID Commande</th>
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
                    <td><?php echo htmlspecialchars($order['id_commande']); ?></td>
                    <td><?php echo htmlspecialchars($order['prenom'] . ' ' . htmlspecialchars($order['nom_utilisateur'])); ?></td>
                    <td><?php echo htmlspecialchars($order['date_commande']); ?></td>
                    <td><?php echo '$ '.htmlspecialchars($order['prix_total']); ?></td>
                    <td><?php echo htmlspecialchars($order['statut']); ?></td>
                    <td>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id_commande']); ?>">
                            <button type="submit" name="action" value="traiter" class="btn btn-success btn-sm" <?php echo ($order['statut'] == 'annulée') ? 'disabled' : ''; ?>>Traiter</button>
                            <button type="submit" name="action" value="expédier" class="btn btn-warning btn-sm" <?php echo ($order['statut'] == 'annulée') ? 'disabled' : ''; ?>>Expédier</button>
                            <button type="submit" name="action" value="annuler" class="btn btn-danger btn-sm" <?php echo ($order['statut'] == 'annulée') ? 'disabled' : ''; ?>>Annuler</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
