<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/vite-gourmand/index.php">Vite & Gourmand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/vite-gourmand/index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/vite-gourmand/pages/menus.php">Nos Menus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/vite-gourmand/pages/contact.php">Contact</a>
                </li>
                <?php if(isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <?php
$roleFolder = $_SESSION['user']['role'] === 'administrateur' ? 'admin' : $_SESSION['user']['role'];
?>
<a class="nav-link" href="/vite-gourmand/espaces/<?= $roleFolder ?>/dashboard.php">Mon Espace</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/vite-gourmand/actions/logout.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/vite-gourmand/pages/login.php">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>