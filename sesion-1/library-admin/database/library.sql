CREATE DATABASE IF NOT EXISTS gestion_bibliotheque CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_bibliotheque;

CREATE TABLE IF NOT EXISTS adherent (
    id_adherent INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS rayon (
    id_rayon INT AUTO_INCREMENT PRIMARY KEY,
    nom_rayon VARCHAR(100) NOT NULL,
    emplacement VARCHAR(150)
);

CREATE TABLE IF NOT EXISTS exemplaire_livre (
    id_exemplaire INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(150) NOT NULL,
    isbn VARCHAR(20),
    etat VARCHAR(50) NOT NULL,
    id_rayon INT NOT NULL,
    INDEX idx_exemplaire_rayon (id_rayon),
    CONSTRAINT fk_exemplaire_rayon FOREIGN KEY (id_rayon) REFERENCES rayon(id_rayon) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    date_emprunt DATE NOT NULL,
    date_retour_prevue DATE NOT NULL,
    date_retour_reelle DATE,
    id_adherent INT NOT NULL,
    id_exemplaire INT NOT NULL,
    INDEX idx_emprunt_adherent (id_adherent),
    INDEX idx_emprunt_exemplaire (id_exemplaire),
    INDEX idx_emprunt_dates (date_retour_prevue, date_retour_reelle),
    CONSTRAINT fk_emprunt_adherent FOREIGN KEY (id_adherent) REFERENCES adherent(id_adherent) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_emprunt_exemplaire FOREIGN KEY (id_exemplaire) REFERENCES exemplaire_livre(id_exemplaire) ON UPDATE CASCADE ON DELETE RESTRICT
);

INSERT INTO rayon (nom_rayon, emplacement) VALUES
('Litterature', 'Aile A'),
('Sciences', 'Aile B'),
('Histoire', 'Aile C');

INSERT INTO adherent (nom, prenom, email, telephone) VALUES
('Martin', 'Claire', 'claire.martin@example.com', '0600000001'),
('Bernard', 'Youssef', 'youssef.bernard@example.com', '0600000002');

INSERT INTO exemplaire_livre (titre, auteur, isbn, etat, id_rayon) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupery', '9782070612758', 'Bon', 1),
('Une breve histoire du temps', 'Stephen Hawking', '9782290059476', 'Bon', 2),
('Sapiens', 'Yuval Noah Harari', '9782226257017', 'Neuf', 3);
