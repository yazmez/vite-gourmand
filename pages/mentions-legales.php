<?php
session_start();
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container mt-5">
    <h2 class="mb-4">Mentions Légales</h2>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Éditeur du site</h5>
        <p>Vite & Gourmand<br>
        Représentants : Julie et José<br>
        Adresse : 1 rue de la Paix, 33000 Bordeaux<br>
        Email : contact@vitegourmand.fr<br>
        Téléphone : 05 00 00 00 00</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Hébergement</h5>
        <p>Ce site est hébergé par Railway.<br>
        Railway Corp, 340 S Lemon Ave #4133, Walnut, CA 91789, USA</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Propriété intellectuelle</h5>
        <p>L'ensemble du contenu de ce site (textes, images, vidéos) est protégé par le droit d'auteur. Toute reproduction est interdite sans autorisation préalable.</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Données personnelles (RGPD)</h5>
        <p>Les données collectées sur ce site sont utilisées uniquement dans le cadre de la gestion des commandes et de la relation client. Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression de vos données. Pour exercer ce droit, contactez-nous à : contact@vitegourmand.fr</p>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>