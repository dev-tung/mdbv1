DROP PROCEDURE IF EXISTS sp_inventory_list;

CREATE PROCEDURE `sp_inventory_list` (IN p_keyword VARCHAR(255)) BEGIN
SELECT
	p.id,
	p.name,
	COALESCE(SUM(i.quantity), 0) AS stock
FROM
	products p
	JOIN inventories i ON i.product_id = p.id
WHERE
	p_keyword IS NULL
	OR p_keyword = ''
	OR p.name LIKE CONCAT ('%', p_keyword, '%')
GROUP BY
	p.id,
	p.name
ORDER BY
	p.id DESC;

END
