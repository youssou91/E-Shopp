<?php
include 'header.php';

if (!isset($_SESSION['id_utilisateur'])) {
    echo '<script>window.location.href = "connexion.php";</script>';
    exit;
}

$userId = $_SESSION['id_utilisateur'];
$userInfo = getUserInfo($userId); // Vous devez avoir cette fonction pour récupérer les infos utilisateur actuelles

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profile = [
        'nom_utilisateur' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'date_naissance' => $_POST['date_naissance'],
        'telephone' => $_POST['telephone'],
        'couriel' => $_POST['email'],
        'id_utilisateur' => $userId
    ];

    if (editProfile($profile)) {
        echo '<script>alert("Profil mis à jour avec succès."); window.location.href = "profile.php";</script>';
        exit;
    } else {
        echo '<script>alert("Une erreur est survenue lors de la mise à jour du profil.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Modifier Profil</h1>
        <form method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($userInfo['nom_utilisateur']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($userInfo['prenom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="date_naissance" class="form-label">Date de Naissance</label>
                <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($userInfo['date_naissance']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($userInfo['telephone']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userInfo['couriel']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
    </div>
</body>
</html>
