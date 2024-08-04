<?php   
    include 'config.php';
    //fonction de connection a la base de donnees
function connexionDB(){
    $conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);
    if($conn != true){
        die('Erreur de connection avec la base de donnée : '.mysqli_connect_error());
    }
    else{
        return $conn;
    }
}
   // Ajout de produits dans la base de données
// function ajoutProduit($produit, $data) {
//     $nom = $produit['nom_prod'];
//     $prix = $produit['prix_prod'];
//     $description = $produit['longueDescription_prod'];
//     $courte_description = $produit['courteDescription_prod'];
//     $quantite = $produit['quantite_prod'];
//     $id_categorie = $produit['id_categorie'];
//     $taille_produit = $produit['taille_produit'];
    
//     $sql = "INSERT INTO produits (nom, prix_unitaire, description, courte_description, quantite, id_categorie, taille_produit) VALUES (?, ?, ?, ?, ?, ?, ?)";
//     $conn = connexionDB();
//     $stmt = mysqli_prepare($conn, $sql);
//     mysqli_stmt_bind_param($stmt, "sdssiis", $nom, $prix, $description, $courte_description, $quantite, $id_categorie, $taille_produit);
//     $resultat = mysqli_stmt_execute($stmt);

//     if ($resultat) {
//         $id_produit = mysqli_insert_id($conn);
//         if (uploadImage($data, $id_produit)) {
//             return true;
//         } else {
//             echo "Erreur lors du téléchargement de l'image.";
//             return false;
//         }
//     }

//     echo "Erreur lors de l'insertion du produit : " . mysqli_error($conn);
//     return false;
// }
function ajoutProduit($produit, $data) {
    $nom = $produit['nom_prod'];
    $prix = $produit['prix_prod'];
    $description = $produit['longueDescription_prod'];
    $courte_description = $produit['courteDescription_prod'];
    $quantite = $produit['quantite_prod'];
    $id_categorie = $produit['id_categorie'];
    $taille_produit = $produit['taille_produit'];
    $sexe_prod = $produit['sexe_prod'];
    $couleurs_prod = implode(", ", $produit['couleurs_prod']); // Concatène les couleurs sélectionnées

    $sql = "INSERT INTO produits (nom, prix_unitaire, description, courte_description, quantite, id_categorie, taille_produit, sexe_prod, couleurs_prod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $conn = connexionDB();
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdssiisss", $nom, $prix, $description, $courte_description, $quantite, $id_categorie, $taille_produit, $sexe_prod, $couleurs_prod);
    $resultat = mysqli_stmt_execute($stmt);

    if ($resultat) {
        $id_produit = mysqli_insert_id($conn);
        if (uploadImage($data, $id_produit)) {
            return true;
        } else {
            echo "Erreur lors du téléchargement de l'image.";
            return false;
        }
    } else {
        echo "Erreur lors de l'ajout du produit.";
        return false;
    }
}


    // Ajout d'image
function uploadImage($data, $id_produit) {
    if (isset($data['image']) && $data['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $data['image']['name'];
        $image_destination = 'images/' . basename($image_name);
        $from = $data['image']['tmp_name'];
        $image_type = strtolower(pathinfo($image_destination, PATHINFO_EXTENSION));

        if (in_array($image_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($from, $image_destination)) {
                $image = ['chemin_image' => $image_destination, 'id_produit' => $id_produit];
                if (ajoutImage($image)) {
                    return true;
                } else {
                    echo "Erreur lors de l'insertion de l'image dans la base de données. ";
                    return false;
                }
            } else {
                echo "Impossible de déplacer le fichier. ";
                return false;
            }
        } else {
            echo "Extension de fichier non valide  : $image_type";
            return false;
        }
    } else {
        echo "Erreur lors du téléchargement du fichier  : " . ($data['image']['error'] ?? 'Erreur inconnue');
        return false;
    }
}
    // ajout du fichier image
function ajoutImage($image) {
    $chemin = $image['chemin_image'];
    $id_produit = $image['id_produit'];

    $sql = "INSERT INTO image (chemin_image, id_produit) VALUES (?, ?)";
    $conn = connexionDB();
    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, 'si', $chemin, $id_produit);

    if (mysqli_stmt_execute($statement)) {
        return true;
    } else {
        echo "Erreur lors de l'insertion de l'image : " . mysqli_error($conn);
        return false;
    }
}
    // verification de la validite de l'email
