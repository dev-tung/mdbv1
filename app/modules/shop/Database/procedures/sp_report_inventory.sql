DROP PROCEDURE IF EXISTS sp_report_inventory;

CREATE PROCEDURE sp_report_inventory (
	IN p_keyword VARCHAR(255),
	IN p_product_id INT,
	IN p_purchase_id INT,
	IN p_stock TINYINT
) BEGIN IF COALESCE(p_product_id, 0) > 0
AND COALESCE(p_purchase_id, 0) > 0 THEN
SELECT
	p.id,
	p.name,
	i.purchase_id,
	i.quantity AS stock
FROM
	inventories i
	JOIN products p ON p.id = i.product_id
WHERE
	i.product_id = p_product_id
	AND i.purchase_id = p_purchase_id;

ELSE
SELECT
	p.id,
	p.name,
	COALESCE(SUM(i.quantity), 0) AS stock
FROM
	products p
	LEFT JOIN inventories i ON i.product_id = p.id
WHERE
	p_keyword IS NULL
	OR p_keyword = ''
	OR p.name LIKE CONCAT ('%', p_keyword, '%')
GROUP BY
	p.id,
	p.name
HAVING
	p_stock IS NULL
	OR (
		p_stock = 1
		AND stock > 0
	)
	OR (
		p_stock = 0
		AND stock = 0
	)
ORDER BY
	p.id DESC;

END IF;

END;
