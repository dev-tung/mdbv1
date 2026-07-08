DROP TABLE IF EXISTS purchase_items;

CREATE TABLE purchase_items (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    purchase_id INT NOT NULL,

    product_id INT UNSIGNED NOT NULL,

    product_name VARCHAR(255) NOT NULL,

    quantity INT UNSIGNED NOT NULL DEFAULT 1,

    purchase_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    selling_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    subtotal_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,

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

    CONSTRAINT fk_purchase_item_purchase
        FOREIGN KEY (purchase_id)
        REFERENCES purchases(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_purchase_item_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;