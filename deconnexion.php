<?php 
//la page de deconnexion des utilisateurs 
// elle retourne a la page de connexion

// include 'header.php';
// if (isset($_POST['btn-connexion'])){
//     $email = $_POST['courriel'];
//     $password = $_POST['mot_de_passe'];
//     $resultat = checkUser($email, $password);
//     if($resultat){
//         $_SESSION['user'] = $resultat;
//         echo '<script>window.location.href = "connexion.php";</script>';
//     } else {
//         // echo '<div class="alert alert-danger" role="alert">Email ou mot de passe incorrect</div>';
//     }
// }

session_start();
session_unset();
session_destroy();
header("Location: connexion.php");
exit();
?>
