<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/nav.php';
$themes = $pdo->query("SELECT * FROM theme")->fetchAll();
$regimes = $pdo->query("SELECT * FROM regime")->fetchAll();
$menus = $pdo->query("
    SELECT m.*, r.libelle as regime_libelle, t.libelle as theme_libelle 
    FROM menu m
    JOIN regime r ON m.regime_id = r.regime_id
    JOIN theme t ON m.theme_id = t.theme_id
")->fetchAll();
?>
<main class="container mt-5">
    <h2 class="mb-4">Nos Menus</h2>
    <div class="card shadow-sm p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label>Prix maximum (€)</label>
                <input type="number" id="prix_max" class="form-control" placeholder="Ex: 100">
            </div>
            <div class="col-md-3">
                <label>Fourchette de prix (€)</label>
                <div class="d-flex gap-2">
                    <input type="number" id="prix_min" class="form-control" placeholder="Min">
                    <input type="number" id="prix_max2" class="form-control" placeholder="Max">
                </div>
            </div>
            <div class="col-md-2">
                <label>Thème</label>
                <select id="theme" class="form-select">
                    <option value="">Tous</option>
                    <?php foreach($themes as $t): ?>
                        <option value="<?= $t['theme_id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Régime</label>
                <select id="regime" class="form-select">
                    <option value="">Tous</option>
                    <?php foreach($regimes as $r): ?>
                        <option value="<?= $r['regime_id'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Nombre de personnes min</label>
                <input type="number" id="nb_personnes" class="form-control" placeholder="Ex: 5">
            </div>
        </div>
        <button class="btn btn-success mt-3" onclick="filterMenus()">Filtrer</button>
    </div>
    <div id="menus-container" class="row">
        <?php foreach($menus as $m): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($m['titre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($m['description']) ?></p>
                        <p><strong>Thème :</strong> <?= htmlspecialchars($m['theme_libelle']) ?></p>
                        <p><strong>Régime :</strong> <?= htmlspecialchars($m['regime_libelle']) ?></p>
                        <p><strong>Personnes min :</strong> <?= $m['nombre_personne_minimum'] ?></p>
                        <p><strong>Prix :</strong> <?= $m['prix_par_personne'] ?> €/pers</p>
                        <p><strong>Stock :</strong> <?= $m['quantite_restante'] ?> disponible(s)</p>
                    </div>
                    <div class="card-footer">
                        <a href="/vite-gourmand/pages/menu-detail.php?id=<?= $m['menu_id'] ?>" class="btn btn-success w-100">Voir le détail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php require_once '../includes/footer.php'; ?>
<script>
function filterMenus() {
    const prix_max = document.getElementById('prix_max').value;
    const prix_min = document.getElementById('prix_min').value;
    const prix_max2 = document.getElementById('prix_max2').value;
    const theme = document.getElementById('theme').value;
    const regime = document.getElementById('regime').value;
    const nb_personnes = document.getElementById('nb_personnes').value;
    fetch(`/vite-gourmand/actions/filter-menus.action.php?prix_max=${prix_max}&prix_min=${prix_min}&prix_max2=${prix_max2}&theme=${theme}&regime=${regime}&nb_personnes=${nb_personnes}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('menus-container').innerHTML = html;
        });
}
</script>