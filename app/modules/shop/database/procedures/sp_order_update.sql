DROP PROCEDURE IF EXISTS sp_order_update;

CREATE PROCEDURE sp_order_update
(

    IN p_id INT,

    IN p_customer_id INT,

    IN p_description VARCHAR(255),

    IN p_note TEXT,

    IN p_status VARCHAR(20),

    IN p_payment VARCHAR(20),

    IN p_subtotal_amount DECIMAL(15,2),

    IN p_discount_amount DECIMAL(15,2),

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
       RESTORE INVENTORY
       CỘNG LẠI TỒN KHO ĐƠN CŨ
    ===================================== */

    UPDATE inventories i
    JOIN order_items oi
        ON oi.product_id = i.product_id
    SET
        i.quantity = i.quantity + oi.quantity
    WHERE oi.order_id = p_id;

    /* =====================================
       UPDATE ORDER
    ===================================== */

    UPDATE orders
    SET

        customer_id      = p_customer_id,

        description      = p_description,

        note             = p_note,

        status           = p_status,

        payment          = p_payment,

        subtotal_amount  = p_subtotal_amount,

        discount_amount  = p_discount_amount,

        vat_rate         = p_vat_rate,

        vat_amount       = p_vat_amount,

        total_amount     = p_total_amount,

        paid_amount      = p_paid_amount,

        debt_amount      = p_debt_amount

    WHERE id = p_id;

    /* =====================================
       DELETE OLD ITEMS
    ===================================== */

    DELETE FROM order_items
    WHERE order_id = p_id;

    /* =====================================
       INSERT NEW ITEMS
    ===================================== */

    INSERT INTO order_items
    (

        order_id,

        product_id,

        product_name,

        quantity,

        purchase_price,

        selling_price,

        subtotal_amount,

        discount_amount,

        is_gift,

        vat_rate,

        vat_amount,

        total_amount

    )

    SELECT

        p_id,

        product_id,

        product_name,

        quantity,

        purchase_price,

        selling_price,

        subtotal_amount,

        discount_amount,

        is_gift,

        vat_rate,

        vat_amount,

        total_amount

    FROM JSON_TABLE
    (

        p_items,

        '$[*]'

        COLUMNS
        (

            product_id INT
                PATH '$.product_id',

            product_name VARCHAR(255)
                PATH '$.product_name',

            quantity INT
                PATH '$.quantity',

            purchase_price DECIMAL(15,2)
                PATH '$.purchase_price',

            selling_price DECIMAL(15,2)
                PATH '$.selling_price',

            subtotal_amount DECIMAL(15,2)
                PATH '$.subtotal_amount',

            discount_amount DECIMAL(15,2)
                PATH '$.discount_amount',

            is_gift TINYINT
                PATH '$.is_gift',

            vat_rate DECIMAL(5,2)
                PATH '$.vat_rate',

            vat_amount DECIMAL(15,2)
                PATH '$.vat_amount',

            total_amount DECIMAL(15,2)
                PATH '$.total_amount'

        )

    ) jt;

    /* =====================================
       UPDATE INVENTORY
       TRỪ TỒN KHO MỚI
    ===================================== */

    UPDATE inventories i

    JOIN JSON_TABLE
    (

        p_items,

        '$[*]'

        COLUMNS
        (

            product_id INT
                PATH '$.product_id',

            quantity INT
                PATH '$.quantity'

        )

    ) jt

    ON i.product_id = jt.product_id

    SET
        i.quantity = i.quantity - jt.quantity;

    COMMIT;

    SELECT p_id AS id;

END