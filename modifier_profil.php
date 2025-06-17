<?php
include 'header.php';

if (!isset($_SESSION['id_utilisateur'])) {
    echo '<script>window.location.href = "connexion.php";</script>';
    exit;
}

$userId = $_SESSION['id_utilisateur'];
$userInfo = getUserInfo($userId); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profile = [
        'nom_utilisateur' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'date_naissance' => $_POST['date_naissance'],
        'telephone' => $_POST['telephone'],
        'couriel' => $_POST['email'],
        'id_utilisateur' => $userId
    ];

    $adresse = [
        'rue' => $_POST['rue'],
        'numero' => $_POST['numero'],
        'ville' => $_POST['ville'],
        'code_postal' => $_POST['code_postal'],
        'province' => $_POST['province'],
        'pays' => $_POST['pays']
    ];

    if (editProfile($profile, $adresse)) {
        echo '<script> window.location.href = "profile.php";</script>';
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
            <!-- Informations Personnelles -->
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

            <!-- Adresse -->
            <div class="mb-3">
                <label for="rue" class="form-label">Rue</label>
                <input type="text" class="form-control" id="rue" name="rue" value="<?= htmlspecialchars($userInfo['rue']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($userInfo['numero']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="ville" class="form-label">Ville</label>
                <input type="text" class="form-control" id="ville" name="ville" value="<?= htmlspecialchars($userInfo['ville']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="code_postal" class="form-label">Code Postal</label>
                <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?= htmlspecialchars($userInfo['code_postal']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="province" class="form-label">Province</label>
                <input type="text" class="form-control" id="province" name="province" value="<?= htmlspecialchars($userInfo['province']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="pays" class="form-label">Pays</label>
                <input type="text" class="form-control" id="pays" name="pays" value="<?= htmlspecialchars($userInfo['pays']) ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
    </div>
</body>
<?php include 'footer.php'; ?>

</html>
