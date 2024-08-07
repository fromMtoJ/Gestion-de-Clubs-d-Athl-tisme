<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width; initial-scale=1.0'/>
	<link rel = 'stylesheet' href = 'style_1.css'/>
	<title>Inscription</title>
</head>
<body>

<?php


// Établir une connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');

// Récupérer les données POST
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$naissance = $_POST['naissance'];
$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];
$clubs_coches = $_POST['club']; // Récupérer les clubs cochés
$test = 0; // iniatialisation dans  le cas ou on ne rentre pas dans la boucle 
// Vérifier si tous les champs nécessaires sont non vides

if(!empty($email)){
    $query = $bdd->prepare("SELECT email FROM utilisateur WHERE email=? ");
    $query->execute([$email]);
    $rowCount = $query->rowCount();
    if ($rowCount > 0) {
        // Vérifier si email deja pris
        echo "Email deja existant";
        ?>
        <p>Cliquer <a href = "inscription.php">ici</a> pour revenir à la page d'inscription</p>


<?php
    } else {
        if (!empty($nom) && !empty($prenom) && !empty($naissance) && !empty($email) && !empty($mot_de_passe)) {
    // Vérifier si l'utilisateur existe déjà
    $query = $bdd->prepare("SELECT nom, prenom, date_de_naissance FROM utilisateur WHERE nom=? AND prenom=? AND date_de_naissance=?");
    $query->execute([$nom, $prenom, $naissance]);
    $rowCount = $query->rowCount();
        
            if ($rowCount > 0) {
                echo "Cet utilisateur existe déjà.";
                ?>
                <p>Cliquer <a href = "inscription.php">ici</a> pour revenir à la page d'inscription</p>


<?php
            } else {
                // Insérer un nouvel utilisateur
                $query2 = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, date_de_naissance, email, mdp) VALUES (?, ?, ?, ?, ?)");
                $query2->execute([$nom, $prenom, $naissance, $email, $mot_de_passe]);

                // Vérifier si l'insertion a réussi
                if ($query2->rowCount() > 0) {
                    $test = 1;
                } else {
                    $test = 0;
                }
                                // Récupérer l'ID de l'utilisateur inséré
                $query3 = $bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE email=? AND mdp=?");
                $query3->execute([$email, $mot_de_passe]);
                $id_utilisateur = $query3->fetchColumn();

                // Vérifier si des clubs ont été sélectionnés
                if (!empty($clubs_coches)) {
                    foreach ($clubs_coches as $idclub) {
                        $query4 = $bdd->prepare("INSERT INTO inscription(id_utilisateur, id_club) VALUES (?, ?)");
                        $query4->execute([$id_utilisateur, $idclub]);
                    }

                    // Vérifier si toutes les insertions ont réussi
                    if ($query4->rowCount() > 0 && $test === 1) {
                        header('Location: connexion.php');
                    } else {
                        echo "Erreur lors de l'ajout de l'utilisateur.";
                    }
                } else {
                    echo "Aucun club sélectionné.";
                }
            }
            } else {
                echo "Tous les champs sont obligatoires.";
            }
}
}


?>
</body>
</html>