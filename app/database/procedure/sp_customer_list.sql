DROP PROCEDURE IF EXISTS sp_customer_list;

CREATE PROCEDURE sp_customer_list (
	IN p_keyword VARCHAR(255),
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_page INT,
	IN p_per_page INT
) BEGIN
DECLARE v_offset INT;
DECLARE v_keyword VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

SET v_keyword = NULLIF(TRIM(p_keyword), '');

SET
	p_page = IFNULL (p_page, 1);

SET
	p_per_page = IFNULL (p_per_page, 10);

SET
	v_offset = (GREATEST (p_page, 1) - 1) * p_per_page;

SET SESSION group_concat_max_len = 65535;

/* =====================================
LIST
===================================== */
SELECT
	c.id,
	c.name,
	c.group_id,
	cg.name AS group_name,
	c.phone,
	c.email,
	c.address,
	(
		SELECT GROUP_CONCAT(
			CONCAT_WS(
				' ',
				CASE e.equipment_type
					WHEN 'shoe' THEN 'Giày' COLLATE utf8mb4_unicode_ci
					ELSE 'Vợt' COLLATE utf8mb4_unicode_ci
				END,
				NULLIF(
					CONCAT_WS(
						' ',
						NULLIF(NULLIF(TRIM(e.brand), ''), '0') COLLATE utf8mb4_unicode_ci,
						NULLIF(NULLIF(TRIM(e.model), ''), '0') COLLATE utf8mb4_unicode_ci
					),
					''
				),
				IF(
					e.equipment_type = 'shoe'
						AND NULLIF(NULLIF(TRIM(e.size), ''), '0') IS NOT NULL,
					CONCAT('(cỡ ', TRIM(e.size) COLLATE utf8mb4_unicode_ci, ')'),
					NULL
				),
				IF(
					NULLIF(NULLIF(TRIM(e.note), ''), '0') IS NOT NULL,
					CONCAT('-', TRIM(e.note) COLLATE utf8mb4_unicode_ci),
					NULL
				),
				IF(
					e.equipment_type = 'racket',
					COALESCE(
						(
							SELECT CONCAT(
								'[Căng cước ',
								GROUP_CONCAT(
									CONCAT_WS(
										' - ',
										NULLIF(
											CONCAT_WS(
												' ',
												NULLIF(NULLIF(TRIM(s.string_type), ''), '0') COLLATE utf8mb4_unicode_ci,
												NULLIF(NULLIF(TRIM(s.tension), ''), '0') COLLATE utf8mb4_unicode_ci
											),
											''
										),
										CAST(s.strung_at AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci,
										NULLIF(NULLIF(TRIM(s.note), ''), '0') COLLATE utf8mb4_unicode_ci
									)
									ORDER BY s.strung_at DESC, s.id DESC
									SEPARATOR '; '
								),
								']'
							)
							FROM customer_equipment s
							WHERE s.customer_id = e.customer_id
								AND s.parent_id = e.id
								AND s.parent_type = 'racket'
								AND s.equipment_type = 'stringing'
						),
						NULL
					),
					NULL
				)
			)
			ORDER BY FIELD(e.equipment_type, 'racket', 'shoe'), e.id
			SEPARATOR ' | '
		)
		FROM customer_equipment e
		WHERE e.customer_id = c.id
			AND e.equipment_type IN ('racket', 'shoe')
	) AS equipment_summary,
	c.description,
	c.created_at
FROM
	customers c
	LEFT JOIN customer_groups cg ON cg.id = c.group_id
WHERE
	(
		v_keyword IS NULL
		OR CONVERT(c.name USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(c.phone USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(c.email USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(c.address USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(c.description USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(cg.name USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CAST(c.id AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(DATE_FORMAT(c.created_at, '%Y-%m-%d') USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR EXISTS (
			SELECT 1
			FROM customer_equipment e
			WHERE e.customer_id = c.id
				AND CONCAT_WS(
					' ',
					CASE e.equipment_type
						WHEN 'shoe' THEN 'Giày' COLLATE utf8mb4_unicode_ci
						WHEN 'racket' THEN 'Vợt' COLLATE utf8mb4_unicode_ci
						ELSE 'Căng cước' COLLATE utf8mb4_unicode_ci
					END,
					CONVERT(e.brand USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.model USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.size USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.string_type USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.tension USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(DATE_FORMAT(e.strung_at, '%Y-%m-%d') USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.note USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CAST(e.id AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci
				) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		)
	)
	AND (
		p_date_from IS NULL
		OR DATE(c.created_at) >= p_date_from
	)
	AND (
		p_date_to IS NULL
		OR DATE(c.created_at) <= p_date_to
	)
ORDER BY
	c.id DESC
LIMIT
	p_per_page
OFFSET
	v_offset;

/* =====================================
SUMMARY
===================================== */
SELECT
	COUNT(*) AS total
FROM
	customers c
	LEFT JOIN customer_groups cg ON cg.id = c.group_id
WHERE
	(
		v_keyword IS NULL
		OR CONVERT(c.name USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(c.phone USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(c.email USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(c.address USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(c.description USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(cg.name USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CAST(c.id AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR CONVERT(DATE_FORMAT(c.created_at, '%Y-%m-%d') USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		OR EXISTS (
			SELECT 1
			FROM customer_equipment e
			WHERE e.customer_id = c.id
				AND CONCAT_WS(
					' ',
					CASE e.equipment_type
						WHEN 'shoe' THEN 'Giày' COLLATE utf8mb4_unicode_ci
						WHEN 'racket' THEN 'Vợt' COLLATE utf8mb4_unicode_ci
						ELSE 'Căng cước' COLLATE utf8mb4_unicode_ci
					END,
					CONVERT(e.brand USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.model USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.size USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.string_type USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.tension USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(DATE_FORMAT(e.strung_at, '%Y-%m-%d') USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CONVERT(e.note USING utf8mb4) COLLATE utf8mb4_unicode_ci,
					CAST(e.id AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci
				) COLLATE utf8mb4_unicode_ci LIKE CONCAT ('%', v_keyword, '%')
		)
	)
	AND (
		p_date_from IS NULL
		OR DATE(c.created_at) >= p_date_from
	)
	AND (
		p_date_to IS NULL
		OR DATE(c.created_at) <= p_date_to
	);

END;
