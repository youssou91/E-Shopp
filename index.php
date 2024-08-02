<?php

include 'header.php';
$connect = mysqli_connect('localhost', 'root', '', 'cours343'); // Connexion à la base de données

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ajouter des produits au panier
if (isset($_POST['add'])) {
    $idProduit = $_GET['id'];
    $nomProduit = $_POST['nom'];
    $prixUnitaire = (float) $_POST['prix_unitaire']; // Utilisation de float pour les calculs
    $quantite = (int) $_POST['quantite'];

    if (isset($_SESSION['cart'])) {
        $item_array_id = array_column($_SESSION['cart'], "id_produit");
        if (in_array($idProduit, $item_array_id)) {
            // Mise à jour de la quantité si le produit est déjà dans le panier
            foreach ($_SESSION['cart'] as $key => $value) {
                if ($value['id_produit'] == $idProduit) {
                    $nouvelleQuantite = $value['quantite'] + $quantite;

                    // Vérifier la disponibilité en stock
                    $query = "SELECT quantite FROM Produits WHERE id_produit = " . $idProduit;
                    $result = mysqli_query($connect, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        if ($nouvelleQuantite <= $row['quantite']) {
                            $_SESSION['cart'][$key]['quantite'] = $nouvelleQuantite;
                            $_SESSION['cart'][$key]['prix_unitaire'] = $prixUnitaire; // Mettre à jour le prix unitaire
                        } else {
                            echo '<script>alert("Quantité demandée non disponible en stock")</script>';
                        }
                    }
                    break;
                }
            }
        } else {
            // Ajout d'un nouveau produit au panier
            $item_array = array(
                'id_produit' => $idProduit,
                'nom' => $nomProduit,
                'prix_unitaire' => $prixUnitaire, // Utilisation du prix réduit
                'quantite' => $quantite
            );
            $_SESSION['cart'][] = $item_array;
        }
    } else {
        // Création du panier et ajout du produit
        $item_array = array(
            'id_produit' => $idProduit,
            'nom' => $nomProduit,
            'prix_unitaire' => $prixUnitaire,
            'quantite' => $quantite
        );
        $_SESSION['cart'][0] = $item_array;
    }
    echo '<script>window.location="index.php"</script>';
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma boutique</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <style>
        .product-grid { display: flex; flex-wrap: wrap; gap: 15px; }
        .product-item { border: 1px solid #ddd; padding: 10px; border-radius: 5px; text-align: center; width: 30%; }
        .product-item img { max-width: 100%; height: auto; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text-center">Ma boutique</h2>
                        <div class="col-md-12 page-link">
                            <div class="row">
                                <?php
                                // Requête pour récupérer les produits avec les promotions
                                $query = "
                                    SELECT p.*, i.chemin_image, pr.valeur, pr.type
                                        FROM Produits p
                                        LEFT JOIN image i ON p.id_produit = i.id_produit
                                        LEFT JOIN ProduitPromotion pp ON p.id_produit = pp.id_produit
                                        LEFT JOIN Promotions pr ON pp.id_promotion = pr.id_promotion
                                        WHERE p.quantite > 0
                                        AND (pr.date_debut IS NULL OR pr.date_debut <= CURDATE())
                                        AND (pr.date_fin IS NULL OR pr.date_fin >= CURDATE());
                                ";
                                $result = mysqli_query($connect, $query);

                                if ($result) {
                                    // Affichage des produits
                                    while ($row = mysqli_fetch_array($result)) {
                                        $idProduit = $row['id_produit'];
                                        $nom = $row['nom'];
                                        $prix = $row['prix_unitaire'];
                                        $quantite = $row['quantite'];
                                        $promoType = $row['type'];
                                        $promoValeur = $row['valeur'];
                                        $prixReduit = $prix;
                                
                                        // Initialiser $promoText avec une valeur par défaut
                                        $promoText = '';
                                        $prixBarre = '';
                                        $stylePromo = '';
                                
                                        // Calcul du prix réduit et définition du texte de la promotion
                                        if ($promoType && $promoValeur) {
                                            $prixReduit = $prix - ($prix * $promoValeur / 100);
                                            $promoText = number_format($promoValeur, 2) . "% off";
                                            $prixBarre = number_format($prix, 2) . "$";
                                            $stylePromo = 'color:red;';
                                        }
                                        ?>
                                        <div class="col-md-4">
                                            <img class="shadow p-0 mb-0 bg-body rounded" width="150" height="150" src="<?php
                                                echo (isset($row['chemin_image']) && !empty($row['chemin_image'])) ? $row['chemin_image'] : "upload_images/Image 001.jpeg"; ?>">
                                                <h5 class="text-center"><?= $row['nom']; ?></h5>
                                            
                                            <p class="text-center">
                                                Prix: 
                                                <?php if ($promoText): ?>
                                                    <span class="text-center" style='text-decoration:line-through;'><?php echo $prixBarre; ?></span>
                                                    <span class="text-center" style='<?php echo $stylePromo; ?>'><?php echo number_format($prixReduit, 2); ?>$</span>
                                                <?php else: ?>
                                                    <?php echo number_format($prix, 2); ?>$
                                                <?php endif; ?>
                                            </p>
                                            <form method="post" action="index.php?id=<?php echo $row['id_produit']; ?>">
                                                <input type="hidden" name="nom" value="<?php echo htmlspecialchars($nom); ?>">
                                                <input type="hidden" name="prix_unitaire" value="<?php echo $prixReduit; ?>"> 
                                                <!-- <input type="number" name="quantite" value="1" max="<?= $row['quantite']?>" min="1" class="form-control p-0 mb-0"> -->
                                                <input type="number" name="quantite" value="1" max="<?= $row['quantite']?>" min="1" class="form-control p-0 mb-0" style="width: 150px; height: 250 text-align: center;">
                                                <button type="submit" name="add" class="btn btn-info btn-block justify-center my-1 px-1 py-2" value="Ajouter au panier">
                                                    <i class="bi bi-cart-plus"></i> Ajouter au panier
                                                </button>
                                            </form>
                                        </div>
                                    <?php } 
                                } else {
                                    echo '<p>Aucun produit disponible.</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!--  -->

                    <!-- <div class="col-md-6">
                        <h2 class="text-center">Produits</h2>
                        <form method="post" action="produit_commande.php">
                            <?php
                                $somme = 0;
                                $output = "";
                                $output .= "<table class='table table-bordered table-striped'>
                                    <tr>
                                        <th align='center'>Produits</th>
                                        <th align='center'>Prix </th>
                                        <th align='center'>Quantité</th>
                                        <th align='center'> Prix Total</th>
                                        <th align='center'>Action</th>
                                    </tr>";
                                    if (!empty($_SESSION['cart'])) {
                                        foreach($_SESSION['cart'] as $key => $value) {
                                            $id_produit = isset($value['id_produit']) ? $value['id_produit'] : '';
                                            $nom_produit = isset($value['nom']) ? htmlspecialchars($value['nom']) : '';
                                            $prix = isset($value['prix_unitaire']) ? (float)$value['prix_unitaire'] : 0;
                                            $quantite = isset($value['quantite']) ? (int)$value['quantite'] : 0;
                                            $output .= "<tr>
                                                <td>" . $nom_produit . "</td>
                                                <td align='center'> $ " . number_format($prix, 2) . "</td>
                                                <td align='center'> " . $quantite . "</td>
                                                <td align='center'> $ " . number_format($quantite * $prix, 2) . "</td>
                                                <td align='center'><a href='index.php?action=supprimer&id=" . $key . "'>
                                                    <button type='button' class='btn btn-danger btn-block'>Supprimer</button>
                                                    </a></td>
                                                </tr>";
                                                $somme += $quantite * $prix;
                                            }
                                            $output .= "<tr>
                                                <td colspan=3 align='left'>Total</td>
                                                <td align='center'> $ " . number_format($somme, 2) . "</td>
                                                <td align='center'>
                                                    <a href='index.php?action=effacer'>
                                                        <button type='button' class='btn btn-warning btn-block'>Vider Panier</button>
                                                    </a>
                                                </td>
                                            </tr>";
                                            $output .= "</table>";
                                            echo $output;

                                            foreach ($_SESSION['cart'] as $key => $value) {
                                                echo "<input type='hidden' name='produits[$key][id_produit]' value='" . $value['id_produit'] . "'>";
                                                echo "<input type='hidden' name='produits[$key][nom]' value='" . htmlspecialchars($value['nom']) . "'>";
                                                echo "<input type='hidden' name='produits[$key][prix]' value='" . $value['prix_unitaire'] . "'>";
                                                echo "<input type='hidden' name='produits[$key][quantite]' value='" . $value['quantite'] . "'>";
                                            }
                    
                                            echo "<input type='hidden' name='total' value='$somme'>";
                                            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                                                echo "<button type='submit' class='btn btn-info btn-block'>Commander</button>";
                                            } else {
                                                echo "<p class='text-center'>Veuillez vous <a href='connexion.php'>connecter</a> pour commander.</p>";
                                            }
                                        } else {
                                            echo "<h3 class='text-center'>Votre panier est vide !!!</h3>";
                                        }
                                        ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-6">
            <h2 class="text-center">Produits</h2>
            <form method="post" action="produit_commande.php">
                <?php
                $somme = 0;
                $output = "<table class='table table-bordered table-striped'>
                    <tr>
                        <th align='center'>Produits</th>
                        <th align='center'>Prix </th>
                        <th align='center'>Quantité</th>
                        <th align='center'>Prix Total</th>
                        <th align='center'>Action</th>
                    </tr>";
                if (!empty($_SESSION['cart'])) {
                    foreach($_SESSION['cart'] as $key => $value) {
                        $id_produit = isset($value['id_produit']) ? $value['id_produit'] : '';
                        $nom_produit = isset($value['nom']) ? htmlspecialchars($value['nom']) : '';
                        $prix = isset($value['prix_unitaire']) ? (float)$value['prix_unitaire'] : 0;
                        $quantite = isset($value['quantite']) ? (int)$value['quantite'] : 0;
                        $output .= "<tr>
                            <td>" . $nom_produit . "</td>
                            <td align='center'> $ " . number_format($prix, 2) . "</td>
                            <td align='center'> " . $quantite . "</td>
                            <td align='center'> $ " . number_format($quantite * $prix, 2) . "</td>
                            <td align='center'><a href='index.php?action=supprimer&id=" . $key . "'>
                                <button type='button' class='btn btn-danger btn-block'>Supprimer</button>
                                </a></td>
                            </tr>";
                            $somme += $quantite * $prix;
                        }
                    $output .= "<tr>
                        <td colspan=3 align='left'>Total</td>
                        <td align='center'> $ " . number_format($somme, 2) . "</td>
                        <td align='center'>
                            <a href='index.php?action=effacer'>
                                <button type='button' class='btn btn-warning btn-block'>Vider Panier</button>
                            </a>
                        </td>
                    </tr>";
                    $output .= "</table>";
                    echo $output;

                    foreach ($_SESSION['cart'] as $key => $value) {
                        echo "<input type='hidden' name='produits[$key][id_produit]' value='" . $value['id_produit'] . "'>";
                        echo "<input type='hidden' name='produits[$key][nom]' value='" . htmlspecialchars($value['nom']) . "'>";
                        echo "<input type='hidden' name='produits[$key][prix]' value='" . $value['prix_unitaire'] . "'>";
                        echo "<input type='hidden' name='produits[$key][quantite]' value='" . $value['quantite'] . "'>";
                    }

                    echo "<input type='hidden' name='total' value='$somme'>";
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                        echo "<button type='submit' class='btn btn-info btn-block'>Commander</button>";
                    } else {
                        echo "<p class='text-center'>Veuillez vous <a href='connexion.php'>connecter</a> pour commander.</p>";
                    }
                } else {
                    echo "<h3 class='text-center'>Votre panier est vide !!!</h3>";
                }
                ?>
            </form>
        </div>
    </div>
</div>

                    <!--  -->

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#hidden-table').DataTable();
        });
    </script>
</body>
<?php include 'footer.php'; ?>
</html>

<?php

// Gérer les actions de panier
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'supprimer' && isset($_GET['id'])) {
        $id_supprimer = $_GET['id'];
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($key == $id_supprimer) {
                unset($_SESSION['cart'][$key]);
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Réindexer le tableau
        echo '<script>window.location="index.php"</script>'; // Redirection après suppression
    }

    if ($_GET['action'] == 'effacer') {
        unset($_SESSION['cart']);
        echo '<script>window.location="index.php"</script>'; // Redirection après effacement
    }
}

?>
