<?php
session_start();
require_once '../config/db.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $pdo->prepare("
    SELECT u.*, r.libelle as role_libelle 
    FROM utilisateur u 
    JOIN role r ON u.role_id = r.role_id 
    WHERE u.email = ?
");
$stmt->execute([$email]);
$user = $stmt->fetch();

if(!$user || !password_verify($password, $user['password'])) {
    header('Location: /vite-gourmand/pages/login.php?error=Email ou mot de passe incorrect');
    exit;
}

$_SESSION['user'] = [
    'id' => $user['utilisateur_id'],
    'nom' => $user['nom'],
    'prenom' => $user['prenom'],
    'email' => $user['email'],
    'role' => $user['role_libelle']
];

header('Location: /vite-gourmand/index.php');
exit;
?>