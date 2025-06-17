<?php
include 'header.php';
$categories = getAllCategories();
if (isset($_POST['btnAjout'])) {
    $resultat = ajoutProduit($_POST, $_FILES);
    if ($resultat) {
        echo "<script>window.location.href = 'produits.php';</script>";
    } else {
        echo "Erreur lors de l'ajout du produit";
    }
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
?>
<html>
    <body class="container mt-5">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom produit</label>
                <input type="text" class="form-control" id="nom" name="nom_prod" placeholder="Nom produit" required>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label">Prix unitaire</label>
                <input type="number" class="form-control" id="prix" name="prix_prod" placeholder="Prix unitaire" required>
            </div>
            <div class="mb-3">
                <label for="quantite" class="form-label">Quantite</label>
                <input type="number" class="form-control" id="quantite" name="quantite_prod" placeholder="Quantite produit" required>
            </div>
            <div class="mb-3">
                <label for="id_categorie" class="form-label">Categorie</label>
                <select name="id_categorie" id="id_categorie" class="form-control" required>
                    <option selected value="">Choisir une categorie</option>
                    <?php
                    $categories = getAllCategories();
                    if (!empty($categories)) {
                        foreach ($categories as $categorie) {
                            echo "<option value=\"". $categorie["id_categorie"]. "\">". $categorie["nom_categorie"]. "</option>";
                        }
                    } else {
                        echo "<option value=\"\">Aucune categorie trouvee</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="taille_produit" class="form-label">Taille</label>
                <select name="taille_produit" id="taille_produit" class="form-control" required>
                    <option selected value="">Choisir une taille</option>
                    <option value="Small">-------S-------</option>
                    <option value="Medium">-------M-------</option>
                    <option value="Large">-------L-------</option>
                    <option value="XL">-------XL-------</option>
                    <option value="XXL">-------XXL-------</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <div class="mb-3">
                <label for="courteDescription" class="form-label">Courte description</label>
                <input type="text" class="form-control" id="courteDescription" name="courteDescription_prod" placeholder="Courte description" required>
            </div>
            <div class="mb-3">
                <label for="longueDescription" class="form-label">Longue Description</label>
                <textarea class="form-control" id="longueDescription" name="longueDescription_prod" placeholder="Longue Description" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Sexe</label>
                <div>
                    <input type="radio" id="homme" name="sexe_prod" value="Homme" required>
                    <label for="homme">Homme</label>
                </div>
                <div>
                    <input type="radio" id="femme" name="sexe_prod" value="Femme" required>
                    <label for="femme">Femme</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Couleurs disponibles</label>
                <div>
                    <input type="checkbox" id="rouge" name="couleurs_prod[]" value="Rouge">
                    <label for="rouge">Rouge</label>
                </div>
                <div>
                    <input type="checkbox" id="bleu" name="couleurs_prod[]" value="Bleu">
                    <label for="bleu">Bleu</label>
                </div>
                <div>
                    <input type="checkbox" id="vert" name="couleurs_prod[]" value="Vert">
                    <label for="vert">Vert</label>
                </div>
                <div>
                    <input type="checkbox" id="noir" name="couleurs_prod[]" value="Noir">
                    <label for="noir">Noir</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="btnAjout">Submit</button>
        </form>
    </body>
    <!-- footer -->
    <?php include 'footer.php';?>
</html>
