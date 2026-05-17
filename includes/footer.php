<?php
require_once __DIR__ . '/../config/db.php';
$stmt = $pdo->query("SELECT * FROM horaire ORDER BY horaire_id ASC");
$horaires = $stmt->fetchAll();
?>

<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Vite & Gourmand</h5>
                <p>Votre traiteur bordelais depuis 25 ans.</p>
            </div>
            <div class="col-md-4">
                <h5>Nos horaires</h5>
                <ul class="list-unstyled">
                    <?php foreach($horaires as $h): ?>
                        <li><?= $h['jour'] ?> : <?= $h['heure_ouverture'] ?> - <?= $h['heure_fermeture'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Informations légales</h5>
                <ul class="list-unstyled">
                    <li><a href="/vite-gourmand/pages/mentions-legales.php" class="text-white">Mentions légales</a></li>
                    <li><a href="/vite-gourmand/pages/cgv.php" class="text-white">CGV</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.com/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>