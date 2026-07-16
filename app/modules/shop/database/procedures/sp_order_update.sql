DROP PROCEDURE IF EXISTS sp_order_update;

CREATE PROCEDURE sp_order_update (
    IN p_id INT,
    IN p_customer_id INT,
    IN p_description VARCHAR(255),
    IN p_note TEXT,
    IN p_status VARCHAR(20),
    IN p_payment VARCHAR(20),
    IN p_subtotal_amount DECIMAL(15,2),
    IN p_vat_rate DECIMAL(5,2),
    IN p_vat_amount DECIMAL(15,2),
    IN p_total_amount DECIMAL(15,2),
    IN p_paid_amount DECIMAL(15,2),
    IN p_debt_amount DECIMAL(15,2),
    IN p_items JSON
)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    /* =====================================
       CHECK ORDER
    ===================================== */

    IF NOT EXISTS (
        SELECT 1
        FROM orders
        WHERE id = p_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Đơn hàng không tồn tại';
    END IF;

    /* =====================================
       RESTORE INVENTORY
    ===================================== */

    UPDATE inventories i
    INNER JOIN order_items oi
        ON oi.purchase_id = i.purchase_id
        AND oi.product_id = i.product_id
    SET i.quantity = i.quantity + oi.quantity
    WHERE oi.order_id = p_id;

    /* =====================================
       CHECK INVENTORY
    ===================================== */

    IF EXISTS (
        SELECT 1
        FROM JSON_TABLE(
            p_items,
            '$[*]' COLUMNS (
                purchase_id INT PATH '$.purchase_id',
                product_id INT PATH '$.product_id',
                quantity INT PATH '$.quantity'
            )
        ) jt
        LEFT JOIN inventories i
            ON i.purchase_id = jt.purchase_id
            AND i.product_id = jt.product_id
        WHERE
            i.id IS NULL
            OR i.quantity < jt.quantity
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Số lượng tồn kho không đủ';
    END IF;

    /* =====================================
       UPDATE ORDER
    ===================================== */

    UPDATE orders
    SET
        customer_id = p_customer_id,
        description = p_description,
        note = p_note,
        status = p_status,
        payment = p_payment,
        subtotal_amount = p_subtotal_amount,
        vat_rate = p_vat_rate,
        vat_amount = p_vat_amount,
        total_amount = p_total_amount,
        paid_amount = p_paid_amount,
        debt_amount = p_debt_amount
    WHERE id = p_id;

    /* =====================================
       DELETE OLD ITEMS
    ===================================== */

    DELETE FROM order_items
    WHERE order_id = p_id;

    /* =====================================
       INSERT NEW ITEMS
    ===================================== */

    INSERT INTO order_items (
        order_id,
        purchase_id,
        product_id,
        product_name,
        quantity,
        selling_price,
        subtotal_amount,
        is_gift,
        vat_rate,
        vat_amount,
        total_amount
    )
    SELECT
        p_id,
        purchase_id,
        product_id,
        product_name,
        quantity,
        selling_price,
        subtotal_amount,
        is_gift,
        vat_rate,
        vat_amount,
        total_amount
    FROM JSON_TABLE(
        p_items,
        '$[*]' COLUMNS (
            purchase_id INT PATH '$.purchase_id',
            product_id INT PATH '$.product_id',
            product_name VARCHAR(255) PATH '$.product_name',
            quantity INT PATH '$.quantity',
            selling_price DECIMAL(15,2) PATH '$.selling_price',
            subtotal_amount DECIMAL(15,2) PATH '$.subtotal_amount',
            is_gift TINYINT PATH '$.is_gift',
            vat_rate DECIMAL(5,2) PATH '$.vat_rate',
            vat_amount DECIMAL(15,2) PATH '$.vat_amount',
            total_amount DECIMAL(15,2) PATH '$.total_amount'
        )
    ) jt;

    /* =====================================
       UPDATE INVENTORY
    ===================================== */

    UPDATE inventories i
    INNER JOIN (
        SELECT
            purchase_id,
            product_id,
            SUM(quantity) AS quantity
        FROM JSON_TABLE(
            p_items,
            '$[*]' COLUMNS (
                purchase_id INT PATH '$.purchase_id',
                product_id INT PATH '$.product_id',
                quantity INT PATH '$.quantity'
            )
        ) jt
        GROUP BY purchase_id, product_id
    ) x
        ON x.purchase_id = i.purchase_id
        AND x.product_id = i.product_id
    SET i.quantity = i.quantity - x.quantity;

    COMMIT;

    SELECT p_id AS id;

END;