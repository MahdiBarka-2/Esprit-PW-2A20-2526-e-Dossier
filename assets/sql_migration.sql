-- ============================================================
-- MIGRATION : Fonctionnalités avancées module Demande
-- À exécuter dans phpMyAdmin > onglet SQL
-- ============================================================

-- Feature 7 : Colonne priorité
ALTER TABLE demande 
  ADD COLUMN IF NOT EXISTS priorite ENUM('normale','urgente','critique') NOT NULL DEFAULT 'normale';

-- Feature 5 : Colonne label pour justification (nom du fichier lisible)
ALTER TABLE justification 
  ADD COLUMN IF NOT EXISTS label VARCHAR(150) DEFAULT NULL;

-- ============================================================
-- VÉRIFICATION
-- ============================================================
DESCRIBE demande;
DESCRIBE justification;
