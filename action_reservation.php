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
$club = $_POST["club"];
$discipline = $_POST["discipline"];
$installation = $_POST["installation"];
$heure_deb = $_POST["heure"];
$date = $_POST["date"];
$duree = $_POST["duree"];

// Convertir la durée en secondes
$duree_seconds = strtotime($duree) - strtotime('00:00:00');

try {
    // Créer un objet DateTime pour l'heure de début
    $heure_deb_obj = new DateTime($heure_deb);

    // Ajouter la durée en secondes à l'heure de début pour obtenir l'heure de fin
    $heure_deb_obj->add(new DateInterval('PT' . $duree_seconds . 'S'));

    // Récupérer l'heure de fin au format souhaité
    $heure_f = $heure_deb_obj->format('H:i:s');
} catch (Exception $e) {
    // Gérer les erreurs
    echo "Erreur lors du calcul de l'heure de fin : " . $e->getMessage();
}


// Vérification des réservations existantes pour cette plage horaire
$query = $bdd->prepare("SELECT heure_debut_reservation, heure_fin_reservation FROM reservation r INNER JOIN installations i ON i.id_installation = r.id_installation WHERE nom_installation = ? AND (heure_debut_reservation < ? AND heure_fin_reservation > ?)");
$query->execute([$installation, $heure_deb, $heure_f]);
$rowCount = $query->rowCount();

// On comtpe le nombre de réservations qui se superposent
if ($rowCount > 0) {
    echo "Cette plage horaire est déjà réservée.";
} else {
    $query = $bdd->prepare("SELECT id_club FROM clubs WHERE nom_club = ?");
    $query->execute([$club]);
    $id_club = $query->fetch(PDO::FETCH_ASSOC)['id_club'];
    
    $query = $bdd->prepare("SELECT id_installation FROM installations WHERE nom_installation = ?");
    $query->execute([$installation]);
    $id_installation = $query->fetch(PDO::FETCH_ASSOC)['id_installation']; 
    
    $id_utilisateur = $_SESSION['id_utilisateur'];
    
    
    $query = $bdd->prepare("INSERT INTO reservation(id_club, id_installation, id_utilisateur, date_debut_reservation, heure_debut_reservation, date_fin_reservation, heure_fin_reservation, blocage) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $query->execute([$id_club, $id_installation, $id_utilisateur, $date, $heure_deb, $date, $heure_f, 0]);
    
    // Vérification des erreurs éventuelles
    if ($query->errorInfo()[0] != '00000') {
        // Gestion de l'erreur
        echo "Erreur lors de l'insertion : " . $query->errorInfo()[2];
    } else {
        // Insertion réussie
        echo "Réservation effectuée avec succès !";
         
    }
}
?>
<p>Retour au profil <a href = "profil_adherent.php">ici</a></p>

</body>
</html>