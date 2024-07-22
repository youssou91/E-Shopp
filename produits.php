<?php
include 'header.php';
$produits = getProduits();
?>

<body>
    <h1 class="text-center text-primary mt-5">Liste des produits</h1>
    <a class="btn btn-primary" href="ajout_produit.php">Ajouter un nouveau produit</a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Nom</th>
                <th scope="col">Quantité</th>
                <th scope="col">Prix Unitaire</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = 1;
                foreach ($produits as $produit) { ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td>
                            <img style='width: 50px; height: 50px; border-radius:50%;' src="<?php
                            echo (isset($produit['chemin_image']) && !empty($produit['chemin_image'])) ?
                            $produit['chemin_image'] : "upload_images/Image 001.jpeg"; ?>">
                        </td>
                        <td><?= $produit['nom']; ?></td>
                        <td><?= $produit['quantite']; ?></td>
                        <td><?= $produit['prix_unitaire']; ?></td>
                        <!-- <td><?= $produit['taille_produit']; ?></td> -->
                        <td>
                            <!-- Bouton pour ouvrir le modal -->
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-<?= $produit['id_produit']; ?>"><i class='bi bi-eye'></i></button>
                            
                            <a href="modifier_produit.php?id=<?= $produit['id_produit']; ?>" class='btn btn-primary'>
                                <i class='bi bi-pencil-square'></i>
                            </a>
                            <a href="suppression.php?id=<?= $produit['id_produit']; ?>" class='btn btn-danger'><i class='bi bi-trash-fill'></i></a>
                        </td>
                    </tr>

                    <!-- Modal pour chaque produit -->
                    <div class="modal fade" id="modal-<?= $produit['id_produit']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Détails du produit</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex justify-content-center">
                                        <img style='width: 100px; height: 100px; border-radius:50%;' src="<?= (isset($produit['chemin_image']) && !empty($produit['chemin_image'])) ? $produit['chemin_image'] : 'upload_images/Image 001.jpeg'; ?>" alt="Image du produit">
                                    </div>
                                    <!-- <img style='width: 100px; height: 100px; border-radius:50%; align:center;' src="<?= (isset($produit['chemin_image']) && !empty($produit['chemin_image'])) ? $produit['chemin_image'] : 'upload_images/Image 001.jpeg'; ?>" alt="Image du produit"> -->
                                    <p><strong>Nom produit: </strong> <?= $produit['nom']; ?></p>
                                    <hr>
                                    <p><strong>Quantité: </strong> <?= $produit['quantite']; ?></p>
                                    <hr>
                                    <p><strong>Prix Unitaire: </strong> <?= $produit['prix_unitaire']; ?></p>
                                    <hr>
                                    <p><strong>Taille: </strong> <?= $produit['taille_produit']; ?></p>
                                    <hr>
                                    <p><strong>Coute Description: </strong> <?= $produit['courte_description'];?></p>
                                    <hr>
                                    <p><strong>Longue Description: </strong> <?= $produit['description'];?></p>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
            }?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
