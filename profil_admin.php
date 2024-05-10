<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width' initial-scale='1.0'/>
	<link rel = 'stylesheet' href = 'style_2.css'/>
	<title>Profil Administateur</title>
</head>
<body>

<?php
session_start();

// voir les erreurs *//
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


<div id = 'titre_1'>
	<p>Tableau de bord</p>
</div>

<div class = 'contenant'>
	<!--Ajouter adhérent-->
	<div class = 'section'>
			<div id = 'titre_2_1'>
			<a><p>Ajouter un adhérent</p></a>
			</div>

			<div id ='action_1'>

				<form method='post' action='profil_admin.php'>
					<div class = 'champ'>Rechercher parmi les inscrits :</div>
					<div class='champ'>
						<label for='Nom'>Nom : </label> <input id='Nom' name='nom' type='text' size='30'
							placeholder='De Courbertin' required='required' />
					</div>
					<div class='champ'>
						<label for='Prénom'>Prénom : </label><input id='Prénom' name='prenom' type='text' size='30'
							placeholder='Pierre' required='required' />
					</div>

					<div class='bouton'><input type='submit' name='envoyer' value='Rechercher' /></div>
				</form>

				<div class = 'champ'>Résultats</div>

				<?php
				if (isset($_POST['nom']) && isset($_POST['prenom'])) {
					$nom_a_a = $_POST['nom'];
					$prenom_a_a = $_POST['prenom'];

					$req_a = $bdd->prepare("SELECT utilisateur.id_utilisateur,nom,prenom,date_de_naissance FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_club = $id_club AND (utilisateur.nom LIKE '%$nom_a_a%' OR utilisateur.prenom LIKE '%$prenom_a_a%') ;");
					$req_a->execute();
					$options = "<option value=''>Choissisez un inscrit</option>";
					while ($row = $req_a->fetch()) {
						$options .= "<option value='" . $row['utilisateur.id_utilisateur']."'>" . $row['nom']. ' ' .$row['prenom']. ' né(e) le '. $row['date_de_naissance']."</option>";
					}
					if ($req_a->rowCount() == 0) {
						$options = "<option value=''>Aucun inscrit trouvé</option>"; 
					}
				} else {
					$options = "<option value=''>Choissisez un inscrit</option>";
				}
				?>

				<form method='post' action='profil_admin.php'>

					<label for="choix_adherent"></label>
					<select id="choix_adherent" name="choix_adherent">
						<?php echo $options; ?>
					</select>
					<div class='bouton'><input type='submit' value='Ajouter un adhérent' /></div>

				</form>

				<?php
				if (isset($_POST['choix_adherent'])) {
					$id_adherent = $_POST['choix_adherent'];
					$req_update = $bdd->prepare("UPDATE inscription SET est_adherent = 1 WHERE id_club = $id_club AND id_utilisateur = $id_adherent;");
					$req_update->execute();
					echo "Adhérent ajouté avec succès.";
				} else if (isset($_POST[""])) {
					echo "Veuillez sélectionner un adhérent.";
				}
				?>
        	</div>
	</div>
	<!--Exclure un adhérent-->
	<div class = 'section'>
			<div id = 'titre_2_2'>
			<p>Exclure un adhérent</p>
			</div>

			<div id ='action_2'>
				<form method='post' action='profil_admin.php'>
					<div class = 'champ'>Rechercher parmi les adhérents :</div>
					<div class='champ'>
						<label for='Nom'>Nom : </label> <input id='Nom' name='nom' type='text' size='30'
							placeholder='De Courbertin' required='required' />
					</div>
					<div class='champ'>
						<label for='Prénom'>Prénom : </label><input id='Prénom' name='prenom' type='text' size='30'
							placeholder='Pierre' required='required' />
					</div>

					<div class='bouton'><input type='submit' name='envoyer' value='Rechercher' /> </div>
				</form>

				<div class = 'champ'>Résultats</div>
				
				<?php
				if (isset($_POST['nom']) && isset($_POST['prenom'])) {
					$nom_a_a = $_POST['nom'];
					$prenom_a_a = $_POST['prenom'];

					$req_a = $bdd->prepare("SELECT utilisateur.id_utilisateur,nom,prenom,date_de_naissance FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_club = $id_club AND (utilisateur.nom LIKE '%$nom_a_a%' OR utilisateur.prenom LIKE '%$prenom_a_a%') ;");
					$req_a->execute();
					$options = "<option value=''>Choissisez un inscrit</option>";
					while ($row = $req_a->fetch()) {
						$options .= "<option value='" . $row['utilisateur.id_utilisateur']."'>" . $row['nom']. ' ' .$row['prenom']. ' né(e) le '. $row['date_de_naissance']."</option>";
					}
					if ($req_a->rowCount() == 0) {
						$options = "<option value=''>Aucun inscrit trouvé</option>"; 
					}
				} else {
					$options = "<option value=''>Choissisez un inscrit</option>";
				}
				?>

				<form method='post' action='profil_admin.php'>

					<label for="choix_adherent"></label>
					<select id="choix_adherent" name="choix_adherent">
						<?php echo $options; ?>
					</select>
					<div class='bouton'><input type='submit' value='Ajouter un adhérent' /></div>

				</form>

				<?php
				if (isset($_POST['supprimer_inscription'])) {
					$id_utilisateur = $_POST['id_utilisateur'];
					$req_supprimer = $bdd->prepare("DELETE FROM inscription WHERE id_utilisateur = $id_utilisateur AND id_club = :id_club");
					$req_supprimer->execute();
					echo "Inscription supprimée avec succès.";
				}
				?>
        
        </div>
	</div>
