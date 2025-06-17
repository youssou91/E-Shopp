<?php
include 'header.php'; 
$commandes = getAllCommandes();
?>
<div class="container">
    <h2 class="text-center">Liste des Commandes</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Date Commande</th>
                <th>Prix Total</th>
                <th>Nom Utilisateur</th>
                <th>Détails</th>
            </tr>
        </thead>
        <tbody> 
            <?php
                
                $i = 1;
                if (is_array($commandes) && count($commandes) > 0) {
                    foreach ($commandes as $i => $command) {?>
                        
                        <tr>
                            <td><?= $i = $i+1; ?></td>
                            <td><?= $command['date_commande'];?></td>
                            <td>$<?= number_format($command['prix_total'], 2);?></td>
                            <td><?= htmlspecialchars($command['nom_utilisateur']);?></td>
                            <td><a href="details_commande.php?id_commande=<?= $command['id_commande'];?>" class="btn btn-info">Voir Détails</a></td>
                        </tr>
                    <?php }?>
                <?php } else {?>
                <tr>
                    <td colspan="5" class="text-center">Aucune commande trouvée. </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>

