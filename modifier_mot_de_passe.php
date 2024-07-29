<?php
include 'header.php';

if (!isset($_SESSION['id_utilisateur'])) {
    echo '<script>window.location.href = "connexion.php";</script>';
    exit;
}

$userId = $_SESSION['id_utilisateur'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ancienMotDePasse = $_POST['ancien_mot_de_passe'];
    $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'];
    $confirmerMotDePasse = $_POST['confirmer_mot_de_passe'];
    
    if ($nouveauMotDePasse == $confirmerMotDePasse) {
        // Ajouter une fonction pour vérifier l'ancien mot de passe et mettre à jour le nouveau mot de passe
        if (updateUserPassword($userId, $ancienMotDePasse, $nouveauMotDePasse)) {
            echo '<script>alert("Mot de passe mis à jour avec succès."); window.location.href = "profile.php";</script>';
            exit;
        } else {
            $error = "L'ancien mot de passe est incorrect.";
        }
    } else {
        $error = "Les nouveaux mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Modifier le mot de passe</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="ancien_mot_de_passe" class="form-label">Ancien mot de passe</label>
                <input type="password" class="form-control" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
            </div>
            <div class="mb-3">
                <label for="nouveau_mot_de_passe" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required>
            </div>
            <div class="mb-3">
                <label for="confirmer_mot_de_passe" class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" class="form-control" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
        </form>
    </div>
</body>
<?php include 'footer.php'; ?>
</html>
