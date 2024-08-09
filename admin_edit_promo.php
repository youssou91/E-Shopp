<?php
include_once 'header.php';

$connect = connexionDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_promo = $_POST['id_promotion'];
    $id_promotion = $_POST['id_promotion'];
    $valeur = $_POST['valeur'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Mettre à jour la table promotions
    $queryUpdatePromotion = "UPDATE promotions SET valeur = ?, date_debut = ?, date_fin = ? WHERE id_promotion = ?";
    if ($stmt = mysqli_prepare($connect, $queryUpdatePromotion)) {
        mysqli_stmt_bind_param($stmt, "issi", $valeur, $date_debut, $date_fin, $id_promotion);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Mettre à jour la table produitpromotion
        $queryUpdateProduitPromotion = "UPDATE produitpromotion SET id_promotion = ? WHERE id_promotion = ?";
        if ($stmt = mysqli_prepare($connect, $queryUpdateProduitPromotion)) {
            mysqli_stmt_bind_param($stmt, "ii", $id_promotion, $id_promo);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            echo 'Erreur : ' . mysqli_error($connect);
        }

        echo '<script>window.location.href = "admin_promotions.php";</script>';
    } else {
        echo 'Erreur : ' . mysqli_error($connect);
    }
} else {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo '<script>window.location.href = "admin_promotions.php";</script>';
        exit();
    }

    $id_promo = $_GET['id'];

    $queryPromo = "SELECT pp.id_promotion, pp.id_promotion, pr.valeur, pr.date_debut, pr.date_fin 
                   FROM produitpromotion pp 
                   JOIN promotions pr ON pp.id_promotion = pr.id_promotion
                   WHERE pp.id_promotion = ?";
    if ($stmtPromo = mysqli_prepare($connect, $queryPromo)) {
        mysqli_stmt_bind_param($stmtPromo, "i", $id_promo);
        mysqli_stmt_execute($stmtPromo);
        $resultPromo = mysqli_stmt_get_result($stmtPromo);
        $promo = mysqli_fetch_assoc($resultPromo);
        mysqli_stmt_close($stmtPromo);
    } else {
        echo 'Erreur : ' . mysqli_error($connect);
        exit();
    }

    $queryProduits = "SELECT id_produit, nom FROM produits";
    $resultProduits = mysqli_query($connect, $queryProduits);

    $queryPromotions = "SELECT id_promotion, type FROM promotions";
    $resultPromotions = mysqli_query($connect, $queryPromotions);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier Promotion</title>
        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
        <h1 class="text-center">Modifier une promotion</h1>
        <h1 class="mb-4">Modifier une promotion</h1>
        <form method="post" action="admin_edit_promo.php">
            <input type="hidden" name="id_promotion" value='<?php echo htmlspecialchars($promo['id_promo']); ?>'>
            <div class="form-group">
                <label for="id_produit">Produit :</label>
                <select name="id_produit" id="id_produit" class="form-control" disabled>
                    <?php
                    while ($row = mysqli_fetch_assoc($resultProduits)) {
                        $selected = ($row['id_produit'] == $promo['id_produit']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['id_produit']) . '" ' . $selected . '>' . htmlspecialchars($row['nom']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <!-- <label for="id_promotion">Nom de la promotion :</label>
                <select name="id_promotion" id="id_promotion" class="form-control">
                    <?php
                    while ($row = mysqli_fetch_assoc($resultPromotions)) {
                        $selected = ($row['id_promotion'] == $promo['id_promotion']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['id_promotion']) . '" ' . $selected . '>' . htmlspecialchars($row['type']) . '</option>';
                    }
                    ?>
                </select> -->
            </div>
            <div class="form-group">
                <label for="valeur">Réduction (%) :</label>
                <input type="number" name="valeur" id="valeur" class="form-control" value='<?php echo htmlspecialchars($promo['valeur']); ?>' required>
            </div>
            <div class="form-group">
                <label for="date_debut">Date de début :</label>
                <input type="date" name="date_debut" id="date_debut" class="form-control" value='<?php echo htmlspecialchars($promo['date_debut']); ?>' required>
            </div>
            <div class="form-group">
                <label for="date_fin">Date de fin :</label>
                <input type="date" name="date_fin" id="date_fin" class="form-control" value='<?php echo htmlspecialchars($promo['date_fin']); ?>' required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
}

include_once 'footer.php';
?>
