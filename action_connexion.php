<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width; initial-scale=1.0'/>
	<link rel = 'stylesheet' href = 'style_1.css'/>
	<title>connexion</title>
</head>
<body>


<?php
// Établir une connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');

// Requête pour récupérer les informations de l'utilisateur
$email = $_POST['email'];
$mdp = $_POST['mot_de_passe'];

$query = $bdd->prepare("SELECT id_utilisateur , nom FROM utilisateur WHERE email = ? AND mdp = ?");
$query->execute([$email, $mdp]);
$user = $query->fetch();

// Vérifier si l'utilisateur existe dans la base de données
if ($user) {
    // Démarrer une session
    session_start();

    // Enregistrer les données de l'utilisateur dans les variables de session
    $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
    $_SESSION['nom'] = $user['nom'];

    // Rediriger l'utilisateur vers son profil
    header('Location: profil_adherent.php');
    exit();
} else {
    echo "Identifiants incorrects";
    ?>
    <p>Retenter votre chance, on réfléchit pour son mot de passe et on fait attention aux fautes de frappes !  <a href = "connexion.php"> Se connecter</a></p>

<?php
}

