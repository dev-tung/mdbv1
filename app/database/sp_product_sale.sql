DROP PROCEDURE IF EXISTS sp_product_sale;

CREATE PROCEDURE sp_product_sale (
	IN p_keyword VARCHAR(255),
	IN p_category_id INT,
	IN p_brand_id INT,
	IN p_status VARCHAR(50),
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_price_min DECIMAL(15,2),
	IN p_price_max DECIMAL(15,2),
	IN p_website TINYINT,
	IN p_page INT,
	IN p_per_page INT
)
BEGIN
	DECLARE v_offset INT;

	SET p_page = GREATEST(COALESCE(p_page, 1), 1);
	SET p_per_page = GREATEST(COALESCE(p_per_page, 999999), 1);
	SET v_offset = (p_page - 1) * p_per_page;

	SELECT
		p.id,
		p.slug,
		p.category_id,
		c.name AS category_name,
		p.brand_id,
		p.name,
		p.price,
		p.sale_price,
		COALESCE(i.quantity, 0) AS stock,
		DATE(pu.created_at) AS import_date,
		DATEDIFF(CURDATE(), DATE(pu.created_at)) AS days_in_stock,
		CASE
			WHEN pi.quantity > 0 THEN pi.total_amount / pi.quantity
			ELSE 0
		END AS import_price,
		p.status,
		p.description,
		p.created_at
	FROM products p
	JOIN (
		SELECT inventory.product_id, inventory.purchase_id, inventory.quantity
		FROM inventories inventory
		JOIN (
			SELECT product_id, MAX(id) AS inventory_id
			FROM inventories
			WHERE quantity > 0
			GROUP BY product_id
		) latest_inventory
			ON latest_inventory.inventory_id = inventory.id
	) i
		ON i.product_id = p.id
	JOIN purchase_items pi
		ON pi.purchase_id = i.purchase_id
		AND pi.product_id = p.id
	JOIN purchases pu
		ON pu.id = i.purchase_id
	LEFT JOIN categories c
		ON c.id = p.category_id
	WHERE
		p.status = 'active'
		AND p.price > 0
		AND pi.quantity > 0
		AND pi.total_amount > 0
		AND (
			p_keyword IS NULL
			OR p_keyword = ''
			OR p.name LIKE CONCAT('%', p_keyword, '%')
		)
		AND (
			p_category_id IS NULL
			OR p.category_id = p_category_id
		)
		AND (
			p_brand_id IS NULL
			OR p.brand_id = p_brand_id
		)
		AND (
			p_status IS NULL
			OR p_status = ''
			OR p.status = p_status
		)
		AND (
			p_date_from IS NULL
			OR DATE(p.created_at) >= p_date_from
		)
		AND (
			p_date_to IS NULL
			OR DATE(p.created_at) <= p_date_to
		)
		AND (
			p_price_min IS NULL
			OR p.price >= p_price_min
		)
		AND (
			p_price_max IS NULL
			OR p.price <= p_price_max
		)
	ORDER BY p.id DESC
	LIMIT p_per_page
	OFFSET v_offset;

	SELECT
		COUNT(*) AS total,
		COALESCE(SUM(p.price), 0) AS total_price,
		COALESCE(SUM(p.sale_price), 0) AS total_sale_price
	FROM products p
	JOIN (
		SELECT inventory.product_id, inventory.purchase_id, inventory.quantity
		FROM inventories inventory
		JOIN (
			SELECT product_id, MAX(id) AS inventory_id
			FROM inventories
			WHERE quantity > 0
			GROUP BY product_id
		) latest_inventory
			ON latest_inventory.inventory_id = inventory.id
	) i
		ON i.product_id = p.id
	JOIN purchase_items pi
		ON pi.purchase_id = i.purchase_id
		AND pi.product_id = p.id
	JOIN purchases pu
		ON pu.id = i.purchase_id
	WHERE
		p.status = 'active'
		AND p.price > 0
		AND pi.quantity > 0
		AND pi.total_amount > 0
		AND (
			p_keyword IS NULL
			OR p_keyword = ''
			OR p.name LIKE CONCAT('%', p_keyword, '%')
		)
		AND (
			p_category_id IS NULL
			OR p.category_id = p_category_id
		)
		AND (
			p_brand_id IS NULL
			OR p.brand_id = p_brand_id
		)
		AND (
			p_status IS NULL
			OR p_status = ''
			OR p.status = p_status
		)
		AND (
			p_date_from IS NULL
			OR DATE(p.created_at) >= p_date_from
		)
		AND (
			p_date_to IS NULL
			OR DATE(p.created_at) <= p_date_to
		)
		AND (
			p_price_min IS NULL
			OR p.price >= p_price_min
		)
		AND (
			p_price_max IS NULL
			OR p.price <= p_price_max
		);
END;
