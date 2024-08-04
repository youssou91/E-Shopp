<?php 
include 'header.php';

if (isset($_POST['btn-connexion'])){
    $email = $_POST['couriel'];
    $password = $_POST['mot_de_pass'];
    
    // Appeler la fonction checkUser pour vérifier les informations d'identification
    $user = checkUser($email, $password);
    
    if ($user && password_verify($password, $user['mot_de_pass'])) {
        // Vérifier si l'utilisateur a le statut "actif"
        if ($user['statut'] === 'actif') {
            // Si l'utilisateur est trouvé, que le mot de passe est correct et que le statut est actif
            $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
            $_SESSION['nom_utilisateur'] = $user['nom_utilisateur']; // Optionnel
            $_SESSION['user'] = $user;
            $_SESSION['role'] = $user['role']; // Stocker le rôle de l'utilisateur
            $_SESSION['loggedin'] = true; // Définir la session de connexion
            
            // Rediriger l'utilisateur vers la page d'accueil
            echo '<script>window.location.href = "index.php";</script>';
        } else {
            // Si le statut n'est pas actif, afficher un message d'erreur
            echo '<div class="alert alert-info" role="alert">Votre compte est désactivé. Veuillez contacter l\'administrateur pour plus d\'informations.</div>';
        }
    } else {
        // Si l'utilisateur n'est pas trouvé, afficher un message d'erreur
        echo '<div class="alert alert-danger" role="alert">Email ou mot de passe incorrect</div>';
    }
}
?>

<form class="container" method="POST">
    <h1 class="text-center text-primary">Page de connexion</h1>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Adresse email</label>
        <input type="email" class="form-control" name="couriel" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
        <input type="password" name="mot_de_pass" class="form-control" id="exampleInputPassword1">
    </div>
    <div class="mb-3 form-check">
        <a href="addUsers.php">
                S'inscrire
        </a>
    </div>
    <input type="submit" name="btn-connexion" value="Se connecter" class="btn btn-primary">
</form>

<?php include 'footer.php';?>
