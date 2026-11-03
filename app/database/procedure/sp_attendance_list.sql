DROP PROCEDURE IF EXISTS sp_attendance_list;

CREATE PROCEDURE sp_attendance_list (
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

	ea.id,

	ea.employee_id,

	u.name AS employee_name,

	ea.work_date,

	ea.check_in_at,

	ea.check_out_at,

	ea.check_in_ip,

	ea.check_out_ip,

	ea.check_in_method,

	ea.check_out_method,

	ea.working_minutes,

	ea.status,

	ea.note,

	ea.created_at


FROM
	employee_attendances ea


INNER JOIN
	employees e

ON
	e.id = ea.employee_id


INNER JOIN
	users u

ON
	u.id = e.user_id


WHERE

	(
		p_keyword IS NULL

		OR p_keyword = ''

		OR u.name LIKE CONCAT('%', p_keyword, '%')
	)


	AND

	(
		p_date_from IS NULL

		OR ea.work_date >= p_date_from
	)


	AND

	(
		p_date_to IS NULL

		OR ea.work_date <= p_date_to
	)


ORDER BY

	ea.id DESC


LIMIT
	p_per_page

OFFSET
	v_offset;



/* =================================================
SUMMARY
================================================= */

SELECT

	COUNT(*) AS total,


	SUM(
		CASE
			WHEN ea.check_in_at IS NOT NULL
			THEN 1
			ELSE 0
		END
	) AS total_in,


	SUM(
		CASE
			WHEN ea.check_out_at IS NOT NULL
			THEN 1
			ELSE 0
		END
	) AS total_out


FROM
	employee_attendances ea


INNER JOIN
	employees e

ON
	e.id = ea.employee_id


INNER JOIN
	users u

ON
	u.id = e.user_id


WHERE

	(
		p_keyword IS NULL

		OR p_keyword = ''

		OR u.name LIKE CONCAT('%', p_keyword, '%')
	)


	AND

	(
		p_date_from IS NULL

		OR ea.work_date >= p_date_from
	)


	AND

	(
		p_date_to IS NULL

		OR ea.work_date <= p_date_to
	);


END;