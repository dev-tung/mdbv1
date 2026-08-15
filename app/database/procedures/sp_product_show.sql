DROP PROCEDURE IF EXISTS sp_product_show;

CREATE PROCEDURE sp_product_show(
	IN p_slug VARCHAR(255),
	IN p_website TINYINT
)
BEGIN

	SET p_website = COALESCE(p_website, 0);


	/* =================================================
	   PRODUCT
	================================================= */

	SELECT

		p.id,
		p.slug,

		p.category_id,
		c.name AS category_name,

		p.brand_id,
		b.name AS brand_name,

		p.name,
		p.thumbnail,

		p.price,
		p.sale_price,

		p.status,

		p.description,

		p.created_at,


		/* ==========================================
		   PURCHASE
		========================================== */

		i.purchase_id,


		/* ==========================================
		   STOCK
		========================================== */

		COALESCE(
			i.stock,
			0
		) AS stock

	FROM products p


	/* =================================================
	   CATEGORY
	================================================= */

	LEFT JOIN categories c
		ON c.id = p.category_id


	/* =================================================
	   BRAND
	================================================= */

	LEFT JOIN brands b
		ON b.id = p.brand_id


	/* =================================================
	   INVENTORY

	   Lấy purchase_id đang còn hàng.

	   Ưu tiên inventory mới nhất.
	================================================= */

	LEFT JOIN (

		SELECT

			i.product_id,

			i.purchase_id,

			i.quantity AS stock

		FROM inventories i

		INNER JOIN (

			SELECT

				product_id,

				MAX(id) AS inventory_id

			FROM inventories

			WHERE
				quantity > 0

			GROUP BY
				product_id

		) x

			ON x.inventory_id = i.id

	) i

		ON i.product_id = p.id


	/* =================================================
	   WHERE
	================================================= */

	WHERE

		(
			p.slug = p_slug

			OR p.id = p_slug
		)

		AND (

			p_website = 0

			OR (

				p.thumbnail IS NOT NULL

				AND TRIM(
					p.thumbnail
				) <> ''

			)

		)

	LIMIT 1;


	/* =================================================
	   IMAGES
	================================================= */

	SELECT

		id,
		product_id,
		image,
		sort_order

	FROM product_images

	WHERE product_id = (

		SELECT
			id

		FROM products

		WHERE

			slug = p_slug

			OR id = p_slug

		LIMIT 1

	)

	ORDER BY

		sort_order ASC,
		id ASC;


	/* =================================================
	   ATTRIBUTES
	================================================= */

	SELECT

		id,
		product_id,
		attribute_name,
		attribute_value

	FROM product_attributes

	WHERE product_id = (

		SELECT
			id

		FROM products

		WHERE

			slug = p_slug

			OR id = p_slug

		LIMIT 1

	)

	ORDER BY

		id ASC;

END;