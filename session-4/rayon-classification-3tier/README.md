# Rayon - architecture 3 tiers

Cette version autonome conserve l'entite `Rayon`, l'interface et le stockage JSON du projet d'origine. Elle ajoute les operations de modification et de suppression dans un dossier separe, sans modifier `rayon-classification-v3`.

## Structure

- `frontend/`: interface du module et appels HTTP vers l'API.
- `backend/api/controllers/controllerRayon.php`: couche presentation, traite les requetes GET, POST, PUT et DELETE.
- `backend/services/BaseService.php`: validations et normalisation communes.
- `backend/services/RayonService.php`: regles metier du CRUD; herite de `BaseService`.
- `backend/dao/RayonDAO.php`: lecture et ecriture des donnees JSON.
- `backend/classes/Rayon.php`: entite et acces encapsule a ses proprietes.
- `backend/data/rayon.json`: stockage persistant utilise par ce module.
- `backend/tests/RayonServiceTest.php`: test du service et du DAO avec un fichier JSON temporaire.

## Lancer

Depuis ce dossier, demarrer le serveur PHP integre:

```powershell
php -S 127.0.0.1:8000 -t .
```

Ouvrir ensuite `http://127.0.0.1:8000/frontend/`. Les donnees utilisees sont celles de `backend/data/rayon.json`.

## Tester

```powershell
php backend/tests/RayonServiceTest.php
```

L'API accepte `GET` et `POST` sur `/backend/api/controllers/controllerRayon.php`. `PUT` et `DELETE` prennent l'identifiant avec `?id=<id>`; les requetes POST et PUT utilisent un objet JSON avec `nom` et `emplacement`.
