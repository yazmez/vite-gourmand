<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Nous contacter</h2>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">Votre message a bien été envoyé.</div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="/vite-gourmand/actions/contact.action.php" method="POST">
                <div class="mb-3">
                    <label>Titre</label>
                    <input type="text" name="titre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Votre email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Message</label>
                    <textarea name="description" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">Envoyer</button>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>