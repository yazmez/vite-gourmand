<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/nav.php';

if(!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['administrateur', 'employe'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if(!$id) {
    header('Location: /vite-gourmand/espaces/admin/dashboard.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$id]);
$menu = $stmt->fetch();

if(!$menu) {
    header('Location: /vite-gourmand/espaces/admin/dashboard.php');
    exit;
}

$themes = $pdo->query("SELECT * FROM theme")->fetchAll();
$regimes = $pdo->query("SELECT * FROM regime")->fetchAll();
$plats = $pdo->query("SELECT * FROM plat")->fetchAll();

$stmt = $pdo->prepare("SELECT plat_id FROM menu_plat WHERE menu_id = ?");
$stmt->execute([$id]);
$platsMenu = array_column($stmt->fetchAll(), 'plat_id');
?>

<main class="container mt-5">
    <h2 class="mb-4">Modifier le menu</h2>

    <form action="/vite-gourmand/actions/edit-menu.action.php" method="POST">
        <input type="hidden" name="menu_id" value="<?= $menu['menu_id'] ?>">

        <div class="card shadow-sm p-4 mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Titre</label>
                    <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($menu['titre']) ?>" required>
                </div>
                <div class="col-md-3">
                    <label>Prix par personne (€)</label>
                    <input type="number" step="0.01" name="prix_par_personne" class="form-control" value="<?= $menu['prix_par_personne'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label>Nombre de personnes minimum</label>
                    <input type="number" name="nombre_personne_minimum" class="form-control" value="<?= $menu['nombre_personne_minimum'] ?>" required>
                </div>
                <div class="col-md-12">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($menu['description']) ?></textarea>
                </div>
                <div class="col-md-3">
                    <label>Stock disponible</label>
                    <input type="number" name="quantite_restante" class="form-control" value="<?= $menu['quantite_restante'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label>Thème</label>
                    <select name="theme_id" class="form-select" required>
                        <?php foreach($themes as $t): ?>
                            <option value="<?= $t['theme_id'] ?>" <?= $menu['theme_id'] == $t['theme_id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Régime</label>
                    <select name="regime_id" class="form-select" required>
                        <?php foreach($regimes as $r): ?>
                            <option value="<?= $r['regime_id'] ?>" <?= $menu['regime_id'] == $r['regime_id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['libelle']) ?></option>
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
                    <input class="form-check-input" type="checkbox" name="plats[]" value="<?= $p['plat_id'] ?>" <?= in_array($p['plat_id'], $platsMenu) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= htmlspecialchars($p['titre_plat']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-success">Sauvegarder</button>
        <a href="/vite-gourmand/espaces/admin/dashboard.php" class="btn btn-outline-secondary ms-2">Retour</a>
    </form>
</main>

<?php require_once '../includes/footer.php'; ?>