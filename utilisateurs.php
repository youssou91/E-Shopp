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
            updateUserStatus($userId, 'bloque');
            break;
        case 'debloquer':
            updateUserStatus($userId, 'actif');
            break;
        
    }
    echo '<script>window.location.href = "utilisateurs.php";</script>';
}
// Récupérer la liste des utilisateurs
$users = getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta tags, title, etc. -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center text-primary">Liste des utilisateurs</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
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
                                    <!-- <button type="submit" name="action" value="supprimer" class="btn btn-danger btn-sm">Supprimer</button> -->
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <script>
            $(document).ready(function() {
                $('.table').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/fr_fr.json" // Traduction française
                    }
                });
            });
        </script>
    </body>
    <?php include 'footer.php';?>
</html>
