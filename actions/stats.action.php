<?php
require_once '../config/db.php';

$stmt = $pdo->query("
    SELECT m.titre, COUNT(c.numero_commande) as total, SUM(c.prix_menu) as chiffre_affaires
    FROM menu m
    LEFT JOIN commande c ON m.menu_id = c.menu_id
    GROUP BY m.menu_id
");
$data = $stmt->fetchAll();

$labels = [];
$counts = [];
$revenues = [];

foreach($data as $row) {
    $labels[] = $row['titre'];
    $counts[] = $row['total'];
    $revenues[] = $row['chiffre_affaires'] ?? 0;
}

header('Content-Type: application/json');
echo json_encode([
    'labels' => $labels,
    'counts' => $counts,
    'revenues' => $revenues
]);
?>