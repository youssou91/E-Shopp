<?php

include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_utilisateur'])) {
        echo '<script>alert("Veuillez vous connecter pour passer une commande."); window.location.href = "connexion.php";</script>';
        exit();
    }

    $id_utilisateur = $_SESSION['id_utilisateur'];
    $date_commande = date('Y-m-d H:i:s');
    $total = $_POST['total'];
    $produits = $_POST['produits'];

    $commande = [
        'id_utilisateur' => $id_utilisateur,
        'date_commande' => $date_commande,
        'prix_total' => $total,
        'produits' => $produits
    ];

    $id_commande = addCommande($commande);
    if ($id_commande) {
        unset($_SESSION['cart']); // Vider le panier après la commande
        echo '<script>alert("Commande enregistrée avec succès !"); window.location.href = "index.php";</script>';
    } else {
        echo '<script>alert("Erreur lors de l\'enregistrement de la commande !!!");</script>';
    }
}

?>
