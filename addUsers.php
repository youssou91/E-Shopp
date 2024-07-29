<?php
include 'header.php';

$errorMessage = "";

if (isset($_POST['addUser'])) {
    $resultat = addUserDB($_POST);
    if ($resultat === true) {
        echo '<div class="alert alert-success" role="alert">Utilisateur ajouté avec succès.</div>';
    } else {
        $errorMessage = $resultat;
    }
}
?>

<body class="container mt-5">
    <?php
    if ($errorMessage) {
        echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
    }
    ?>
     <form method="POST">
        <!-- Informations Personnelles -->
        <div class="card mb-3">
            <div class="card-header">Informations Personnelles</div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom_utilisateur" placeholder="Saisir le nom" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir le prénom" required>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="datNaiss" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="datNaiss" name="datNaiss" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coordonnées -->
        <div class="card mb-3">
            <div class="card-header">Coordonnées</div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="number" class="form-control" id="telephone" name="telephone" placeholder="Saisir le téléphone" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="couriel" class="form-label">Email</label>
                        <input type="email" class="form-control" id="couriel" name="couriel" placeholder="Saisir l'email" required>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Saisir le mot de passe" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="cpassword" class="form-label">Confirmer Mot de passe</label>
                        <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirmer Mot de passe" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adresse -->
        <div class="card mb-3">
            <div class="card-header">Adresse</div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="rue" class="form-label">Rue</label>
                        <input type="text" class="form-control" id="rue" name="rue" placeholder="Saisir la rue" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="numero" class="form-label">Numéro</label>
                        <input type="text" class="form-control" id="numero" name="numero" placeholder="Saisir le numéro" required>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="ville" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="ville" name="ville" placeholder="Saisir la ville" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="code_postal" class="form-label">Code Postal</label>
                        <input type="text" class="form-control" id="code_postal" name="code_postal" placeholder="Saisir le code postal" required>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="province" class="form-label">Province</label>
                        <input type="text" class="form-control" id="province" name="province" placeholder="Saisir la province" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="pays" class="form-label">Pays</label>
                        <input type="text" class="form-control" id="pays" name="pays" placeholder="Saisir le pays" value="Canada" required>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="addUser">Ajouter Utilisateur</button>
    </form>
</body>
<?php include 'footer.php'; ?>

</html>
