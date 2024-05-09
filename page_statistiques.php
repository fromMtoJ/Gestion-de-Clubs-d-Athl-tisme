<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width' initial-scale=1.0/>
	<link rel = 'stylesheet' href = 'style_2.css'/>
	<title>Statistiques</title>
</head>
<body>

	<!--Base de donnée-->
	<?php
	session_start();
	/*error_reporting(E_ALL);
    ini_set('display_errors', 1);*/
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

	<div id = 'sous_titre'>
		<div id = 'sous_titre_1'><?php echo $row_n_p['prenom']." ". $row_n_p['nom']?></div><div id = 'sous_titre_2'><?php echo $row_nc['nom_club']?></div>
	</div>

	<div id = 'titre_1'><p>Statistiques du club</p></div>

	<div class = 'nom_stat'><p>Nombre d'adhérents : </p></div>

	<div class = 'nom_stat'><p>Nombre d’heures réservées sur l’année d'un adhérent :</p></div>
		<div class = 'condition_stat'> </div>
	
	<div class = 'nom_stat'><p>Taux de réservation moyen d'une installation par semaine :</p></div>
		<div class = 'condition_stat'> 

		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'></form>
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
		</form>
		</div>
		<div id = 'contenant_2'><div class = 'bouton'><input type="submit" value="Choisir"></div></div>					
							
</body>
</html>