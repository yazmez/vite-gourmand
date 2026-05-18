<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}
$commande_id = $_POST['commande_id'];
$note = $_POST['note'];
$description = trim($_POST['description']);
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("
    SELECT * FROM commande 
    WHERE numero_commande = ? 
    AND utilisateur_id = ?
    AND statut = 'terminée'
");
$stmt->execute([$commande_id, $user_id]);
$commande = $stmt->fetch();

if(!$commande) {
    header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php');
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM avis WHERE commande_id = ?");
$stmt->execute([$commande_id]);
if($stmt->fetch()) {
    header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php?error=Vous avez déjà donné votre avis');
    exit;
}
$stmt = $pdo->prepare("
    INSERT INTO avis (note, description, statut, utilisateur_id, commande_id)
    VALUES (?, ?, 'en attente', ?, ?)
");
$stmt->execute([$note, $description, $user_id, $commande_id]);

header('Location: /vite-gourmand/pages/avis.php?commande_id=' . $commande_id . '&success=1');
exit;
?>