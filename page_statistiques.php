<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width' initial-scale=1.0/>
	<link rel = 'stylesheet' href = 'style_2.css'/>
	<title>Statistiques</title>
</head>
<form>

	<!--Base de donnée-->
	<?php
	session_start();
	error_reporting(E_ALL);
    ini_set('display_errors', 1);
	$id_utilisateur = $_SESSION['id_utilisateur'];
	$bdd = new PDO("mysql:host=localhost;dbname=donnees;charset=utf8", "root", "");
	?>

	<?php
	$req_n_p = $bdd->prepare("SELECT nom,prenom FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_utilisateur = '$id_utilisateur' AND inscription.administrateur = 1 ;");
	$req_n_p->execute();
	$row_n_p = $req_n_p->fetch();

	$req_nc = $bdd->prepare("SELECT nom_club FROM clubs INNER JOIN inscription ON clubs.id_club = inscription.id_club INNER JOIN utilisateur ON inscription.id_utilisateur = utilisateur.id_utilisateur WHERE inscription.id_utilisateur = '$id_utilisateur' AND inscription.administrateur = 1;");
	$req_nc->execute();
	$row_nc = $req_nc->fetch();
	?>

	<?php
	$req_ic = $bdd->prepare("SELECT id_club FROM inscription WHERE inscription.id_utilisateur = '$id_utilisateur' AND inscription.administrateur = 1;");
	$req_ic->execute();
	$row_ic = $req_ic->fetch();
	$id_club = $row_ic['id_club'];
	?>

	<div id = 'sous_titre'>
		<div id = 'sous_titre_1'><?php echo $row_n_p['prenom']." ". $row_n_p['nom']?></div><div id = 'sous_titre_2'><?php echo $row_nc['nom_club']?></div>
	</div>

	<div id = 'titre_1'><p>Statistiques du club</p></div>
	<!--Nombre d'adhérents-->
	<div class = 'stat'>
		<?php
		$req_n_a = $bdd->prepare("SELECT COUNT(*) FROM inscription WHERE inscription.id_club = '$id_club' AND est_adherent = 1;");
		$req_n_a->execute();
		$nb_adherent = $req_n_a->fetchColumn();
		?>
		<div>Nombre d'adhérents :</div><div class = 'resultat'><?php echo $nb_adherent?></div>
	</div>
	<!--Nombre d’heures réservées sur l’année d'un adhérent -->
	<div class = 'stat'>
		<?php
		$nb_h_r_a = 0;
		?>
	<div>Nombre d’heures réservées sur l’année d'un adhérent :</div><div class = 'resultat'><?php echo $nb_h_r_a." heures"?></div>
	</div>
	<div class = 'contenant_3'>
	<div class = 'condition_stat'><div>Rechercher un adhérent :</div><div id = 'condition_2'>Résultats :</div></div> 
	
	<form action='page_statistiques.php' method='post'>
		<div class = 'champ_2' ><label for='Nom'>Nom : </label> <input id='Nom' name='nom' type='text' size='30'placeholder='De Courbertin' required='required'   /></div>
		<div class = 'champ_2' ><label for='Prénom'>Prénom : </label><input id='Prénom' name='prenom' type='text' size='30'placeholder='Pierre' required='required'  /></div>
	</div>
	<div class='contenant_2'><input type='submit' name='envoyer' value='Rechercher'/></div>
	
	<?php
print_r($_POST);
?>

	<?php
	    if (isset($_POST['nom']) && isset($_POST['prenom'])) {
		$nom_a = $_POST['nom'];
		$prenom_a = $_POST['prenom'];	
		echo "Nom: $nom_a, Prénom: $prenom_a";
		
		$req_a = $bdd->prepare("SELECT nom,prenom,date_de_naissance FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_club = $id_club AND (utilisateur.nom LIKE '%$nom_a%' OR utilisateur.prenom LIKE '%$prenom_a%') ;");
		$req_a->execute();

			$options = "<option value=''>Choissisez un adhérent</option>";
			while ($row = $req_a->fetch()) {
			$options .= "<option value='" . $row['nom']."'>" . $row['nom']. ' ' .$row['prenom']. ' né(e) le '. $row['date_de_naissance']."</option>";
			}
			if ($req_a->rowCount() == 0) {
				$options = "<option value=''>Aucun adhérent trouvé</option>"; 
			}
		} else {
		$options = "<option value=''>Choissisez bip bip un adhérent</option>";
		}
		?>
	
	
	
	
		<label for="choix_adherent"> Essai</label>
		<select id="choix_adherent" name="choix_adherent">
				<?php echo $options; ?>
		</select>
		<input type='submit' value='Choisir' /></div>
	</form>
	<!--Taux de réservation moyen d'une installation par semaine -->

	<div class = 'stat'>	
	<div class = 'nom_stat'><p>Taux de réservation moyen d'une installation par semaine :</p></div>
	</div>
		<div class = 'contenant_3'><div class = 'condition_stat'>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<label for='installation'>Choissisez l'installation :  </label>
		<select id ='installation' name='installations'>
		<?php
		$req = $bdd->prepare("SELECT nom_installation FROM installations INNER JOIN presence ON installations.id_installation = presence.id_installation INNER JOIN clubs ON presence.id_club = clubs.id_club WHERE clubs.id_club = 1;");
		$req->execute();
		?>

		<?php
			while($data = $req->fetch())
			{
		?>

		<option value=<?php echo $data["nom_installation"]; ?>><?php echo $data["nom_installation"]; ?></option>
		
		<?php
			}
		?>
		</select>
		</div>
		</div>
		<div class = 'contenant_2'><div class = 'bouton'><input type="submit" value="Choisir"></div></div>
		</form>			
							
</body>
</html>