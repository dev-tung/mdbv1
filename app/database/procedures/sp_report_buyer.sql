DROP PROCEDURE IF EXISTS sp_report_buyer;

CREATE PROCEDURE sp_report_buyer (
	IN p_from_date DATE,
	IN p_to_date DATE
)
BEGIN

	/* ===========================================
	   RESULT 1: DANH SÁCH KHÁCH HÀNG ĐÃ MUA
	=========================================== */

	SELECT
		c.id AS customer_id,

		c.name AS customer_name,

		c.phone,

		cg.id AS customer_group_id,

		cg.name AS customer_group_name,

		COUNT(DISTINCT o.id) AS total_orders,

		COALESCE(
			SUM(oi.quantity),
			0
		) AS total_quantity,

		COALESCE(
			SUM(oi.total_amount),
			0
		) AS total_revenue,

		COALESCE(
			SUM(
				oi.total_amount -
				COALESCE(
					(pi.total_amount / NULLIF(pi.quantity, 0)) * oi.quantity,
					0
				)
			),
			0
		) AS total_profit

	FROM orders o

	INNER JOIN customers c
		ON c.id = o.customer_id

	LEFT JOIN customer_groups cg
		ON cg.id = c.group_id

	INNER JOIN order_items oi
		ON oi.order_id = o.id

	LEFT JOIN purchase_items pi
		ON pi.purchase_id = oi.purchase_id
		AND pi.product_id = oi.product_id

	WHERE
		(
			p_from_date IS NULL
			OR DATE(o.created_at) >= p_from_date
		)
		AND
		(
			p_to_date IS NULL
			OR DATE(o.created_at) <= p_to_date
		)

	GROUP BY
		c.id,
		c.name,
		c.phone,
		cg.id,
		cg.name

	ORDER BY
		total_revenue DESC,
		c.name ASC;


	/* ===========================================
	   RESULT 2: TỔNG HỢP
	=========================================== */

	SELECT
		COUNT(*) AS total_customers,

		COALESCE(
			SUM(t.total_orders),
			0
		) AS total_orders,

		COALESCE(
			SUM(t.total_quantity),
			0
		) AS total_quantity,

		COALESCE(
			SUM(t.total_revenue),
			0
		) AS total_revenue,

		COALESCE(
			SUM(t.total_profit),
			0
		) AS total_profit

	FROM (

		SELECT
			c.id,

			COUNT(DISTINCT o.id) AS total_orders,

			COALESCE(
				SUM(oi.quantity),
				0
			) AS total_quantity,

			COALESCE(
				SUM(oi.total_amount),
				0
			) AS total_revenue,

			COALESCE(
				SUM(
					oi.total_amount -
					COALESCE(
						(pi.total_amount / NULLIF(pi.quantity, 0)) * oi.quantity,
						0
					)
				),
				0
			) AS total_profit

		FROM orders o

		INNER JOIN customers c
			ON c.id = o.customer_id

		INNER JOIN order_items oi
			ON oi.order_id = o.id

		LEFT JOIN purchase_items pi
			ON pi.purchase_id = oi.purchase_id
			AND pi.product_id = oi.product_id

		WHERE
			(
				p_from_date IS NULL
				OR DATE(o.created_at) >= p_from_date
			)
			AND
			(
				p_to_date IS NULL
				OR DATE(o.created_at) <= p_to_date
			)

		GROUP BY
			c.id

	) t;

END;