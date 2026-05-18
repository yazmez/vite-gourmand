<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/nav.php';

if(!isset($_SESSION['user'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$commande_id = $_GET['commande_id'] ?? null;
if(!$commande_id) {
    header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT c.*, m.titre as menu_titre 
    FROM commande c
    JOIN menu m ON c.menu_id = m.menu_id
    WHERE c.numero_commande = ? 
    AND c.utilisateur_id = ?
    AND c.statut = 'terminée'
");
$stmt->execute([$commande_id, $_SESSION['user']['id']]);
$commande = $stmt->fetch();
if(!$commande) {
    header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php');
    exit;
}
?>
<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Donner mon avis</h2>
            <p class="text-muted">Commande : <strong><?= htmlspecialchars($commande['menu_titre']) ?></strong></p>
            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">Merci pour votre avis, il sera visible après validation.</div>
            <?php endif; ?>

            <form action="/vite-gourmand/actions/avis.action.php" method="POST">
                <input type="hidden" name="commande_id" value="<?= $commande_id ?>">
                <div class="mb-3">
                    <label>Note</label>
                    <select name="note" class="form-select" required>
                        <option value="">Choisir une note</option>
                        <option value="1">⭐ 1/5</option>
                        <option value="2">⭐⭐ 2/5</option>
                        <option value="3">⭐⭐⭐ 3/5</option>
                        <option value="4">⭐⭐⭐⭐ 4/5</option>
                        <option value="5">⭐⭐⭐⭐⭐ 5/5</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Commentaire</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">Envoyer mon avis</button>
                <a href="/vite-gourmand/espaces/utilisateur/dashboard.php" class="btn btn-outline-secondary w-100 mt-2">Retour</a>
            </form>
        </div>
    </div>
</main>
<?php require_once '../includes/footer.php'; ?>