# Library Management System - Admin Dashboard

Application PHP MVC simple pour gerer une bibliotheque: tableau de bord, adherents, rayons, livres, emprunts et retours.

## Technologies

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript vanilla
- PDO

## Installation

1. Copier le dossier `library-admin` dans votre serveur local (`htdocs`, `www` ou equivalent).
2. Creer/importer la base avec `database/library.sql`.
3. Modifier les identifiants dans `config/database.php` si necessaire.
4. Demarrer Apache et MySQL avec XAMPP, Laragon ou WAMP.
5. Ouvrir `http://localhost/library-admin/public/`.

## Architecture

- `app/controllers`: reception des requetes, validation, redirection.
- `app/models`: acces aux donnees avec PDO et requetes preparees.
- `app/views`: pages HTML et composants de layout.
- `config`: configuration de la connexion MySQL.
- `public`: point d'entree, assets CSS et JavaScript.
- `database`: schema SQL.

## Fonctionnalites

- Tableau de bord avec statistiques issues de MySQL.
- CRUD complet des adherents.
- CRUD complet des rayons.
- CRUD complet des livres avec relation dynamique vers les rayons.
- Creation et consultation des emprunts.
- Enregistrement des retours depuis les emprunts actifs.
- Protection CSRF, echappement HTML, validation serveur et confirmations de suppression.
