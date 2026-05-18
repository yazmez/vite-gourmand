<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$numero_commande = $_POST['numero_commande'];
$date_prestation = $_POST['date_prestation'];
$heure_livraison = $_POST['heure_livraison'];
$nombre_personne = (int)$_POST['nombre_personne'];

$stmt = $pdo->prepare("
    SELECT c.*, m.nombre_personne_minimum, m.prix_par_personne
    FROM commande c
    JOIN menu m ON c.menu_id = m.menu_id
    WHERE c.numero_commande = ? AND c.utilisateur_id = ? AND c.statut = 'en attente'
");
$stmt->execute([$numero_commande, $_SESSION['user']['id']]);
$commande = $stmt->fetch();

if(!$commande) {
    header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php?error=Modification impossible');
    exit;
}

if($nombre_personne < $commande['nombre_personne_minimum']) {
    header('Location: /vite-gourmand/pages/edit-commande.php?id=' . $numero_commande . '&error=Nombre de personnes insuffisant');
    exit;
}

$prix_menu = $nombre_personne * $commande['prix_par_personne'];
if($nombre_personne >= $commande['nombre_personne_minimum'] + 5) {
    $prix_menu = $prix_menu * 0.90;
}

$stmt = $pdo->prepare("
    UPDATE commande 
    SET date_prestation=?, heure_livraison=?, nombre_personne=?, prix_menu=?
    WHERE numero_commande=?
");
$stmt->execute([$date_prestation, $heure_livraison, $nombre_personne, $prix_menu, $numero_commande]);

header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php?success=Commande modifiée avec succès');
exit;
?>