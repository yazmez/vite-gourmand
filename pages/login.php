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
        <div class="col-md-5">
            <h2 class="mb-4">Connexion</h2>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>

            <form action="/vite-gourmand/actions/login.action.php" method="POST">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Se connecter</button>
                <p class="mt-3 text-center">Pas encore de compte ? <a href="/vite-gourmand/pages/register.php">S'inscrire</a></p>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>