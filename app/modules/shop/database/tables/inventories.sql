DROP TABLE IF EXISTS inventories;

CREATE TABLE inventories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    purchase_id INT NOT NULL,
    product_id INT UNSIGNED NOT NULL,

    purchase_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    selling_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    quantity INT UNSIGNED NOT NULL DEFAULT 0,

    vat_rate DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    vat_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total_amount_with_vat DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    INDEX idx_purchase (purchase_id),
    INDEX idx_product (product_id),

    CONSTRAINT fk_inventory_purchase
        FOREIGN KEY (purchase_id)
        REFERENCES purchases(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_inventory_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;