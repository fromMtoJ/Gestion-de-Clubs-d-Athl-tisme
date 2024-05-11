<?php
session_start();
// Connexion à la base de données
$id_utilisateur = $_SESSION['id_utilisateur'];
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link rel = 'stylesheet' href = 'style_2.css'/>
</head>
<?php
        $req_n_p = $bdd->prepare("SELECT nom,prenom FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_utilisateur = '$id_utilisateur' ;");
            $req_n_p->execute();
            $row_n_p = $req_n_p->fetch(); 
    ?>

<div id = 'sous_titre'>
    <div id = 'sous_titre_1'><?php echo $row_n_p['prenom']." ". $row_n_p['nom']?></div>
</div>

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
    <select name="clubs" id="clubs">
        <option value="">Sélectionnez un club</option>
        <?php
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
    <div class ='bouton'><input type="submit" value="Filtrer"></div>

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
                print_r($discipline);
                $nom_discipline = $discipline["type_discipline"];
                echo "<option value='$nom_discipline'>$nom_discipline</option>";
            }
            
        }
        
        ?>
    </select>
    <div class ='bouton'><input type="submit" value="Filtrer"></div>

    
    installations : 
    <select name="installations" id="instal">
        <option value="">Séléctionnez une installation</option>
        <?php
        if (isset($_POST['disciplines']) && !empty($_POST['disciplines'])){

            $discipline_selected = $_POST["disciplines"];
            $query = $bdd->prepare("
            SELECT nom_installation FROM installations i
            INNER JOIN disciplines d ON i.id_discipline = d.id_discipline
            WHERE d.type_discipline = ?
            ");
            $query->execute(array($discipline_selected));
            while ($installation = $query->fetch()){
                
                $nom_installation = $installation["nom_installation"];
                
                echo "<option value = '$nom_installation'>$nom_installation</option>";
            }
        }else
        {
            echo "pas de discipline enregistré";
        }
        
        
        ?>
    </select>
    <div class ='bouton'><input type="submit" value="Filtrer"></div>
    <br><br>
    <input type="submit" value="Réserver">
</form>

<?php
$req = $bdd->prepare("SELECT administrateur FROM inscription WHERE id_utilisateur = $id_utilisateur;");
$req->execute();

while ($row = $req->fetch()) {
    if  ($row['administrateur'] === 1) {
        echo '<a href="profil_admin.php">Tableau de bord</a>';
    }
}
?>
</body>
</html>