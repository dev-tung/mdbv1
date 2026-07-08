DROP TABLE IF EXISTS purchases;

CREATE TABLE purchases (
    id INT NOT NULL AUTO_INCREMENT,
    warehouse_id INT NOT NULL,
    supplier_id INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    payment VARCHAR(20) NOT NULL DEFAULT 'unpaid',
    description VARCHAR(255) DEFAULT NULL,
    note TEXT,
    subtotal_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    vat_rate DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
    vat_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    paid_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    debt_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    created_by INT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_supplier (supplier_id),
    INDEX idx_warehouse (warehouse_id),
    INDEX idx_status (status),
    CONSTRAINT fk_purchase_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers (id),
    CONSTRAINT fk_purchase_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses (id),
    CONSTRAINT fk_purchase_user FOREIGN KEY (created_by) REFERENCES users (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
