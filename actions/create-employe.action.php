<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$nom = trim($_POST['nom']);
$prenom = trim($_POST['prenom']);
$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
if($stmt->fetch()) {
    header('Location: /vite-gourmand/espaces/admin/dashboard.php?error=Email déjà utilisé');
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("
    INSERT INTO utilisateur (email, password, nom, prenom, telephone, ville, pays, adresse_postale, role_id)
    VALUES (?, ?, ?, ?, '', 'Bordeaux', 'France', '', 2)
");
$stmt->execute([$email, $hashed, $nom, $prenom]);

header('Location: /vite-gourmand/espaces/admin/dashboard.php?success=Compte employé créé avec succès');
exit;
?>