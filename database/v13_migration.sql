-- ====================================================
-- SAV MIKEM V13 : Migration SQL pour PhpMyAdmin
-- À exécuter dans l'onglet "SQL" de PhpMyAdmin
-- ====================================================

-- 1. Ajouter les colonnes de traçabilité temporelle à la table missions
ALTER TABLE `missions`
    ADD COLUMN `started_at` TIMESTAMP NULL DEFAULT NULL AFTER `date_mission`,
    ADD COLUMN `work_finished_at` TIMESTAMP NULL DEFAULT NULL AFTER `started_at`,
    ADD COLUMN `submitted_at` TIMESTAMP NULL DEFAULT NULL AFTER `work_finished_at`,
    ADD COLUMN `validated_at` TIMESTAMP NULL DEFAULT NULL AFTER `submitted_at`;

-- 2. Étendre les statuts possibles (ajouter "soumis" et "a_modifier")
ALTER TABLE `missions`
    MODIFY COLUMN `statut` ENUM('en_attente', 'en_cours', 'en_pause', 'suspendue', 'soumis', 'a_modifier', 'terminee') DEFAULT 'en_attente';

-- 3. Ajouter les champs de validation à la table rapports
ALTER TABLE `rapports`
    ADD COLUMN `fiche_passage_path` VARCHAR(255) NULL DEFAULT NULL AFTER `actions_realisees`,
    ADD COLUMN `admin_notes` TEXT NULL DEFAULT NULL AFTER `fiche_passage_path`;

-- ====================================================
-- FIN DE LA MIGRATION V13
-- ====================================================
