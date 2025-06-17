<?php
include_once 'header.php';
$connect = connexionDB();
$query = "SELECT pp.id_promotion, p.nom AS nom_produit, pr.code_promotion, pr.valeur, pr.date_debut, pr.date_fin 
          FROM produitpromotion pp 
          JOIN produits p ON pp.id_produit = p.id_produit
          JOIN promotions pr ON pp.id_promotion = pr.id_promotion";

$result = mysqli_query($connect, $query);
?>
<div class="container">
    <h2 class="text-center">Gestion des promotions</h2>
    <a href="admin_add_promo.php" class="btn btn-primary mb-3">Ajouter une promotion</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Réduction</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['nom_produit']) ?></td>
                    <td><?= htmlspecialchars($row['valeur']) ?>%</td>
                    <td><?= htmlspecialchars($row['date_debut']) ?></td>
                    <td><?= htmlspecialchars($row['date_fin']) ?></td>
                    <td>
                        <a href='admin_edit_promo.php?id=<?= htmlspecialchars($row['id_promotion']) ?>' class="btn btn-warning btn-sm mr-2">Modifier</a>
                        <a href='admin_delete_promo.php?id=<?= htmlspecialchars($row['id_promotion']) ?>' class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<?php
include_once 'footer.php';
?>