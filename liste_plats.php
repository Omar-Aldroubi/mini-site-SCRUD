<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/config.php'; 
require_once __DIR__ . '/includes/donnees.php'; 

$allowed_sort_columns = ['id', 'nom', 'type', 'region', 'date_ajout'];
$allowed_sort_directions = ['ASC', 'DESC'];
$sort_param_url = $_GET['tri'] ?? 'nom ASC'; 

$sort_column = 'nom';
$sort_direction = 'ASC';

$parts = explode(' ', trim($sort_param_url));
if (count($parts) === 2) {
    $potential_column = strtolower($parts[0]);
    $potential_direction = strtoupper($parts[1]);
    if (in_array($potential_column, $allowed_sort_columns) && in_array($potential_direction, $allowed_sort_directions)) {
        $sort_column = $potential_column;
        $sort_direction = $potential_direction;
    }
} elseif (count($parts) === 1 && in_array(strtolower($parts[0]), $allowed_sort_columns)) {
    $sort_column = strtolower($parts[0]);
}
$tri_fonction_param = $sort_column . ' ' . $sort_direction;



$plats = [];
$erreur_db = '';
$message_feedback = '';

if (isset($_GET['ajout']) && $_GET['ajout'] === 'succes') {
    $message_feedback = "Plat ajouté avec succès !";
} elseif (isset($_GET['modif']) && $_GET['modif'] === 'succes') {
    $message_feedback = "Plat modifié avec succès !";
} elseif (isset($_GET['delete'])) {
    if ($_GET['delete'] === 'succes') {
        $message_feedback = "Plat supprimé avec succès !";
    } elseif ($_GET['delete'] === 'echec') {
        $message_feedback = "Erreur lors de la tentative de suppression du plat.";
    } elseif ($_GET['delete'] === 'invalid') {
        $message_feedback = "Erreur : Requête de suppression invalide ou ID manquant.";
    }
}

try {
  
    $plats = Plat::listerTous($pdo, $tri_fonction_param);

} catch (PDOException $e) {
    $erreur_db = "Erreur lors de la récupération des plats.";
    error_log("Erreur DB dans index.php: " . $e->getMessage());
}

function generate_sort_link($current_col, $current_dir, $target_col, $display_text) {
    $new_dir = ($current_col === $target_col && $current_dir === 'ASC') ? 'DESC' : 'ASC';
    $link = "liste_plats.php?tri=" . urlencode($target_col . ' ' . $new_dir);
    $arrow = '';
    if ($current_col === $target_col) {
        $arrow = ($current_dir === 'ASC') ? ' ▲' : ' ▼';
    }
    return '<a href="' . $link . '">' . htmlspecialchars($display_text) . $arrow . '</a>';
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Délices de Syrie - Liste des Plats</title>
    <link rel="stylesheet" href="styles/css/style.css">
    <style>
        .message-feedback { padding: 10px 15px; margin-bottom: 15px; border-radius: 4px; border: 1px solid transparent; }
        .message-feedback.succes { background-color: #d4edda; color: #155724; border-color: #c3e6cb;}
        .message-feedback.erreur { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .erreur-db { padding: 10px 15px; margin-bottom: 15px; border-radius: 4px; background-color: #f5c6cb; color: #721c24; font-weight: bold; border: 1px solid darkred;}
        .bouton-supprimer { background: none; border: none; color: red; text-decoration: underline; cursor: pointer; padding: 0; margin: 0; font-size: inherit; font-family: inherit; display: inline; }
        .bouton-supprimer:hover { color: darkred; }
        .plat-actions { margin-top: 10px; }
        .plat-actions > *, .plat-actions form { margin-right: 10px; display: inline-block; }
        .sort-options { margin-bottom: 15px; padding: 10px; background-color: #f0f0f0; border-radius: 4px; }
        .sort-options strong { margin-right: 10px; }
        .sort-options a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .sort-options a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <header>
        <h1><a href="index.php">Délices de Syrie</a></h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="liste_plats.php">Liste des Plats</a></li>
                <li><a href="ajout_plat.php">Ajouter un plat</a></li>
                <li><a href="a_propos.php">À Propos</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Liste des Plats Syriens</h2>

        <?php if ($message_feedback): ?>
            <?php $feedback_class = (strpos($message_feedback, 'Erreur') === false) ? 'succes' : 'erreur'; ?>
            <p class="message-feedback <?= $feedback_class; ?>"><?php echo htmlspecialchars($message_feedback); ?></p>
        <?php endif; ?>
        <?php if ($erreur_db): ?>
            <p class="erreur-db">Une erreur interne est survenue. Veuillez réessayer plus tard.</p>
        <?php endif; ?>

        <div class="sort-options">
            <strong>Trier par :</strong>
            <?= generate_sort_link($sort_column, $sort_direction, 'nom', 'Nom'); ?> |
            <?= generate_sort_link($sort_column, $sort_direction, 'type', 'Type'); ?> |
            <?= generate_sort_link($sort_column, $sort_direction, 'date_ajout', 'Date Ajout'); ?> |
            <?= generate_sort_link($sort_column, $sort_direction, 'id', 'ID'); ?>
        </div>
        <hr>

        <?php if (!$erreur_db && empty($plats)): ?>
            <p>Aucun plat n'a été trouvé...</p>
            <p><a href="ajout_plat.php">Ajouter un nouveau</a> !</p>
        <?php elseif (!empty($plats)): ?>
            <section class="liste-plats">
                <?php foreach ($plats as $plat): ?>
                    <article class="plat-item" style="border-bottom: 1px solid #eee; margin-bottom: 15px; padding-bottom: 15px;">
                        <h3><?= htmlspecialchars($plat->nom ?? 'Nom inconnu'); ?></h3>
                        <?php if (!empty($plat->type)): ?>
                            <p><strong>Type :</strong> <?= htmlspecialchars($plat->type); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($plat->description)): ?>
                            <p><?= nl2br(htmlspecialchars($plat->description)); ?></p>
                        <?php endif; ?>
                        <div class="plat-actions">
                            <a href="details_plat.php?id=<?= htmlspecialchars($plat->id ?? 0); ?>" class="bouton">Détails</a>
                            <a href="modifier_plat.php?id=<?= htmlspecialchars($plat->id ?? 0); ?>" class="bouton bouton-modifier">Modifier</a>
                            <form action="supprimer_plat.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plat : \'<?= htmlspecialchars(addslashes($plat->nom ?? '')); ?>\' ?');">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($plat->id ?? 0); ?>">
                                <button type="submit" class="bouton-supprimer">Supprimer</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> - Délices de Syrie - Projet TW3</p>
    </footer>

</body>
</html>