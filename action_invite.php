<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width; initial-scale=1.0'/>
	<link rel = 'stylesheet' href = 'style_1.css'/>
	<title>Reservation</title>
</head>
<body>

<?php
session_start();
// Récupération des valeurs du formulaire
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $club = $_POST["club"];
    $id_reservation = $_POST["id_reservation"];
    
    // Vérification si le champ "nom" est vide
    if (!empty($_POST['nom1'])) {
        $p1 = $_POST["nom1"]; 
        if ($p1 != "guest"){
            $query = $bdd->prepare("SELECT nom FROM inscription i 
            INNER JOIN clubs c ON i.id_club = c.id_club 
            INNER JOIN utilisateur u ON u.id_utilisateur = i.id_utilisateur
            WHERE u.nom=? AND nom_club = ?");
            $query->execute([$p1, $club]);
            $rowCount = $query->rowCount();
            // On compte pour savoir s'il y a bien quelqu'un qui correspond au critère
            if ($rowCount == 0 ) {
                echo "Partenaire 1 introuvable";
            } else {
                // Récupération de l'ID de l'utilisateur 1
                $query = $bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = ?");
                $query->execute([$p1]);
                $id_utilisateur = $query->fetchColumn(); 
                // Insertion de l'ID de l'utilisateur 1 dans la table "invite"
                $query = $bdd->prepare("INSERT INTO invite(id_reservation, id_utilisateur) VALUES(?,?)");
                $query->execute([$id_reservation, $id_utilisateur]);
                echo "Partenaire 1 ajouté";
            } 
        } else{
            echo "Invité 1 ajouté";
        }
    }
    
    if (!empty($_POST['nom2'])) {
        $p2 = $_POST["nom2"]; 
        if ($p2 != "guest"){
            $query = $bdd->prepare("SELECT nom FROM inscription i 
            INNER JOIN clubs c ON i.id_club = c.id_club 
            INNER JOIN utilisateur u ON u.id_utilisateur = i.id_utilisateur
            WHERE u.nom=? AND nom_club = ?");
            $query->execute([$p2, $club]);
            $rowCount = $query->rowCount();
            // On compte pour savoir s'il y a bien quelqu'un qui correspond au critère
            if ($rowCount == 0 ) {
                echo "Partenaire 2 introuvable";
            } else {
                // Récupération de l'ID de l'utilisateur 2
                $query = $bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = ?");
                $query->execute([$p2]);
                $id_utilisateur = $query->fetchColumn(); 
                // Insertion de l'ID de l'utilisateur 2 dans la table "invite"
                $query = $bdd->prepare("INSERT INTO invite(id_reservation, id_utilisateur) VALUES(?,?)");
                $query->execute([$id_reservation, $id_utilisateur]);
                echo "Partenaire 2 ajouté";
            } 
        } else{
            echo "Invité 2 ajouté";
        }
    }

    if (!empty($_POST['nom3'])) {
        $p3 = $_POST["nom3"]; 
        if ($p3 != "guest"){
            $query = $bdd->prepare("SELECT nom FROM inscription i 
            INNER JOIN clubs c ON i.id_club = c.id_club 
            INNER JOIN utilisateur u ON u.id_utilisateur = i.id_utilisateur
            WHERE u.nom=? AND nom_club = ?");
            $query->execute([$p3, $club]);
            $rowCount = $query->rowCount();
            // On compte pour savoir s'il y a bien quelqu'un qui correspond au critère
            if ($rowCount == 0 ) {
                echo "Partenaire 3 introuvable";
            } else {
                // Récupération de l'ID de l'utilisateur 3
                $query = $bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = ?");
                $query->execute([$p3]);
                $id_utilisateur = $query->fetchColumn(); 
                // Insertion de l'ID de l'utilisateur 3 dans la table "invite"
                $query = $bdd->prepare("INSERT INTO invite(id_reservation, id_utilisateur) VALUES(?,?)");
                $query->execute([$id_reservation, $id_utilisateur]);
                echo "Partenaire 3 ajouté";
            } 
        } else{
            echo "Invité 3 ajouté";
        }
    }
}
?>
<p>Retour au profil <a href = "profil_adherent.php">ici</a></p>


</body>
</html>
