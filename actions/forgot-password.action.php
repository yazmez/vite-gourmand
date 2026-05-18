<?php
session_start();
require_once '../config/db.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/forgot-password.php');
    exit;
}

$email = trim($_POST['email']);

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if($user) {
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
   
    $_SESSION['reset_token'] = $token;
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_expiry'] = $expiry;
}

header('Location: /vite-gourmand/pages/forgot-password.php?success=1');
exit;
?>