function emailFormat($email) {
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email) === 1;
}
//fonction de deconnexion
function deconnexionDB($conn) {
    if (isset($conn)) {
        mysqli_close($conn);
    }
}

//reinitialisation du password           
function checkUser($email, $password) {
    $conn = connexionDB();
    $user = getElementByEmailForLogin($email, $conn);
    $stmt = $conn->prepare("SELECT u.*, r.description as role
        FROM utilisateur u
        JOIN role_utilisateur ru ON u.id_utilisateur = ru.id_utilisateur
        JOIN role r ON ru.id_role = r.id_role
        WHERE u.couriel = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;

    //////////////////////////////////////////////////////////////// 
}

   // Vérification de l'unicite de l'email
function getElementByEmail($email, $password, $conn) {
    $sql = "SELECT * FROM utilisateur WHERE couriel = ? AND mot_de_pass = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    return $user;
}
// Récupération des informations utilisateur et adresse par son id
function getUserInfo($id_utilisateur){
    $conn = connexionDB();
    $sql = "SELECT u.*, a.rue, a.numero, a.ville, a.code_postal, a.province, a.pays
            FROM utilisateur u
            LEFT JOIN utilisateur_adresse ua ON u.id_utilisateur = ua.id_utilisateur
            LEFT JOIN adresse a ON ua.id_adresse = a.id_adresse
            WHERE u.id_utilisateur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_utilisateur);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
    $stmt->close();
    $conn->close();
}

// Modification des informations utilisateur
function editProfile($profile, $adresse){
    $conn = connexionDB();
    $sql = "UPDATE utilisateur 
            SET nom_utilisateur = ?, prenom = ?, date_naissance = ?, couriel = ?, telephone = ? 
            WHERE id_utilisateur = ?";
    // Vérifier que l'email est valide
    if (!emailFormat($profile['couriel'])) {
        throw new Exception("Le format de l'email n'est pas valide.");
    }

    // Vérifier que l'email est unique
    if (getElementByEmailForAddUser($profile['couriel'], $conn)) {
        throw new Exception("Email existe déjà dans la base de données.");
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", 
        $profile['nom_utilisateur'], 
        $profile['prenom'], 
        $profile['date_naissance'], 
        $profile['couriel'], 
        $profile['telephone'], 
        $profile['id_utilisateur']);
    
    if ($stmt->execute()) {
        $sqlAdresse = "UPDATE adresse 
                       SET rue = ?, numero = ?, ville = ?, code_postal = ?, province = ?, pays = ? 
                       WHERE id_adresse = (SELECT id_adresse FROM utilisateur_adresse WHERE id_utilisateur = ?)";
        $stmtAdresse = $conn->prepare($sqlAdresse);
        $stmtAdresse->bind_param("ssssssi", 
            $adresse['rue'], 
            $adresse['numero'], 
            $adresse['ville'], 
            $adresse['code_postal'], 
            $adresse['province'], 
            $adresse['pays'], 
            $profile['id_utilisateur']);
        
        return $stmtAdresse->execute();
    } else {
        return false;
    }
}

   // Calcul de l'âge 
function calculAge($anneeNaiss) {
    $annee = new DateTime($anneeNaiss);
    $anneeCourante = new DateTime();
    $age = $anneeCourante->diff($annee)->y;
    return $age;
}

