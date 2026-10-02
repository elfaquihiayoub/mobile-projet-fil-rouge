CREATE DATABASE gestion_bibliotheque;

USE gestion_bibliotheque;


CREATE TABLE adherent (
    id_adherent INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telephone VARCHAR(20)
);


-- =========================
-- TABLE : RAYON
-- =========================

CREATE TABLE rayon (
    id_rayon INT AUTO_INCREMENT PRIMARY KEY,
    nom_rayon VARCHAR(100) NOT NULL,
    emplacement VARCHAR(150)
);
CREATE TABLE exemplaire_livre (
    id_exemplaire INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(150) NOT NULL,
    isbn VARCHAR(20),
    etat VARCHAR(50) NOT NULL,

    id_rayon INT NOT NULL,

    CONSTRAINT fk_exemplaire_rayon
        FOREIGN KEY (id_rayon)
        REFERENCES rayon(id_rayon)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


CREATE TABLE emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    date_emprunt DATE NOT NULL,
    date_retour_prevue DATE NOT NULL,
    date_retour_reelle DATE,

    id_adherent INT NOT NULL,
    id_exemplaire INT NOT NULL,

    CONSTRAINT fk_emprunt_adherent
        FOREIGN KEY (id_adherent)
        REFERENCES adherent(id_adherent)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_emprunt_exemplaire
        FOREIGN KEY (id_exemplaire)
        REFERENCES exemplaire_livre(id_exemplaire)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);