<?php

require_once __DIR__ . '/includes/config.php'; 
require_once __DIR__ . '/includes/donnees.php';

$erreurs = [];
$plat_a_modifier = null;
$id_plat = null; 

$nom = '';
$ingredients = '';
$description = '';
$recette = '';
$type = '';
$region = '';
$nom_image = '';

$id_plat = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id_plat === null || $id_plat === false || $id_plat <= 0) {
  
    die("Erreur : ID du plat invalide ou manquant.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $recette = trim($_POST['recette'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $region = trim($_POST['region'] ?? '');
    $nom_image = trim($_POST['nom_image'] ?? ''); 

    if (empty($nom)) {
        $erreurs['nom'] = "Le nom du plat est obligatoire.";
    }
    if (empty($ingredients)) {
        $erreurs['ingredients'] = "Les ingrédients sont obligatoires.";
    }

    if (empty($erreurs)) {
        try {
            $plat_modifie = new Plat(
                nom: $nom,
                ingredients: $ingredients,
                description: $description ?: null,
                recette: $recette ?: null,
                type: $type ?: null,
                region: $region ?: null,
                nom_image: $nom_image ?: null,
                id: $id_plat 
            );

            $succes = $plat_modifie->modifier($pdo);

            if ($succes) {
                header('Location: liste_plats.php?ajout=succes'); 
                exit;
            } else {
                $erreurs['generale'] = "Une erreur technique est survenue lors de la modification du plat.";
            }

        } catch (PDOException $e) {
            $erreurs['generale'] = "Erreur base de données : " . $e->getMessage();
        } catch (Exception $e) {
            $erreurs['generale'] = "Erreur : " . $e->getMessage();
        }
    } else {
         $erreurs['generale'] = "Veuillez corriger les erreurs dans le formulaire.";
    }
  
} 



if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !empty($erreurs)) {
 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $plat_a_modifier = Plat::trouverParId($pdo, $id_plat);

        if ($plat_a_modifier === false) {
            die("Erreur : Plat non trouvé pour cet ID.");
        }

        $nom = $plat_a_modifier->nom;
        $ingredients = $plat_a_modifier->ingredients;
        $description = $plat_a_modifier->description ?? ''; 
        $recette = $plat_a_modifier->recette ?? '';
        $type = $plat_a_modifier->type ?? '';
        $region = $plat_a_modifier->region ?? '';
        $nom_image = $plat_a_modifier->nom_image ?? '';
    }

}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier : <?= htmlspecialchars($nom ?: 'Plat inconnu'); ?></title>
    <link rel="stylesheet" href="styles/css/style.css"> <style>
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; box-sizing: border-box; }
        textarea { min-height: 100px; resize: vertical; }
        .erreur { color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; }
        .erreur-champ { color: red; font-size: 0.9em; }
        button[type="submit"] { padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        button[type="submit"]:hover { background-color: #218838; }
    </style>
</head>
<body>

    <header>
    <h1><a href="index.php">Délices de Syrie - Modification</a></h1>

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
        <h2>Modifier le plat : "<?= htmlspecialchars($nom); ?>"</h2>

        <?php if (!empty($erreurs['generale'])): ?>
            <p class="erreur"><?php echo htmlspecialchars($erreurs['generale']); ?></p>
        <?php endif; ?>

        <form action="modifier_plat.php" method="post" novalidate>

            <input type="hidden" name="id" value="<?= htmlspecialchars($id_plat); ?>">

            <div class="form-group">
                <label for="nom">Nom du plat :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom); ?>" required>
                <?php if (!empty($erreurs['nom'])): ?>
                    <span class="erreur-champ"><?= htmlspecialchars($erreurs['nom']); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="ingredients">Ingrédients :</label>
                <textarea id="ingredients" name="ingredients" rows="4" required><?= htmlspecialchars($ingredients); ?></textarea>
                <?php if (!empty($erreurs['ingredients'])): ?>
                    <span class="erreur-champ"><?= htmlspecialchars($erreurs['ingredients']); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="description">Description (courte) :</label>
                <textarea id="description" name="description" rows="3"><?= htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-group">
                <label for="recette">Recette :</label>
                <textarea id="recette" name="recette" rows="6"><?= htmlspecialchars($recette); ?></textarea>
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
            </div>

            <div class="form-group">
                <label for="region">Région d'origine (optionnel) :</label>
                <input type="text" id="region" name="region" value="<?= htmlspecialchars($region); ?>">
            </div>

             <div class="form-group">
                <label for="nom_image">Nom fichier image (ex: monplat.jpg) :</label>
                <input type="text" id="nom_image" name="nom_image" value="<?= htmlspecialchars($nom_image); ?>">
                 </div>


            <div class="form-group">
                <button type="submit">Enregistrer les modifications</button>
                <a href="index.php" style="margin-left: 15px;">Annuler</a>
            </div>

        </form>

    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> - 22107485 - Projet TW3</p>
    </footer>

</body>
</html>
