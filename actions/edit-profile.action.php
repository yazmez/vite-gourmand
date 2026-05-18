<?php
session_start();
require_once '../config/db.php';
if(!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}
$user_id = $_SESSION['user']['id'];
$nom = trim($_POST['nom']);
$prenom = trim($_POST['prenom']);
$telephone = trim($_POST['telephone']);
$ville = trim($_POST['ville']);
$pays = trim($_POST['pays']);
$adresse_postale = trim($_POST['adresse_postale']);
$password = $_POST['password'];
if(!empty($password)) {
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/';
    if(!preg_match($pattern, $password)) {
        header('Location: /vite-gourmand/pages/edit-profile.php?error=Mot de passe invalide');
        exit;
    }
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("
        UPDATE utilisateur 
        SET nom=?, prenom=?, telephone=?, ville=?, pays=?, adresse_postale=?, password=?
        WHERE utilisateur_id=?
    ");
    $stmt->execute([$nom, $prenom, $telephone, $ville, $pays, $adresse_postale, $hashed, $user_id]);
} else {
    $stmt = $pdo->prepare("
        UPDATE utilisateur 
        SET nom=?, prenom=?, telephone=?, ville=?, pays=?, adresse_postale=?
        WHERE utilisateur_id=?
    ");
    $stmt->execute([$nom, $prenom, $telephone, $ville, $pays, $adresse_postale, $user_id]);
}
$_SESSION['user']['nom'] = $nom;
$_SESSION['user']['prenom'] = $prenom;
header('Location: /vite-gourmand/pages/edit-profile.php?success=1');
exit;
?>