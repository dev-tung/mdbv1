DROP PROCEDURE IF EXISTS sp_report_inventory;

CREATE PROCEDURE sp_report_inventory (
	IN p_keyword VARCHAR(255),
	IN p_product_id INT,
	IN p_purchase_id INT,
	IN p_stock TINYINT
)
BEGIN

	IF COALESCE(p_product_id, 0) > 0
		AND COALESCE(p_purchase_id, 0) > 0 THEN

		SELECT
			p.id AS product_id,
			p.name AS product_name,
			i.purchase_id,
			i.quantity,
			pi.selling_price,
			pu.vat_rate
		FROM inventories i
		JOIN products p
			ON p.id = i.product_id
		JOIN purchase_items pi
			ON pi.purchase_id = i.purchase_id
			AND pi.product_id = i.product_id
		JOIN purchases pu
			ON pu.id = i.purchase_id
		WHERE
			i.product_id = p_product_id
			AND i.purchase_id = p_purchase_id;

	ELSE

		SELECT
			p.id AS product_id,
			p.name AS product_name,
			i.purchase_id,
			COALESCE(SUM(i.quantity), 0) AS quantity,
			MAX(pi.selling_price) AS selling_price,
			MAX(pu.vat_rate) AS vat_rate
		FROM products p
		LEFT JOIN inventories i
			ON i.product_id = p.id
		LEFT JOIN purchase_items pi
			ON pi.purchase_id = i.purchase_id
			AND pi.product_id = i.product_id
		LEFT JOIN purchases pu
			ON pu.id = i.purchase_id
		WHERE
			p_keyword IS NULL
			OR p_keyword = ''
			OR p.name LIKE CONCAT('%', p_keyword, '%')
		GROUP BY
			p.id,
			p.name,
			i.purchase_id
		HAVING
			p_stock IS NULL
			OR (p_stock = 1 AND quantity > 0)
			OR (p_stock = 0 AND quantity = 0)
		ORDER BY
			p.id DESC,
			i.purchase_id DESC;

	END IF;

END;