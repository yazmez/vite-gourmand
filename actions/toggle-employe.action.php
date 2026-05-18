<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$id]);
$employe = $stmt->fetch();

if(!$employe) {
    header('Location: /vite-gourmand/espaces/admin/dashboard.php?error=Employé introuvable');
    exit;
}

$newPays = $employe['pays'] === 'inactif' ? 'France' : 'inactif';

$stmt = $pdo->prepare("UPDATE utilisateur SET pays = ? WHERE utilisateur_id = ?");
$stmt->execute([$newPays, $id]);

header('Location: /vite-gourmand/espaces/admin/dashboard.php?success=Statut employé mis à jour');
exit;
?>