</div>

<div class = 'contenant'>
	<!--Ajouter une installation-->
	<div class = 'section'>
			<div id = 'titre_2_3'>
			<p>Ajouter une installation</p>
			</div>

				<div id = 'action_3'>
						<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
							<div class = 'champ'>
							<label for='Type discipline'> Type de discipline : </label>
								<select id='Type discipline' name='type_discipline'>
								
								<?php
								    $req = $bdd->prepare("SELECT type_discipline FROM disciplines;");
									$req->execute();
								?>
								<?php
								$options = "<option value=''>Choissisez un type de discpline</option>";
								while($data = $req->fetch())
									$options = "<option value=".  $data["type_discipline"]."'>" .$data["type_discipline"]."</option>";
								{
									?>
									
									<?php
								}
								?>
								</select>
							</div>
							<div class ='bouton'><input type="submit" value="Filtrer"></div>
							<?php
							
							if (isset($_POST['type_discipline'])) {
								$discipline = $_POST['type_discipline'];


								// Requête SQL pour récupérer les installations filtrées
								$req2 = $bdd->prepare("SELECT nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline WHERE type_discipline = '$discipline'");
								$req2->execute();

								// Générer les options HTML pour la liste déroulante "Installation"
								$options = "<option value=''>Sélectionnez une installation</option>";
								while ($row = $req2->fetch()) {
									$options .= "<option value='" . $row['id'] . "'>" . $row['nom_installation'] . "</option>";
								}
							} else {
								$discipline = "";
								$options = "<option value=''>Sélectionnez une installation</option>";
							}

							?>
							<label for="installation">Installation :</label>
						<select id="installation" name="installation">
							<?php echo $options; ?>
						</select>
						<div class = 'champ'>
								<div class = 'champ' ><label for='Emplacement'> Emplacement : </label></div>
								<div  class = 'champ'><input id='bouton_1' name='emplacement' type='radio' /><label for='Emplacement'> Sur Piste  </label></div>
								<div  class = 'champ'><input id='bouton_2' name='emplacement' type='radio' /><label for='Emplacement'> Hors-Piste </label></div>
						</div>
							
						<div class = 'bouton'><input type='submit' name='envoyer' value='Ajouter l&#39installation'/></div>
						</form>

				</div>
	</div>

	<!--Modifier les informations d'une installation-->
	<div class = 'section'>
				<div id = 'titre_2_4'>
				<p>Modifier les informations d'une installation</p>
				</div>

				<div id = 'action_4'>
						<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
							<div class = 'champ'>
							<label for='Type discipline'> Type de discipline : </label>
								<select id='Type discipline' name='type_discipline'>
								<?php
								while($data = $req->fetch())
								{
									?>
									<option value=<?php echo $data["type_discipline"]; ?>><?php echo $data["type_discipline"]; ?></option>
									<?php
								}
								?>
								</select>
							</div>
							<div class = 'bouton'><input type="submit" value="Filtrer"></div>
							<?php
							
							// Vérifier si le formulaire a été soumis
							if (isset($_POST['type_discipline'])) {
								$discipline = $_POST['type_discipline'];


								// Requête SQL pour récupérer les installations filtrées
								$req2 = $bdd->prepare("SELECT nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline WHERE type_discipline = '$discipline'");
								$req2->execute();

								// Générer les options HTML pour la liste déroulante "Installation"
								$options = "<option value=''>Sélectionnez une installation</option>";
								while ($row = $req2->fetch()) {
									$options .= "<option value='" . $row['id'] . "'>" . $row['nom_installation'] . "</option>";
								}
							} else {
								$discipline = "";
								$options = "<option value=''>Sélectionnez une installation</option>";
							}

							?>
							<label for="installation">Installation :</label>
						<select id="installation" name="installation">
							<?php echo $options; ?>
						</select>
						<div class = 'champ'>
								<div class = 'champ' ><label for='Emplacement'> Emplacement : </label></div>
								<div  class = 'champ'><input id='bouton 1' name='emplacement' type='radio' /><label for='Emplacement'> Sur Piste  </label></div>
								<div  class = 'champ'><input id='bouton 2' name='emplacement' type='radio' /><label for='Emplacement'> Hors-Piste </label></div>
							</div>
							
							<div class = 'bouton'><input type='submit' name='envoyer' value='Modifier les informations de l&#39installation'/></div>
						</form>

				</div>
	</div>

	<!--Bloquer une ou plusieurs installations-->
	<div class = 'section'>
				<div id = 'titre_2_5'>
				<p>Bloquer une ou plusieurs installations</p>
				</div>

				<div id = 'action_5'>
						<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
							<div class = 'champ'>
							<label for='Type discipline'> Type de discipline : </label>
								<select id='Type discipline' name='type_discipline'>
								<?php
								while($data = $req->fetch())
								{
									?>
									<option .value=<?php echo $data["type_discipline"]; ?>><?php echo $data["type_discipline"]; ?></option>
									<?php
								}
								?>
								</select>
							</div>
							<div class = 'bouton'><input type="submit" value="Filtrer"></div>
							<?php
							
							// Vérifier si le formulaire a été soumis
							if (isset($_POST['type_discipline'])) {
								$discipline = $_POST['type_discipline'];


								// Requête SQL pour récupérer les installations filtrées
								$req2 = $bdd->prepare("SELECT nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline WHERE type_discipline = '$discipline'");
								$req2->execute();

								// Générer les options HTML pour la liste déroulante "Installation"
								$options = "<option value=''>Sélectionnez une installation</option>";
								while ($row = $req2->fetch()) {
									$options .= "<option value='" . $row['id'] . "'>" . $row['nom_installation'] . "</option>";
								}
							} else {
								$discipline = "";
								$options = "<option value=''>Sélectionnez une installation</option>";
							}

							?>
							<label for="installation">Installation :</label>
						<select id="installation" name="installation">
							<?php echo $options; ?>
						</select>
						<div class = 'champ'>
								<div class = 'champ' ><label for='Emplacement'> Emplacement : </label></div>
								<div  class = 'champ'><input id='bouton 1' name='emplacement' type='radio' /><label for='Emplacement'> Sur Piste  </label></div>
								<div class = 'champ' ><input id='bouton 2' name='emplacement' type='radio' /><label for='Emplacement'> Hors-Piste </label></div>
						</div>
							
						<div class = 'bouton'><input type='submit' name='envoyer' value='Ajouter l&#39installation'/></div>
						</form>

				</div>
	</div>

	<!--Supprimer une installation-->
	<div class = 'section'>
				<div id = 'titre_2_6'>
				<p>Supprimer une installation</p>
				</div>

				<div id = 'action_6'>
						<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
							<div class = 'champ'>
							<label for='Type discipline'> Type de discipline : </label>
								<select id='Type discipline' name='type_discipline'>
								<?php
								while($data = $req->fetch())
								{
									?>
									<option value=<?php echo $data["type_discipline"]; ?>><?php echo $data["type_discipline"]; ?></option>
									<?php
								}
								?>
								</select>
							</div>
							<input type="submit" value="Filtrer">
							<?php
							
							// Vérifier si le formulaire a été soumis
							if (isset($_POST['type_discipline'])) {
								$discipline = $_POST['type_discipline'];


								// Requête SQL pour récupérer les installations filtrées
								$req2 = $bdd->prepare("SELECT nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline WHERE type_discipline = '$discipline'");
								$req2->execute();

								// Générer les options HTML pour la liste déroulante "Installation"
								$options = "<option value=''>Sélectionnez une installation</option>";
								while ($row = $req2->fetch()) {
									$options .= "<option value='" . $row['id'] . "'>" . $row['nom_installation'] . "</option>";
								}
							} else {
								$discipline = "";
								$options = "<option value=''>Sélectionnez une installation</option>";
							}

							?>
							<label for="installation">Installation:</label>
						<select id="installation" name="installation">
							<?php echo $options; ?>
						</select>
						<div class = 'champ'>
								<div class = 'champ' ><label for='Emplacement'> Emplacement : </label></div>
								<div  class = 'champ'><input id='bouton 1' name='emplacement' type='radio' /><label for='Emplacement'> Sur Piste  </label></div>
								<input id='bouton 2' name='emplacement' type='radio' /><label for='Emplacement'> Hors-Piste </label>
							</div>
							
							<input type='submit' name='envoyer' value='Ajouter l&#39installation'/>
						</form>

				</div>
	</div>
