<?php 
include 'header.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Contactez-nous</h1>
        <p>Si vous avez des questions, des commentaires ou des préoccupations, n'hésitez pas à nous contacter en utilisant le formulaire ci-dessous. Nous ferons de notre mieux pour vous répondre dans les plus brefs délais.</p>
        
        <form action="traitement_contact.php" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="sujet" class="form-label">Sujet :</label>
                <input type="text" class="form-control" id="sujet" name="sujet" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message :</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>
</body>
</html>



<?php include 'footer.php';?>
