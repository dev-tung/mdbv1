DROP PROCEDURE IF EXISTS sp_inventory_stock;

CREATE PROCEDURE `sp_inventory_stock`(
    IN p_keyword VARCHAR(255)
)
BEGIN
    SELECT
        p.id,
        p.name,
        i.purchase_id,
        i.product_id,
        i.purchase_price,
        i.selling_price,
        i.quantity
    FROM inventories i
    INNER JOIN products p
        ON p.id = i.product_id
    INNER JOIN purchases pu
        ON pu.id = i.purchase_id
    WHERE i.quantity > 0
      AND pu.status = 'received'
      AND (
            p_keyword IS NULL
         OR p_keyword = ''
         OR p.name LIKE CONCAT('%', p_keyword, '%')
      )
    ORDER BY
        p.id,
        i.purchase_id;
END