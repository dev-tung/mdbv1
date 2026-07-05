DROP PROCEDURE IF EXISTS sp_purchase_show;

CREATE PROCEDURE sp_purchase_show(
    IN p_id INT
)
BEGIN

    -- Purchase
    SELECT
        p.id,
        p.supplier_id,
        s.name AS supplier_name,
        p.warehouse_id,
        w.name AS warehouse_name,
        p.description,
        p.status,
        p.payment,
        p.total_amount,
        p.paid_amount,
        p.debt_amount,
        p.created_at,
        p.updated_at
    FROM purchases p
    LEFT JOIN suppliers s
        ON s.id = p.supplier_id
    LEFT JOIN warehouses w
        ON w.id = p.warehouse_id
    WHERE p.id = p_id
    LIMIT 1;

    -- Purchase Items
    SELECT
        pi.id,
        pi.product_id,
        pr.name AS product_name,
        pi.purchase_price,
        pi.quantity,
        pi.vat_rate,
        pi.vat_amount,
        pi.total_amount
    FROM purchase_items pi
    INNER JOIN products pr
        ON pr.id = pi.product_id
    WHERE pi.purchase_id = p_id
    ORDER BY pi.id;

END;