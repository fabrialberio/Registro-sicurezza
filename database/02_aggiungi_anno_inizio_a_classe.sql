ALTER TABLE classe ADD COLUMN anno_inizio INT NOT NULL;
UPDATE classe SET anno_inizio = 2024;
