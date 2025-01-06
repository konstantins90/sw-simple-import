
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- files
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `filename` VARCHAR(255) NOT NULL,
    `config_name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- file_status
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `file_status`;

CREATE TABLE `file_status`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `file_id` INTEGER NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `file_status_fi_568a7d` (`file_id`),
    CONSTRAINT `file_status_fk_568a7d`
        FOREIGN KEY (`file_id`)
        REFERENCES `files` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- import_history
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `import_history`;

CREATE TABLE `import_history`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `file_id` INTEGER NOT NULL,
    `imported_at` DATETIME NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `count_imported_products` INTEGER DEFAULT 0 NOT NULL,
    `count_errors` INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `import_history_fi_568a7d` (`file_id`),
    CONSTRAINT `import_history_fk_568a7d`
        FOREIGN KEY (`file_id`)
        REFERENCES `files` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
