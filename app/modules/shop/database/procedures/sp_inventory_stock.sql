DROP PROCEDURE IF EXISTS sp_inventory_stock;

CREATE PROCEDURE sp_inventory_stock (IN p_keyword VARCHAR(255)) BEGIN
SELECT
	i.purchase_id,
	i.product_id,
	p.name AS product_name,
	i.quantity,
	i.purchase_price,
	i.selling_price,
	i.vat_rate,
	i.vat_amount,
	i.total_amount
FROM
	inventories i
	INNER JOIN purchases pu ON pu.id = i.purchase_id
	INNER JOIN products p ON p.id = i.product_id
WHERE
	i.quantity > 0
	AND (
		p_keyword IS NULL
		OR p_keyword = ''
		OR p.name LIKE CONCAT ('%', p_keyword, '%')
	)
ORDER BY
	p.name ASC,
	pu.created_at ASC,
	i.purchase_id ASC;

END;
