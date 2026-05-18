<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['administrateur', 'employe'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$titre = trim($_POST['titre']);
$prix = $_POST['prix_par_personne'];
$minimum = $_POST['nombre_personne_minimum'];
$description = trim($_POST['description']);
$stock = $_POST['quantite_restante'];
$theme_id = $_POST['theme_id'];
$regime_id = $_POST['regime_id'];
$plats = $_POST['plats'] ?? [];

$stmt = $pdo->prepare("
    INSERT INTO menu (titre, nombre_personne_minimum, prix_par_personne, description, quantite_restante, regime_id, theme_id)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$titre, $minimum, $prix, $description, $stock, $regime_id, $theme_id]);
$menu_id = $pdo->lastInsertId();

foreach($plats as $plat_id) {
    $stmt = $pdo->prepare("INSERT INTO menu_plat (menu_id, plat_id) VALUES (?, ?)");
    $stmt->execute([$menu_id, $plat_id]);
}

header('Location: /vite-gourmand/espaces/admin/dashboard.php?success=Menu créé avec succès');
exit;
?>