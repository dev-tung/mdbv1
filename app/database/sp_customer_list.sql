DROP PROCEDURE IF EXISTS sp_customer_list;

CREATE PROCEDURE sp_customer_list (
	IN p_keyword VARCHAR(255),
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_page INT,
	IN p_per_page INT
) BEGIN DECLARE v_offset INT;

SET
	p_keyword = NULLIF(TRIM(p_keyword), '');

SET
	p_page = IFNULL (p_page, 1);

SET
	p_per_page = IFNULL (p_per_page, 10);

SET
	v_offset = (GREATEST (p_page, 1) - 1) * p_per_page;

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
WHERE
	(
		p_keyword IS NULL
		OR c.name LIKE CONCAT ('%', p_keyword, '%')
		OR c.phone LIKE CONCAT ('%', p_keyword, '%')
		OR c.email LIKE CONCAT ('%', p_keyword, '%')
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
