<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['administrateur', 'employe'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$menu_id = $_POST['menu_id'];
$titre = trim($_POST['titre']);
$prix = $_POST['prix_par_personne'];
$minimum = $_POST['nombre_personne_minimum'];
$description = trim($_POST['description']);
$stock = $_POST['quantite_restante'];
$theme_id = $_POST['theme_id'];
$regime_id = $_POST['regime_id'];
$plats = $_POST['plats'] ?? [];

$stmt = $pdo->prepare("
    UPDATE menu 
    SET titre=?, prix_par_personne=?, nombre_personne_minimum=?, description=?, quantite_restante=?, theme_id=?, regime_id=?
    WHERE menu_id=?
");
$stmt->execute([$titre, $prix, $minimum, $description, $stock, $theme_id, $regime_id, $menu_id]);

$stmt = $pdo->prepare("DELETE FROM menu_plat WHERE menu_id = ?");
$stmt->execute([$menu_id]);

foreach($plats as $plat_id) {
    $stmt = $pdo->prepare("INSERT INTO menu_plat (menu_id, plat_id) VALUES (?, ?)");
    $stmt->execute([$menu_id, $plat_id]);
}

header('Location: /vite-gourmand/espaces/admin/dashboard.php?success=Menu modifié avec succès');
exit;
?>