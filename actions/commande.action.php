<?php
session_start();
require_once '../config/db.php';
if(!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/index.php');
    exit;
}
$menu_id = $_POST['menu_id'];
$nombre_personne = (int)$_POST['nombre_personne'];
$date_prestation = $_POST['date_prestation'];
$heure_livraison = $_POST['heure_livraison'];
$adresse_livraison = $_POST['adresse_livraison'];
$ville_livraison = strtolower(trim($_POST['ville_livraison']));
$utilisateur_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$menu_id]);
$menu = $stmt->fetch();

if(!$menu) {
    header('Location: /vite-gourmand/pages/menus.php');
    exit;
}
if($nombre_personne < $menu['nombre_personne_minimum']) {
    header('Location: /vite-gourmand/pages/commande.php?menu_id=' . $menu_id . '&error=Nombre de personnes insuffisant');
    exit;
}

$prix_menu = $nombre_personne * $menu['prix_par_personne'];
if($nombre_personne >= $menu['nombre_personne_minimum'] + 5) {
    $prix_menu = $prix_menu * 0.90;
}
$prix_livraison = 0;
if($ville_livraison !== 'bordeaux') {
    $prix_livraison = 5;
}
$numero_commande = 'CMD-' . strtoupper(uniqid());
$stmt = $pdo->prepare("
    INSERT INTO commande 
    (numero_commande, date_commande, date_prestation, heure_livraison, prix_menu, nombre_personne, prix_livraison, statut, pret_materiel, restitution_materiel, utilisateur_id, menu_id)
    VALUES (?, NOW(), ?, ?, ?, ?, ?, 'en attente', false, false, ?, ?)
");
$stmt->execute([
    $numero_commande,
    $date_prestation,
    $heure_livraison,
    $prix_menu,
    $nombre_personne,
    $prix_livraison,
    $utilisateur_id,
    $menu_id
]);
$stmt = $pdo->prepare("UPDATE menu SET quantite_restante = quantite_restante - 1 WHERE menu_id = ?");
$stmt->execute([$menu_id]);

header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php?success=Commande passée avec succès');
exit;
?>