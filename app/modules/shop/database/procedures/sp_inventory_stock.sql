DROP PROCEDURE IF EXISTS sp_inventory_stock;

CREATE PROCEDURE sp_inventory_stock(IN p_keyword VARCHAR(255))
BEGIN

    SELECT
        p.id AS product_id,
        p.name AS product_name,
        SUM(i.quantity) AS quantity,
        MAX(i.purchase_price) AS purchase_price,
        MAX(i.selling_price) AS selling_price,
        MAX(i.vat_rate) AS vat_rate
    FROM inventories i
    INNER JOIN products p
        ON p.id = i.product_id
    WHERE (
        p_keyword IS NULL
        OR p_keyword = ''
        OR p.name LIKE CONCAT('%', p_keyword, '%')
    )
    GROUP BY
        p.id,
        p.name
    HAVING SUM(i.quantity) > 0
    ORDER BY p.name ASC;

END;