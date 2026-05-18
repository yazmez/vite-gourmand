<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/nav.php';
if(!isset($_SESSION['user'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Modifier mes informations</h2>
            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">Informations mises à jour avec succès.</div>
            <?php endif; ?>

            <form action="/vite-gourmand/actions/edit-profile.action.php" method="POST">
                <div class="mb-3">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone']) ?>">
                </div>
                <div class="mb-3">
                    <label>Ville</label>
                    <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($user['ville']) ?>">
                </div>
                <div class="mb-3">
                    <label>Pays</label>
                    <input type="text" name="pays" class="form-control" value="<?= htmlspecialchars($user['pays']) ?>">
                </div>
                <div class="mb-3">
                    <label>Adresse postale</label>
                    <input type="text" name="adresse_postale" class="form-control" value="<?= htmlspecialchars($user['adresse_postale']) ?>">
                </div>
                <div class="mb-3">
                    <label>Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-success w-100">Sauvegarder</button>
                <a href="/vite-gourmand/espaces/utilisateur/dashboard.php" class="btn btn-outline-secondary w-100 mt-2">Retour</a>
            </form>
        </div>
    </div>
</main>
<?php require_once '../includes/footer.php'; ?>