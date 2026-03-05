<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/donnees.php'; 

$plat = null; 
$plat_id = null;
$erreur_message = '';

if (!isset($_GET['id'])) {
    $erreur_message = "Aucun identifiant de plat fourni.";
} else {
    $plat_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($plat_id === false || $plat_id <= 0) {
        $erreur_message = "Identifiant de plat invalide.";
        $plat_id = null;
    }
}

if ($plat_id !== null) {
    try {
        $plat = Plat::trouverParId($pdo, $plat_id);

        if ($plat === false) {
            $erreur_message = "Plat non trouvé pour l'identifiant $plat_id.";
            $plat = null; 
        }
    } catch (PDOException $e) {
        $erreur_message = "Erreur lors de la récupération des détails du plat : " . $e->getMessage();
         $plat = null;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Plat - Délices de Syrie</title>
    <link rel="stylesheet" href="styles/css/style.css">
</head>
<body>

    <header>
        <h1>Délices de Syrie</h1>
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
        
        <?php if ($plat): ?>
            <script>document.title = "<?php echo htmlspecialchars($plat->nom); ?> - Délices de Syrie";</script>
            <h2>Détails du plat : <?php echo htmlspecialchars($plat->nom); ?></h2>
        <?php else: ?>
             <h2>Détails du plat</h2>
        <?php endif; ?>


       

        <?php if ($erreur_message): ?>
            
            <p class="erreur"><?php echo htmlspecialchars($erreur_message); ?></p>
            <p><a href="index.php">Retour à la liste</a></p>

        <?php elseif ($plat):  ?>

            <article class="plat-detail">
                <?php ?>
                <?php if ($plat->nom_image): ?>
                    <div class="plat-image">
                        
                        <img src="images/<?php echo htmlspecialchars($plat->nom_image); ?>" alt="Image de <?php echo htmlspecialchars($plat->nom); ?>">
                        <p><small>(Fichier : <?php echo htmlspecialchars($plat->nom_image); ?>)</small></p>
                    </div>
                <?php else: ?>
                    <p><small>(Aucune image fournie)</small></p>
                <?php endif; ?>

                <?php if ($plat->description): ?>
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($plat->description)); ?></p>
                <?php endif; ?>

                <h3>Ingrédients</h3>
                <p><?php echo nl2br(htmlspecialchars($plat->ingredients)); ?></p>

                <?php if ($plat->recette): ?>
                    <h3>Recette</h3>
                    <p><?php echo nl2br(htmlspecialchars($plat->recette)); ?></p>
                <?php endif; ?>

                <dl class="plat-meta">
                    <?php if ($plat->type): ?>
                        <dt>Type :</dt>
                        <dd><?php echo htmlspecialchars($plat->type); ?></dd>
                    <?php endif; ?>

                    <?php if ($plat->region): ?>
                        <dt>Région :</dt>
                        <dd><?php echo htmlspecialchars($plat->region); ?></dd>
                    <?php endif; ?>

                    <?php if ($plat->date_ajout): ?>
                        <dt>Ajouté le :</dt>
                        
                        <dd><?php echo date('d/m/Y à H:i', strtotime($plat->date_ajout)); ?></dd>
                    <?php endif; ?>
                </dl>

                <div class="plat-actions">
                    <a href="index.php" class="bouton">Retour à la liste</a>
                    <a href="edit_plat.php?id=<?php echo $plat->id; ?>" class="bouton bouton-modifier">Modifier ce plat</a>
                    <a href="supprimer_plat.php?id=<?php echo $plat->id; ?>"
                       class="bouton bouton-supprimer"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer le plat \'<?php echo htmlspecialchars(addslashes($plat->nom)); ?>\' ?');">
                       Supprimer ce plat
                    </a>
                </div>

            </article>

        <?php else: ?>
            <p>Le plat demandé ne peut pas être affiché.</p>
            <p><a href="index.php">Retour à la liste</a></p>
        <?php endif; ?>

    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> - Votre Nom/Numéro Étudiant - Projet TW3</p>
    </footer>

</body>
</html>
