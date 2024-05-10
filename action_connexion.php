<?php
// Établir une connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=donnees', 'root', '');

// Requête pour récupérer les informations de l'utilisateur (par exemple, à partir d'un formulaire de connexion)
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

    // Rediriger l'utilisateur vers une autre page, par exemple, son profil
    header('Location: profil_adherent.php');
    exit();
} else {
    // Si l'utilisateur n'existe pas, afficher un message d'erreur ou rediriger vers une page de connexion avec un message d'erreur
    echo "Identifiants incorrects";
}

