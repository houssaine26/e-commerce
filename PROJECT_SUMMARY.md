# Rapport de Projet : Création d'un Site E-commerce avec Symfony

Ce document résume les quatre premières étapes de développement de notre application e-commerce. L'objectif est de démontrer une progression logique, allant de l'intégration de maquettes statiques à la mise en place d'un système de sécurité robuste et professionnel.

---

## Étape 1 : Fondations et Intégration du Design
**Objectif :** Mettre en place la structure de base de l'application et intégrer les templates HTML/CSS.

*   **Architecture Twig :** Utilisation de l'héritage de templates (`base.html.twig`). Nous avons découpé l'interface en **partials** (`_header.html.twig`, `_footer.html.twig`) pour favoriser la réutilisabilité et la maintenance.
*   **Asset Management :** Intégration des fichiers CSS et images. Nous avons configuré **AssetMapper** (nouveauté Symfony 6.3+) pour gérer les assets sans avoir besoin de Node.js, simplifiant ainsi l'environnement de développement.
*   **Routage Initial :** Création du `MainController` pour servir les premières pages statiques (Accueil, Login, Profil, Panier).

---

## Étape 2 : Modélisation des Données (Doctrine ORM)
**Objectif :** Passer d'un site statique à une application pilotée par les données.

*   **Entités et Relations :** Création des entités `Product` et `Category`.
    *   Mise en place d'une relation **ManyToOne** (un produit appartient à une catégorie).
    *   Utilisation de PHP 8 Attributes pour le mapping Doctrine.
*   **Migrations :** Utilisation de `DoctrineMigrationsBundle` pour versionner l'évolution de la base de données de manière sécurisée.
*   **Seeding :** Création d'une commande Symfony personnalisée (`app:seed-data`) pour remplir automatiquement la base de données avec des produits de test.

---

## Étape 3 : Dynamisation et Gestion du Panier
**Objectif :** Rendre le catalogue interactif et implémenter la logique métier du panier.

*   **Contrôleurs Dynamiques :** Utilisation des **Repositories** pour récupérer les données en base et les afficher via Twig (filtre par catégorie, détails produit).
*   **Système de Panier (Service Pattern) :** 
    *   Création d'un `CartHandler` pour centraliser la logique (Ajout, Suppression, Récupération).
    *   Utilisation de la **Session** pour stocker les articles du panier, permettant une persistance durant la visite de l'utilisateur.
    *   Injection de dépendances pour utiliser le service partout dans l'application.

---

## Étape 4 : Sécurité et Principes SOLID
**Objectif :** Sécuriser l'application, gérer les utilisateurs et appliquer les bonnes pratiques de programmation.

*   **Gestion des Utilisateurs :**
    *   Création de l'entité `User` implémentant `UserInterface` et `PasswordAuthenticatedUserInterface`.
    *   Configuration du `security.yaml` avec un `form_login` et un système de déconnexion.
*   **Inscription Professionnelle :**
    *   Formulaire utilisant `RepeatedType` (confirmation du mot de passe) et `PasswordType`.
    *   **Hachage des mots de passe :** Utilisation de `UserPasswordHasherInterface` pour garantir que les mots de passe ne sont jamais stockés en clair.
*   **Respect de SOLID :**
    *   Création d'un `RegistrationService`. Au lieu de mettre toute la logique dans le contrôleur, nous avons délégué la création de l'utilisateur à un service dédié. Cela respecte le **Single Responsibility Principle** (SRP).
*   **Contrôle d'Accès :** Sécurisation de la page `/profile` via `access_control`, forçant l'utilisateur à être authentifié (`ROLE_USER`) pour y accéder.

---

## Conclusion Technique
À ce stade, l'application dispose d'un cycle complet : un utilisateur peut s'inscrire, se connecter, naviguer dans un catalogue dynamique, ajouter des produits à son panier et consulter son profil sécurisé. Le code est structuré, découplé et prêt pour des évolutions futures (paiement, administration).
