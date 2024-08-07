<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width' initial-scale='1.0'/>
	<title>Profil Administateur</title>
	<link rel="stylesheet" href="style_2.css">
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

				<form method='post' action='profil_admin.php'>

					<label for="diff_adherent"></label>
					<select id="diff_adherent" name="diff_adherent">
						<?php echo $options; ?>
					</select>
					<div class='champ'><input id='bouton_admin' name='admin' type='radio' /><label for='admin'> Administrateur </label></div>
					<div class='bouton'><input type='submit' name ='choix_adherent' value='Ajouter un adhérent' /></div>

				</form>

				<?php
				if (isset($_POST['choix_adherent'])) {
					$id_utilisateur = $_POST['diff_adherent'];
					$est_admin = isset($_POST['admin']) ? 1 : 0;
					$req_a_a = $bdd->prepare("UPDATE inscription SET est_adherent = 1, date_adhesion = CURRENT_DATE(), administrateur = $est_admin WHERE id_club = $id_club AND id_utilisateur = $id_utilisateur;");
					if ($req_a_a->execute()) {
						echo "<script>alert('Adhérent ajouté avec succès !');</script>";
					}
					} else {
						echo "<script>alert('L'adhérent n'a pas pu être ajouté.');</script>";
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
						<label for='Nom'>Nom : </label> <input id='Nom' name='nom_e' type='text' size='30'
							placeholder='De Courbertin' required='required' />
					</div>
					<div class='champ'>
						<label for='Prénom'>Prénom : </label><input id='Prénom' name='prenom_e' type='text' size='30'
							placeholder='Pierre' required='required' />
					</div>

					<div class='bouton'><input type='submit' name='envoyer' value='Rechercher' /> </div>
				</form>

				<div class = 'champ'>Résultats</div>
				
				<?php
				if (isset($_POST['nom_e']) && isset($_POST['prenom_e'])) {
					$nom_e_a = $_POST['nom_e'];
					$prenom_e_a = $_POST['prenom_e'];

					$req_e = $bdd->prepare("SELECT utilisateur.id_utilisateur,nom,prenom,date_de_naissance FROM utilisateur INNER JOIN inscription ON utilisateur.id_utilisateur = inscription.id_utilisateur WHERE inscription.id_club = $id_club AND (utilisateur.nom LIKE '%$nom_e_a%' OR utilisateur.prenom LIKE '%$prenom_e_a%') AND inscription.est_adherent = 1 ;");
					$req_e->execute();
					$options = "<option value=''>Choissisez un adhérent</option>";
					while ($row = $req_e->fetch()) {
						$options .= "<option value='" . $row['id_utilisateur']."'>" . $row['nom']. ' ' .$row['prenom']. ' né(e) le '. $row['date_de_naissance']."</option>";
					}
					if ($req_e->rowCount() == 0) {
						$options = "<option value=''>Aucun adhérent trouvé</option>"; 
					}
				} else {
					$options = "<option value=''>Choissisez un adhérent</option>";
				}
				?>

				<form method='post' action='profil_admin.php'>

					<label for="a_adherent"></label>
					<select id="a_adherent" name="a_adherent">
						<?php echo $options; ?>
					</select>
					<div class='bouton'><input type='submit' name ='supprimer_adherent' value='Exclure un adhérent' /></div>

				</form>

				<?php
				if (isset($_POST['supprimer_adherent'])) {
					$id_utilisateur = $_POST['a_adherent'];
					$req_exclure = $bdd->prepare("UPDATE inscription SET est_adherent = 0, date_adhesion = '0000-00-00', administrateur = 0 WHERE id_club = $id_club AND id_utilisateur = $id_utilisateur;");
					$req_id_inscription = $bdd->prepare("SELECT id_inscription FROM inscription WHERE id_club = $id_club AND id_utilisateur = $id_utilisateur;");
					$req_id_inscription->execute();
					$id_inscription = $req_id_inscription->fetchColumn();
					$req_a_exclure = $bdd->prepare("INSERT INTO exclusion VALUES ($id_inscription, CURRENT_DATE())");
					if ($req_exclure->execute() && $req_a_exclure->execute()) {
						echo "<script>alert('Adhérent exclu avec succès !');</script>";
					} else {
						echo "<script>alert('L'adhérent n'a pas pu être exclu.');</script>";
					}
				}
				?>
        
        </div>
	</div>
</div>

<div class = 'contenant'>
	<!--Ajouter une installation existante-->
	<div class = 'section'>
			<div id = 'titre_2_3'>
			<p>Ajouter une installation existante</p>
			</div>

				<div id = 'action_3'>
						<form action='profil_admin.php' method='post'>
							<?php
							$req_t_d = $bdd->prepare("SELECT type_discipline FROM disciplines;");
							$req_t_d->execute();
							$options = "<option value=''>Choissisez un type de discipline</option>";
							while ($row = $req_t_d->fetch()) {
								$options .= "<option value='" . $row['type_discipline']."'>" . $row['type_discipline']."</option>";
							}
							?>

							<div class = 'champ'>
								<label for='type discipline'> Type de discipline : </label>
								<select id='type discipline' name='type_discipline'>
									<?php echo $options; ?>
								</select>
							</div>

							<div class ='bouton'><input type="submit" name="filtre" value="Filtrer"></div>
						</form>	
							
						<?php
						if (isset($_POST['filtre'])) {
                            $discipline = $_POST['type_discipline'];
                                $req_filtre = $bdd->prepare("SELECT id_installation,nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline WHERE type_discipline = '$discipline'");
                                $req_filtre->execute();

                                $options = "<option value=''>Sélectionnez une installation</option>";
                                while ($row = $req_filtre->fetch()) {
                                    $options .= "<option value='" . $row['id_installation'] . "'>" . $row['nom_installation'] . "</option>";
                                }
                            } else {
                                $discipline = "";
                                $options = "<option value=''>Sélectionnez une installation</option>";
                            }
                    	?>
						<form action='profil_admin.php' method='post'>
                            <label for="installation">Installation :</label>
								<select id="installation" name="installation">
									<?php echo $options; ?>
								</select>
								
							<div class='bouton'><input type='submit' name='choix_installation' value='Ajouter l&#39installation'/></div>
						</form>

						<?php
						if (isset($_POST['choix_installation'])) {
							$id_installation = $_POST['installation'];
							$req_a_i = $bdd->prepare("INSERT INTO presence VALUES ($id_club , $id_installation);");
							if ($req_a_i->execute()) {
								echo "<script>alert('Installation ajoutée avec succès !');</script>";
							} else {
								echo "<script>alert('L'installation n'a pas pu être ajoutée.');</script>";
							}
							
						}
						?>

				</div>
	</div>

	<!--Créer et ajouter une installation -->
	<div class = 'section'>
			<div id = 'titre_2_8'>
						<p>Créer et ajouter une installation </p>
			</div>

				<div id = 'action_8'>
							<form action='profil_admin.php' method='post'>
							<div class='champ'>
								<label for='Nom'>Nom de l'installation : </label> <input id='Nom' name='nom_i_c' type='text' size='50'
									placeholder='Sautoir de triple saut/saut en longueur' required='required' />
							</div>

							<?php
							$req_t_d_6 = $bdd->prepare("SELECT type_discipline FROM disciplines;");
							$req_t_d_6->execute();
							$options = "<option value=''>Choissisez un type de discipline</option>";
							while ($row = $req_t_d_6->fetch()) {
								$options .= "<option value='" . $row['type_discipline'] . "'>" . $row['type_discipline'] . "</option>";
							}
							?>

							<div class='champ'>
								<label for='type discipline'> Type de discipline : </label>
								<select id='type discipline' name='type_discipline'>
									<?php echo $options; ?>
								</select>
							</div>

							<div class='champ'>
								<div class='champ'><label for='Emplacement'> Emplacement : </label></div>
								<div class='champ'><input id='bouton 2' name='emplacement' type='radio' /><label for='Emplacement'> Hors-Piste </label></div>
							</div>

							<div class='bouton'><input type='submit' name='créer' value='Créer l&#39;installation'/></div>
							</form>

							<?php
							if  (isset($_POST['créer'])) {
								$nom_c_i = $_POST['nom_i_c'];
								$type_discipline = $_POST['type_discipline'];
								$req_i_t2 = $bdd->prepare("SELECT id_discipline FROM disciplines WHERE type_discipline = '$type_discipline';");
								$req_i_t2->execute();
								$id_discipline = $req_i_t2->fetchColumn();
								$hors_piste = isset($_POST['emplacement']) ? 1 : 0;
								$req_c_i = $bdd->prepare("INSERT INTO installations(nom_installation, id_discipline, hors_piste) VALUES (?, ?, ?)");
    							$req_c_i->execute([$nom_c_i, $id_discipline, $hors_piste]);
								$id_installation_c = $bdd->lastInsertId();
								$req_a_i_2 = $bdd->prepare("INSERT INTO presence VALUES ($id_club , $id_installation_c);");
								if ($req_c_i && $req_a_i_2->execute()) {
									echo "<script>alert('Installation crée et ajoutée avec succès !');</script>";
								} else {
									echo "<script>alert('L\'installation n\'a pas pu être crée.');</script>";
								}
							}
							?>		
				</div>

			
	</div>

	<!--Modifier les informations d'une installation-->
	<div class = 'section'>
			<div id = 'titre_2_4'>
			<p>Modifier les informations d'une installation</p>
			</div>
			<div id='action_4'>
				<div class='champ'>Choisir une installation :</div>
				<form action='profil_admin.php' method='post'>
					<?php
					$req_c_i = $bdd->prepare("SELECT installations.id_installation, nom_installation FROM installations INNER JOIN presence ON installations.id_installation = presence.id_installation WHERE presence.id_club = $id_club;");
					$req_c_i->execute();
					$options = "<option value=''>Choissisez une installation</option>";
					while ($row = $req_c_i->fetch()) {
						$options .= "<option value='" . $row['id_installation'] . "'>" . $row['nom_installation'] . "</option>";
					}
					?>

					<div class='champ'>
						<label for='installation'> Installation : </label>
						<select id='installation' name='installation'>
							<?php echo $options; ?>
						</select>
					</div>

					<div class='bouton'><input type="submit" name="choix" value="Choisir"></div>
					
				</form>

				<?php
				if (isset($_POST['choix'])) {
					var_dump($_POST['installation']);
					$installation = $_POST['installation'];
				} else {
					$installation = null; 
				}
				?>

				<div class='champ'>Modifier :</div>

				<form action='profil_admin.php' method='post'>
				<?php
				//pour garder la valeur du premier formulaire
				echo "<input type='hidden' name='id_installation' value='$installation' />";
				?>
				<div class='champ'>
					<label for='Nom'>Nom de l'installation : </label> <input id='Nom' name='nom_i' type='text' size='50'
						placeholder='Sautoir de triple saut/saut en longueur' required='required' />
				</div>

				<?php
				$req_t_d_2 = $bdd->prepare("SELECT type_discipline FROM disciplines;");
				$req_t_d_2->execute();
				$options = "<option value=''>Choissisez un type de discipline</option>";
				while ($row = $req_t_d_2->fetch()) {
					$options .= "<option value='" . $row['type_discipline'] . "'>" . $row['type_discipline'] . "</option>";
				}
				?>

				<div class='champ'>
					<label for='type discipline'> Type de discipline : </label>
					<select id='type discipline' name='type_discipline'>
						<?php echo $options; ?>
					</select>
				</div>

				<div class='champ'>
					<div class='champ'><label for='Emplacement'> Emplacement : </label></div>
					<div class='champ'><input id='bouton 2' name='emplacement' type='radio' /><label for='Emplacement'> Hors-Piste </label></div>
				</div>

				<div class='bouton'><input type='submit' name='modifier' value='Modifier les informations de l&#39;installation'/></div>
				</form>

				<?php
				if  (isset($_POST['modifier'])) {
					$id_installation = $_POST['id_installation'];
					$nom_i = $_POST['nom_i'];
					$type_discipline = $_POST['type_discipline'];
					$req_i_t = $bdd->prepare("SELECT id_discipline FROM disciplines WHERE type_discipline = '$type_discipline';");
					$req_i_t->execute();
					$id_discipline = $req_i_t->fetchColumn();
					$hors_piste = isset($_POST['emplacement']) ? 1 : 0;
					$req_m_i = $bdd->prepare("UPDATE installations SET nom_installation = '$nom_i', id_discipline = $id_discipline, hors_piste = $hors_piste WHERE id_installation = $id_installation;");
					if ($req_m_i->execute()) {
						echo "<script>alert('Installation modifiée avec succès !');</script>";
					} else {
						echo "<script>alert('L\'installation n\'a pas pu être modifiée.');</script>";
					}
				}

				?>

								</div>
					</div>

	<!--Bloquer une ou plusieurs installations-->
	<div class = 'section'>
				<div id = 'titre_2_5'>
				<p>Bloquer une ou plusieurs installations</p>
				</div>

				<div id = 'action_5'>
					<div class='champ'>Choisir une installation :</div>
					<form action='profil_admin.php' method='post'>
						<!--Sélectionner une discipline-->
						<?php	
						$req_t_d_4 = $bdd->prepare("SELECT type_discipline FROM disciplines;");
						$req_t_d_4->execute();
						$options = "<option value=''>Choissisez un type de discipline</option>";
						while ($row = $req_t_d_4->fetch()) {
							$options .= "<option value='" . $row['type_discipline']."'>" . $row['type_discipline']."</option>";
						}
						?>

						<div class = 'champ'>
							<label for='type discipline'> Type de discipline : </label>
							<select id='type discipline' name='type_discipline'>
								<?php echo $options; ?>
							</select>
						</div>

						<div class ='bouton'><input type="submit" name="filtre" value="Filtrer"></div>
						</form>	
							
						<?php
						if (isset($_POST['filtre'])) {
                            $discipline = $_POST['type_discipline'];
                                $req_filtre_b = $bdd->prepare("SELECT installations.id_installation,nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline INNER JOIN presence ON installations.id_installation = presence.id_installation WHERE type_discipline = '$discipline' AND presence.id_club = $id_club;");
                                $req_filtre_b->execute();

                                $options = "<option value=''>Sélectionnez une installation</option>";
                                while ($row = $req_filtre_b->fetch()) {
                                    $options .= "<option value='" . $row['id_installation'] . "'>" . $row['nom_installation'] . "</option>";
                                }
                            } else {
                                $discipline = "";
                                $options = "<option value=''>Sélectionnez une installation</option>";
                            }
                    	?>

						<?php
						$req_h_d_f = $bdd->prepare("SELECT heure_ouverture, heure_fermeture FROM clubs WHERE id_club = $id_club;");
						$req_h_d_f->execute();
						$row = $req_h_d_f->fetch();
						$heure_debut = $row['heure_ouverture'];
						$heure_fin = $row['heure_fermeture'];
						?>

						<!--Sélectionner une installation et pour combien de temps la bloquer-->
						<form action='profil_admin.php' method='post'>
							<div class='champ'>
                            <label for="installation">Installation :</label>
								<select id="installation" name="installation">
									<?php echo $options; ?>
								</select>
							</div>
							
							<div class='champ'>
								<label for="date_debut_b">Date de début :</label>
								<input type="date" id="date_debut_b" name="date_debut_b" required>
							</div>

							<div class='champ'>
							<label for="heure_debut_b">Heure de fin :</label>
							<select id="heure_debut_b" name="heure_debut_b" required>
								<?php
								$heure_debut_f1 = strtotime($heure_debut);
								$heure_fin_f1 = strtotime($heure_fin) - 3600; 
								while ($heure_debut_f1 <= $heure_fin_f1) {
									for ($minute_f1 = 0; $minute_f1 < 60; $minute_f1 += 15) {
										echo "<option value='" . date('H:i', $heure_debut_f1 + $minute_f1* 60) . "'>" . date('H:i', $heure_debut_f1 + $minute_f1 * 60) . "</option>";
									}
									$heure_debut_f1 += 900;
								}
								?>
							</select>
							</div>
							
							<div class='champ'>
							<label for="date_fin_b">Date de fin :</label>
							<input type="date" id="date_fin_b" name="date_fin_b" required>
							</div>
							
							<div class='champ'>
							<label for="heure_fin_b">Heure de fin :</label>
							<select id="heure_fin_b" name="heure_fin_b" required>
								<?php
								$heure_debut_f2 = strtotime($heure_debut) +900;
								$heure_fin_f2 = strtotime($heure_fin) - 3600; 
								while ($heure_debut_f2 <= $heure_fin_f2 + 900) {
									for ($minute_f2 = 0; $minute_f2 < 60; $minute_f2 += 15) {
										echo "<option value='" . date('H:i', $heure_debut_f2 + $minute_f2 * 60) . "'>" . date('H:i', $heure_debut_f2 + $minute_f2 * 60) . "</option>";
									}
									$heure_debut_f2 += 900;
								}
								?>
							</select>
							</div>
							<div class='bouton'><input type="submit" name="choix_1" value="Choisir"></div>
								
						</form>
						
					<?php
					if (isset($_POST['choix_1']) ) {
						$id_installation_b = $_POST['installation'];
						$date_debut_b = $_POST['date_debut_b'];
						$heure_debut_b = $_POST['heure_debut_b'];
						$date_fin_b = $_POST['date_fin_b'];
						$heure_fin_b = $_POST['heure_fin_b'];
						$req_b_i = $bdd->prepare("INSERT INTO reservation VALUES ($id_installation_b,$id_utilisateur, $id_club, '$date_debut_b', '$heure_debut_b', '$date_fin_b', '$heure_fin_b',1);");
						if ($req_b_i->execute()) {
							echo "<script>alert('Installation bloquée avec succès !');</script>";
						} else {
							echo "<script>alert('L'installation n'a pas pu être bloquée.');</script>";
							}
					} 
					?>
							
				</div>
	</div>

	<!--Supprimer une installation-->
	<div class = 'section'>
				<div id = 'titre_2_6'>
				<p>Supprimer une installation</p>
				</div>

				<div id = 'action_6'>
					<form action='profil_admin.php' method='post'>
						<?php	
						$req_t_d_4 = $bdd->prepare("SELECT type_discipline FROM disciplines;");
						$req_t_d_4->execute();
						$options = "<option value=''>Choissisez un type de discipline</option>";
						while ($row = $req_t_d_4->fetch()) {
							$options .= "<option value='" . $row['type_discipline']."'>" . $row['type_discipline']."</option>";
						}
						?>

						<div class = 'champ'>
							<label for='type discipline'> Type de discipline : </label>
							<select id='type discipline' name='type_discipline'>
								<?php echo $options; ?>
							</select>
						</div>

						<div class ='bouton'><input type="submit" name="filtre_supprimer" value="Filtrer"></div>
						</form>	
							
						<?php
						if (isset($_POST['filtre_supprimer'])) {
                            $discipline = $_POST['type_discipline'];
                                $req_filtre = $bdd->prepare("SELECT installations.id_installation,nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline INNER JOIN presence ON installations.id_installation = presence.id_installation WHERE type_discipline = '$discipline' AND presence.id_club = $id_club;");
                                $req_filtre->execute();

                                $options = "<option value=''>Sélectionnez une installation</option>";
                                while ($row = $req_filtre->fetch()) {
                                    $options .= "<option value='" . $row['id_installation'] . "'>" . $row['nom_installation'] . "</option>";
                                }
                            } else {
                                $discipline = "";
                                $options = "<option value=''>Sélectionnez une installation</option>";
                            }
                    	?>
						<form action='profil_admin.php' method='post'>
                            <label for="installation">Installation :</label>
								<select id="installation" name="installation">
									<?php echo $options; ?>
								</select>

						<div class='bouton'><input type="submit" name="choix_suppr" value="Supprimer l&#39installation"></div>
					</form>	
							
					<?php
					if  (isset($_POST['choix_suppr'])) {
						$id_installation = $_POST['installation'];
						$req_s_i = $bdd->prepare("DELETE FROM presence WHERE id_club = $id_club AND id_installation = $id_installation;");
						if ($req_s_i->execute()) {
							echo "<script>alert('Installation supprimée avec succès !');</script>";
						} else {
							echo "<script>alert('L'installation n'a pas pu être supprimée.');</script>";
						}
					}
					?>

				</div>
	</div>
</div>

<div class = 'contenant'>
	<!--Consulter des réservations-->

	<div class = 'section'>

		<div id = 'titre_2_9'>
			<p>Consulter des réservations</p>
		</div>

		<div id = 'action_9'>
			<div class='champ'>Choisir une installation :</div>
				<form action='profil_admin.php' method='post'>
				<?php	
					$req_t_d_8 = $bdd->prepare("SELECT type_discipline FROM disciplines;");
					$req_t_d_8->execute();
					$options = "<option value=''>Choissisez un type de discipline</option>";
					while ($row = $req_t_d_8->fetch()) {
						$options .= "<option value='" . $row['type_discipline']."'>" . $row['type_discipline']."</option>";
					}
					?>

					<div class = 'champ'>
						<label for='type discipline'> Type de discipline : </label>
						<select id='type discipline' name='type_discipline'>
							<?php echo $options; ?>
						</select>
					</div>

					<div class ='bouton'><input type="submit" name="filtre_t" value="Filtrer"></div>
				</form>    

				<?php
				if (isset($_POST['filtre_t'])) {
					$discipline = $_POST['type_discipline'];
					$req_filtre_t = $bdd->prepare("SELECT installations.id_installation,nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline INNER JOIN presence ON installations.id_installation = presence.id_installation WHERE type_discipline = :discipline AND presence.id_club = :id_club;");
					$req_filtre_t->bindParam(':discipline', $discipline);
					$req_filtre_t->bindParam(':id_club', $id_club);
					$req_filtre_t->execute();

					$options = "<option value=''>Sélectionnez une installation</option>";
					while ($row = $req_filtre_t->fetch()) {
						$options .= "<option value='" . $row['id_installation'] . "'>" . $row['nom_installation'] . "</option>";
					}
				} else {
					$discipline = "";
					$options = "<option value=''>Sélectionnez une installation</option>";
				}
				?>
				<form action='profil_admin.php' method='post'>
					<div class = 'champ'>
					<label for="installations">Installation :</label>
					<select id="installations" name="installations">
						<?php echo $options; ?>
					</select>
					</div>
					<div class='bouton'><input type="submit" name="choix_8" value="Choisir"></div>
				</form>

				<!--Affichage des réservations-->
				<form action="profil_admin.php" method="get">
					<?php
						if (isset($_POST['choix_8'])) {
							$id_installation = $_POST['installations'];
							$sql_installation = "SELECT nom_installation FROM installations WHERE id_installation = :installation";
							$stmt_installation = $bdd->prepare($sql_installation);
							$stmt_installation->bindParam(':installation', $id_installation);
							$stmt_installation->execute();
							$installation_name = $stmt_installation->fetchColumn();
							$_SESSION['id_installation'] = $id_installation;
							$_SESSION['installation_name'] = $installation_name;
							}
					?>
					<div class = 'champ'>
					<label for="week">Choisir une semaine :</label>
					<input type="week" name="week" id="week" value="<?php echo date('Y-\WW'); ?>" />
					</div>
					<div class = 'bouton'> <input type="submit" name="valider" value="Valider" /></div>
				</form>

				<?php
				if (isset($_GET['valider'])) {
					$annee = date('Y');
					$semaine_choisie = $_GET['week'] ?? date('oW');

					if (preg_match('/^(\d{4})(\d{2})$/', $semaine_choisie, $matches)) {
						$annee_semaine = $matches[1];
						$num_semaine = $matches[2];
					} else {
						$annee_semaine = $annee;
						$num_semaine = substr($semaine_choisie, -2);
						}

					$j_debut_semaine = date('Y-m-d', strtotime($annee_semaine . 'W' . $num_semaine . '1'));
					$j_fin_semaine = date('Y-m-d', strtotime($annee_semaine . 'W' . $num_semaine . '7'));

					echo "<div class = 'champ'>Réservations de l'installation : " . $_SESSION['installation_name'] . "</div>";
					echo "Semaine du " . date('d/m', strtotime($j_debut_semaine)) . " au " . date('d/m', strtotime($j_fin_semaine));
				
					
					$creneaux_horaires = [];
					$heure_deb = strtotime($heure_debut); 
					$heure_fi = strtotime($heure_fin); 
					$intervalle = 15 * 60; 
					$heure_actuelle = $heure_deb;
					while ($heure_actuelle <= $heure_fi) {
						$creneaux_horaires[] = date('H:i', $heure_actuelle);
						$heure_actuelle += $intervalle;
					}

					$id_installation = $_SESSION['id_installation'];
					$req_res  = $bdd->prepare("SELECT date_debut_reservation, heure_debut_reservation, date_fin_reservation, heure_fin_reservation
							FROM reservation
							WHERE id_installation = $id_installation
						AND date_debut_reservation BETWEEN '$j_debut_semaine' AND '$j_fin_semaine'
						AND id_club = $id_club;");
					$req_res->execute();
					$reservations = $req_res->fetchAll(PDO::FETCH_ASSOC);

					$tableau = [];
					$date_actuelle = $j_debut_semaine;
					while ($date_actuelle <= $j_fin_semaine) {
						$tableau[$date_actuelle] = array_fill_keys($creneaux_horaires, 'o');
						$date_actuelle = date('Y-m-d', strtotime($date_actuelle . ' +1 day'));
					}

					foreach ($reservations as $reservation) {
						$debut_horaire = $reservation['date_debut_reservation'] . ' ' . $reservation['heure_debut_reservation'];
						$fin_horaire = $reservation['date_fin_reservation'] . ' ' . $reservation['heure_fin_reservation'];
						$debut_r = strtotime($debut_horaire);
						$fin_r = strtotime($fin_horaire);
						$heure_actuelle = $debut_r;
						while ($heure_actuelle < $fin_r) {
							$date_actuelle = date('Y-m-d', $heure_actuelle);
							$creneau_actuel = date('H:i', $heure_actuelle);
							$tableau[$date_actuelle][$creneau_actuel] = 'x';
							$heure_actuelle += $intervalle;
						}
					}

					echo "<table>";
					echo "<tr><th>Heures</th>";
					foreach ($tableau as $date => $creneau) {
						$jours_semaine = date('N', strtotime($date));
						$noms_jours_semaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'][$jours_semaine - 1];
						echo "<th>$noms_jours_semaine " . date('d/m', strtotime($date)) . "</th>";
					}
					echo "</tr>";
					foreach ($creneaux_horaires as $temps) {
						echo "<tr><td>$temps</td>";
						foreach ($tableau as $date => $creneau) {
							echo "<td>{$creneau[$temps]}</td>";
						}
						echo "</tr>";
						}
						echo "</table>";
				
				}
				?>


		</div>
	</div>

	<!--Annuler une réservation-->
	<div class = 'section'>
	
	
		<div id = 'titre_2_7'>
		<p>Annuler une réservation</p>
		</div>

		<div id = 'action_7'>
			<div class='champ'>Choisir une installation :</div>
				<form action='profil_admin.php' method='post'>
					<?php	

					$req_t_d_4 = $bdd->prepare("SELECT type_discipline FROM disciplines;");
					$req_t_d_4->execute();
					$options = "<option value=''>Choissisez un type de discipline</option>";
					while ($row = $req_t_d_4->fetch()) {
						$options .= "<option value='" . $row['type_discipline']."'>" . $row['type_discipline']."</option>";
					}
					?>

					<div class = 'champ'>
						<label for='type discipline'> Type de discipline : </label>
						<select id='type discipline' name='type_discipline'>
							<?php echo $options; ?>
						</select>
					</div>

					<div class ='bouton'><input type="submit" name="filtre_a" value="Filtrer"></div>
				</form>    

				<?php
				if (isset($_POST['filtre_a'])) {
					$discipline = $_POST['type_discipline'];
					$req_filtre_a = $bdd->prepare("SELECT installations.id_installation,nom_installation FROM installations JOIN disciplines ON installations.id_discipline = disciplines.id_discipline INNER JOIN presence ON installations.id_installation = presence.id_installation WHERE type_discipline = :discipline AND presence.id_club = :id_club;");
					$req_filtre_a->bindParam(':discipline', $discipline);
					$req_filtre_a->bindParam(':id_club', $id_club);
					$req_filtre_a->execute();

					$options = "<option value=''>Sélectionnez une installation</option>";
					while ($row = $req_filtre_a->fetch()) {
						$options .= "<option value='" . $row['id_installation'] . "'>" . $row['nom_installation'] . "</option>";
					}
				} else {
					$discipline = "";
					$options = "<option value=''>Sélectionnez une installation</option>";
				}
				?>
				<form action='profil_admin.php' method='post'>
					<div class = 'champ'>
					<label for="installation">Installation :</label>
					<select id="installation" name="installation">
						<?php echo $options; ?>
					</select>
					</div>
					<div class='bouton'><input type="submit" name="choix_3" value="Choisir"></div>
				</form>


				<form action = 'profil_admin.php' method = 'post'>
					<div class='champ'>
						<label for="date_debut_a">Date de début :</label>
						<input type="date" id="date_debut_a" name="date_debut_a" required>
					</div>

					<div class='champ'>
						<label for="heure_debut_a">Heure de début :</label>
						<select id="heure_debut_a" name="heure_debut_a" required>
					</div>
						<?php
						$heure_debut_f3 = strtotime($heure_debut);
						$heure_fin_f3 = strtotime($heure_fin) - 3600; 
						while ($heure_debut_f3 <= $heure_fin_f3) {
							for ($minute_f3 = 0; $minute_f3 < 60; $minute_f3 += 15) {
								echo "<option value='" . date('H:i', $heure_debut_f3 + $minute_f3 * 60) . "'>" . date('H:i', $heure_debut_f3 + $minute_f3 * 60) . "</option>";
							}
							$heure_debut_f3 += 900;
					
						}
						?>
					</select>
					
					<div class='champ'>
						<label for="date_fin_a">Date de fin :</label>
						<input type="date" id="date_fin_a" name="date_fin_a" required>
					</div>
					
					<div class='champ'>
					<label for="heure_fin_a">Heure de fin :</label>
					<select id="heure_fin_a" name="heure_fin_a" required>
						<?php
						$heure_debut_f4 = strtotime($heure_debut) +900;
						$heure_fin_f4 = strtotime($heure_fin) - 3600; 
						while ($heure_debut_f4 <= $heure_fin_f4 + 900) {
							for ($minute_f4 = 0; $minute_f4 < 60; $minute_f4 += 15) {
								echo "<option value='" . date('H:i', $heure_debut_f4 + $minute_f4 * 60) . "'>" . date('H:i', $heure_debut_f4 + $minute_f4 * 60) . "</option>";
							}
							$heure_debut_f4 += 900;
						}
						?>
					</select>
					</div>

				<div class = bouton><input type="submit" name="annuler_reservation" value="Annuler la réservation"> </div>
				</div>

				</form>
				<?php
				if (isset($_POST['annuler_reservation'])) {
					$id_installation = $_SESSION['id_installation'];
					$date_debut = $_POST['date_debut_a'];
					$heure_debut = date('H:i:s', strtotime($_POST['heure_debut_a'])) ;
					$date_fin = $_POST['date_fin_a'];
					$heure_fin = date('H:i:s', strtotime($_POST['heure_fin_a'])) ;
					echo $id_installation;
					$sql_suppr_res = $bdd->prepare("DELETE FROM reservation WHERE id_club = $id_club AND id_installation = $id_installation AND date_debut_reservation = '$date_debut' AND heure_debut_reservation = '$heure_debut' AND date_fin_reservation = '$date_fin' AND heure_fin_reservation =  '$heure_fin';");
					echo $sql_suppr_res->queryString;

					/*if ($sql_suppr_res->execute()) {
						echo "<script>alert('Réservation annulée avec succès !');</script>";
					} else {
						echo "<script>alert('La réservation n'a pas pu être annulée.');</script>";
						}*/
				}
				?>
		</div>
	</div>

<div class = 'contenant'>
	<!--Statistiques-->
	<div class = 'section'>
		<a href ='page_statistiques.php'><div id = 'titre_2_7'>Statistiques</div></a>
	</div>
</div>

<script src="admin.js"></script>

</body>
</html>
