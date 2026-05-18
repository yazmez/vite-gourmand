<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$role = $_SESSION['user']['role'];
if($role !== 'employe' && $role !== 'administrateur') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$id = $_GET['id'];
$action = $_GET['action'];

if($action === 'valider') {
    $statut = 'validé';
} else {
    $statut = 'refusé';
}

$stmt = $pdo->prepare("UPDATE avis SET statut = ? WHERE avis_id = ?");
$stmt->execute([$statut, $id]);

header('Location: /vite-gourmand/espaces/' . $role . '/dashboard.php?success=Avis traité avec succès');
exit;
?>