<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À Propos - Délices de Syrie</title>
    <link rel="stylesheet" href="styles/css/style.css"> <style>
        .info-etudiant, .fonctionnalites, .remarques {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h3 {
            margin-top: 0;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
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
        <h2>À Propos de ce Mini-Site</h2>

        <section class="info-etudiant">
            <h3>Informations Étudiant</h3>
            <ul>
                <li><strong>Numéro :</strong> 22107485</li>
                <li><strong>Nom :</strong> ALDROUBI</li>
                <li><strong>Prénom :</strong> Mohamad Omar</li>
                <li><strong>Groupe de TP :</strong> Groupe 4A</li>
            </ul>
        </section>

        <section class="fonctionnalites">
            <h3>Fonctionnalités Réalisées</h3>
            <p>Ce mini-site implémente les fonctionnalités attendues pour la gestion d'une collection de plats syriens :</p>
            <ul>
              <li>Fonctionnalités principales (CRUD) :
*Liste : Affichage de tous les plats enregistrés dans la base de données sur la page principale .
*Détails : Affichage des informations complètes d'un plat spécifique sur une page dédiée (`details_plat.php`).
*Ajout : Formulaire permettant d'ajouter un nouveau plat à la collection (`ajouter_plat.php`).
*Modification : Formulaire pré-rempli permettant de modifier les informations d'un plat existant (`modifier_plat.php`).
*Suppression : Possibilité de supprimer un plat de la liste, avec une demande de confirmation JavaScript (`supprimer_plat.php` via un formulaire POST).
</li>
            </ul>

            <h4> Complément Réalisé :</h4>
            <ul>
                <li> Tri dynamique de la liste des plats :** L'utilisateur peut choisir de trier la liste principale des plats selon différents critères (Nom, Type, Date d'Ajout, ID) et dans les deux sens (Ascendant ▲ / Descendant ▼). Le critère de tri actif est indiqué.
                </li>
            </ul>
             
        </section>

        <section class="remarques">
            <h3>Remarques Utiles</h3>

            <p>
                Ce projet a été une bonne opportunité de mettre en pratique les concepts de développement web côté serveur avec PHP et MySQL vus en cours et en TP. L'implémentation du modèle CRUD complet ainsi que du complément de tri dynamique a permis de bien appréhender le cycle de vie d'une donnée dans une application web simple.
            </p>
            <p>
                Quelques défis techniques ont été rencontrés, notamment lors de la mise en place de la récupération des données dans les objets PHP (interaction entre le constructeur de la classe et le mode `PDO::FETCH_CLASS`) et lors de l'implémentation correcte de la logique de tri dynamique passé via l'URL. Ces points ont nécessité un débogage attentif mais ont permis de mieux comprendre le fonctionnement interne de PDO et la gestion des paramètres GET.
            </p>
             <p>
                Le choix s'est porté sur l'implémentation du tri comme fonctionnalité complémentaire. La validation des paramètres de tri a été mise en place pour éviter les injections SQL potentielles. Les actions modifiant les données (Ajout, Modification, Suppression) utilisent la méthode POST pour plus de sécurité, et une confirmation JavaScript a été ajoutée pour la suppression.
            </p>
            <p>
                 </p>
            
        </section>

    </main>

    <footer>
         <p>&copy; <?php echo date('Y'); ?> - - Projet TW3</p>
    </footer>

</body>
</html>
