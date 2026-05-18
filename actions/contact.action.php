<?php
session_start();

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/contact.php');
    exit;
}

$titre = trim($_POST['titre']);
$email = trim($_POST['email']);
$description = trim($_POST['description']);

if(empty($titre) || empty($email) || empty($description)) {
    header('Location: /vite-gourmand/pages/contact.php?error=Tous les champs sont obligatoires');
    exit;
}

header('Location: /vite-gourmand/pages/contact.php?success=1');
exit;
?>