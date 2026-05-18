<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$role = $_SESSION['user']['role'];
if($role !== 'employe' && $role !== 'administrateur') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$numero_commande = $_POST['numero_commande'];
$statut = $_POST['statut'];
$motif = trim($_POST['motif'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM commande WHERE numero_commande = ?");
$stmt->execute([$numero_commande]);
$commande = $stmt->fetch();

if(!$commande) {
    header('Location: /vite-gourmand/espaces/employe/dashboard.php?error=Commande introuvable');
    exit;
}

$stmt = $pdo->prepare("UPDATE commande SET statut = ? WHERE numero_commande = ?");
$stmt->execute([$statut, $numero_commande]);

header('Location: /vite-gourmand/espaces/' . $role . '/dashboard.php?success=Commande mise à jour');
exit;
?>