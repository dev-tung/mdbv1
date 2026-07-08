DROP PROCEDURE IF EXISTS sp_order_show;

CREATE PROCEDURE sp_order_show (IN p_id INT) BEGIN
/* =====================================
ORDER
===================================== */
SELECT
    o.*,
    c.name AS customer_name
FROM
    orders o
    LEFT JOIN customers c ON c.id = o.customer_id
WHERE
    o.id = p_id;

/* =====================================
ORDER ITEMS
===================================== */
SELECT
    oi.id,
    oi.order_id,
    oi.product_id,
    oi.product_name,
    oi.quantity,
    oi.purchase_price,
    oi.selling_price,
    oi.discount_amount,
    oi.is_gift,
    oi.subtotal_amount,
    oi.vat_rate,
    oi.vat_amount,
    oi.total_amount
FROM
    order_items oi
WHERE
    oi.order_id = p_id
ORDER BY
    oi.id;

END
