DROP PROCEDURE IF EXISTS sp_order_list;

CREATE PROCEDURE sp_order_list (
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_customer VARCHAR(255) CHARACTER
	SET
		utf8mb4 COLLATE utf8mb4_0900_ai_ci,
		IN p_payment VARCHAR(20) CHARACTER
	SET
		utf8mb4 COLLATE utf8mb4_0900_ai_ci,
		IN p_status VARCHAR(20) CHARACTER
	SET
		utf8mb4 COLLATE utf8mb4_0900_ai_ci
) BEGIN
/* =====================================
DATA
===================================== */
SELECT
	o.id,
	o.customer_id,
	c.name AS customer_name,
	o.description,
	o.note,
	o.status,
	o.payment,
	o.subtotal_amount,
	o.vat_rate,
	o.vat_amount,
	o.total_amount,
	o.paid_amount,
	o.debt_amount,
	o.created_at,
	o.updated_at
FROM
	orders o
	INNER JOIN customers c ON c.id = o.customer_id
WHERE
	(
		p_date_from IS NULL
		OR DATE(o.created_at) >= p_date_from
	)
	AND (
		p_date_to IS NULL
		OR DATE(o.created_at) <= p_date_to
	)
	AND (
		p_customer IS NULL
		OR p_customer = ''
		OR c.name LIKE CONCAT ('%', p_customer, '%')
		OR c.phone LIKE CONCAT ('%', p_customer, '%')
	)
	AND (
		p_payment IS NULL
		OR p_payment = ''
		OR o.payment = p_payment
	)
	AND (
		p_status IS NULL
		OR p_status = ''
		OR o.status = p_status
	)
ORDER BY
	o.id DESC;

/* =====================================
SUMMARY
===================================== */
SELECT
	COUNT(*) AS total,
	COALESCE(SUM(o.total_amount), 0) AS total_amount,
	COALESCE(SUM(o.paid_amount), 0) AS paid_amount,
	COALESCE(SUM(o.debt_amount), 0) AS debt_amount
FROM
	orders o
	INNER JOIN customers c ON c.id = o.customer_id
WHERE
	(
		p_date_from IS NULL
		OR DATE(o.created_at) >= p_date_from
	)
	AND (
		p_date_to IS NULL
		OR DATE(o.created_at) <= p_date_to
	)
	AND (
		p_customer IS NULL
		OR p_customer = ''
		OR c.name LIKE CONCAT ('%', p_customer, '%')
		OR c.phone LIKE CONCAT ('%', p_customer, '%')
	)
	AND (
		p_payment IS NULL
		OR p_payment = ''
		OR o.payment = p_payment
	)
	AND (
		p_status IS NULL
		OR p_status = ''
		OR o.status = p_status
	);

END;
