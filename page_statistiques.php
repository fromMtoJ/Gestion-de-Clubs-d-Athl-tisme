<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width' initial-scale=1.0/>
	<title>Statistiques</title>
	<link rel = 'stylesheet' href = 'style_statistiques.css' />
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
	//Récupération du nom et prénom de l'utilisateur
	$req_n_p = $bdd->prepare("SELECT nom,prenom FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_utilisateur = '$id_utilisateur' AND inscription.administrateur = 1 ;");
	$req_n_p->execute();
	$row_n_p = $req_n_p->fetch();

	//Récupération du nom du club
	$req_nc = $bdd->prepare("SELECT nom_club FROM clubs INNER JOIN inscription ON clubs.id_club = inscription.id_club INNER JOIN utilisateur ON inscription.id_utilisateur = utilisateur.id_utilisateur WHERE inscription.id_utilisateur = '$id_utilisateur' AND inscription.administrateur = 1;");
	$req_nc->execute();
	$row_nc = $req_nc->fetch();
	?>

	<?php
	//Récupération de l'id du club
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
	<div class = 'stat_1'>

		<?php
		//Récupération du nombre d'adhérents
		$req_n_a = $bdd->prepare("SELECT COUNT(*) FROM inscription WHERE inscription.id_club = '$id_club' AND est_adherent = 1;");
		$req_n_a->execute();
		$nb_adherent = $req_n_a->fetchColumn();
		?>

		<div>Nombre d'adhérents :</div><div class = 'resultat'><?php echo $nb_adherent?></div>

	</div>

	<!--Nombre d’heures réservées sur l’année d'un adhérent -->
		<div class="sous_stat">Nombre d'heures réservées sur l'année de l'adhérent(e)</div>
	
		<div class = 'contenant_1'>
		<?php
		$nb_h_r_a = null;
		$req_h_d_f = $bdd->prepare("SELECT heure_ouverture, heure_fermeture FROM clubs WHERE id_club = $id_club;");
		$req_h_d_f->execute();
		$row = $req_h_d_f->fetch();
		$heure_ouverture = $row['heure_ouverture'];
		$heure_fermeture = $row['heure_fermeture'];
		?>

		<div class = 'section_1'><div>Rechercher un adhérent :</div>

			<form method='post' action='page_statistiques.php'>
				<!--Formulaire pour rechercher un adhérent et choisir une année-->
				<div class='champ'>
				<label for='Date'>Choisir une année : </label>

				<?php 
				$annee_actuelle = date("Y");
				$annees = range($annee_actuelle - 80, $annee_actuelle + 80); 
				?>
				<select id = "annee" name =" annee">
					<?php foreach($annees as $annee) : ?>
						<option value="<?php echo $annee; ?>" <?php if ($annee == $annee_actuelle) echo 'selected'; ?>><?php echo $annee; ?></option>
					<?php endforeach; ?>
				</select>

				<div class='champ'></div>
					<label for='Nom'>Nom : </label> <input id='Nom' name='nom_a' type='text' size='30'
						placeholder='De Courbertin' required='required' />
				</div>

				<div class='champ'>
					<label for='Prénom'>Prénom : </label><input id='Prénom' name='prenom_a' type='text' size='30'
						placeholder='Pierre' required='required' />
				</div>

				<div class='bouton'><input type='submit' name='envoyer' value='Rechercher' /></div>
			</form>
		</div>

		<div class = 'section_1'>
			<div class = 'champ'>Résultats</div>

			<?php
				if (isset($_POST['nom_a']) && isset($_POST['prenom_a']) && isset($_POST['annee'])) {
					$nom_a_a = $_POST['nom_a'];
					$prenom_a_a = $_POST['prenom_a'];
					$_SESSION['annee'] = $_POST['annee'];

					//Récupération des adhérents
					$req_a = $bdd->prepare("SELECT utilisateur.id_utilisateur,nom,prenom,date_de_naissance FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_club = $id_club AND (utilisateur.nom LIKE '%$nom_a_a%' OR utilisateur.prenom LIKE '%$prenom_a_a%') ;");
					$req_a->execute();
					$options = "<option value=''>Choissisez un adhérent</option>";
					while ($row = $req_a->fetch()) {
						$options .= "<option value='" . $row['id_utilisateur']."'>" . $row['nom']. ' ' .$row['prenom']. ' né(e) le '. $row['date_de_naissance']."</option>";
					}
					if ($req_a->rowCount() == 0) {
						$options = "<option value=''>Aucun adhérent trouvé</option>"; 
					}
				} else {
					$options = "<option value=''>Choissisez un adhérent</option>";
				}
			?>

			<!--Formulaire pour choisir un adhérent-->
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
				$annee_choisie = $_SESSION['annee'];
				//Récupération des heures réservées par l'adhérent
				$req_h_r_a = $bdd->prepare("SELECT date_debut_reservation, date_fin_reservation, heure_debut_reservation, heure_fin_reservation FROM reservation WHERE id_utilisateur = $id_adherent AND blocage = 0 AND date_debut_reservation LIKE '".$annee_choisie."%' AND date_fin_reservation LIKE '".$annee_choisie."%';");
				$req_h_r_a->execute();
				$total_heures_reservees = 0;

				while ($row_h_r_a = $req_h_r_a->fetch()) {
					$date_debut = $row_h_r_a['date_debut_reservation'];
					$date_fin = $row_h_r_a['date_fin_reservation'];
					$heure_debut = $row_h_r_a['heure_debut_reservation'];
					$heure_fin = $row_h_r_a['heure_fin_reservation'];
					
					$debut1 = strtotime($date_debut . ' ' . $heure_debut);
					$fin1 = strtotime($date_fin . ' ' . $heure_fin);
					
					
					if ($debut1 < strtotime($date_debut . ' ' . $heure_ouverture)) {
						$debut1 = strtotime($date_debut . ' ' . $heure_ouverture);
					}
					
					if ($fin1 > strtotime($date_fin . ' ' . $heure_fermeture)) {
						$fin1 = strtotime($date_fin . ' ' . $heure_fermeture);
					}
					
					$heures_reservees = ($fin1 - $debut1) / 3600;
					$total_heures_reservees += $heures_reservees;
				}
				$nb_h_r_a = $total_heures_reservees;
				}
				?>
		</div>
		</div>

		<?php if (isset($_POST['choix_adherent'])): ?>
			<div class = 'stat'>
			<div class="nom_stat">Nombre d'heures réservées sur l'année de l'adhérent(e) :</div> <div class = "resultat"><?php  echo " ".$nb_h_r_a !== null ? $nb_h_r_a ." heures" : "0"; ?></div>
			</div>
		<?php endif; ?>


	<!--Taux de réservation moyen d'une installation par semaine -->

	<div class = 'sous_stat'>	
	Taux de réservation moyen d'une installation par semaine :
	</div>

		<div class = 'contenant_1'>
			<div class = 'section_2'>

			<?php
			//Récupération des installations du club
			$req_i = $bdd->prepare("SELECT installations.id_installation,nom_installation FROM installations INNER JOIN presence ON installations.id_installation = presence.id_installation WHERE presence.id_club = $id_club;");
			$req_i->execute();
			$options = "<option value=''>Choissisez une installation</option>";
			while ($row = $req_i->fetch()) {
				$options .= "<option value='" . $row['id_installation']."'>" . $row['nom_installation']. "</option>";
			}
			?>
			<!--Formulaire pour choisir une installation-->
			<form action='page_statistiques.php' method='post'>
				<div class='champ'>
				<label for='installation'></label>
				<select id ='installation' name='installations'>
					<?php echo $options; ?>
				</select>
				</div>
				<div class='bouton'><input type='submit' name='envoyer' value='Valider' /></div>
			</form>
			</div>
		</div>
			
			<?php
			//Récupération des horaires de reservation
			if (isset($_POST['installations'])) {
				$id_installation = $_POST['installations'];
				$req_taux_res = $bdd->prepare("SELECT date_debut_reservation, date_fin_reservation, heure_debut_reservation, heure_fin_reservation, WEEK(date_debut_reservation) AS semaine 
				FROM reservation 
				INNER JOIN installations ON reservation.id_installation = installations.id_installation 
				WHERE installations.id_installation = $id_installation AND reservation.id_club = $id_club
				GROUP BY WEEK(date_debut_reservation);");
				$req_taux_res->execute();

				$nb_heures_reservees = 0;
				$total_semaines = 0;
				$taux_res_semaine_total = 0;

				while ($row_taux_res = $req_taux_res->fetch()) {
					$semaine = $row_taux_res['semaine'];
					$total_hours_reserved = 0;
					$date_debut = $row_taux_res['date_debut_reservation'];
					$date_fin = $row_taux_res['date_fin_reservation'];
					$heure_debut = $row_taux_res['heure_debut_reservation'];
					$heure_fin = $row_taux_res['heure_fin_reservation'];
						
						$debut = strtotime($date_debut . ' ' . $heure_debut);
						$fin = strtotime($date_fin . ' ' . $heure_fin);
						

						if ($debut < strtotime($date_debut . ' ' . $heure_ouverture)) {
							$debut = strtotime($date_debut . ' ' . $heure_ouverture);
						}
						
						if ($fin > strtotime($date_fin . ' ' . $heure_fermeture)) {
							$fin = strtotime($date_fin . ' ' . $heure_fermeture);
						}
						//calcul du nombre d'heures réservées
						$heures_reservees = ($fin - $debut) / 3600;
						$nb_heures_reservees += $heures_reservees;

						//calcul du nombre d'heures disponibles
						$nb_heures_disponibles = 7 * ((strtotime($heure_fermeture) - strtotime($heure_ouverture))/3600);
						
						//calcul du taux de réservation par semaine
						$taux_res_semaine = ($nb_heures_reservees / $nb_heures_disponibles)*100;

						
					}

					if ($taux_res_semaine > 0) {
						$taux_res_semaine_total += $taux_res_semaine;
						$total_semaines++;
					}
					//moyenne des taux de reservation par semaine
					$taux_res_moyen_semaine = $taux_res_semaine_total / $total_semaines;
				}
				
		?>

		<?php if (isset($_POST['installations'])): ?>
			<div class="stat_2">
			<div class="nom_stat">Taux de réservation moyen de l'installation par semaine : </div><div class = "resultat"><?php echo isset($taux_res_moyen_semaine) ? number_format($taux_res_moyen_semaine, 2) : "N/A"; ?>. %</div>
			</div>
		<?php endif; ?>

		</div>
		
</body>
</html>