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
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Saisir le nom" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prenom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir le prenom" required>
        </div>
        <div class="mb-3">
            <label for="datNaiss" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" id="datNaiss" name="datNaiss" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Telephone</label>
            <input type="number" class="form-control" id="telephone" name="telephone" placeholder="Saisir le telephone" required>
        </div>
        <div class="mb-3">
            <label for="couriel" class="form-label">Email</label>
            <input type="email" class="form-control" id="couriel" name="couriel" placeholder="Saisir l'email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Saisir le mot de passe" required>
        </div>
        <div class="mb-3">
            <label for="cpassword" class="form-label">Confirmer Mot de passe</label>
            <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirmer Mot de passe" required>
        </div>
        <button type="submit" class="btn btn-primary" name="addUser">Ajouter Utilisateur</button>
    </form>
</body>
</html>
