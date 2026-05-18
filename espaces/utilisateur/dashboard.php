<?php
session_start();
require_once '../../config/db.php';
require_once '../../includes/header.php';
require_once '../../includes/nav.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'utilisateur') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch user orders
$stmt = $pdo->prepare("
    SELECT c.*, m.titre as menu_titre
    FROM commande c
    JOIN menu m ON c.menu_id = m.menu_id
    WHERE c.utilisateur_id = ?
    ORDER BY c.date_commande DESC
");
$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll();

$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<main class="container mt-5">
    <h2 class="mb-4">Mon Espace</h2>

    <?php if($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Personal Info -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-4">
                <h5>Mes informations</h5>
                <hr>
                <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['telephone']) ?></p>
                <p><strong>Adresse :</strong> <?= htmlspecialchars($user['adresse_postale']) ?></p>
                <p><strong>Ville :</strong> <?= htmlspecialchars($user['ville']) ?></p>
                <a href="/vite-gourmand/pages/edit-profile.php" class="btn btn-outline-success w-100 mt-2">Modifier mes informations</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <h5>Mes commandes</h5>
                <hr>
                <?php if(empty($commandes)): ?>
                    <p class="text-muted">Vous n'avez pas encore de commande.</p>
                <?php else: ?>
                    <?php foreach($commandes as $c): ?>
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($c['menu_titre']) ?></h6>
                                        <small class="text-muted">Commande : <?= htmlspecialchars($c['numero_commande']) ?></small><br>
                                        <small class="text-muted">Prestation : <?= $c['date_prestation'] ?> à <?= $c['heure_livraison'] ?></small><br>
                                        <small class="text-muted">Personnes : <?= $c['nombre_personne'] ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge 
                                            <?php 
                                            switch($c['statut']) {
                                                case 'en attente': echo 'bg-warning'; break;
                                                case 'accepté': echo 'bg-info'; break;
                                                case 'en préparation': echo 'bg-primary'; break;
                                                case 'en cours de livraison': echo 'bg-primary'; break;
                                                case 'livré': echo 'bg-success'; break;
                                                case 'terminée': echo 'bg-success'; break;
                                                case 'annulé': echo 'bg-danger'; break;
                                                default: echo 'bg-secondary';
                                            }
                                            ?>">
                                            <?= htmlspecialchars($c['statut']) ?>
                                        </span>
                                        <div class="mt-2">
                                            <strong><?= number_format($c['prix_menu'] + $c['prix_livraison'], 2) ?> €</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex gap-2">
                                    <?php if($c['statut'] === 'en attente'): ?>
                                        <a href="/vite-gourmand/pages/edit-commande.php?id=<?= $c['numero_commande'] ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                                        <a href="/vite-gourmand/actions/cancel-commande.action.php?id=<?= $c['numero_commande'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Confirmer l\'annulation ?')">Annuler</a>
                                    <?php endif; ?>

                                    <?php if($c['statut'] === 'terminée' || $c['statut'] === 'livré'): ?>
                                        <a href="/vite-gourmand/pages/avis.php?commande_id=<?= $c['numero_commande'] ?>" class="btn btn-sm btn-outline-success">Donner mon avis</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php require_once '../../includes/footer.php'; ?>