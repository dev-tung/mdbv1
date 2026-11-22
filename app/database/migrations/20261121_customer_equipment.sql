CREATE TABLE IF NOT EXISTS customer_equipment (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    equipment_type ENUM('racket', 'shoe', 'stringing') NOT NULL,
    customer_id INT UNSIGNED NOT NULL,
    parent_id INT UNSIGNED NULL,
    parent_type ENUM('racket', 'shoe', 'stringing') NULL,
    brand VARCHAR(100) NULL,
    model VARCHAR(150) NULL,
    size VARCHAR(20) NULL,
    string_type VARCHAR(100) NULL,
    tension VARCHAR(40) NULL,
    strung_at DATE NULL,
    note VARCHAR(1000) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id, equipment_type),
    KEY idx_customer_equipment_customer_type (customer_id, equipment_type),
    KEY idx_customer_equipment_parent (parent_id, parent_type),
    CONSTRAINT fk_customer_equipment_customer
        FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE,
    CONSTRAINT fk_customer_equipment_parent
        FOREIGN KEY (parent_id, parent_type)
        REFERENCES customer_equipment (id, equipment_type) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP PROCEDURE IF EXISTS migrate_customer_equipment;

DELIMITER $$
CREATE PROCEDURE migrate_customer_equipment()
BEGIN
    IF EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
            AND table_name = 'customer_rackets'
    ) THEN
        INSERT IGNORE INTO customer_equipment (
            id, equipment_type, customer_id, brand, model, note, created_at, updated_at
        )
        SELECT id, 'racket', customer_id, brand, model, note, created_at, updated_at
        FROM customer_rackets;
    END IF;

    IF EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
            AND table_name = 'customer_shoes'
    ) THEN
        INSERT IGNORE INTO customer_equipment (
            id, equipment_type, customer_id, brand, model, size, note, created_at, updated_at
        )
        SELECT id, 'shoe', customer_id, brand, model, size, note, created_at, updated_at
        FROM customer_shoes;
    END IF;

    IF EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
            AND table_name = 'customer_stringing_records'
    ) THEN
        INSERT IGNORE INTO customer_equipment (
            id, equipment_type, customer_id, parent_id, parent_type,
            string_type, tension, strung_at, note, created_at
        )
        SELECT
            s.id, 'stringing', r.customer_id, r.id, 'racket',
            s.string_type, s.tension, s.strung_at, s.note, s.created_at
        FROM customer_stringing_records s
        INNER JOIN customer_rackets r ON r.id = s.racket_id;
    END IF;

    DROP TABLE IF EXISTS customer_stringing_records;
    DROP TABLE IF EXISTS customer_shoes;
    DROP TABLE IF EXISTS customer_rackets;
END$$

CALL migrate_customer_equipment()$$
DROP PROCEDURE migrate_customer_equipment$$
DELIMITER ;
