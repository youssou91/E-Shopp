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
function ajoutProduit($produit, $data) {
    $nom = $produit['nom_prod'];
    $prix = $produit['prix_prod'];
    $description = $produit['longueDescription_prod'];
    $courte_description = $produit['courteDescription_prod'];
    $quantite = $produit['quantite_prod'];
    $id_categorie = $produit['id_categorie'];
    $taille_produit = $produit['taille_produit'];
    
    $sql = "INSERT INTO produits (nom, prix_unitaire, description, courte_description, quantite, id_categorie, taille_produit) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $conn = connexionDB();
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdssiis", $nom, $prix, $description, $courte_description, $quantite, $id_categorie, $taille_produit);
    $resultat = mysqli_stmt_execute($stmt);

    if ($resultat) {
        $id_produit = mysqli_insert_id($conn);
        if (uploadImage($data, $id_produit)) {
            return true;
        } else {
            echo "Erreur lors du téléchargement de l'image.";
            return false;
        }
    }

    echo "Erreur lors de l'insertion du produit : " . mysqli_error($conn);
    return false;
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
function checkUser($email, $password) {
    $conn = connexionDB();
    $user = getElementByEmailForLogin($email, $conn);

    if ($user && password_verify($password, $user['mot_de_pass'])) {
        return $user;
    } else {
        return false;
    }
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
function getUserInfo($id_utilisateur){
    $conn = connexionDB();
    $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = ?";
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
function editProfile($profile) {
    $conn = connexionDB();
    $sql = "UPDATE utilisateur SET nom_utilisateur = ?, prenom = ?, date_naissance = ?, telephone = ?, couriel = ? WHERE id_utilisateur = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("sssssi", $profile['nom_utilisateur'], $profile['prenom'], $profile['date_naissance'], $profile['telephone'], $profile['couriel'], $profile['id_utilisateur']);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        echo "Error updating record: " . $conn->error;
        $stmt->close();
        $conn->close();
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

// Ajout d'un utilisateur dans la base de donnees
function addUserDB($user) {
    $nom = $user['nom'];
    $prenom = $user['prenom'];
    $datNaiss = $user['datNaiss'];
    $telephone = $user['telephone'];
    $emailUser = $user['couriel'];
    $password = $user['password'];
    $cpassword = $user['cpassword'];
    
    // Vérifier que l'email est valide
    if (!emailFormat($emailUser)) {
        return "Le format de l'email n'est pas valide.";
    }
    // Vérifier que la taille du password est valide
    if (strlen($password) < 6 ||!preg_match('/[a-z]/', $password) ||!preg_match('/[A-Z]/', $password) ||!preg_match('/\d/', $password) ||!preg_match('/[@$!%*?&]/', $password)) {
        return "Le mot de passe doit contenir au moins 6 caractères, une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.";
    }

    $conn = connexionDB();

    // Vérifier que l'email est unique
    if (getElementByEmailForAddUser($emailUser, $conn)) {
        return "Email existe deja dans la base de donnees.";
    }

    // Vérifier que l'utilisateur a au moins 16 ans
    if (calculAge($datNaiss) < 16) {
        return "L'utilisateur doit avoir au moins 16 ans.";
    }

    // Vérifier que les mots de passe sont identiques
    if ($password === $cpassword) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur (nom_utilisateur, prenom, date_naissance, couriel, mot_de_pass, telephone) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $nom, $prenom, $datNaiss, $emailUser, $password, $telephone); 
        $resultat = mysqli_stmt_execute($stmt);
        if ($resultat) {
            $role = getRoleByDescription('client');
            $id_utilisateur = mysqli_insert_id($conn);
            intsertRoleUser($role['id_role'], $id_utilisateur);
            return true;
        }
        return false;
    }
    return "Les mots de passe ne correspondent pas.";
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
    $sql = "SELECT c.*, s.description as statut_description
            FROM commande c
            JOIN statuts_commande s ON c.id_statut = s.id_statut
            WHERE c.id_utilisateur = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $orders;
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

////////////////////////////////////////////

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
    return mysqli_fetch_assoc($resultat);
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
    //Suppression de produits
    function deleteProduit($id){
        // suppression de l'image de produit
        $sqlImage = "DELETE FROM image WHERE id_produit = ?";
        $conn = connexionDB();
        $stmtImage = mysqli_prepare($conn, $sqlImage);
        mysqli_stmt_bind_param($stmtImage, "i", $id);
        mysqli_stmt_execute($stmtImage);
        // suppression du produit
        $sql = "DELETE FROM produits WHERE id_produit =?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
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
        $conn = connexionDB();
        $id_statut = getStatutIdByDescription('En attente', $conn);
        
        if ($id_statut === null) {
            return "Le statut par défaut 'En attente' n'existe pas dans la base de données.";
        }

        $sql = "INSERT INTO commande (id_utilisateur, id_statut, date_commande, prix_total) VALUES (?, ?, NOW(), ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iis", $commande['id_utilisateur'], $id_statut, $commande['prix_total']);
        
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
    
    function getAllCommandes(){
        $sql = "SELECT c.id_commande, c.date_commande, c.prix_total, u.nom_utilisateur 
                FROM commande c 
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur";
        $conn = connexionDB();
        
        if (!$conn) {
            die("Erreur de connexion à la base de données: " . mysqli_connect_error());
        }
    
        $resultats = mysqli_query($conn, $sql);
    
        if (!$resultats) {
            die("Erreur lors de l'exécution de la requête: " . mysqli_error($conn));
        }
    
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
        } 
    
        mysqli_close($conn);
        return $commandes;
    }
    





    ////////////////////////////////////////////////////////////////
    //récupérer la liste des commandes d'un utilisateur
    function getCommandesByUtilisateur($id_utilisateur) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit WHERE c.id_utilisateur =?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_utilisateur);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
            
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par date
    function getCommandesByDate($date_debut, $date_fin) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit WHERE c.date_commande BETWEEN? AND?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $date_debut, $date_fin);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
        }else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par montant
    function getCommandesByMontant($montant_min, $montant_max) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit WHERE c.prix_total BETWEEN? AND?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "dd", $montant_min, $montant_max);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
        }
        else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par produit
    function getCommandesByProduit($id_produit) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit WHERE lc.id_produit =?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_produit);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
            
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par categorie
    function getCommandesByCategorie($id_categorie) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit JOIN categorie_produit cp ON p.id_produit = cp.id_produit WHERE cp.id_categorie =?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_categorie);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
            
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par utilisateur et par produit
    function getCommandesByUtilisateurAndProduit($id_utilisateur, $id_produit) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit WHERE c.id_utilisateur =? AND lc.id_produit =?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id_utilisateur, $id_produit);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
        }
        else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par utilisateur et par categorie
    function getCommandesByUtilisateurAndCategorie($id_utilisateur, $id_categorie) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit JOIN categorie_produit cp ON p.id_produit = cp.id_produit WHERE c.id_utilisateur =? AND cp.id_categorie =?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id_utilisateur, $id_categorie);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
            
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par utilisateur, par produit et par categorie
    function getCommandesByUtilisateurAndProduitAndCategorie($id_utilisateur, $id_produit, $id_categorie) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit JOIN categorie_produit cp ON p.id_produit = cp.id_produit WHERE c.id_utilisateur =? AND lc.id_produit =? AND cp.id_categorie =?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $id_utilisateur, $id_produit, $id_categorie);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
            
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par utilisateur, par produit et par categorie avec pagination
    function getCommandesByUtilisateurAndProduitAndCategoriePagination($id_utilisateur, $id_produit, $id_categorie, $page, $nb_commandes_par_page) {
        $offset = ($page - 1) * $nb_commandes_par_page;
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit JOIN categorie_produit cp ON p.id_produit = cp.id_produit WHERE c.id_utilisateur =? AND lc.id_produit =? AND cp.id_categorie =? ORDER BY c.date_commande DESC LIMIT?,?";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiii", $id_utilisateur, $id_produit, $id_categorie, $offset, $nb_commandes_par_page);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
            
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par utilisateur, par produit et par categorie avec tri par date de commande
    function getCommandesByUtilisateurAndProduitAndCategorieTriDate($id_utilisateur, $id_produit, $id_categorie, $tri) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit JOIN categorie_produit cp ON p.id_produit = cp.id_produit WHERE c.id_utilisateur =? AND lc.id_produit =? AND cp.id_categorie =? ORDER BY c.date_commande $tri";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $id_utilisateur, $id_produit, $id_categorie);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
            
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    //récupérer la liste des commandes par utilisateur, par produit et par categorie avec tri par montant de commande
    function getCommandesByUtilisateurAndProduitAndCategorieTriMontant($id_utilisateur, $id_produit, $id_categorie, $tri) {
        $sql = "SELECT c.*, p.nom, p.prix_unitaire, p.quantite FROM commande c JOIN ligne_commande lc ON c.id_commande = lc.id_commande JOIN produits p ON lc.id_produit = p.id_produit JOIN categorie_produit cp ON p.id_produit = cp.id_produit WHERE c.id_utilisateur =? AND lc.id_produit =? AND cp.id_categorie =? ORDER BY c.montant_commande $tri";
        $conn = connexionDB();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $id_utilisateur, $id_produit, $id_categorie);
        mysqli_stmt_execute($stmt);
        $resultats = mysqli_stmt_get_result($stmt);
        $commandes = [];
        if (mysqli_num_rows($resultats) > 0) {
            while ($commande = mysqli_fetch_assoc($resultats)) {
                $commandes[] = $commande;
            }
            mysqli_close($conn);
            return $commandes;
            
        } else {
            mysqli_close($conn);
            return false;
        }
    }
             
?>
    
