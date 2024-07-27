<?php

include 'header.php';
$connect = mysqli_connect('localhost', 'root', '', 'cours343'); // Connexion à la base de données
if (isset($_POST['add'])) {
    if (isset($_SESSION['cart'])) {
        $item_array_id = array_column($_SESSION['cart'], "id_produit");
        if (in_array($_GET['id'], $item_array_id)) {
            // Produit déjà dans le panier, augmenter la quantité
            foreach ($_SESSION['cart'] as $key => $value) {
                if ($value['id_produit'] == $_GET['id']) {
                    $new_quantity = $value['quantite'] + $_POST['quantite'];
                    
                    // Vérifier si la nouvelle quantité est disponible en stock
                    $query = "SELECT quantite FROM Produits WHERE id_produit = " . $_GET['id'];
                    $result = mysqli_query($connect, $query);
                    $row = mysqli_fetch_assoc($result);
                    
                    if ($new_quantity <= $row['quantite']) {
                        $_SESSION['cart'][$key]['quantite'] = $new_quantity;
                    } else {
                        echo '<script>alert("Quantité demandée non disponible en stock")</script>';
                    }
                    break;
                }
            }
        } else {
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
                                                <button type="submit" name="add" class="btn btn-info btn-block justify-center my-1 px-2 py-2 " value="Ajouter au panier"> <i class="bi bi-cart-plus"></i> Ajouter au panier</button>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="text-center">Produits</h2>
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
        </div>
        <script>
            $(document).ready(function() {
                var table = $('#hidden-table').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": false,
                    "info": false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/fr_fr.json"
                    }
                });

                function updateProductGrid() {
                    var productGrid = $('#product-grid');
                    productGrid.empty();
                    
                    table.rows({ page: 'current' }).data().each(function(value) {
                        var rowData = table.row(value).data();
                        var idProduit = rowData[0];
                        var nom = rowData[1];
                        var prix = rowData[2];
                        var image = rowData[3];

                        var productHtml = '<div class="product-item">' +
                            '<img src="' + image + '" alt="' + nom + '">' +
                            '<h5>' + nom + '</h5>' +
                            '<h5>$' + parseFloat(prix).toFixed(2) + '</h5>' +
                            '<form method="post" action="index.php?id=' + idProduit + '">' +
                            '<input type="hidden" name="nom" value="' + nom + '">' +
                            '<input type="hidden" name="prix_unitaire" value="' + parseFloat(prix).toFixed(2) + '">' +
                            '<input type="number" name="quantite" value="1" min="1" class="form-control">' +
                            '<button type="submit" name="add" class="btn btn-info btn-block">Ajouter au panier</button>' +
                            '</form>' +
                            '</div>';

                        productGrid.append(productHtml);
                    });
                }

                table.on('draw', updateProductGrid);
                updateProductGrid();
            });
        </script>
    </body>
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