function addUserDB($user) {
    $conn = connexionDB();

    // Démarrer une transaction
    mysqli_begin_transaction($conn);

    try {
        // Ajouter un utilisateur
        $id_utilisateur = insertUser($user, $conn);

        // Ajouter une adresse
        $id_adresse = insertAddress($user, $conn);

        // Associer l'utilisateur et l'adresse
        associateUserAddress($id_utilisateur, $id_adresse, $conn);

        // Associer un rôle à l'utilisateur
        assignUserRole($id_utilisateur, 'client', $conn);

        // Valider la transaction
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        mysqli_rollback($conn);
        return "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
}

function insertUser($user, $conn) {
    $nom = $user['nom_utilisateur'];
    $prenom = $user['prenom'];
    $datNaiss = $user['datNaiss'];
    $telephone = $user['telephone'];
    $emailUser = $user['couriel'];
    $password = $user['password'];
    $cpassword = $user['cpassword'];
    $statut = 'actif'; // Statut par défaut

    // Vérification des données utilisateur
    validateUserData($emailUser, $password, $cpassword, $datNaiss, $conn);

    // Hashage du mot de passe
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Insertion dans la table utilisateur
    $sql = "INSERT INTO utilisateur (nom_utilisateur, prenom, date_naissance, couriel, mot_de_pass, telephone, statut) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $nom, $prenom, $datNaiss, $emailUser, $password, $telephone, $statut);
    mysqli_stmt_execute($stmt);

    return mysqli_insert_id($conn);
}

function insertAddress($user, $conn) {
    $rue = $user['rue'];
    $numero = $user['numero'];
    $ville = $user['ville'];
    $code_postal = $user['code_postal'];
    $province = $user['province'];
    $pays = $user['pays'];

    // Insertion dans la table adresse
    $sql = "INSERT INTO adresse (rue, ville, code_postal, pays, numero, province) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $rue, $ville, $code_postal, $pays, $numero, $province);
    mysqli_stmt_execute($stmt);

    return mysqli_insert_id($conn);
}

function associateUserAddress($id_utilisateur, $id_adresse, $conn) {
    $sql = "INSERT INTO utilisateur_adresse (id_utilisateur, id_adresse) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_utilisateur, $id_adresse);
    mysqli_stmt_execute($stmt);
}

function assignUserRole($id_utilisateur, $role_description, $conn) {
    $role = getRoleByDescription($role_description);
    $sql = "INSERT INTO role_utilisateur (id_role, id_utilisateur) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $role['id_role'], $id_utilisateur);
    mysqli_stmt_execute($stmt);
}

function validateUserData($email, $password, $cpassword, $birthDate, $conn) {
    // Vérifier que l'email est valide
    if (!emailFormat($email)) {
        throw new Exception("Le format de l'email n'est pas valide.");
    }

    // Vérifier que l'email est unique
    if (getElementByEmailForAddUser($email, $conn)) {
        throw new Exception("Email existe déjà dans la base de données.");
    }

    // Vérifier que la taille du mot de passe est valide
    if (strlen($password) < 6 || !preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[@$!%*?&]/', $password)) {
        throw new Exception("Le mot de passe doit contenir au moins 6 caractères, une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.");
    }

    // Vérifier que les mots de passe sont identiques
    if ($password !== $cpassword) {
        throw new Exception("Les mots de passe ne correspondent pas.");
    }

    // Vérifier que l'utilisateur a au moins 16 ans
    if (calculAge($birthDate) < 16) {
        throw new Exception("L'utilisateur doit avoir au moins 16 ans.");
    }
}

// Vérification de l'unicité de l'email lors de l'ajout d'un utilisateur
function getElementByEmailForAddUser($email, $conn) {
    $sql = "SELECT * FROM utilisateur WHERE couriel = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}

// Vérification de l'email et du mot de passe lors de la connexion
function getElementByEmailForLogin($email, $conn) {
    $sql = "SELECT * FROM utilisateur WHERE couriel = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}

// Récupérer tous les utilisateurs
function getAllUsers() {
    $conn = connexionDB();
    $sql = "SELECT u.*, r.description as role 
            FROM utilisateur u 
            JOIN role_utilisateur ru ON u.id_utilisateur = ru.id_utilisateur 
            JOIN role r ON ru.id_role = r.id_role";
    $result = $conn->query($sql);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $conn->close();
    return $users;
}
function update_commandeOrderstatut($orderId, $newStatus) {
    $conn = connexionDB();
    $stmt = $conn->prepare("UPDATE commande SET statut = ? WHERE id_commande = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}
// Récupérer toutes les commandes
function getAllcommandes() {
    $conn = connexionDB();
    // $sql = "SELECT * FROM commande";
    $sql = "SELECT 
            commande.id_commande, 
            commande.id_utilisateur, 
            commande.date_commande, 
            commande.prix_total, 
            utilisateur.nom_utilisateur, 
            utilisateur.prenom,
            commande.statut
        FROM 
            commande
        INNER JOIN  utilisateur ON commande.id_utilisateur = utilisateur.id_utilisateur";
    $result = $conn->query($sql);
    $commande = [];
    while ($row = $result->fetch_assoc()) {
        $commande[] = $row;
    }
    $conn->close();
    return $commande;
}

// Mettre à jour le statut d'une commande
function updatecommandetatus($orderId, $status) {
    $conn = connexionDB();
    $sql = "UPDATE commande SET statut=? WHERE id_order=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $orderId);
    $stmt->execute();
    $conn->close();
}

// Mettre à jour le statut d'un utilisateur
function updateUserStatus($userId, $status) {
    $conn = connexionDB();
    $sql = "UPDATE utilisateur SET statut=? WHERE id_utilisateur=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $userId);
    $stmt->execute();
    $conn->close();
}

// Fonction pour mettre à jour le statut de la commande
function updateCommandeStatus($commandId, $statusId) {
    $conn = connexionDB();
    $sql = "UPDATE commande SET id_statut = ? WHERE id_commande = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "ii", $statusId, $commandId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Fonction pour récupérer les statuts de commande
function getCommandeStatuses() {
    $conn = connexionDB();
    $sql = "SELECT * FROM statuts_commande";
    $result = mysqli_query($conn, $sql);
    $statuses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $statuses[] = $row;
    }
    return $statuses;
}

// Fonction pour récupérer les commandes d'un utilisateur avec leurs statuts


function getUserCommandWithStatus($userId) {
    $conn = connexionDB();
    $sql = "SELECT *
            FROM commande
            
            WHERE id_utilisateur = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $commande = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $commande[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $commande;
}

// Fonction de modification du mot de passe
function updateUserPassword($userId, $ancienMotDePasse, $nouveauMotDePasse) {
    $conn = connexionDB();
    $stmt = $conn->prepare("SELECT mot_de_pass FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($motDePasseActuel);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($ancienMotDePasse, $motDePasseActuel)) {
        $nouveauMotDePasseHash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE utilisateur SET mot_de_pass = ? WHERE id_utilisateur = ?");
        $stmt->bind_param("si", $nouveauMotDePasseHash, $userId);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        return false;
    }
}


function getStatutIdByDescription($description, $conn) {
    $sql = "SELECT id_statut FROM statuts_commande WHERE description = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $description);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_statut);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    return $id_statut;
}

function getStatusClass($statusDescription) {
    switch ($statusDescription) {
        case 'En attente':
            return 'status-pending';
        case 'En traitement':
            return 'status-processing';
        case 'Expédiée':
            return 'status-shipped';
        case 'Livrée':
            return 'status-delivered';
        case 'Annulée':
            return 'status-cancelled';
        default:
            return '';
    }
}

    // lister les produits de la base de données
function getProduits(){
    $sql = "SELECT p.*, i.chemin_image FROM  produits p LEFT JOIN image i ON p.id_produit = i.id_produit";
    $conn = connexionDB();
    $resultats = mysqli_query($conn, $sql);
    $produits = [];
    if (mysqli_num_rows($resultats) > 0) {
        while ($produit = mysqli_fetch_assoc($resultats)) {
            $produits[] = $produit;
        }
    }
    mysqli_close($conn); // Assurez-vous de fermer la connexion après utilisation
    return $produits;
}
    // recuperation de produits a partir de l'id_produit
function getProduitById($id){
    $sql = "SELECT * FROM produits WHERE id_produit =?";
    $conn = connexionDB();
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $resultat =  mysqli_stmt_execute($stmt);
        
    if ($resultat) {
        $resultat = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($resultat) > 0) {
            return mysqli_fetch_assoc($resultat);
        }else {
            return false;
        }
    }
    return $resultat;
}
    //Modification de produits
function updateProduit($produit){
    $id = $produit['id_produit'];
    $nom = $produit['nom'];
    $prix = $produit['prix_unitaire'];
    $description = $produit['description'];
    $courte_description = $produit['courte_description'];
    $quantite = $produit['quantite'];
    $sql = "UPDATE produits SET nom =?, prix_unitaire =?, description =?, courte_description =?, quantite =? WHERE id_produit =?";
    $conn = connexionDB();
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdssii", $nom, $prix, $description, $courte_description, $quantite, $id);
    return mysqli_stmt_execute($stmt);
}

function deleteProduitPromotion($idProduit) {
    $conn = connexionDB();
    $sql = "DELETE FROM produitpromotion WHERE id_produit = ?";
    if ($stmtDeletePromo = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmtDeletePromo, "i", $idProduit);
        mysqli_stmt_execute($stmtDeletePromo);
        mysqli_stmt_close($stmtDeletePromo);
        return true;
    } else {
        return false;
    }
}

function deleteProduitImages($idProduit) {
    $conn = connexionDB();
    $sql = "DELETE FROM image WHERE id_produit = ?";
    if ($stmtDeleteImage = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmtDeleteImage, "i", $idProduit);
        mysqli_stmt_execute($stmtDeleteImage);
        mysqli_stmt_close($stmtDeleteImage);
        return true;
    } else {
        return false;
    }
}

function deleteProduit($idProduit) {
    $conn = connexionDB();

    // Supprimer les enregistrements associés dans produitpromotion
    if (!deleteProduitPromotion($idProduit)) {
        return false;
    }

    // Supprimer les enregistrements associés dans image
    if (!deleteProduitImages($idProduit)) {
        return false;
    }

    // Ensuite, supprimer l'enregistrement du produit
    $queryDeleteProduit = "DELETE FROM produits WHERE id_produit = ?";
    if ($stmtDeleteProduit = mysqli_prepare($conn, $queryDeleteProduit)) {
        mysqli_stmt_bind_param($stmtDeleteProduit, "i", $idProduit);
        mysqli_stmt_execute($stmtDeleteProduit);
        mysqli_stmt_close($stmtDeleteProduit);
        return true;
    } else {
        return false;
    }
}

function getRoleByDescription($description){
    $sql = "SELECT * FROM role WHERE description =?";
    $conn = connexionDB();
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $description);
    mysqli_stmt_execute($stmt);
    $resultat =  mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($resultat) > 0) {
        return mysqli_fetch_assoc($resultat);
    } else {
        return false;
    }
}

function intsertRoleUser($id_role, $id_utilisateur){
    $sql = "INSERT INTO role_utilisateur (id_role, id_utilisateur) VALUES (?,?)";
    $conn = connexionDB();
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_role, $id_utilisateur);
    return mysqli_stmt_execute($stmt);
}

