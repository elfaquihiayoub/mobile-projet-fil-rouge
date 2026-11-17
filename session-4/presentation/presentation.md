---
marp: true
theme: default
size: 16:9
paginate: true
style: |
  section {
    font-family: "Aptos", "Segoe UI", sans-serif;
    background: #f8fafc;
    color: #172033;
    padding: 48px 64px;
  }
  h1, h2 {
    color: #243b6b;
  }
  h1 {
    font-size: 2.4em;
  }
  h2 {
    font-size: 1.8em;
    margin-bottom: 0.7em;
  }
  ul {
    font-size: 1.25em;
    line-height: 1.65;
  }
  section.title-slide {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    gap: 12px;
    padding: 32px 48px 40px;
  }
  section.title-slide .brand-row {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 28px;
    width: 100%;
    margin: 8px 0 18px;
  }
  section.title-slide .brand-row img {
    max-height: 88px;
    width: auto;
    object-fit: contain;
    filter: drop-shadow(0 3px 8px rgba(36, 59, 107, 0.12));
  }
  section.image-only {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 24px;
  }
  section.image-only img {
    max-width: 96%;
    max-height: 78%;
    object-fit: contain;
  }
---

<!-- _class: title-slide -->

# Rapport de projet



### Projet fil rouge · Gestion de bibliothèque  


El faquihi Ayoub
 · Encadrant : Fouad ESSARAJ
<div class="brand-row">
  <img src="ofppt.png" alt="Logo OFPPT" />
  <img src="solicode.png" alt="Logo SOLICODE" />
</div>

---

## Remerciements

---

## Introduction

---

## Contexte du projet

<!-- _class: image-only -->

![Diagramme de contexte de la gestion de bibliothèque](diagrams/context-bibliotheque.svg)

---



## Cahier des charges

---
## Methode de travail
---
<!-- _class: image-only -->

## Scrum

![Méthode Scrum](diagrams/scrum.jpg)

---
<!-- _class: image-only -->

## Processus 2TUP

![Processus 2TUP](diagrams/processus-2tup.jpg)

---
<!-- _class: image-only -->

## Design Thinking


![Design Thinking width:900](diagrams/design%20thinking.png)

---

## Planification

---

## Branche fonctionnelle

---

## Carte d'empathie

---

## Définition du problème

---

## Idéation

---

## Diagramme de cas d'utilisation

<!-- _class: image-only -->

![Cas d’utilisation de l’application de bibliothèque](diagrams/use-cases-bibliotheque.svg)

---

## Stack technologique · Projet technique

- **Backend** · PHP
- **Frontend** · HTML · JavaScript · Tailwind CSS
- **API** · HTTP · JSON
- **Persistance du prototype** · fichier JSON
- **Base relationnelle décrite par le modèle** · MySQL / MariaDB

---

## Architecture · Projet technique

**Interface HTML / JavaScript**

↓ Requêtes HTTP / réponses JSON

**ControllerThematique**

↓

**ThematiqueService**

↓

**ThematiqueDao** → **thematique.json**

`BaseService` existe dans le dossier, mais il est vide et n’est pas utilisé par
`ThematiqueService`.

---

## Prototype · Projet technique

- **Domaine cible** · Animateurs · Thématiques · Épisodes
- **API réalisée** · Consultation et création des thématiques
- **Données utilisées** · Nom et description d’une thématique
- **Persistance actuelle** · Fichier JSON
- Le MCD et le script SQL décrivent les trois entités ; le prototype ne les
  gère pas encore toutes.

---

## Relations · Projet technique

- **Animateur** · anime un ou plusieurs épisodes
- **Épisode** · est animé par un seul animateur
- **Thématique** · peut regrouper zéro ou plusieurs épisodes
- **Épisode** · appartient à une seule thématique

---

## Structure technique · Projet technique

- `controllerThematique.php` reçoit la requête.
- `ThematiqueService` coordonne l’opération et appelle `ThematiqueDao`.
- Le DAO lit et écrit les données JSON, puis retourne des objets `Thematique`.
- L’API actuelle expose `GET` et `POST` pour les thématiques.

---

<!-- _class: image-only -->


<!-- _class: image-only -->

## Diagramme de classes · Projet fil rouge

![Diagramme de classes de la gestion de bibliothèque width:900](diagrams/classes-bibliotheque.svg)

---

## Maquettes

---

## Interfaces

---

## Conclusion

- Le projet fil rouge porte sur la gestion de bibliothèque.
- Le projet technique porte sur un annuaire de podcasts.
- Le prototype podcasts valide la consultation et la création de thématiques
  avec une API PHP et un stockage JSON.
