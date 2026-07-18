DROP PROCEDURE IF EXISTS sp_product_list;

CREATE PROCEDURE sp_product_list (
	IN p_keyword VARCHAR(255),
	IN p_category_id INT,
	IN p_status VARCHAR(50),
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_price_min DECIMAL(15,2),
	IN p_price_max DECIMAL(15,2),
	IN p_page INT,
	IN p_per_page INT
)
BEGIN
	DECLARE v_offset INT;

	SET v_offset = (GREATEST(p_page, 1) - 1) * p_per_page;

	/* =================================================
	   LIST
	================================================= */

	SELECT
		p.id,
		p.category_id,
		c.name AS category_name,
		p.brand_id,
		p.name,
		p.thumbnail,
		p.price,
		p.sale_price,
		p.status,
		p.description,
		p.created_at
	FROM products p
	LEFT JOIN categories c
		ON c.id = p.category_id
	WHERE
		(
			p_keyword IS NULL
			OR p_keyword = ''
			OR p.name LIKE CONCAT('%', p_keyword, '%')
		)
		AND (
			p_category_id IS NULL
			OR p.category_id = p_category_id
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
	ORDER BY
		p.id DESC
	LIMIT p_per_page
	OFFSET v_offset;

	/* =================================================
	   SUMMARY
	================================================= */

	SELECT
		COUNT(*) AS total,
		COALESCE(SUM(p.price), 0) AS total_price,
		COALESCE(SUM(p.sale_price), 0) AS total_sale_price
	FROM products p
	WHERE
		(
			p_keyword IS NULL
			OR p_keyword = ''
			OR p.name LIKE CONCAT('%', p_keyword, '%')
		)
		AND (
			p_category_id IS NULL
			OR p.category_id = p_category_id
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