function getUserByEmail($email) {
    $sql = 'select * from utilisateur where couriel = ?';
    $conn = connexionDB();
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Erreur de préparation de la requête : ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);
    if ($resultat === false) {
        die('Erreur d\'exécution de la requête : ' . mysqli_stmt_error($stmt));
    }
    if (mysqli_num_rows($resultat) > 0) {
        return mysqli_fetch_assoc($resultat);
    } else {
        return false;
    }

}
//return la liste des categories de la base de donnees
function getAllCategories() {
    $conn = connexionDB();
    $sql = "SELECT * FROM categorie";
    $resultats = mysqli_query($conn, $sql);
    $categories = [];
    if (mysqli_num_rows($resultats) > 0) {
        while ($categorie = mysqli_fetch_assoc($resultats)) {
            $categories[] = $categorie;
        }
    }
    mysqli_close($conn);
    return $categories;
}  

function addCommande($commande) {
    $id_utilisateur = $commande['id_utilisateur'];
    $date_commande = $commande['date_commande'];
    $prix_total = $commande['prix_total'];
    $statut = 'En attente'; 
    $conn = connexionDB();

    $sql = "INSERT INTO commande (id_utilisateur, statut, date_commande, prix_total) VALUES (?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $commande['id_utilisateur'], $statut, $commande['prix_total']);
    
    $resultat = mysqli_stmt_execute($stmt);

    if ($resultat) {
        $id_commande = mysqli_insert_id($conn);
        foreach ($commande['produits'] as $produit) {
            if (!addProduitCommande($conn, $id_commande, $produit)) {
                echo "Erreur lors de l'ajout du produit à la commande.<br>";
                mysqli_close($conn);
                return false;
            }
        }
        mysqli_close($conn);
        return $id_commande;
    } else {
        echo "Erreur lors de l'insertion dans commande: " . mysqli_stmt_error($stmt) . "<br>";
        mysqli_close($conn);
        return false;
    }
}

