CREATE TABLE `argomento`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `titolo` TEXT NOT NULL
);
CREATE TABLE `argomento_svolto`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_lezione` BIGINT UNSIGNED NOT NULL,
    `argomento` TEXT NOT NULL
);
CREATE TABLE `docente`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `cognome` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
);
CREATE TABLE `permessi`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_docente` BIGINT UNSIGNED NOT NULL,
    `amministratore` BOOLEAN NOT NULL
);
CREATE TABLE `classe`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `anno` INT NOT NULL,
    `sezione` VARCHAR(15) NOT NULL
);
CREATE TABLE `studente`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `cognome` VARCHAR(255) NOT NULL,
    `id_classe` BIGINT UNSIGNED NOT NULL
);
CREATE TABLE `lezione`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_docente` BIGINT UNSIGNED NOT NULL,
    `id_classe` BIGINT UNSIGNED NOT NULL,
    `ora_inizio` TIME NOT NULL,
    `ora_fine` TIME NOT NULL,
    `data` DATE NOT NULL,
    `aggiunta` DATETIME NOT NULL
);
CREATE TABLE `presenze`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_studente` BIGINT UNSIGNED NOT NULL,
    `id_lezione` BIGINT UNSIGNED NOT NULL,
    `presente` TINYINT(1) NOT NULL
);

ALTER TABLE
    `argomento_svolto` ADD CONSTRAINT `argomento_svolto_id_lezione_foreign` FOREIGN KEY(`id_lezione`) REFERENCES `lezione`(`id`);
ALTER TABLE
    `permessi` ADD CONSTRAINT `permessi_id_docente_foreign` FOREIGN KEY(`id_docente`) REFERENCES `docente`(`id`);
ALTER TABLE
    `lezione` ADD CONSTRAINT `lezione_id_docente_foreign` FOREIGN KEY(`id_docente`) REFERENCES `docente`(`id`);
ALTER TABLE
    `lezione` ADD CONSTRAINT `lezione_id_classe_foreign` FOREIGN KEY(`id_classe`) REFERENCES `classe`(`id`);
ALTER TABLE
    `studente` ADD CONSTRAINT `studente_id_classe_foreign` FOREIGN KEY(`id_classe`) REFERENCES `classe`(`id`);
ALTER TABLE
    `presenze` ADD CONSTRAINT `presenze_id_studente_foreign` FOREIGN KEY(`id_studente`) REFERENCES `studente`(`id`);
ALTER TABLE
    `presenze` ADD CONSTRAINT `presenze_id_lezione_foreign` FOREIGN KEY(`id_lezione`) REFERENCES `lezione`(`id`);