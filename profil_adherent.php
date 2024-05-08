<?php
session_start();
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
</head>
<body>
    <h2>Réservation</h2>


    <form id="reservationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        Club :
        <select name="clubs" id="clubs" onchange="this.form.submit()">
            <option value="">Sélectionnez un club</option>
            <?php
            // Incluez ici votre connexion à la base de données
            // Assurez-vous que $bdd est correctement initialisé
            $query = $bdd->prepare("SELECT nom_club FROM clubs c INNER JOIN inscription i ON c.id_club = i.id_club WHERE id_utilisateur = ?");
            $query->execute(array($_SESSION['id_utilisateur']));
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

        <!-- Menu déroulant pour sélectionner la discipline -->
        Discipline :
        <select name="disciplines" id="disciplines">
            <option value="">Sélectionnez une discipline</option>
            <?php
            // Lorsque le formulaire est soumis, récupérez les disciplines associées au club sélectionné
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
                    echo "<option value='$nom_discipline'>$nom_discipline</option>";
                }
            }
            ?>
        </select>
        installations : 
        <select name="installations" id="installations">
            <option value="">Séléctionnez une installation</option>
            <?php
            if (isset($_POST['disciplines']) && !empty($_POST['disciplines'])){
                $discpline_selected = $_POST["disciplines"];
                $query = $bdd->prepare("
                SELECT nom_installation FROM installations i
                INNER JOIN disciplines d ON i.id_discipline = d.id_discipline
                WHERE d.type_discipline = ?
                
                ");
                $query->execute(array($discpline_selected));
                while ($installation = $query->fetch()){
                    $nom_installation = $installation["nom_installation"];
                    echo "<option value = '$nom_installation'>$nom_installation</option>";
                }
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Réserver">
    </form>
</body>
</html>




