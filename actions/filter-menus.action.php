<?php
require_once '../config/db.php';

$where = [];
$params = [];

if(!empty($_GET['prix_max'])) {
    $where[] = "m.prix_par_personne <= ?";
    $params[] = $_GET['prix_max'];
}

if(!empty($_GET['prix_min'])) {
    $where[] = "m.prix_par_personne >= ?";
    $params[] = $_GET['prix_min'];
}

if(!empty($_GET['prix_max2'])) {
    $where[] = "m.prix_par_personne <= ?";
    $params[] = $_GET['prix_max2'];
}

if(!empty($_GET['theme'])) {
    $where[] = "m.theme_id = ?";
    $params[] = $_GET['theme'];
}

if(!empty($_GET['regime'])) {
    $where[] = "m.regime_id = ?";
    $params[] = $_GET['regime'];
}

if(!empty($_GET['nb_personnes'])) {
    $where[] = "m.nombre_personne_minimum >= ?";
    $params[] = $_GET['nb_personnes'];
}

$sql = "
    SELECT m.*, r.libelle as regime_libelle, t.libelle as theme_libelle 
    FROM menu m
    JOIN regime r ON m.regime_id = r.regime_id
    JOIN theme t ON m.theme_id = t.theme_id
";

if(!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menus = $stmt->fetchAll();

if(empty($menus)) {
    echo '<p class="text-center text-muted">Aucun menu ne correspond à vos critères.</p>';
} else {
    foreach($menus as $m) {
        echo '
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($m['titre']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($m['description']) . '</p>
                    <p><strong>Thème :</strong> ' . htmlspecialchars($m['theme_libelle']) . '</p>
                    <p><strong>Régime :</strong> ' . htmlspecialchars($m['regime_libelle']) . '</p>
                    <p><strong>Personnes min :</strong> ' . $m['nombre_personne_minimum'] . '</p>
                    <p><strong>Prix :</strong> ' . $m['prix_par_personne'] . ' €/pers</p>
                    <p><strong>Stock :</strong> ' . $m['quantite_restante'] . ' disponible(s)</p>
                </div>
                <div class="card-footer">
                    <a href="/vite-gourmand/pages/menu-detail.php?id=' . $m['menu_id'] . '" class="btn btn-success w-100">Voir le détail</a>
                </div>
            </div>
        </div>';
    }
}
?>