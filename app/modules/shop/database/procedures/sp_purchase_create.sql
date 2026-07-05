DROP PROCEDURE IF EXISTS sp_purchase_create;

CREATE PROCEDURE `sp_purchase_create`(
    IN p_supplier_id INT,
    IN p_warehouse_id INT,
    IN p_description TEXT,
    IN p_status VARCHAR(50),
    IN p_payment VARCHAR(50),
    IN p_paid_amount DECIMAL(15,2),
    IN p_debt_amount DECIMAL(15,2),
    IN p_items JSON
)
BEGIN

    DECLARE v_purchase_id INT;
    DECLARE v_total_amount DECIMAL(15,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    /* Tính tổng tiền */
    SELECT
        COALESCE(SUM(purchase_price * quantity),0)
    INTO v_total_amount
    FROM JSON_TABLE(
        p_items,
        '$[*]'
        COLUMNS(
            purchase_price DECIMAL(15,2) PATH '$.purchase_price',
            quantity INT PATH '$.quantity'
        )
    ) jt;

    /* Purchase */
    INSERT INTO purchases(
        supplier_id,
        warehouse_id,
        description,
        status,
        payment,
        paid_amount,
        debt_amount,
        total_amount
    )
    VALUES(
        p_supplier_id,
        p_warehouse_id,
        p_description,
        p_status,
        p_payment,
        p_paid_amount,
        p_debt_amount,
        v_total_amount
    );

    SET v_purchase_id = LAST_INSERT_ID();

    /* Purchase Items */
    INSERT INTO purchase_items(
        purchase_id,
        product_id,
        purchase_price,
        order_price,
        quantity
    )
    SELECT
        v_purchase_id,
        product_id,
        purchase_price,
        order_price,
        quantity
    FROM JSON_TABLE(
        p_items,
        '$[*]'
        COLUMNS(
            product_id INT PATH '$.product_id',
            purchase_price DECIMAL(15,2) PATH '$.purchase_price',
            order_price DECIMAL(15,2) PATH '$.order_price',
            quantity INT PATH '$.quantity'
        )
    ) jt;

    /* Inventory */
    INSERT INTO inventories(
        purchase_id,
        product_id,
        purchase_price,
        order_price,
        quantity
    )
    SELECT
        v_purchase_id,
        product_id,
        purchase_price,
        order_price,
        quantity
    FROM JSON_TABLE(
        p_items,
        '$[*]'
        COLUMNS(
            product_id INT PATH '$.product_id',
            purchase_price DECIMAL(15,2) PATH '$.purchase_price',
            order_price DECIMAL(15,2) PATH '$.order_price',
            quantity INT PATH '$.quantity'
        )
    ) jt;

    COMMIT;

END