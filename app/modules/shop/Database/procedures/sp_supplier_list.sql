DROP PROCEDURE IF EXISTS sp_supplier_list;

CREATE PROCEDURE sp_supplier_list (
	IN p_keyword VARCHAR(255),
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_page INT,
	IN p_per_page INT
) BEGIN DECLARE v_offset INT;

/* =================================================
DEFAULT PAGINATION
================================================= */
SET
	p_page = COALESCE(p_page, 1);

SET
	p_per_page = COALESCE(p_per_page, 999999);

SET
	v_offset = (p_page - 1) * p_per_page;

/* =================================================
LIST
================================================= */
SELECT
	s.id,
	s.name,
	s.phone,
	s.email,
	s.address,
	s.description,
	s.created_at
FROM
	suppliers s
WHERE
	(
		p_keyword IS NULL
		OR p_keyword = ''
		OR s.name LIKE CONCAT ('%', p_keyword, '%')
		OR s.phone LIKE CONCAT ('%', p_keyword, '%')
		OR s.email LIKE CONCAT ('%', p_keyword, '%')
	)
	AND (
		p_date_from IS NULL
		OR DATE(s.created_at) >= p_date_from
	)
	AND (
		p_date_to IS NULL
		OR DATE(s.created_at) <= p_date_to
	)
ORDER BY
	s.id DESC
LIMIT
	p_per_page
OFFSET
	v_offset;

/* =================================================
SUMMARY
================================================= */
SELECT
	COUNT(*) AS total
FROM
	suppliers s
WHERE
	(
		p_keyword IS NULL
		OR p_keyword = ''
		OR s.name LIKE CONCAT ('%', p_keyword, '%')
		OR s.phone LIKE CONCAT ('%', p_keyword, '%')
		OR s.email LIKE CONCAT ('%', p_keyword, '%')
	)
	AND (
		p_date_from IS NULL
		OR DATE(s.created_at) >= p_date_from
	)
	AND (
		p_date_to IS NULL
		OR DATE(s.created_at) <= p_date_to
	);

END;
