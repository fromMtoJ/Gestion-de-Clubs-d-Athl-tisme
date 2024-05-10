<<<<<<< HEAD
<?php

// Vérifier si les données POST sont définies
// Établir une connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');

// Récupérer les données POST
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$naissance = $_POST['naissance'];
$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];
$clubs_coches = $_POST['club']; // Récupérer les clubs cochés

// Vérifier si tous les champs nécessaires sont non vides
if (!empty($nom) && !empty($prenom) && !empty($naissance) && !empty($email) && !empty($mot_de_passe)) {
// Préparer et exécuter la requête d'insertion pour l'utilisateur
$query = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, date_de_naissance, email, mdp) VALUES (?, ?, ?, ?, ?)");
$query->execute([$nom, $prenom, $naissance, $email, $mot_de_passe]);

// Vérifier si l'insertion de l'utilisateur a réussi
if($query->rowCount() > 0) {
$test = 1;
} else {
$test = 0;
}

// Récupérer l'ID de l'utilisateur inséré
$query = $bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE email=? AND mdp=?");
$query->execute([$email, $mot_de_passe]);
$id_utilisateur = $query->fetchColumn();

// Vérifier si des clubs ont été sélectionnés
if (!empty($clubs_coches)) {
foreach($clubs_coches as $idclub) {
$query = $bdd->prepare("INSERT INTO inscription(id_utilisateur, id_club) VALUES (?, ?)"); 
$query->execute([$id_utilisateur, $idclub]);
}

// Vérifier si toutes les insertions ont réussi
if($query->rowCount() > 0 && $test === 1) {
    header('Location: connexion.php');
} else {
echo "Erreur lors de l'ajout de l'utilisateur.";
}
} else {
echo "Aucun club sélectionné.";
}
} else {
echo "tous les champs sont obligatoires";
}
=======
<?php

// Vérifier si les données POST sont définies
// Établir une connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');

// Récupérer les données POST
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$naissance = $_POST['naissance'];
$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];
$clubs_coches = $_POST['club']; // Récupérer les clubs cochés

// Vérifier si tous les champs nécessaires sont non vides
if (!empty($nom) && !empty($prenom) && !empty($naissance) && !empty($email) && !empty($mot_de_passe)) {
// Préparer et exécuter la requête d'insertion pour l'utilisateur
$query = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, date_de_naissance, email, mdp) VALUES (?, ?, ?, ?, ?)");
$query->execute([$nom, $prenom, $naissance, $email, $mot_de_passe]);

// Vérifier si l'insertion de l'utilisateur a réussi
if($query->rowCount() > 0) {
$test = 1;
} else {
$test = 0;
}

// Récupérer l'ID de l'utilisateur inséré
$query = $bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE email=? AND mdp=?");
$query->execute([$email, $mot_de_passe]);
$id_utilisateur = $query->fetchColumn();

// Vérifier si des clubs ont été sélectionnés
if (!empty($clubs_coches)) {
foreach($clubs_coches as $idclub) {
$query = $bdd->prepare("INSERT INTO inscription(id_utilisateur, id_club) VALUES (?, ?)"); 
$query->execute([$id_utilisateur, $idclub]);
}

// Vérifier si toutes les insertions ont réussi
if($query->rowCount() > 0 && $test === 1) {
    header('Location: connexion.php');
} else {
echo "Erreur lors de l'ajout de l'utilisateur.";
}
} else {
echo "Aucun club sélectionné.";
}
} else {
echo "tous les champs sont obligatoires";
}
>>>>>>> 01d851c5ace7b07a8bc5d3bbbeef8ab4be5b3f5c
?>