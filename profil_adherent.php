
<?php
session_start();
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
</head>
<body>
    <h2>Réservation</h2>
    <form action="action_reservation.php" method="POST">
        Club :
        <?php 
        $query = $bdd->prepare("SELECT nom_club FROM clubs c INNER JOIN inscription i ON c.id_club = i.id_club 
            WHERE id_utilisateur = ?");
        $query->execute(array($_SESSION['id_utilisateur']));
        // Récupération des résultats sous forme de tableau
        // Affichage des options dans le premier menu déroulant
        ?>
        <select name="clubs" id="clubs">
            <?php
            while ($club = $query->fetch()) {
                echo "<option value='" . $club["nom_club"] . "'>" . $club["nom_club"] . "</option>";
            }
            $club = $_POST['clubs']
            ?>
        </select>

        <!-- Menu déroulant pour sélectionner la discipline -->
        Discipline :
        <select name="disciplines" id="disciplines">
            <option value="">Sélectionnez une discipline</option>
            <?php
            // Requête simplifiée pour récupérer les disciplines disponibles dans les clubs de l'utilisateur
            $query = $bdd->prepare("
                SELECT DISTINCT d.id_discipline, d.type_discipline
                FROM disciplines d
                INNER JOIN installations i ON d.id_discipline = i.id_discipline
                INNER JOIN presence p ON 
                WHERE ins.id_utilisateur = ?");
            $query->execute(array($_SESSION['id_utilisateur']));
            // Récupération des résultats sous forme de tableau
            $disciplines = $query->fetchAll();
            // Affichage des options dans le premier menu déroulant
            foreach ($disciplines as $discipline) {
                echo "<option value='" . $discipline['id_discipline'] . "'>" . $discipline['type_discipline'] . "</option>";
            }


            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer la valeur sélectionnée dans le menu déroulant des disciplines
                $discipline_id = $_POST['disciplines'];
            
                // Utilisez cette valeur comme vous le souhaitez, par exemple, l'insérer dans votre base de données ou l'afficher
                echo "La discipline sélectionnée a l'ID : " . $discipline_id;
            }
            ?>
            ?>
            
        </select>

        <!-- Menu déroulant pour afficher les installations -->
        Installation :
        <select name="installations" id="installations">
            <option value="">Sélectionnez une discipline d'abord</option>
        </select>

        Date de réservation: <input type="date" name="date_reservation" required><br>
        Heure de début: <input type="time" name="heure_debut" required><br>
        Heure de fin: <input type="time" name="heure_fin" required><br>
        <input type="submit" value="Réserver">
    </form>
</body>
</html>

