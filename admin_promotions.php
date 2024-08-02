<?php
// include_once 'database_connection.php';
include_once 'header.php';

$connect = connexionDB();

$query = "SELECT pp.id_promotion, p.nom AS nom_produit, pr.code_promotion, pr.valeur, pr.date_debut, pr.date_fin 
          FROM produitpromotion pp 
          JOIN produits p ON pp.id_produit = p.id_produit
          JOIN promotions pr ON pp.id_promotion = pr.id_promotion";

$result = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Promotions</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body><?php
include_once 'header.php';

$connect = connexionDB();

$query = "SELECT pp.id_promotion, p.nom AS nom_produit, pr.valeur, pr.date_debut, pr.date_fin 
          FROM produitpromotion pp 
          JOIN produits p ON pp.id_produit = p.id_produit
          JOIN promotions pr ON pp.id_promotion = pr.id_promotion";


$result = mysqli_query($connect, $query);

echo '<div class="container mt-5">';
echo '<h1 class="mb-4">Gestion des promotions</h1>';
echo '<a href="admin_add_promo.php" class="btn btn-primary mb-3">Ajouter une promotion</a>';
echo '<table class="table table-striped table-bordered">';
echo '<thead class="thead-dark">';
echo '<tr><th>Produit</th><th>Réduction</th><th>Date de début</th><th>Date de fin</th><th>Actions</th></tr>';
echo '</thead>';
echo '<tbody>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $row['nom_produit'] . '</td>';
    echo '<td>' . $row['valeur'] . '%</td>';
    echo '<td>' . $row['date_debut'] . '</td>';
    echo '<td>' . $row['date_fin'] . '</td>';
    echo '<td>';
    echo '<a href="admin_edit_promo.php?id=' . $row['id_promotion'] . '" class="btn btn-warning btn-sm mr-2">Modifier</a>';
    echo '<a href="admin_delete_promo.php?id=' . $row['id_promotion'] . '" class="btn btn-danger btn-sm">Supprimer</a>';
    echo '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
echo '</div>';

include_once 'footer.php';
?>