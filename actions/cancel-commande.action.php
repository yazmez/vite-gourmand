<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$numero_commande = $_GET['id'];
$user_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("
    SELECT * FROM commande 
    WHERE numero_commande = ? 
    AND utilisateur_id = ? 
    AND statut = 'en attente'
");
$stmt->execute([$numero_commande, $user_id]);
$commande = $stmt->fetch();

if(!$commande) {
    header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php?error=Annulation impossible');
    exit;
}

$stmt = $pdo->prepare("UPDATE commande SET statut = 'annulé' WHERE numero_commande = ?");
$stmt->execute([$numero_commande]);

$stmt = $pdo->prepare("UPDATE menu SET quantite_restante = quantite_restante + 1 WHERE menu_id = ?");
$stmt->execute([$commande['menu_id']]);

header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php?success=Commande annulée avec succès');
exit;
?>