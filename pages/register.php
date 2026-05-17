<?php
session_start();
if(isset($_SESSION['user'])) {
    header('Location: /vite-gourmand/index.php');
    exit;
}
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Créer un compte</h2>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="/vite-gourmand/actions/register.action.php" method="POST">
                <div class="mb-3">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Ville</label>
                    <input type="text" name="ville" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Pays</label>
                    <input type="text" name="pays" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Adresse postale</label>
                    <input type="text" name="adresse_postale" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                    <small class="text-muted">10 caractères minimum, une majuscule, une minuscule, un chiffre, un caractère spécial</small>
                </div>
                <button type="submit" class="btn btn-success w-100">Créer mon compte</button>
                <p class="mt-3 text-center">Déjà un compte ? <a href="/vite-gourmand/pages/login.php">Se connecter</a></p>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>