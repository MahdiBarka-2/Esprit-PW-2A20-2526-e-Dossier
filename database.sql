CREATE DATABASE IF NOT EXISTS e_municipality;
USE e_municipality;

CREATE TABLE IF NOT EXISTS materiels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    etat VARCHAR(50) DEFAULT 'Disponible'
);

CREATE TABLE IF NOT EXISTS missions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL
);

-- Note: In a full app, a mission might have a relation to materiels (e.g., many-to-many or one-to-many).
-- A joint table: mission_materiels if multiple materiels per mission
CREATE TABLE IF NOT EXISTS mission_materiels (
    mission_id INT NOT NULL,
    materiel_id INT NOT NULL,
    PRIMARY KEY (mission_id, materiel_id),
    FOREIGN KEY (mission_id) REFERENCES missions(id) ON DELETE CASCADE,
    FOREIGN KEY (materiel_id) REFERENCES materiels(id) ON DELETE CASCADE
);
