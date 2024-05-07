<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width; initial-scale=1.0'/>
	<link rel = 'stylesheet' href = 'style.css'/>
	<title>Inscription</title>
</head>
<body>
<div class ='contenant'>
	<h1>S'inscrire</h1>
	<p>Vous avez déjà un compte ? <a href ='http://localhost/projet_if3a/connexion.php' title="Cliquez ici pour vous connectez">Se connecter</a></p>
	<div class = 'formulaire'>
		<form method = 'post' action = 'action_inscription.php'>
			<div class = 'champ entree'>
				<label for = 'Nom'>Nom : </label> <input id='Nom' name='nom' type='text' size = '30' placeholder='De Courbertin' required='required' /> 
			</div>
			<div class = 'champ entree'>
				<label for = 'Prénom'>Prénom : </label><input id='Prénom' name='prenom' type='text' size = '30' placeholder='Pierre'required='required'/>  
			</div>
			<div class = 'champ entree'>
				<label for = 'Date de naissance'>Date de naissance : </label><input id='Date naissance' name='naissance' type='date' required='required'/>  
			</div>
			<div class = 'champ entree'>
			<label for = 'E-mail'>E-mail : </label><input id='E-mail' name='e-mail' type='email' size = '30'placeholder='pierre.decoubertin@utbm.fr' required='required'/>  
			</div>
			<div class = 'champ entree'>
				<label for = 'Mot de passe'>Mot de passe : </label><input id='Mot de passe' name='mot de passe' type='text' size = '30'required='required'/>  
			</div>
	<p>Clubs :</p>
	<?php
	$bdd = new PDO("mysql:host=localhost;dbname=donnees;charset=utf8", "root", "");
	$req = $bdd->prepare("SELECT nom_club FROM clubs;");
    $req->execute();
	while($data = $req->fetch())
    {
		?>
	<div class = 'case'>
		<input name='club ' type='checkbox'/> <label for = 'Club'><?php echo $data["nom_club"]; ?></label>  
	</div>
	<?php
	}
    ?>
	 <input type='submit' name='envoyer' value='S&#39inscrire'/>
	</form>
</div>
</body>
</html>