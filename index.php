<?php
session_start();
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/nav.php';
$stmt = $pdo->query("
    SELECT a.note, a.description, u.nom, u.prenom 
    FROM avis a 
    JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id 
    WHERE a.statut = 'validé'
    ORDER BY a.avis_id DESC
");
$avis = $stmt->fetchAll();
?>
<main>
    <section class="bg-dark text-white py-5 text-center">
        <div class="container">
            <h1 class="display-4">Vite & Gourmand</h1>
            <p class="lead">Votre traiteur bordelais depuis 25 ans</p>
            <a href="/vite-gourmand/pages/menus.php" class="btn btn-success btn-lg mt-3">Découvrir nos menus</a>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Qui sommes-nous ?</h2>
                    <p>Vite & Gourmand est une entreprise familiale fondée par Julie et José il y a 25 ans à Bordeaux. Nous proposons des prestations culinaires pour tous vos événements : repas de Noël, Pâques, mariages, anniversaires et bien plus encore.</p>
                    <p>Notre menu est en constante évolution pour vous offrir le meilleur de la gastronomie bordelaise.</p>
                </div>
                <div class="col-md-6 text-center">
                    <div class="p-4 bg-light rounded">
                        <h3 class="text-success">25 ans</h3>
                        <p>d'expérience à votre service</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4">Notre équipe</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <h4>Julie</h4>
                        <p class="text-muted">Co-fondatrice & Chef cuisinière</p>
                        <p>Passionnée de gastronomie, Julie crée des menus sur mesure pour chaque événement.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <h4>José</h4>
                        <p class="text-muted">Co-fondateur & Responsable logistique</p>
                        <p>José assure la livraison et la coordination de chaque prestation avec rigueur.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <h4>Notre engagement</h4>
                        <p class="text-muted">Qualité & Ponctualité</p>
                        <p>Chaque commande est préparée avec soin et livrée dans les délais convenus.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Avis de nos clients</h2>
            <?php if(empty($avis)): ?>
                <p class="text-center text-muted">Aucun avis pour le moment.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach($avis as $a): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm p-3">
                                <div class="text-warning mb-2">
                                    <?php for($i = 0; $i < $a['note']; $i++): ?>⭐<?php endfor; ?>
                                </div>
                                <p>"<?= htmlspecialchars($a['description']) ?>"</p>
                                <small class="text-muted">— <?= htmlspecialchars($a['prenom']) ?> <?= htmlspecialchars($a['nom']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php require_once 'includes/footer.php'; ?>