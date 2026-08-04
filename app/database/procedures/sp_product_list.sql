DROP PROCEDURE IF EXISTS sp_product_list;

CREATE PROCEDURE sp_product_list (
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

	/* ==========================================
	   DEFAULT PAGINATION
	========================================== */

	SET p_page = COALESCE(p_page, 1);

	SET p_per_page = COALESCE(p_per_page, 999999);

	SET p_website = COALESCE(p_website, 0);

	SET v_offset = (p_page - 1) * p_per_page;


	/* ==========================================
	   LIST
	========================================== */

	SELECT
		p.id,
		p.category_id,
		c.name AS category_name,
		p.brand_id,
		p.name,
		p.thumbnail,
		p.price,
		p.sale_price,

		/* TỒN KHO */
		COALESCE(i.stock, 0) AS stock,

		p.status,
		p.description,
		p.created_at

	FROM products p

	LEFT JOIN categories c
		ON c.id = p.category_id

	/* ==========================================
	   INVENTORY
	========================================== */

	LEFT JOIN (
		SELECT
			product_id,
			SUM(quantity) AS stock
		FROM inventories
		GROUP BY product_id
	) i
		ON i.product_id = p.id

	WHERE

		/* ======================================
		   KEYWORD
		====================================== */

		(
			p_keyword IS NULL
			OR p_keyword = ''
			OR p.name LIKE CONCAT('%', p_keyword, '%')
		)

		/* ======================================
		   CATEGORY
		====================================== */

		AND (
			p_category_id IS NULL
			OR p.category_id = p_category_id
		)

		/* ======================================
		   BRAND
		====================================== */

		AND (
			p_brand_id IS NULL
			OR p.brand_id = p_brand_id
		)

		/* ======================================
		   STATUS
		====================================== */

		AND (
			p_status IS NULL
			OR p_status = ''
			OR p.status = p_status
		)

		/* ======================================
		   DATE FROM
		====================================== */

		AND (
			p_date_from IS NULL
			OR DATE(p.created_at) >= p_date_from
		)

		/* ======================================
		   DATE TO
		====================================== */

		AND (
			p_date_to IS NULL
			OR DATE(p.created_at) <= p_date_to
		)

		/* ======================================
		   PRICE MIN
		====================================== */

		AND (
			p_price_min IS NULL
			OR p.price >= p_price_min
		)

		/* ======================================
		   PRICE MAX
		====================================== */

		AND (
			p_price_max IS NULL
			OR p.price <= p_price_max
		)

		/* ======================================
		   WEBSITE
		====================================== */

		AND (
			p_website = 0
			OR (
				p.thumbnail IS NOT NULL
				AND TRIM(p.thumbnail) <> ''
			)
		)


	/* ==========================================
	   SORT PRIORITY

	   1. VỢT CẦU LÔNG
	   2. CÒN HÀNG
	   3. CÓ GIÁ > 0
	   4. ID MỚI NHẤT
	========================================== */

	ORDER BY

		/* 1. Vợt cầu lông */
		CASE
			WHEN p.category_id = 1 THEN 0
			ELSE 1
		END ASC,

		/* 2. Còn hàng */
		CASE
			WHEN COALESCE(i.stock, 0) > 0 THEN 0
			ELSE 1
		END ASC,

		/* 3. Có giá */
		CASE
			WHEN p.price > 0 THEN 0
			ELSE 1
		END ASC,

		/* 4. Mới nhất */
		p.id DESC

	LIMIT p_per_page
	OFFSET v_offset;


	/* ==========================================
	   SUMMARY
	========================================== */

	SELECT
		COUNT(*) AS total,

		COALESCE(
			SUM(p.price),
			0
		) AS total_price,

		COALESCE(
			SUM(p.sale_price),
			0
		) AS total_sale_price

	FROM products p

	WHERE

		/* ======================================
		   KEYWORD
		====================================== */

		(
			p_keyword IS NULL
			OR p_keyword = ''
			OR p.name LIKE CONCAT('%', p_keyword, '%')
		)

		/* ======================================
		   CATEGORY
		====================================== */

		AND (
			p_category_id IS NULL
			OR p.category_id = p_category_id
		)

		/* ======================================
		   BRAND
		====================================== */

		AND (
			p_brand_id IS NULL
			OR p.brand_id = p_brand_id
		)

		/* ======================================
		   STATUS
		====================================== */

		AND (
			p_status IS NULL
			OR p_status = ''
			OR p.status = p_status
		)

		/* ======================================
		   DATE FROM
		====================================== */

		AND (
			p_date_from IS NULL
			OR DATE(p.created_at) >= p_date_from
		)

		/* ======================================
		   DATE TO
		====================================== */

		AND (
			p_date_to IS NULL
			OR DATE(p.created_at) <= p_date_to
		)

		/* ======================================
		   PRICE MIN
		====================================== */

		AND (
			p_price_min IS NULL
			OR p.price >= p_price_min
		)

		/* ======================================
		   PRICE MAX
		====================================== */

		AND (
			p_price_max IS NULL
			OR p.price <= p_price_max
		)

		/* ======================================
		   WEBSITE
		====================================== */

		AND (
			p_website = 0
			OR (
				p.thumbnail IS NOT NULL
				AND TRIM(p.thumbnail) <> ''
			)
		);

END;