<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width; initial-scale=1.0'/>
	<link rel = 'stylesheet' href = 'style.css'/>
	<title>Connexion</title>
</head>
<body>
<div class ='contenant'>
	<h1>Se connecter</h1> 
	<p>Vous n'avez pas de compte ? <a href = "inscription.php">S'inscrire</a></p>
	<div class = 'formulaire'>
		<form method = 'post' action = 'action_connexion.php'>
			<div class = 'champ entree'>
				<label for = 'E-mail'>E-mail : </label><input id='E-mail' name='email' size = '30' type='email' placeholder='pierre.decoubertin@utbm.fr' required='required'/>
			</div>
			<div class = 'champ entree'>
				<label for = 'Mot de passe'>Mot de passe : </label><input id='Mot de passe' name='mot_de_passe' size = '30' type='password' required='required'/> 
			</div>
			<input type='submit' name='envoyer' value='Se connecter'/>
		</form>
	</div>
</div>
</body>
</html>


