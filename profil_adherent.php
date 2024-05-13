<?php
session_start();
// Connexion à la base de données
$id_utilisateur = $_SESSION['id_utilisateur'];
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');

// Récupération du nom et du prénom de l'utilisateur
$req_n_p = $bdd->prepare("SELECT nom, prenom FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_utilisateur = ?");
$req_n_p->execute(array($id_utilisateur));
$row_n_p = $req_n_p->fetch();
$nom_prenom = $row_n_p['prenom'] . " " . $row_n_p['nom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link rel="stylesheet" href="style_reservation.css"/>
</head>
<body>
    <div id="sous_titre">
        <div id="sous_titre_1"><?php echo $nom_prenom; ?></div>
        <?php
    // Vérification des droits de l'utilisateur pour permettre l'acces a la page admin pour les admins
    $req = $bdd->prepare("SELECT administrateur FROM inscription WHERE id_utilisateur = ?");
    $req->execute(array($id_utilisateur));
    
    while ($row = $req->fetch()) {
        if ($row['administrateur'] === 1) {
            echo "<div id = 'sous_titre_2'><a href='profil_admin.php'>Tableau de bord</a></div>";
        }
    }
    ?>
    <div id = 'sous_titre_3'><a href = "deconnexion.php">Se déconnecter</a></div>

    </div>
    
 
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélection de date et d'horaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.css">
</head>
<body>
    <div id = 'contenant'>
        <h1>Réservation</h1>
    <!-- permet de poster les infos sur la meme page après chaque changement d'état -->    
    <form id="reservationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

    <div class = 'section'><span>Club :</span>
    <select name="clubs" id="clubs" onchange="this.form.submit()"> 
     <!-- gère le changement d'état -->    
        <option value="">Sélectionnez un club</option>
        <?php
        //affichage dynamique des clubs dont l'utilisateur à acces
        $query = $bdd->prepare("SELECT nom_club FROM clubs c INNER JOIN inscription i ON c.id_club = i.id_club WHERE id_utilisateur = ?");
        $query->execute(array($id_utilisateur));
        while ($club = $query->fetch()) {
            $nomClub = $club["nom_club"];
            echo "<option value='$nomClub' ";
            if (isset($_POST['clubs']) && $_POST['clubs'] == $nomClub) {
                echo "selected";
            }
            echo ">$nomClub</option>";
        }
        ?>
    </select>
    </div>
    <div class = 'section'><span>Discipline : </span>
    <select name="disciplines" id="disciplines" onchange="this.form.submit()">
        <option value="">Sélectionnez une discipline</option>
        <?php
        // affichage dynamique des dispclines presentent dans le club selectionné
        if (isset($_POST['clubs']) && !empty($_POST['clubs'])) {
            $club_selected = $_POST['clubs'];
            $query = $bdd->prepare("
                SELECT DISTINCT d.type_discipline
                FROM disciplines d
                INNER JOIN installations i ON d.id_discipline = i.id_discipline
                INNER JOIN presence p ON p.id_installation = i.id_installation
                INNER JOIN clubs c ON c.id_club = p.id_club
                WHERE c.nom_club = ?");
            $query->execute(array($club_selected));
            while ($discipline = $query->fetch()) {
                $nom_discipline = $discipline["type_discipline"];
                echo "<option value='$nom_discipline'";
                if (isset($_POST['disciplines']) && $_POST['disciplines'] == $nom_discipline) {
                    echo " selected";
                }
                echo ">$nom_discipline</option>";
            }
        }
        ?>
    </select>
    </div>

   <div class = 'section'><span>Installation :</span> 
    <select name="installations" id="installations">
        <option value="">Séléctionnez une installation</option>
        <?php
        // affichage dynamique des installations disponible en fonction de la discipline choisie
        if (isset($_POST['disciplines']) && !empty($_POST['disciplines'])) {
            $discipline_selected = $_POST["disciplines"];
            $query = $bdd->prepare("
                SELECT nom_installation FROM installations i
                INNER JOIN disciplines d ON i.id_discipline = d.id_discipline
                WHERE d.type_discipline = ?");
            $query->execute(array($discipline_selected));
            while ($installation = $query->fetch()) {
                $nom_installation = $installation["nom_installation"];
                echo "<option value='$nom_installation'";
                if (isset($_POST['installations']) && $_POST['installations'] == $nom_installation) {
                    echo " selected";
                }
                echo ">$nom_installation</option>";
            }
        }
        ?>
    </select>
    </div>
    <input type="submit" value="Choisir">
    </form>
   
   
    <?php
    // valeur pretes a etre recuperer evite les bugs de variable indefini
$club ="";
$discipline = "";
$installation = "";    
if (isset($_POST["clubs"], $_POST["disciplines"], $_POST["installations"])) {
    
    $club = $_POST["clubs"];
    $discipline = $_POST["disciplines"];
    $installation = $_POST["installations"];
}    
?><?php
// Fonction pour récupérer les dates bloquées depuis la base de données
$query = $bdd->prepare("SELECT date_debut_reservation, date_fin_reservation FROM reservation r 
INNER JOIN installations i ON i.id_installation = r.id_installation WHERE nom_installation = ? AND blocage = ?");
$query->execute([$installation, 1]);
$reservations = $query->fetchAll();

// Liste des dates bloquées
$dates_bloquees = [];

foreach ($reservations as $reservation) {
    // Convertir les dates en objets DateTime
    $date_deb = new DateTime($reservation['date_debut_reservation']);
    $date_fin = new DateTime($reservation['date_fin_reservation']);

    // Ajouter les dates à la liste
    while ($date_deb <= $date_fin) {
        $dates_bloquees[] = $date_deb->format('Y-m-d');
        $date_deb->modify('+1 day');
    }
}


// Liste pour stocker toutes les dates bloquées
$liste_dates_totales = [];

// Boucle pour traiter chaque date bloquée
foreach ($dates_bloquees as $date_bloquee) {
    // Ajoutez chaque date bloquée à la liste des dates à désactiver
    $liste_dates_totales[] = $date_bloquee;
}
?>
<?php
$query = $bdd->prepare("SELECT heure_debut_reservation, heure_fin_reservation FROM reservation r 
INNER JOIN installations i ON i.id_installation = r.id_installation WHERE nom_installation = ? AND blocage = ?");
$query->execute([$installation, 1]);
$heures = $query->fetchAll();
// recuperer la date selectionner dans le calendrier 

$query = $bdd->prepare("SELECT heure_ouverture, heure_fermeture FROM clubs WHERE nom_club= ?");
$query->execute([$club]);
$heure_club = $query->fetch();

$heure_ouverture = $heure_club['heure_ouverture'];
$heure_fermeture = $heure_club['heure_fermeture'];

?>
<h2>Sélection de date et d'horaire</h2>

<form action="action_reservation.php" method="post">
    <!-- Champs cachés permettent de transmettre à la page action les informations déjà poster avant -->
    <input type="hidden" name="club" value="<?php echo htmlspecialchars($club); ?>">
    
    <!-- Champ caché pour la discipline -->
    <input type="hidden" name="discipline" value="<?php echo htmlspecialchars($discipline); ?>">
    
    <!-- Champ caché pour l'installation -->
    <input type="hidden" name="installation" value="<?php echo htmlspecialchars($installation); ?>">

    <div class = 'section'>
    <label for="date">Date :</label>
    <input type="text" id="date" name="date" placeholder="Sélectionnez une date" required>
    </div>

    <div class = 'section'>
    <label for="heure">Heure :</label>
    <input type="time" id="heure" name="heure" required>
    </div>

    <div class = 'section'>
    <label for="duree">Durée :</label>
    <select name="duree", id="duree">
        <option value="">Sélectionner une durée</option>
        <option value="00:30:00">30min</option>
        <option value="01:00:00">1h</option>
        <option value="01:30:00">1h30</option>
        <option value="02:00:00">2h</option>
        <?php 
            // Vérification des droits de l'utilisateur permet aux admins de reserver plus longtemps
            $req = $bdd->prepare("SELECT administrateur FROM inscription WHERE id_utilisateur = ?");
            $req->execute(array($id_utilisateur));
            
            while ($row = $req->fetch()) {
                if ($row['administrateur'] === 1) {
                    ?>
                    <option value="03:00:00">3h</option>
                    <option value="04:00:00">4h</option>
                    <option value="05:00:00">5h</option>
                    <option value="06:00:00">6h</option>
                    <option value="07:00:00">7h</option>
                    <?php
                }
            }
            
        ?>

    </select>
    <button type="submit">Soumettre</button>
    </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Tableau des dates à désactiver converti pour le javascript
    var liste_dates_totales = <?php echo json_encode($liste_dates_totales); ?>;
    
    // Initialisation de Flatpickr pour la date
    flatpickr("#date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: "today",
        disable: liste_dates_totales
    });

</script>
    
<script>
    // Récupération des horaires d'ouverture et de fermeture du club depuis PHP vers Javascript
    var heureOuverture = "<?php echo $heure_ouverture; ?>";
    var heureFermeture = "<?php echo $heure_fermeture; ?>";

    // Initialisation de Flatpickr pour l'heure
    flatpickr("#heure", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        minTime: heureOuverture,
        maxTime: heureFermeture
    });
</script>


   
</body>
</html>