function addProduitCommande($conn, $id_commande, $produit) {
    $id_produit = $produit['id_produit'];
    $quantite = $produit['quantite'];

    // Vérifiez si le produit existe dans la table produits
    $stmtCheckProduit = mysqli_prepare($conn, "SELECT id_produit FROM produits WHERE id_produit = ?");
    mysqli_stmt_bind_param($stmtCheckProduit, "i", $id_produit);
    mysqli_stmt_execute($stmtCheckProduit);
    mysqli_stmt_store_result($stmtCheckProduit);

    if (mysqli_stmt_num_rows($stmtCheckProduit) > 0) {
        // Le produit existe, nous pouvons insérer
        $stmtProduitCommande = mysqli_prepare($conn, "INSERT INTO produit_commande (id_commande, id_produit, quantite) VALUES (?,?,?)");
        mysqli_stmt_bind_param($stmtProduitCommande, "iii", $id_commande, $id_produit, $quantite);

        if (!mysqli_stmt_execute($stmtProduitCommande)) {
            echo "Erreur lors de l'insertion dans produit_commande: " . mysqli_stmt_error($stmtProduitCommande) . "<br>";
            return false;
        }           

        // Mettre à jour la quantité du produit dans la table produits
        if (!miseAJourQuantiteProduit($conn, $id_produit, $quantite)) {
            return false;
        }
    } else {
        echo "Erreur: Le produit avec l'ID $id_produit n'existe pas.<br>";
        return false;
    }

    mysqli_stmt_close($stmtCheckProduit);
    return true;
}

function miseAJourQuantiteProduit($conn, $id_produit, $quantite) {
    $sql = "UPDATE produits SET quantite = quantite - ? WHERE id_produit = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $quantite, $id_produit);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Erreur lors de la mise à jour de la quantité du produit: " . mysqli_stmt_error($stmt) . "<br>";
        return false;
    }

    return true;
}   

//fonction de gestion de la promotion
function appliquerPromotion($total, $code_promotion) {
    global $connect;
    // Vérifiez si la promotion est valide et active
    global $connect;
    $query = "SELECT * FROM Promotions WHERE code_promotion = '$code_promotion' AND date_debut <= CURDATE() AND date_fin >= CURDATE()";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $promotion = mysqli_fetch_assoc($result);
        if ($promotion['type'] == 'pourcentage') {
            $total -= ($total * ($promotion['valeur'] / 100));
        } elseif ($promotion['type'] == 'montant') {
            $total -= $promotion['valeur'];
        }
    } else {
        echo '<script>alert("Code promotionnel invalide ou expiré.")</script>';
    }
    return $total;
}
