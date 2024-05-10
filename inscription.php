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
		<p>Vous avez déjà un compte ? <a href ='connexion.php' title="Cliquez ici pour vous connectez">Se connecter</a></p>
		<div class = 'formulaire'>
			<form method = 'post' action = 'action_inscription.php'>
				<div class = 'champ entree'>
					<label for = 'Nom'>Nom : </label> <input id='nom' name='nom' type='text' size = '30' placeholder='De Courbertin' required='required' /> 
				</div>
				<div class = 'champ entree'>
					<label for = 'Prénom'>Prénom : </label><input id='prenom' name='prenom' type='text' size = '30' placeholder='Pierre'required='required'/>  
				</div>
				<div class = 'champ entree'>
					<label for = 'Date de naissance'>Date de naissance : </label><input id='date-naissance' name='naissance' type='date' required='required'/>  
				</div>
				<div class = 'champ entree'>
					<label for = 'Email'>E-Mail : </label><input id='email' name='email' type='text' size = '30' placeholder='pierre.coubertin@gmail.com'required='required'/>  
				</div>
				<div class = 'champ entree'>
					<label for = 'Mot de passe'>Mot de passe : </label><input id='mdp' name='mot_de_passe' type='password' size = '30'required='required'/>  
				</div>
				<p>Clubs :</p>
				<?php
					$bdd = new PDO("mysql:host=localhost;dbname=donnees;charset=utf8", "root", "");
					$req = $bdd->prepare("SELECT * FROM clubs;");
					$req->execute();
					while($data = $req->fetch()) {
				?>
				<div class = 'case'>
					<input name="club[]" type="checkbox" value="<?php echo $data["id_club"]?>"/><label for = 'Club'><?php echo $data["nom_club"]; ?></label>
				</div>
			<?php
				}
				?>
				<input type='submit' name='envoyer' value='inscrire'/>
			</form>
		</div>
	</body>
</html>