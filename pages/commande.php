<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['user'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}
if(!isset($_GET['menu_id'])) {
    header('Location: /vite-gourmand/pages/menus.php');
    exit;
}
$menu_id = $_GET['menu_id'];
$stmt = $pdo->prepare("
    SELECT m.*, r.libelle as regime_libelle, t.libelle as theme_libelle 
    FROM menu m
    JOIN regime r ON m.regime_id = r.regime_id
    JOIN theme t ON m.theme_id = t.theme_id
    WHERE m.menu_id = ?
");
$stmt->execute([$menu_id]);
$menu = $stmt->fetch();

if(!$menu) {
    header('Location: /vite-gourmand/pages/menus.php');
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$user = $stmt->fetch();
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>
<main class="container mt-5">
    <h2 class="mb-4">Commander un menu</h2>
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <form action="/vite-gourmand/actions/commande.action.php" method="POST">
        <input type="hidden" name="menu_id" value="<?= $menu['menu_id'] ?>">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm p-4 mb-4">
                    <h5>Vos informations</h5>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Prénom</label>
                            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Téléphone</label>
                            <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone']) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm p-4 mb-4">
                    <h5>Informations de livraison</h5>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label>Adresse de livraison</label>
                            <input type="text" name="adresse_livraison" class="form-control" value="<?= htmlspecialchars($user['adresse_postale']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label>Ville de livraison</label>
                            <input type="text" name="ville_livraison" class="form-control" id="ville_livraison" value="<?= htmlspecialchars($user['ville']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Date de prestation</label>
                            <input type="date" name="date_prestation" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Heure de livraison souhaitée</label>
                            <input type="time" name="heure_livraison" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm p-4 mb-4">
                    <h5>Nombre de personnes</h5>
                    <hr>
                    <p class="text-muted">Minimum requis : <strong><?= $menu['nombre_personne_minimum'] ?> personnes</strong></p>
                    <input type="number" 
                           name="nombre_personne" 
                           id="nombre_personne"
                           class="form-control" 
                           min="<?= $menu['nombre_personne_minimum'] ?>" 
                           value="<?= $menu['nombre_personne_minimum'] ?>"
                           required>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-4 mb-4 sticky-top" style="top: 20px">
                    <h5>Récapitulatif</h5>
                    <hr>
                    <p><strong>Menu :</strong> <?= htmlspecialchars($menu['titre']) ?></p>
                    <p><strong>Prix/personne :</strong> <?= $menu['prix_par_personne'] ?> €</p>
                    <hr>
                    <p><strong>Nombre de personnes :</strong> <span id="recap_nb">-</span></p>
                    <p><strong>Prix menu :</strong> <span id="recap_prix_menu">-</span> €</p>
                    <p><strong>Livraison :</strong> <span id="recap_livraison">-</span> €</p>
                    <p class="text-success"><strong>Réduction :</strong> <span id="recap_reduction">-</span></p>
                    <hr>
                    <h5>Total : <span id="recap_total">-</span> €</h5>

                    <div class="card border-warning p-3 mt-3">
                        <small class="text-warning">⚠️ Toute commande doit être passée 48h avant la prestation. Le matériel prêté doit être restitué sous 10 jours ouvrés.</small>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3 btn-lg">Confirmer la commande</button>
                </div>
            </div>
        </div>
    </form>
</main>
<?php require_once '../includes/footer.php'; ?>
<script>
const prixParPersonne = <?= $menu['prix_par_personne'] ?>;
const minimum = <?= $menu['nombre_personne_minimum'] ?>;
function updatePrice() {
    const nb = parseInt(document.getElementById('nombre_personne').value) || minimum;
    const ville = document.getElementById('ville_livraison').value.toLowerCase().trim();

    let prixMenu = nb * prixParPersonne;
    let reduction = 0;
    if(nb >= minimum + 5) {
        reduction = prixMenu * 0.10;
        prixMenu = prixMenu - reduction;
        document.getElementById('recap_reduction').textContent = '-' + reduction.toFixed(2) + ' €';
    } else {
        document.getElementById('recap_reduction').textContent = 'Aucune';
    }
    let livraison = 0;
    if(ville !== 'bordeaux') {
        livraison = 5;
        document.getElementById('recap_livraison').textContent = livraison.toFixed(2) + ' (+ 0.59€/km)';
    } else {
        document.getElementById('recap_livraison').textContent = '0.00';
    }
    const total = prixMenu + livraison;
    document.getElementById('recap_nb').textContent = nb;
    document.getElementById('recap_prix_menu').textContent = prixMenu.toFixed(2);
    document.getElementById('recap_total').textContent = total.toFixed(2);
}
document.getElementById('nombre_personne').addEventListener('input', updatePrice);
document.getElementById('ville_livraison').addEventListener('input', updatePrice);
updatePrice();
</script>