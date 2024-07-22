<?php 
include 'header.php';

if (isset($_POST['btn-connexion'])){
    $email = $_POST['couriel'];
    $password = $_POST['mot_de_pass'];
    
    $conn = connexionDB();

    // Appeler la fonction getElementByEmailForLogin pour vérifier les informations d'identification
    $user = getElementByEmailForLogin($email, $conn);
    
    if ($user && password_verify($password, $user['mot_de_pass'])) {
        // Si l'utilisateur est trouvé et que le mot de passe est correct, stocker les informations dans la session
        session_start();
        $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
        $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
        $_SESSION['user'] = $user;
        $_SESSION['loggedin'] = true; // Définir la session de connexion
        
        // Rediriger l'utilisateur vers la page d'accueil
        echo '<script>window.location.href = "index.php";</script>';
    } else {
        // Si l'utilisateur n'est pas trouvé, afficher un message d'erreur
        echo '<div class="alert alert-danger" role="alert">Email ou mot de passe incorrect</div>';
    }
}
?>

<form class="container" method="POST">
    <h1 class="text-center text-primary">Page de connexion </h1>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" class="form-control" name="couriel" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="mot_de_pass" class="form-control" id="exampleInputPassword1">
    </div>
    <div class="mb-3 form-check">
        <a href="" class="btn-btn-info">
            <label class="form-check-label" for="exampleCheck1">Mot de passe oublié</label>
        </a>
        <br>            
        <a href="addUsers.php" class="btn-btn-info">
            <label class="form-check-label" for="exampleCheck1">S'inscrire</label>
        </a>
    </div>
    <input type="submit" name="btn-connexion" value="Se connecter" class="btn btn-primary">
</form>
