<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width' initial-scale='1.0'/>
	<link rel = 'stylesheet' href = 'style_p_a.css'/>
	<title>Profil Administateur</title>
</head>
<body>
<?php
$bdd = new PDO("mysql:host=localhost;dbname=donnees;charset=utf8", "root", "");
    $req = $bdd->prepare("SELECT type_discipline FROM disciplines;");
    $req->execute();

?>



<div id = 'sous_titre'>
	<div id = 'sous_titre_1'><p>Tableau de bord</p></div><div id = 'sous_titre_2'><p>Tableau de bord</p></div>
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
            <form method='post' action='accueil.php'>
                <div class='champ'>
                    <label for='Nom'>Nom : </label> <input id='Nom' name='nom' type='text' size='30'
                        placeholder='De Courbertin' required='required' />
                </div>
                <div class='champ'>
                    <label for='Prénom'>Prénom : </label><input id='Prénom' name='prenom' type='text' size='30'
                        placeholder='Pierre' required='required' />
                </div>

                <div class='bouton'><input type='submit' name='envoyer' value='Ajouter au club' /></div>
            </form>
        	</div>
	</div>
	<!--Exclure un adhérent-->
	<div class = 'section'>
			<div id = 'titre_2_2'>
			<p>Exclure un adhérent</p>
			</div>

			<div id ='action_2'>
            <form method='post' action='accueil.php'>
                <div class='champ'>
                    <label for='Nom'>Nom : </label> <input id='Nom' name='nom' type='text' size='30'
                        placeholder='De Courbertin' required='required' />
                </div>
                <div class='champ'>
                    <label for='Prénom'>Prénom : </label><input id='Prénom' name='prenom' type='text' size='30'
                        placeholder='Pierre' required='required' />
                </div>

                <div class='bouton'><input type='submit' name='envoyer' value='Exclure du club' /> </div>
            </form>
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
								while($data = $req->fetch())
								{
									?>
									<option value=<?php echo $data["type_discipline"]; ?>><?php echo $data["type_discipline"]; ?></option>
									<?php
								}
								?>
								</select>
							</div>
							<div class ='bouton'><input type="submit" value="Filtrer"></div>
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
		<a href ='http://localhost/projet_if3a/page_statistiques.php'><div id = 'titre_2_7'>Statistiques</div></a>
	</div>
</div>

<script src = 'admin.js'></script>
</body>
</html>