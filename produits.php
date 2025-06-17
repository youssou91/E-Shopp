<?php
include 'header.php';
$produits = getProduits();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    </head>
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
                                $produit['chemin_image'] : "images/Image 001.jpeg"; ?>">
                            </td>
                            <td><?= $produit['nom']; ?></td>
                            <td><?= $produit['quantite']; ?></td>
                            <td><?= $produit['prix_unitaire']; ?></td>
                            <td>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-<?= $produit['id_produit']; ?>"><i class='bi bi-eye'></i></button>
                                <a href="modifier_produit.php?id=<?= $produit['id_produit']; ?>" class='btn btn-primary'>
                                    <i class='bi bi-pencil-square'></i>
                                </a>
                                <button class='btn btn-danger' data-bs-toggle="modal" data-bs-target="#modalSupprimerProduit<?= $produit['id_produit']; ?>">
                                    <i class='bi bi-trash-fill'></i>
                                </button>
                            </td>
                        </tr>
                    
                        <!-- Modal pour les détails de chaque produit -->
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
                    
                        <!-- Modal pour la suppression de chaque produit -->
                        <div class="modal fade" id="modalSupprimerProduit<?= $produit['id_produit']; ?>" tabindex="-1" aria-labelledby="modalSupprimerLabel<?= $produit['id_produit']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalSupprimerLabel<?= $produit['id_produit']; ?>">Confirmation de suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Voulez-vous vraiment supprimer ce produit ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <a href="suppression.php?id=<?= $produit['id_produit']; ?>" class="btn btn-danger">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } 
                    
                ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function() {
                $('.table').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true
                });
            });
        </script>
    </body>
    <?php include 'footer.php'; ?>

</html>

