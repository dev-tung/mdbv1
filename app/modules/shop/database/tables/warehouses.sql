DROP TABLE IF EXISTS warehouses;

CREATE TABLE warehouses (
    id INT NOT NULL AUTO_INCREMENT,

    code VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,

    address VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    description VARCHAR(255) DEFAULT NULL,

    status VARCHAR(20) NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    UNIQUE KEY uk_warehouse_code (code),

    INDEX idx_warehouse_name (name),
    INDEX idx_warehouse_status (status)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;