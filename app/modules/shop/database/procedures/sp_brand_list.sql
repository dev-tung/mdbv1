DROP PROCEDURE IF EXISTS sp_brand_list;

CREATE PROCEDURE sp_brand_list (
	IN p_keyword VARCHAR(255),
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_page INT,
	IN p_per_page INT
)
BEGIN
	DECLARE v_offset INT;

	/* =================================================
	   DEFAULT PAGINATION
	================================================= */

	SET p_page = COALESCE(p_page, 1);

	SET p_per_page = COALESCE(p_per_page, 999999);

	SET v_offset = (p_page - 1) * p_per_page;

	/* =================================================
	   LIST
	================================================= */

	SELECT
		b.id,
		b.name,
		b.description,
		b.created_at
	FROM brands b
	WHERE
		(
			p_keyword IS NULL
			OR p_keyword = ''
			OR b.name LIKE CONCAT('%', p_keyword, '%')
		)
		AND (
			p_date_from IS NULL
			OR DATE(b.created_at) >= p_date_from
		)
		AND (
			p_date_to IS NULL
			OR DATE(b.created_at) <= p_date_to
		)
	ORDER BY
		b.id DESC
	LIMIT
		p_per_page
	OFFSET
		v_offset;

	/* =================================================
	   SUMMARY
	================================================= */

	SELECT
		COUNT(*) AS total
	FROM brands b
	WHERE
		(
			p_keyword IS NULL
			OR p_keyword = ''
			OR b.name LIKE CONCAT('%', p_keyword, '%')
		)
		AND (
			p_date_from IS NULL
			OR DATE(b.created_at) >= p_date_from
		)
		AND (
			p_date_to IS NULL
			OR DATE(b.created_at) <= p_date_to
		);

END;