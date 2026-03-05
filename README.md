# 🇸🇾 Délices de Syrie - Mini Site CRUD

Ce dépôt contient le code source de mon mini-site web dédié à la gastronomie syrienne.

Il s'agit d'une application CRUD complète développée en PHP permettant de gérer une base de données de recettes et de plats.


---

## Fonctionnalités

Le site implémente les fonctionnalités standard d'administration de données (CRUD), ainsi qu'une option de tri dynamique :

* **Lecture (Read) :** Affichage de tous les plats sur la page d'accueil et vue détaillée par recette (ingrédients, instructions, origine).
* **Création (Create) :** Formulaire d'ajout de nouveaux plats avec validation des champs.
* **Mise à jour (Update) :** Formulaire pré-rempli pour modifier les informations d'un plat existant.
* **Suppression (Delete) :** Suppression d'un plat (avec demande de confirmation en JavaScript pour éviter les erreurs).
* **Complément - Tri dynamique :** La liste des plats peut être triée par Nom, Type, Date d'ajout ou ID, par ordre croissant ou décroissant, en cliquant sur les en-têtes.

## Stack Technique & Architecture

Contrairement à une architecture MVC stricte, ce projet utilise une approche **Orientée Objet** simple et adaptée à sa taille :

* **Backend :** PHP avec l'extension PDO.
* **Modèle de données :** Toute la logique métier et les requêtes SQL sont centralisées dans la classe `Plat` (`includes/donnees.php`).
* **Base de données :** MySQL / MariaDB.
* **Frontend :** HTML5 et CSS3 (utilisation de Flexbox pour une mise en page fluide).

### Sécurité mise en place
* Utilisation exclusive de **requêtes préparées (PDO)** pour empêcher les injections SQL.
* Traitement des affichages avec `htmlspecialchars()` pour prévenir les failles XSS.
* Les actions de modification et de suppression utilisent la méthode HTTP `POST`.