</div>

<div class = 'contenant'>
	<!--Annuler une réservation-->
	<div class = 'section'>
				<div id = 'titre_2_7'>
				<p>Annuler une réservation</p>
				</div>

				<div id = 'action_7'>
						<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
							<div class = 'champ'>
							<label for='Type discipline'> Type de discipline : </label>
								<select id='Type discipline' name='type_discipline'>
								<?php
								while($data = $req->fetch())
								{
									?>
									<option value=<?php echo $data["type_discipline"]; ?>><?php echo $data["type_discipline"]; ?></option>
									<?php
								}
								?>
								</select>
							</div>
							<input type="submit" value="Filtrer">
							<?php
							
							// Vérifier si le formulaire a été soumis
							if (isset($_POST['type_discipline'])) {
								$discipline = $_POST['type_discipline'];


								// Requête SQL pour récupérer les installations filtrées
								$req2 = $bdd->prepare("SELECT nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline WHERE type_discipline = '$discipline'");
								$req2->execute();

								// Générer les options HTML pour la liste déroulante "Installation"
								$options = "<option value=''>Sélectionnez une installation</option>";
								while ($row = $req2->fetch()) {
									$options .= "<option value='" . $row['id'] . "'>" . $row['nom_installation'] . "</option>";
								}
							} else {
								$discipline = "";
								$options = "<option value=''>Sélectionnez une installation</option>";
							}

							?>
							<label for="installation">Installation:</label>
						<select id="installation" name="installation">
							<?php echo $options; ?>
						</select>
						
								<div class = 'champ' ><label for='Emplacement'> Emplacement : </label></div>
								<div  class = 'champ'><input id='bouton 1' name='emplacement' type='radio' /><label for='Emplacement'> Sur Piste  </label></div>
								<div class = 'champ' ><input id='bouton 2' name='emplacement' type='radio' /><label for='Emplacement'> Hors-Piste </label></div>
								
								<input type='submit' name='envoyer' value='Ajouter l&#39installation'/>
								</form>
							</div>
							
						

				</div>
	</div>
</div>

<div class = 'contenant'>
	<!--Statistiques-->
	<div class = 'section'>
		<a href ='page_statistiques.php'><div id = 'titre_2_7'>Statistiques</div></a>
	</div>
</div>

<script src = 'admin.js'></script>
</body>
</html>