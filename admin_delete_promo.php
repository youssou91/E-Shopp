<?php
include_once 'header.php';

$connect = connexionDB();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<script>window.location.href = "admin_promotions.php";</script>';
    exit();
}

$id_promo = $_GET['id'];

$query = "DELETE FROM produitpromotion WHERE id_promotion = ?";
if ($stmt = mysqli_prepare($connect, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $id_promo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo '<script>window.location.href = "admin_promotions.php";</script>';
} else {
    echo 'Erreur : ' . mysqli_error($connect);
}
?>
