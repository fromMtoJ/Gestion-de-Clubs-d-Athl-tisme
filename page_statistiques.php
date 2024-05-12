<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width' initial-scale=1.0/>
	<title>Statistiques</title>
</head>
<body>

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
		$nb_h_r_a = null;
		$req_h_d_f = $bdd->prepare("SELECT heure_ouverture, heure_fermeture FROM clubs WHERE id_club = $id_club;");
		$req_h_d_f->execute();
		$row = $req_h_d_f->fetch();
		$heure_ouverture = $row['heure_ouverture'];
		$heure_fermeture = $row['heure_fermeture'];
		?>
	
</div>

	<div class = 'contenant_3'>
	<div class = 'condition_stat'><div>Rechercher un adhérent :</div><div id = 'condition_2'>Résultats :</div></div> 

	<form method='post' action='page_statistiques.php'>
		<div class = 'champ'>Rechercher parmi les adhérents :</div>
		<div class='champ'>
			<label for='Nom'>Nom : </label> <input id='Nom' name='nom_a' type='text' size='30'
				placeholder='De Courbertin' required='required' />
		</div>
		<div class='champ'>
			<label for='Prénom'>Prénom : </label><input id='Prénom' name='prenom_a' type='text' size='30'
				placeholder='Pierre' required='required' />
		</div>

					<div class='bouton'><input type='submit' name='envoyer' value='Rechercher' /></div>
	</form>

	<div class = 'champ'>Résultats</div>

	<?php
	if (isset($_POST['nom_a']) && isset($_POST['prenom_a'])) {
		$nom_a_a = $_POST['nom_a'];
		$prenom_a_a = $_POST['prenom_a'];

		$req_a = $bdd->prepare("SELECT utilisateur.id_utilisateur,nom,prenom,date_de_naissance FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_club = $id_club AND (utilisateur.nom LIKE '%$nom_a_a%' OR utilisateur.prenom LIKE '%$prenom_a_a%') ;");
		$req_a->execute();
		$options = "<option value=''>Choissisez un inscrit</option>";
		while ($row = $req_a->fetch()) {
			$options .= "<option value='" . $row['id_utilisateur']."'>" . $row['nom']. ' ' .$row['prenom']. ' né(e) le '. $row['date_de_naissance']."</option>";
		}
		if ($req_a->rowCount() == 0) {
			$options = "<option value=''>Aucun inscrit trouvé</option>"; 
		}
	} else {
		$options = "<option value=''>Choissisez un inscrit</option>";
	}
	?>

	<form method='post' action='page_statistiques.php'>

		<label for="diff_adherent"></label>
		<select id="diff_adherent" name="diff_adherent">
			<?php echo $options; ?>
		</select>
		<div class='bouton'><input type='submit' name ='choix_adherent' value='Valider' /></div>
	</form>
	
	<?php
	if (isset($_POST['choix_adherent'])) {
		$id_adherent = $_POST['diff_adherent'];
		$req_h_r_a = $bdd->prepare("SELECT date_debut_reservation, date_fin_reservation, heure_debut_reservation, heure_fin_reservation FROM reservation WHERE id_utilisateur = $id_adherent AND blocage = 0;");
		$req_h_r_a->execute();
		$total_hours_reserved = 0;
		while ($row_h_r_a = $req_h_r_a->fetch()) {
			$date_debut = $row_h_r_a['date_debut_reservation'];
			$date_fin = $row_h_r_a['date_fin_reservation'];
			$heure_debut = $row_h_r_a['heure_debut_reservation'];
			$heure_fin = $row_h_r_a['heure_fin_reservation'];
			
			$start = strtotime($date_debut . ' ' . $heure_debut);
			$end = strtotime($date_fin . ' ' . $heure_fin);
			
			// Check if the reservation starts before the opening time
			if ($start < strtotime($date_debut . ' ' . $heure_ouverture)) {
				$start = strtotime($date_debut . ' ' . $heure_ouverture);
			}
			
			// Check if the reservation ends after the closing time
			if ($end > strtotime($date_fin . ' ' . $heure_fermeture)) {
				$end = strtotime($date_fin . ' ' . $heure_fermeture);
			}
			
			$hours_reserved = ($end - $start) / 3600;
			$total_hours_reserved += $hours_reserved;
		}
		$nb_h_r_a = $total_hours_reserved;
	}
	?>
	<?php if (isset($_POST['choix_adherent'])): ?>
<div class="stat">Nombre d'heures réservées sur l'année d'un adhérent : <?php echo $nb_h_r_a !== null ? $nb_h_r_a . " heures" : "N/A"; ?></div>
<?php endif; ?>

	
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