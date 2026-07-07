DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;

CREATE TABLE orders (
    id INT NOT NULL AUTO_INCREMENT,

    customer_id INT NOT NULL,

    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    payment VARCHAR(20) NOT NULL DEFAULT 'unpaid',

    description VARCHAR(255) DEFAULT NULL,
    note TEXT,

    subtotal_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    discount_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    vat_rate DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    vat_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    paid_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    debt_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    created_by INT NOT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    INDEX idx_customer (customer_id),
    INDEX idx_status (status),

    CONSTRAINT fk_order_customer
        FOREIGN KEY (customer_id)
        REFERENCES customers(id),

    CONSTRAINT fk_order_user
        FOREIGN KEY (created_by)
        REFERENCES users(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;