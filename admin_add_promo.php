<?php
include_once 'header.php';

$connect = connexionDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produit = $_POST['id_produit'];
    $valeur = $_POST['valeur'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Ajouter la promotion dans la table promotions
    $queryPromotion = "INSERT INTO promotions (valeur, date_debut, date_fin) VALUES (?, ?, ?)";
    if ($stmtPromotion = mysqli_prepare($connect, $queryPromotion)) {
        mysqli_stmt_bind_param($stmtPromotion, "iss", $valeur, $date_debut, $date_fin);
        mysqli_stmt_execute($stmtPromotion);
        $id_promotion = mysqli_insert_id($connect);  // Obtenir l'ID de la promotion insérée
        mysqli_stmt_close($stmtPromotion);

        // Associer la promotion au produit dans la table produitpromotion
        $queryProduitPromotion = "INSERT INTO produitpromotion (id_produit, id_promotion) VALUES (?, ?)";
        if ($stmtProduitPromotion = mysqli_prepare($connect, $queryProduitPromotion)) {
            mysqli_stmt_bind_param($stmtProduitPromotion, "ii", $id_produit, $id_promotion);
            mysqli_stmt_execute($stmtProduitPromotion);
            mysqli_stmt_close($stmtProduitPromotion);

            echo '<script>window.location.href = "admin_promotions.php";</script>';
        } else {
            echo 'Erreur : ' . mysqli_error($connect);
        }
    } else {
        echo 'Erreur : ' . mysqli_error($connect);
    }
} else {
    $query = "SELECT id_produit, nom FROM produits";
    $result = mysqli_query($connect, $query);
?>

<div class="container">
    <h2 class="text-center">Ajouter une promotion</h2>
    <form method="post" action="admin_add_promo.php">
        <div class="form-group">
            <label for="id_produit">Produit :</label>
            <select name="id_produit" id="id_produit" class="form-control">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['id_produit'] . '">' . $row['nom'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="valeur">Réduction (%) :</label>
            <input type="number" name="valeur" id="valeur" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="date_debut">Date de début :</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="date_fin">Date de fin :</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
<?php
}

include_once 'footer.php';
?>
