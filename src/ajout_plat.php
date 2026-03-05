<?php


require_once __DIR__ . '/includes/config.php'; 
require_once __DIR__ . '/includes/donnees.php'; 

$nom = '';
$ingredients = '';
$description = '';
$recette = '';
$type = '';
$region = '';

$erreurs = []; 
$succes_message = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $recette = trim($_POST['recette'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $region = trim($_POST['region'] ?? '');

    if (empty($nom)) {
        $erreurs['nom'] = "Le nom du plat est obligatoire.";
    }
    if (empty($ingredients)) {
        $erreurs['ingredients'] = "Les ingrédients sont obligatoires.";
    }

    if (empty($erreurs)) {
        try {
            $nouveauPlat = new Plat(
                nom: $nom,
                ingredients: $ingredients,
                description: $description,
                recette: $recette,
                type: $type,
                region: $region
            );

            $succes = $nouveauPlat->enregistrer($pdo);

            if ($succes) {
                header('Location: index.php?ajout=succes');
                exit;
            } else {
                $erreurs['generale'] = "Une erreur technique est survenue lors de l'ajout du plat.";
            }

        } catch (PDOException $e) {
             $erreurs['generale'] = "Erreur base de données : " . $e->getMessage();
        } catch (Exception $e) {
             $erreurs['generale'] = "Erreur : " . $e->getMessage();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Plat Syrien</title>
    <link rel="stylesheet" href="styles/css/style.css">
</head>
<body>

    <header>
        <h1 class="acceuil"><a href="index.php">Délices de Syrie</a></h1>        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="liste_plats.php">Liste des Plats</a></li>
                <li><a href="ajout_plat.php">Ajouter un plat</a></li>
                <li><a href="a_propos.php">À Propos</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Ajouter un nouveau plat syrien</h2>

        <?php if (!empty($erreurs['generale'])): ?>
            <p class="erreur"><?php echo htmlspecialchars($erreurs['generale']); ?></p>
        <?php endif; ?>

        <?php if ($succes_message):  ?>
            <p class="succes"><?php echo htmlspecialchars($succes_message); ?></p>
        <?php endif; ?>

        <form action="ajout_plat.php" method="post" novalidate>
            <?php ?>

            <div class="form-group">
                <label for="nom">Nom du plat :</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                <?php if (!empty($erreurs['nom'])): ?>
                    <span class="erreur-champ"><?php echo htmlspecialchars($erreurs['nom']); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="ingredients">Ingrédients :</label>
                <textarea id="ingredients" name="ingredients" rows="4" required><?php echo htmlspecialchars($ingredients); ?></textarea>
                <?php if (!empty($erreurs['ingredients'])): ?>
                    <span class="erreur-champ"><?php echo htmlspecialchars($erreurs['ingredients']); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="description">Description (courte) :</label>
                <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                 <?php ?>
            </div>

            <div class="form-group">
                <label for="recette">Recette :</label>
                <textarea id="recette" name="recette" rows="6"><?php echo htmlspecialchars($recette); ?></textarea>
                 <?php  ?>
            </div>

            <div class="form-group">
                <label for="type">Type de plat :</label>
                <select id="type" name="type">
                    <option value="" <?php if($type === '') echo 'selected'; ?>>-- Choisir --</option>
                    <option value="Entrée" <?php if($type === 'Entrée') echo 'selected'; ?>>Entrée</option>
                    <option value="Plat Principal" <?php if($type === 'Plat Principal') echo 'selected'; ?>>Plat Principal</option>
                    <option value="Dessert" <?php if($type === 'Dessert') echo 'selected'; ?>>Dessert</option>
                    <option value="Boisson" <?php if($type === 'Boisson') echo 'selected'; ?>>Boisson</option>
                    <option value="Autre" <?php if($type === 'Autre') echo 'selected'; ?>>Autre</option>
                </select>
                 <?php  ?>
            </div>

             <div class="form-group">
                <label for="region">Région d'origine (optionnel) :</label>
                <input type="text" id="region" name="region" value="<?php echo htmlspecialchars($region); ?>">
                 <?php  ?>
            </div>

            

            <div class="form-group">
                <button type="submit">Ajouter le plat</button>
            </div>

        </form>

    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> - Votre Nom/Numéro Étudiant - Projet TW3</p>
    </footer>

</body>
</html>
