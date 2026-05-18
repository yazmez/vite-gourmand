<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/nav.php';

if(!isset($_GET['id'])) {
    header('Location: /vite-gourmand/pages/menus.php');
    exit;
}
$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT m.*, r.libelle as regime_libelle, t.libelle as theme_libelle 
    FROM menu m
    JOIN regime r ON m.regime_id = r.regime_id
    JOIN theme t ON m.theme_id = t.theme_id
    WHERE m.menu_id = ?
");
$stmt->execute([$id]);
$menu = $stmt->fetch();

if(!$menu) {
    header('Location: /vite-gourmand/pages/menus.php');
    exit;
}
$stmt = $pdo->prepare("
    SELECT p.*, GROUP_CONCAT(a.libelle SEPARATOR ', ') as allergenes
    FROM plat p
    JOIN menu_plat mp ON p.plat_id = mp.plat_id
    LEFT JOIN plat_allergene pa ON p.plat_id = pa.plat_id
    LEFT JOIN allergene a ON pa.allergene_id = a.allergene_id
    WHERE mp.menu_id = ?
    GROUP BY p.plat_id
");
$stmt->execute([$id]);
$plats = $stmt->fetchAll();
?>

<main class="container mt-5">
    <a href="/vite-gourmand/pages/menus.php" class="btn btn-outline-secondary mb-4">← Retour aux menus</a>

    <div class="row">
        <div class="col-md-8">
            <h2><?= htmlspecialchars($menu['titre']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($menu['description']) ?></p>

            <div class="row mb-3">
                <div class="col-md-4">
                    <span class="badge bg-success"><?= htmlspecialchars($menu['theme_libelle']) ?></span>
                    <span class="badge bg-info ms-1"><?= htmlspecialchars($menu['regime_libelle']) ?></span>
                </div>
            </div>

            <h4 class="mt-4">Les plats</h4>
            <?php if(empty($plats)): ?>
                <p class="text-muted">Aucun plat renseigné pour ce menu.</p>
            <?php else: ?>
                <ul class="list-group mb-4">
                    <?php foreach($plats as $p): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($p['titre_plat']) ?></strong>
                            <?php if($p['allergenes']): ?>
                                <br><small class="text-danger">Allergènes : <?= htmlspecialchars($p['allergenes']) ?></small>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            <!-- Pricing Card -->
            <div class="card shadow-sm p-4 mb-4">
                <h5>Informations</h5>
                <hr>
                <p><strong>Prix :</strong> <?= $menu['prix_par_personne'] ?> €/personne</p>
                <p><strong>Minimum :</strong> <?= $menu['nombre_personne_minimum'] ?> personnes</p>
                <p><strong>Prix minimum :</strong> <?= $menu['prix_par_personne'] * $menu['nombre_personne_minimum'] ?> €</p>
                <p><strong>Stock :</strong> <?= $menu['quantite_restante'] ?> disponible(s)</p>
                <p><strong>Thème :</strong> <?= htmlspecialchars($menu['theme_libelle']) ?></p>
                <p><strong>Régime :</strong> <?= htmlspecialchars($menu['regime_libelle']) ?></p>
            </div>
            <div class="card border-warning shadow-sm p-4 mb-4">
                <h5 class="text-warning">⚠️ Conditions importantes</h5>
                <hr>
                <p>Toute commande doit être passée au minimum 48h avant la date de prestation.</p>
                <p>Le matériel prêté doit être restitué sous 10 jours ouvrés après la prestation.</p>
            </div>
            <?php if(isset($_SESSION['user'])): ?>
                <a href="/vite-gourmand/pages/commande.php?menu_id=<?= $menu['menu_id'] ?>" class="btn btn-success w-100 btn-lg">Commander ce menu</a>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    <p>Vous devez être connecté pour commander.</p>
                    <a href="/vite-gourmand/pages/login.php" class="btn btn-success me-2">Se connecter</a>
                    <a href="/vite-gourmand/pages/register.php" class="btn btn-outline-success">S'inscrire</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>