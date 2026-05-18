<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM menu_plat WHERE menu_id = ?");
$stmt->execute([$id]);

$stmt = $pdo->prepare("DELETE FROM menu WHERE menu_id = ?");
$stmt->execute([$id]);

header('Location: /vite-gourmand/espaces/admin/dashboard.php?success=Menu supprimé avec succès');
exit;
?>