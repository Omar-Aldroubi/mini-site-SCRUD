<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Délices de Syrie</title>
    <link rel="stylesheet" href="styles/css/style.css"> </head>
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

    <main style="padding: 20px; text-align: center;"> <?php  ?>
        <h2>Bienvenue sur le site des Délices de Syrie !</h2>

        <p>Ce mini-site vous permet de gérer une collection de recettes et plats syriens.</p>
        <p>Utilisez la navigation ci-dessus pour explorer les différentes sections.</p>

        <p style="margin-top: 30px;">
            <a href="liste_plats.php" style="font-size: 1.2em; padding: 10px 20px; background-color:#007bff; color:white; border-radius:5px; text-decoration:none;">Voir tous les plats</a>
        </p>

    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> - Délices de Syrie - Projet TW3</p>
    </footer>

</body>
</html>