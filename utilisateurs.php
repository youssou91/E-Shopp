<?php 
include 'header.php';

// Vérifiez si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    echo '<script>window.location.href = "index.php";</script>';
    exit;
}

// Traitement des actions des utilisateurs
if (isset($_POST['action'])) {
    $userId = $_POST['user_id'];
    $action = $_POST['action'];
    switch ($action) {
        case 'bloquer':
            updateUserStatus($userId, 'bloqué');
            break;
        case 'debloquer':
            updateUserStatus($userId, 'actif');
            break;
        case 'supprimer':
            deleteUser($userId);
            break;
    }
    echo '<script>window.location.href = "utilisateurs.php";</script>';
}

// Récupérer la liste des utilisateurs
$users = getAllUsers();
?>

<div class="container">
    <h1 class="text-center text-primary">Liste des utilisateurs</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID Utilisateur</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id_utilisateur']); ?></td>
                    <td><?php echo htmlspecialchars($user['nom_utilisateur']); ?></td>
                    <td><?php echo htmlspecialchars($user['couriel']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td><?php echo htmlspecialchars($user['statut']); ?></td>
                    <td>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id_utilisateur']); ?>">
                            <button type="submit" name="action" value="bloquer" class="btn btn-danger btn-sm">Bloquer</button>
                            <button type="submit" name="action" value="debloquer" class="btn btn-success btn-sm">Débloquer</button>
                            <button type="submit" name="action" value="supprimer" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
