<?php
session_start();
require_once '../../config/db.php';
require_once '../../includes/header.php';
require_once '../../includes/nav.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employe') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$where = ["c.statut != 'annulé'"];
$params = [];

if(!empty($_GET['statut'])) {
    $where = ["c.statut = ?"];
    $params[] = $_GET['statut'];
}

if(!empty($_GET['client'])) {
    $where[] = "(u.nom LIKE ? OR u.prenom LIKE ? OR u.email LIKE ?)";
    $params[] = '%' . $_GET['client'] . '%';
    $params[] = '%' . $_GET['client'] . '%';
    $params[] = '%' . $_GET['client'] . '%';
}

$sql = "
    SELECT c.*, m.titre as menu_titre, u.nom as client_nom, u.prenom as client_prenom, u.email as client_email, u.telephone as client_telephone
    FROM commande c
    JOIN menu m ON c.menu_id = m.menu_id
    JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
";

if(!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY c.date_commande DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$commandes = $stmt->fetchAll();

$avis = $pdo->query("
    SELECT a.*, u.nom, u.prenom, m.titre as menu_titre
    FROM avis a
    JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
    JOIN commande c ON a.commande_id = c.numero_commande
    JOIN menu m ON c.menu_id = m.menu_id
    WHERE a.statut = 'en attente'
")->fetchAll();

$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<main class="container mt-5">
    <h2 class="mb-4">Espace Employé</h2>

    <?php if($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4 mb-4">
        <form method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Filtrer par statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous (sauf annulés)</option>
                        <option value="en attente" <?= ($_GET['statut'] ?? '') === 'en attente' ? 'selected' : '' ?>>En attente</option>
                        <option value="accepté" <?= ($_GET['statut'] ?? '') === 'accepté' ? 'selected' : '' ?>>Accepté</option>
                        <option value="en préparation" <?= ($_GET['statut'] ?? '') === 'en préparation' ? 'selected' : '' ?>>En préparation</option>
                        <option value="en cours de livraison" <?= ($_GET['statut'] ?? '') === 'en cours de livraison' ? 'selected' : '' ?>>En cours de livraison</option>
                        <option value="livré" <?= ($_GET['statut'] ?? '') === 'livré' ? 'selected' : '' ?>>Livré</option>
                        <option value="en attente du retour de matériel" <?= ($_GET['statut'] ?? '') === 'en attente du retour de matériel' ? 'selected' : '' ?>>En attente retour matériel</option>
                        <option value="terminée" <?= ($_GET['statut'] ?? '') === 'terminée' ? 'selected' : '' ?>>Terminée</option>
                        <option value="annulé" <?= ($_GET['statut'] ?? '') === 'annulé' ? 'selected' : '' ?>>Annulé</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Filtrer par client</label>
                    <input type="text" name="client" class="form-control" placeholder="Nom, prénom ou email" value="<?= htmlspecialchars($_GET['client'] ?? '') ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Filtrer</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5>Commandes</h5>
        <hr>
        <?php if(empty($commandes)): ?>
            <p class="text-muted">Aucune commande trouvée.</p>
        <?php else: ?>
            <?php foreach($commandes as $c): ?>
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6><?= htmlspecialchars($c['menu_titre']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($c['numero_commande']) ?></small><br>
                                <small>Client : <strong><?= htmlspecialchars($c['client_prenom']) ?> <?= htmlspecialchars($c['client_nom']) ?></strong></small><br>
                                <small>📞 <?= htmlspecialchars($c['client_telephone']) ?></small><br>
                                <small>✉️ <?= htmlspecialchars($c['client_email']) ?></small>
                            </div>
                            <div class="col-md-3">
                                <small>Prestation : <?= $c['date_prestation'] ?></small><br>
                                <small>Heure : <?= $c['heure_livraison'] ?></small><br>
                                <small>Personnes : <?= $c['nombre_personne'] ?></small><br>
                                <small>Total : <strong><?= number_format($c['prix_menu'] + $c['prix_livraison'], 2) ?> €</strong></small>
                            </div>
                            <div class="col-md-5">
                                <form action="/vite-gourmand/actions/update-commande.action.php" method="POST">
                                    <input type="hidden" name="numero_commande" value="<?= $c['numero_commande'] ?>">
                                    <div class="mb-2">
                                        <select name="statut" class="form-select form-select-sm">
                                            <option value="en attente" <?= $c['statut'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
                                            <option value="accepté" <?= $c['statut'] === 'accepté' ? 'selected' : '' ?>>Accepté</option>
                                            <option value="en préparation" <?= $c['statut'] === 'en préparation' ? 'selected' : '' ?>>En préparation</option>
                                            <option value="en cours de livraison" <?= $c['statut'] === 'en cours de livraison' ? 'selected' : '' ?>>En cours de livraison</option>
                                            <option value="livré" <?= $c['statut'] === 'livré' ? 'selected' : '' ?>>Livré</option>
                                            <option value="en attente du retour de matériel" <?= $c['statut'] === 'en attente du retour de matériel' ? 'selected' : '' ?>>En attente retour matériel</option>
                                            <option value="terminée" <?= $c['statut'] === 'terminée' ? 'selected' : '' ?>>Terminée</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" name="motif" class="form-control form-control-sm" placeholder="Motif si annulation (obligatoire)">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm w-100">Mettre à jour</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm p-4">
        <h5>Avis en attente de validation</h5>
        <hr>
        <?php if(empty($avis)): ?>
            <p class="text-muted">Aucun avis en attente.</p>
        <?php else: ?>
            <?php foreach($avis as $a): ?>
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($a['prenom']) ?> <?= htmlspecialchars($a['nom']) ?></strong>
                                <span class="ms-2 text-warning"><?= str_repeat('⭐', $a['note']) ?></span>
                                <p class="mb-1 mt-1"><?= htmlspecialchars($a['description']) ?></p>
                                <small class="text-muted">Menu : <?= htmlspecialchars($a['menu_titre']) ?></small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="/vite-gourmand/actions/validate-avis.action.php?id=<?= $a['avis_id'] ?>&action=valider" class="btn btn-success btn-sm">Valider</a>
                                <a href="/vite-gourmand/actions/validate-avis.action.php?id=<?= $a['avis_id'] ?>&action=refuser" class="btn btn-danger btn-sm">Refuser</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>