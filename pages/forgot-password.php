<?php
session_start();
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h2 class="mb-4">Mot de passe oublié</h2>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">Si cet email existe, un lien de réinitialisation vous a été envoyé.</div>
            <?php endif; ?>

            <form action="/vite-gourmand/actions/forgot-password.action.php" method="POST">
                <div class="mb-3">
                    <label>Votre email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Envoyer le lien</button>
                <a href="/vite-gourmand/pages/login.php" class="btn btn-outline-secondary w-100 mt-2">Retour</a>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>