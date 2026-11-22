DROP PROCEDURE IF EXISTS sp_customer_list;

CREATE PROCEDURE sp_customer_list (
	IN p_keyword VARCHAR(255),
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_page INT,
	IN p_per_page INT
) BEGIN
DECLARE v_offset INT;

SET
	p_keyword = NULLIF(TRIM(p_keyword), '');

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
					WHEN 'shoe' THEN 'Giày'
					ELSE 'Vợt'
				END,
				NULLIF(
					CONCAT_WS(
						' ',
						NULLIF(NULLIF(TRIM(e.brand), ''), '0'),
						NULLIF(NULLIF(TRIM(e.model), ''), '0')
					),
					''
				),
				IF(
					e.equipment_type = 'shoe'
						AND NULLIF(NULLIF(TRIM(e.size), ''), '0') IS NOT NULL,
					CONCAT('(cỡ ', TRIM(e.size), ')'),
					NULL
				),
				IF(
					NULLIF(NULLIF(TRIM(e.note), ''), '0') IS NOT NULL,
					CONCAT('-', TRIM(e.note)),
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
												NULLIF(NULLIF(TRIM(s.string_type), ''), '0'),
												NULLIF(NULLIF(TRIM(s.tension), ''), '0')
											),
											''
										),
										s.strung_at,
										NULLIF(NULLIF(TRIM(s.note), ''), '0')
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
		p_keyword IS NULL
		OR c.name LIKE CONCAT ('%', p_keyword, '%')
		OR c.phone LIKE CONCAT ('%', p_keyword, '%')
		OR c.email LIKE CONCAT ('%', p_keyword, '%')
		OR c.address LIKE CONCAT ('%', p_keyword, '%')
		OR c.description LIKE CONCAT ('%', p_keyword, '%')
		OR cg.name LIKE CONCAT ('%', p_keyword, '%')
		OR CAST(c.id AS CHAR) LIKE CONCAT ('%', p_keyword, '%')
		OR DATE_FORMAT(c.created_at, '%Y-%m-%d') LIKE CONCAT ('%', p_keyword, '%')
		OR EXISTS (
			SELECT 1
			FROM customer_equipment e
			WHERE e.customer_id = c.id
				AND CONCAT_WS(
					' ',
					CASE e.equipment_type
						WHEN 'shoe' THEN 'Giày'
						WHEN 'racket' THEN 'Vợt'
						ELSE 'Căng cước'
					END,
					e.brand,
					e.model,
					e.size,
					e.string_type,
					e.tension,
					DATE_FORMAT(e.strung_at, '%Y-%m-%d'),
					e.note,
					e.id
				) COLLATE utf8mb4_unicode_ci
					LIKE CONCAT ('%', p_keyword, '%') COLLATE utf8mb4_unicode_ci
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
		p_keyword IS NULL
		OR c.name LIKE CONCAT ('%', p_keyword, '%')
		OR c.phone LIKE CONCAT ('%', p_keyword, '%')
		OR c.email LIKE CONCAT ('%', p_keyword, '%')
		OR c.address LIKE CONCAT ('%', p_keyword, '%')
		OR c.description LIKE CONCAT ('%', p_keyword, '%')
		OR cg.name LIKE CONCAT ('%', p_keyword, '%')
		OR CAST(c.id AS CHAR) LIKE CONCAT ('%', p_keyword, '%')
		OR DATE_FORMAT(c.created_at, '%Y-%m-%d') LIKE CONCAT ('%', p_keyword, '%')
		OR EXISTS (
			SELECT 1
			FROM customer_equipment e
			WHERE e.customer_id = c.id
				AND CONCAT_WS(
					' ',
					CASE e.equipment_type
						WHEN 'shoe' THEN 'Giày'
						WHEN 'racket' THEN 'Vợt'
						ELSE 'Căng cước'
					END,
					e.brand,
					e.model,
					e.size,
					e.string_type,
					e.tension,
					DATE_FORMAT(e.strung_at, '%Y-%m-%d'),
					e.note,
					e.id
				) COLLATE utf8mb4_unicode_ci
					LIKE CONCAT ('%', p_keyword, '%') COLLATE utf8mb4_unicode_ci
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
