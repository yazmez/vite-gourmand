<?php
session_start();
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container mt-5">
    <h2 class="mb-4">Conditions Générales de Vente</h2>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Article 1 — Objet</h5>
        <p>Les présentes CGV régissent les relations contractuelles entre Vite & Gourmand et ses clients dans le cadre de la commande de prestations culinaires.</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Article 2 — Commandes</h5>
        <p>Toute commande doit être passée au minimum 48 heures avant la date de prestation souhaitée. Vite & Gourmand se réserve le droit de refuser une commande en cas de stock insuffisant.</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Article 3 — Prix et livraison</h5>
        <p>Les prix sont indiqués en euros TTC. Une majoration de 5 euros est appliquée pour toute livraison hors de la ville de Bordeaux, augmentée de 0,59 euro par kilomètre supplémentaire.</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Article 4 — Réduction</h5>
        <p>Une réduction de 10% est appliquée pour toute commande dont le nombre de personnes est supérieur d'au moins 5 au minimum requis par le menu.</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Article 5 — Annulation</h5>
        <p>L'annulation d'une commande est possible tant qu'elle n'a pas été acceptée par l'équipe Vite & Gourmand. Passé ce délai, toute annulation devra faire l'objet d'une prise de contact directe.</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Article 6 — Matériel prêté</h5>
        <p>Dans le cadre de certaines prestations, du matériel peut être prêté au client. Ce matériel doit être restitué dans un délai de 10 jours ouvrés suivant la prestation. En cas de non-restitution dans ce délai, une pénalité de 600 euros sera facturée au client, conformément aux présentes CGV.</p>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Article 7 — Données personnelles</h5>
        <p>Les informations collectées lors de la commande sont utilisées uniquement pour le traitement de celle-ci. Conformément au RGPD, vous disposez d'un droit d'accès et de rectification de vos données.</p>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>