ALTER TABLE classe ADD COLUMN anno_inizio INT NOT NULL;
UPDATE classe SET anno_inizio = 2024;

UPDATE classe SET sezione = CONCAT(anno, sezione);
ALTER TABLE classe DROP COLUMN anno;
