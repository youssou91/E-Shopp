<?php

include 'header.php';
$connect = mysqli_connect('localhost', 'root', '', 'cours343'); // Connexion à la base de données
if (isset($_POST['add'])) {
    if (isset($_SESSION['cart'])) {
        $item_array_id = array_column($_SESSION['cart'], "id_produit");
        if (!in_array($_GET['id'], $item_array_id)) {
            // Récupérer les informations du produit depuis la base de données
            $query = "SELECT * FROM Produits WHERE id_produit = " . $_GET['id'] . " AND quantite > 0";
            $result = mysqli_query($connect, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // Ajouter le produit au panier
                $item_array = array(
                    'id_produit' => $row['id_produit'],
                    'nom' => $row['nom'],
                    'prix_unitaire' => $row['prix_unitaire'],
                    'quantite' => $_POST['quantite']
                );
                $_SESSION['cart'][] = $item_array;
                echo '<script>window.location="index.php"</script>';
            } else {
                echo '<script>alert("Produit inexistant en stock")</script>';
            }
        } else {
            echo '<script>alert("Le produit est déjà dans le panier")</script>';
        }
    } else {
        $query = "SELECT * FROM Produits WHERE id_produit = " . $_GET['id'] . " AND quantite > 0";
        $result = mysqli_query($connect, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Créer le panier s'il n'existe pas et ajouter le produit
            $item_array = array(
                'id_produit' => $row['id_produit'],
                'nom' => $row['nom'],
                'prix_unitaire' => $row['prix_unitaire'],
                'quantite' => $_POST['quantite']
            );
            $_SESSION['cart'][0] = $item_array;
        } else {
            echo '<script>alert("Produit inexistant en stock")</script>';
        }
    }
}
?>

<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <h2 class="text-center">Ma boutique</h2>
                <div class="col-md-12 page-link">
                    <div class="row">
                        <?php
                        $query = "SELECT * FROM Produits p LEFT JOIN image i ON p.id_produit = i.id_produit WHERE p.quantite > 0";
                        $result = mysqli_query($connect, $query);
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <div class="col-md-4">
                                <form method="post" action="index.php?id=<?= $row['id_produit'] ?>">
                                    <img class="shadow p-0 mb-0 bg-body rounded" width="150" height="150" src="<?php
                                    echo (isset($row['chemin_image']) && !empty($row['chemin_image'])) ? $row['chemin_image'] : "upload_images/Image 001.jpeg"; ?>">
                                    <h5 class="text-center"><?= $row['nom']; ?></h5>
                                    <h5 class="text-center">$<?= number_format($row['prix_unitaire'], 2); ?></h5>
                                    <input type="hidden" name="nom" value="<?= $row['nom'] ?>">
                                    <input type="hidden" name="prix_unitaire" value="<?= $row['prix_unitaire'] ?>">
                                    <input type="number" name="quantite" value="1" max="<?= $row['quantite']?>" min="1" class="form-control">
                                    <input type="submit" name="add" class="btn btn-info btn-block my-2" value="Ajouter au panier">
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="text-center">Produits</h2>
                <!-- Formulaire pour le panier -->
                <form method="post" action="produit_commande.php">
                    <?php
                    $somme = 0;
                    $output = "";
                    $output .= "<table class='table table-bordered table-striped'>
                            <tr>
                                <th align='center'>Nom produit</th>
                                <th align='center'>Prix produit</th>
                                <th align='center'>Quantité</th>
                                <th align='center'>Prix total</th>
                                <th align='center'>Action</th>
                            </tr>";
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $key => $value) {
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

                        // Ajoutez les données du panier dans le formulaire caché
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
</div>

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
// if (isset($_GET['action'])) {
//     // Supprimer un élément du panier
//     if ($_GET['action'] == 'supprimer' && isset($_GET['id'])) {
//         $id_supprimer = $_GET['id'];
//         foreach ($_SESSION['cart'] as $key => $value) {
//             if ($key == $id_supprimer) {
//                 unset($_SESSION['cart'][$key]);
//             }
//         }
//         echo '<script>window.location="index.php"</script>'; // Ajouté pour éviter la duplication des éléments après suppression
//     }
//     // Vider le panier
//     if ($_GET['action'] == 'effacer') {
//         unset($_SESSION['cart']);
//         echo '<script>window.location="index.php"</script>'; // Ajouté pour éviter la duplication des éléments après effacement
//     }
// }
?>
