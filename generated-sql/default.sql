
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
    `path` VARCHAR(255) NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `import_type` VARCHAR(50) DEFAULT 'create_update' NOT NULL,
    `product_status` VARCHAR(50),
    `prefix` VARCHAR(255),
    `marge` FLOAT,
    `exchange_rate` FLOAT DEFAULT 1,
    `preorder` INTEGER,
    `preorder_deadline` DATETIME,
    `preorder_delivery` DATETIME,
    `preorder_state` VARCHAR(255),
    `config_id` INTEGER NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `files_fi_ecb45f` (`config_id`),
    CONSTRAINT `files_fk_ecb45f`
        FOREIGN KEY (`config_id`)
        REFERENCES `config` (`id`)
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
    `log_file` VARCHAR(255),
    `log` JSON,
    `imported_at` DATETIME,
    `status` VARCHAR(50),
    `count_imported_products` INTEGER DEFAULT 0,
    `count_errors` INTEGER DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `import_history_fi_568a7d` (`file_id`),
    CONSTRAINT `import_history_fk_568a7d`
        FOREIGN KEY (`file_id`)
        REFERENCES `files` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- config
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `prefix` VARCHAR(255),
    `marge` FLOAT DEFAULT 1 NOT NULL,
    `exchange_rate` FLOAT DEFAULT 1,
    `mapping` TEXT,
    `csv_headers` TEXT,
    `mapping_properties` TEXT,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
