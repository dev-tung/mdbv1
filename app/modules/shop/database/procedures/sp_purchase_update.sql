DROP PROCEDURE IF EXISTS sp_purchase_update;

CREATE PROCEDURE `sp_purchase_update`(
    IN p_purchase_id INT,
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

    DECLARE v_total_amount DECIMAL(15,2);
    DECLARE v_vat_amount DECIMAL(15,2);
    DECLARE v_total_amount_with_vat DECIMAL(15,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    /* =========================
       TOTAL
    ========================= */

    SELECT
        COALESCE(SUM(total_amount), 0),
        COALESCE(SUM(vat_amount), 0),
        COALESCE(SUM(total_amount_with_vat), 0)
    INTO
        v_total_amount,
        v_vat_amount,
        v_total_amount_with_vat
    FROM JSON_TABLE(
        p_items,
        '$[*]'
        COLUMNS(
            total_amount DECIMAL(15,2) PATH '$.total_amount',
            vat_amount DECIMAL(15,2) PATH '$.vat_amount',
            total_amount_with_vat DECIMAL(15,2) PATH '$.total_amount_with_vat'
        )
    ) jt;

    /* =========================
       PURCHASE
    ========================= */

    UPDATE purchases
    SET
        supplier_id            = p_supplier_id,
        warehouse_id           = p_warehouse_id,
        description            = p_description,
        status                 = p_status,
        payment                = p_payment,
        paid_amount            = p_paid_amount,
        debt_amount            = p_debt_amount,
        total_amount           = v_total_amount,
        vat_amount             = v_vat_amount,
        total_amount_with_vat  = v_total_amount_with_vat,
        updated_at             = NOW()
    WHERE id = p_purchase_id;

    /* =========================
       PURCHASE ITEMS
    ========================= */

    DELETE
    FROM purchase_items
    WHERE purchase_id = p_purchase_id;

    INSERT INTO purchase_items(
        purchase_id,
        product_id,
        purchase_price,
        order_price,
        quantity,
        vat_rate,
        vat_amount,
        total_amount,
        total_amount_with_vat
    )
    SELECT
        p_purchase_id,
        product_id,
        purchase_price,
        order_price,
        quantity,
        vat_rate,
        vat_amount,
        total_amount,
        total_amount_with_vat
    FROM JSON_TABLE(
        p_items,
        '$[*]'
        COLUMNS(
            product_id INT PATH '$.product_id',
            purchase_price DECIMAL(15,2) PATH '$.purchase_price',
            order_price DECIMAL(15,2) PATH '$.order_price',
            quantity INT PATH '$.quantity',
            vat_rate DECIMAL(5,2) PATH '$.vat_rate',
            vat_amount DECIMAL(15,2) PATH '$.vat_amount',
            total_amount DECIMAL(15,2) PATH '$.total_amount',
            total_amount_with_vat DECIMAL(15,2) PATH '$.total_amount_with_vat'
        )
    ) jt;

    /* =========================
       INVENTORIES
    ========================= */

    DELETE
    FROM inventories
    WHERE purchase_id = p_purchase_id;

    INSERT INTO inventories(
        purchase_id,
        product_id,
        purchase_price,
        order_price,
        quantity,
        vat_rate,
        vat_amount,
        total_amount,
        total_amount_with_vat
    )
    SELECT
        p_purchase_id,
        product_id,
        purchase_price,
        order_price,
        quantity,
        vat_rate,
        vat_amount,
        total_amount,
        total_amount_with_vat
    FROM JSON_TABLE(
        p_items,
        '$[*]'
        COLUMNS(
            product_id INT PATH '$.product_id',
            purchase_price DECIMAL(15,2) PATH '$.purchase_price',
            order_price DECIMAL(15,2) PATH '$.order_price',
            quantity INT PATH '$.quantity',
            vat_rate DECIMAL(5,2) PATH '$.vat_rate',
            vat_amount DECIMAL(15,2) PATH '$.vat_amount',
            total_amount DECIMAL(15,2) PATH '$.total_amount',
            total_amount_with_vat DECIMAL(15,2) PATH '$.total_amount_with_vat'
        )
    ) jt;

    COMMIT;

END;