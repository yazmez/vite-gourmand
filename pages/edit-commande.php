<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/nav.php';

if(!isset($_SESSION['user'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if(!$id) {
    header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT c.*, m.titre as menu_titre, m.nombre_personne_minimum
    FROM commande c
    JOIN menu m ON c.menu_id = m.menu_id
    WHERE c.numero_commande = ? AND c.utilisateur_id = ? AND c.statut = 'en attente'
");
$stmt->execute([$id, $_SESSION['user']['id']]);
$commande = $stmt->fetch();

if(!$commande) {
    header('Location: /vite-gourmand/espaces/utilisateur/dashboard.php?error=Modification impossible');
    exit;
}
?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <h2 class="mb-4">Modifier ma commande</h2>
            <p class="text-muted">Menu : <strong><?= htmlspecialchars($commande['menu_titre']) ?></strong></p>

            <form action="/vite-gourmand/actions/edit-commande.action.php" method="POST">
                <input type="hidden" name="numero_commande" value="<?= $commande['numero_commande'] ?>">

                <div class="card shadow-sm p-4 mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Date de prestation</label>
                            <input type="date" name="date_prestation" class="form-control" value="<?= $commande['date_prestation'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Heure de livraison</label>
                            <input type="time" name="heure_livraison" class="form-control" value="<?= $commande['heure_livraison'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Nombre de personnes</label>
                            <input type="number" name="nombre_personne" class="form-control" 
                                   min="<?= $commande['nombre_personne_minimum'] ?>" 
                                   value="<?= $commande['nombre_personne'] ?>" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100">Sauvegarder</button>
                <a href="/vite-gourmand/espaces/utilisateur/dashboard.php" class="btn btn-outline-secondary w-100 mt-2">Retour</a>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>