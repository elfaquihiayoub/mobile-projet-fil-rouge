# Projet technique — Annuaire de podcasts

## Présentation du projet

Le projet consiste à concevoir un annuaire de podcasts administré depuis une
interface web. Le modèle métier s’organise autour des animateurs, des
thématiques et des épisodes. Une thématique permet de classer les épisodes ;
chaque épisode est animé par un animateur.

Le modèle de données et le prototype doivent être distingués : le MCD et le
script SQL décrivent les trois entités, tandis que le prototype PHP disponible
met principalement en œuvre la consultation et l’ajout de thématiques avec un
stockage JSON.

## Objectifs

- Organiser les épisodes par thématique.
- Associer chaque épisode à son animateur.
- Décrire les épisodes par leur titre, leur description, leur durée et leur
  date de publication.
- Fournir une interface d’administration pour consulter et gérer les données.
- Séparer progressivement la présentation, le traitement métier et l’accès
  aux données.

## Fonctionnalités prévues

- Gérer les animateurs et leurs coordonnées.
- Gérer les thématiques et leur description.
- Gérer les épisodes et leurs informations de publication.
- Associer chaque épisode à une thématique et à un animateur.
- Consulter les thématiques depuis l’interface.

Les quatre premières fonctions décrivent le périmètre fonctionnel visé par le
modèle. Dans le prototype de la version actuelle, seules la consultation et
la création de thématiques sont prises en charge par l’API ; la gestion CRUD
complète des animateurs et des épisodes n’est pas encore implémentée.

## Modèle de données

### Animateur

- `id_animateur` : identifiant.
- `nom` : nom de l’animateur.
- `prenom` : prénom de l’animateur.
- `email` : adresse électronique unique.

### Thématique

- `id_thematique` : identifiant.
- `Nom_thematique` : nom de la thématique dans le script SQL.
- `descreption_thematique` : description dans le script SQL.

### Épisode

- `id_episode` : identifiant.
- `titre` : titre de l’épisode.
- `descreption_episode` : description dans le script SQL.
- `duree_episode` : durée au format `TIME`.
- `date_publication` : date et heure de publication.
- `id_animateur` : référence vers l’animateur.
- `id_thematique` : référence vers la thématique.

### Relations et cardinalités

- Un animateur anime un ou plusieurs épisodes ; chaque épisode a un seul
  animateur.
- Une thématique peut être associée à aucun ou plusieurs épisodes ; chaque
  épisode appartient à une seule thématique.

Le MCD fourni affiche les identifiants sous forme d’UUID. Le script SQL
disponible déclare toutefois les identifiants comme des entiers
`AUTO_INCREMENT`. Ce choix doit être harmonisé entre le MCD, le script SQL et
les éventuelles données de l’application avant une mise en production.

## Technologies et architecture

### Interface

- HTML pour les pages d’administration.
- JavaScript pour charger et afficher les thématiques et envoyer les
  formulaires.
- Tailwind CSS chargé depuis un CDN pour la mise en forme du prototype.

### Backend

- PHP pour le point d’entrée API, les classes métier, le service et le DAO.
- Échanges HTTP avec des données au format JSON.
- L’API actuelle du prototype expose la consultation (`GET`) et la création
  (`POST`) de thématiques.

### Persistance

- Le prototype utilise `backend/data/thematique.json`.
- Le script SQL définit une base `Annuaire_de_podcasts` avec les tables
  `Animateur`, `thematique` et `episode`.
- Le schéma relationnel constitue le modèle de base de données ; le prototype
  JSON actuel ne persiste pas encore les trois entités dans MySQL/MariaDB.

### Organisation des couches

Dans le prototype, le parcours d’une requête de thématique est le suivant :

1. Le navigateur envoie une requête à `controllerThematique.php`.
2. Le contrôleur construit la réponse et délègue les opérations à
   `ThematiqueService`.
3. Le service appelle `ThematiqueDao`.
4. Le DAO lit ou met à jour le fichier JSON et hydrate des objets
   `Thematique`.

Le fichier `BaseService.php` existe dans la version examinée, mais sa classe
est vide et `ThematiqueService` n’en hérite pas. Il ne faut donc pas lui
attribuer de règles de validation partagées dans la description de
l’implémentation actuelle.

## Classes principales du prototype

- **`Thematique`** : encapsule l’identifiant, le nom et la description d’une
  thématique.
- **`ControllerThematique`** : reçoit les requêtes HTTP, appelle le service
  et sérialise les résultats.
- **`ThematiqueService`** : coordonne la consultation et la création des
  thématiques.
- **`ThematiqueDao`** : lit le fichier JSON, crée les enregistrements et
  transforme les lignes stockées en objets métier.
- **`BaseService`** : classe de base présente, mais encore vide dans cette
  version du projet.

## Parcours fonctionnel actuellement réalisé

1. L’interface demande la liste des thématiques à l’API.
2. Le contrôleur appelle le service, qui délègue la lecture au DAO.
3. Le DAO lit le fichier JSON et retourne les objets `Thematique`.
4. L’interface affiche le nom et la description des thématiques.
5. Le formulaire envoie une nouvelle thématique en JSON ; le contrôleur la
   transmet au service puis au DAO pour l’enregistrement.

## Conclusion et évolutions

Le projet définit un annuaire de podcasts dont le modèle comprend les
animateurs, les thématiques et les épisodes. Le prototype actuel valide la
consultation et la création de thématiques à travers une interface web, une
API PHP, un service, un DAO et un stockage JSON.

Les évolutions possibles sont d’ajouter les opérations de modification et de
suppression, de compléter les services et leurs validations, puis d’implémenter
la gestion des animateurs et des épisodes. Une étape ultérieure pourra relier
le prototype au schéma relationnel MySQL/MariaDB. Avant cette migration, il
faudra harmoniser les identifiants UUID du MCD avec les identifiants entiers
`AUTO_INCREMENT` du script SQL.
