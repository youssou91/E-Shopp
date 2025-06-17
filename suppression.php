<?php
// include 'controlleur.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    ?>
    <script>
        window.location.href = "produits.php";
    </script>
    <?php
    exit();
} else {
    include 'header.php';
    $id_produit = $_GET['id'];
    $resultat = deleteProduit($id_produit);
    if ($resultat) {
        ?>
        <script>
            window.location.href = "produits.php";
        </script>
        <?php
    } else {
        ?>
        <script>
            alert('Erreur lors de la suppression du produit.');
            window.location.href = "produits.php";
        </script>
        <?php
    }
}
?>
