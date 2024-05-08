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
	$bdd = new PDO("mysql:host=localhost;dbname=donnees;charset=utf8", "root", "");
	?>

	<div id = 'sous_titre'>
		<div id = 'sous_titre_1'><p>Tableau de bord</p></div><div id = 'sous_titre_2'><p>Tableau de bord</p></div>
	</div>

	<div id = 'titre_1'><p>Statistiques du club</p></div>

	<div class = 'nom_stat'><p>Nombre d'adhérents : </p></div>

	<div class = 'nom_stat'><p>Nombre d’heures réservées sur l’année d'un adhérent :</p></div>
		<div class = 'condition_stat'> </div>
	
	<div class = 'nom_stat'><p>Taux de réservation moyen d'une installation par semaine :</p></div>
		<div class = 'condition_stat'> 

		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'></form>
		<label for='installation'>Choissisez l'installation : </label>
		<select id ='installation' name='installations'></select>

		<?php
		$req = $bdd->prepare("SELECT nom_installation FROM installations INNER JOIN presence ON installations.id_installation = presence.id_installation INNER JOIN club ON presence.id_club = club.id_club WHERE club.id_club = 1;");
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
		<div class = 'bouton'><input type="submit" value="Choisir"></div>					
							
</body>
</html>