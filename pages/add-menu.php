<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/nav.php';

if(!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['administrateur', 'employe'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$themes = $pdo->query("SELECT * FROM theme")->fetchAll();
$regimes = $pdo->query("SELECT * FROM regime")->fetchAll();
$plats = $pdo->query("SELECT * FROM plat")->fetchAll();
?>

<main class="container mt-5">
    <h2 class="mb-4">Ajouter un menu</h2>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="/vite-gourmand/actions/add-menu.action.php" method="POST">
        <div class="card shadow-sm p-4 mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Titre</label>
                    <input type="text" name="titre" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Prix par personne (€)</label>
                    <input type="number" step="0.01" name="prix_par_personne" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Nombre de personnes minimum</label>
                    <input type="number" name="nombre_personne_minimum" class="form-control" required>
                </div>
                <div class="col-md-12">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="col-md-3">
                    <label>Stock disponible</label>
                    <input type="number" name="quantite_restante" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Thème</label>
                    <select name="theme_id" class="form-select" required>
                        <?php foreach($themes as $t): ?>
                            <option value="<?= $t['theme_id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Régime</label>
                    <select name="regime_id" class="form-select" required>
                        <?php foreach($regimes as $r): ?>
                            <option value="<?= $r['regime_id'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <h5>Plats du menu</h5>
            <hr>
            <?php foreach($plats as $p): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="plats[]" value="<?= $p['plat_id'] ?>">
                    <label class="form-check-label"><?= htmlspecialchars($p['titre_plat']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-success">Créer le menu</button>
        <a href="/vite-gourmand/espaces/admin/dashboard.php" class="btn btn-outline-secondary ms-2">Retour</a>
    </form>
</main>

<?php require_once '../includes/footer.php'; ?>