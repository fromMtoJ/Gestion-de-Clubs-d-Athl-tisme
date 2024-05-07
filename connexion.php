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
	<p>Vous n'avez pas de compte ? <a href = http://localhost/projet_if3a/inscription.php>S'inscrire</a></p>
	<div class = 'formulaire'>
		<form method = 'post' action = 'accueil.php'>
			<div class = 'champ entree'>
				<label for = 'E-mail'>E-mail : </label><input id='E-mail' name='e-mail' size = '30' type='email' placeholder='pierre.decoubertin@utbm.fr' required='required'/>
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


<?php
// Établir une connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');

// Requête pour récupérer les informations de l'utilisateur (par exemple, à partir d'un formulaire de connexion)
$email = $_POST['e-mail'];
$mdp = $_POST['mot_de_passe'];

$query = $bdd->prepare("SELECT id_utilisateur , nom FROM utilisateur WHERE email = ? AND mot_de_passe = ?");
$query->execute([$email, $mdp]);
$user = $query->fetch();

// Vérifier si l'utilisateur existe dans la base de données
if ($user) {
    // Démarrer une session
    session_start();

    // Enregistrer les données de l'utilisateur dans les variables de session
    $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
    $_SESSION['nom'] = $user['nom'];

    // Rediriger l'utilisateur vers une autre page, par exemple, son profil
    header('Location: profil_adherent.php');
    exit();
} else {
    // Si l'utilisateur n'existe pas, afficher un message d'erreur ou rediriger vers une page de connexion avec un message d'erreur
    echo "Identifiants incorrects";
}
?>