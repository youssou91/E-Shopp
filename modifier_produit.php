<?php
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
    $produit = getProduitById($id_produit);
    if (isset($_POST['btn-mdp'])) {
        $_POST['id_produit'] = $id_produit;
        $resultat = updateProduit($_POST);
        if ($resultat) {
            ?>
            <script>
                window.location.href = "produits.php";
            </script>
            <?php
        } else {

        }

    }
}

?>

<body class="container mt-5">
<h1 class="text-center text-primary">Modification de produit</h1>
<form method="post">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?= $produit['nom']; ?>">
    </div>
    <div class="mb-3">
        <label for="prix_unitaire" class="form-label">Prix unitaire</label>
        <input type="text" class="form-control" id="prix_unitaire" name="prix_unitaire"
               value="<?= $produit['prix_unitaire']; ?>">
    </div>
    <div class="mb-3">
        <label for="quantite" class="form-label">Quantite</label>
        <input type="number" min="0" class="form-control" id="quantite" name="quantite"
               value="<?= $produit['quantite']; ?>">
    </div>
    <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Courte description" name="courte_description" id="courte_description">
            <?= $produit['courte_description']; ?>
        </textarea>
        <label for="floatingTextarea">Courte description</label>
    </div>
    <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Description" name="description" id="description">
            <?=$produit['description']; ?>
        </textarea>
        <label for="floatingTextarea">Description</label>
    </div>
    <input type="submit" class="btn btn-primary" name="btn-mdp" value="Modifier un produit">
</form>


</body>
</html>