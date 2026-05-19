<?php
session_start();
require_once '../config/db.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/register.php');
    exit;
}

$nom = trim($_POST['nom']);
$prenom = trim($_POST['prenom']);
$email = trim($_POST['email']);
$telephone = trim($_POST['telephone']);
$ville = trim($_POST['ville']);
$pays = trim($_POST['pays']);
$adresse_postale = trim($_POST['adresse_postale']);
$password = $_POST['password'];

$pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/';
if(!preg_match($pattern, $password)) {
    header('Location: /vite-gourmand/pages/register.php?error=Mot de passe invalide');
    exit;
}

$stmt = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
if($stmt->fetch()) {
    header('Location: /vite-gourmand/pages/register.php?error=Cet email est déjà utilisé');
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO utilisateur (email, password, nom, prenom, telephone, ville, pays, adresse_postale, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
$stmt->execute([$email, $hashed, $nom, $prenom, $telephone, $ville, $pays, $adresse_postale]);
require_once '../includes/mailer.php';

$welcomeBody = "
<h2>Bienvenue chez Vite & Gourmand !</h2>
<p>Bonjour <strong>" . htmlspecialchars($prenom) . " " . htmlspecialchars($nom) . "</strong>,</p>
<p>Votre compte a bien été créé. Vous pouvez dès maintenant consulter nos menus et passer commande.</p>
<p>À très bientôt,<br>L'équipe Vite & Gourmand</p>
";
sendMail($email, 'Bienvenue chez Vite & Gourmand !', $welcomeBody);
header('Location: /vite-gourmand/pages/login.php?success=Compte créé avec succès');
exit;