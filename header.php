<?php
session_start();
include 'controlleur.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="index.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- Header design -->
    <script src="js/script.js"></script>
    <header class="header">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">YAHLI-SHOP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="contact.php">Contact</a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="commandesAdmin.php">Commandes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="produits.php">Produits</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="utilisateurs.php">Utilisateurs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_promotions.php">Promo</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!-- Ajouter des liens conditionnels -->
                        <?php if (isset($_SESSION['id_utilisateur'])): ?>
                            <!-- Si l'utilisateur est connecté -->
                            <li class="nav-item">
                                <a class="nav-link" href="profile.php">Mon Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-danger nav-link" href="deconnexion.php">
                                    <i class="bi bi-box-arrow-right"></i> 
                                    Déconnexion
                                </a>
                            </li>
                        <?php else: ?>
                            <!-- Si l'utilisateur n'est pas connecté -->
                            <li class="nav-item">
                                <a class="btn btn-primary nav-link" href="connexion.php">
                                    <i class="bi bi-box-arrow-in-right"></i> 
                                    Connexion
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
     <!-- afficher une image en forme de carroselle -->
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></li>
            <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img style='width: 200px; height: 200px;'  src="./images/color.jpg"  class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img style='width: 200px; height: 200px;'  src="./images/Media.jpg"  class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img style='width: 200px; height: 200px;'  src="./images/black.jpg"  class="d-block w-100" alt="...">
            </div>  
        </div>
    </div>
</body>
</html>
