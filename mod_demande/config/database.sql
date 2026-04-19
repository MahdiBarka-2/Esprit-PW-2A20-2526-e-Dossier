CREATE DATABASE IF NOT EXISTS gestion_demandes
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gestion_demandes;

CREATE TABLE IF NOT EXISTS categorie (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS demande (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur  VARCHAR(150) NOT NULL,
    email        VARCHAR(150) NOT NULL,
    categorie_id INT NOT NULL,
    statut       ENUM('en_attente','approuvee','rejetee') DEFAULT 'en_attente',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS justification (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    demande_id  INT NOT NULL,
    document    VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY (demande_id) REFERENCES demande(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO categorie (nom, description) VALUES
  ('Logement',   'Demande de logement universitaire'),
  ('Bourse',     'Demande de bourse d\'études'),
  ('Carte',      'Demande de carte étudiant'),
  ('Certificat', 'Demande de certificat de scolarité');
