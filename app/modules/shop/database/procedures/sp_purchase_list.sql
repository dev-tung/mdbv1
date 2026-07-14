DROP PROCEDURE IF EXISTS sp_purchase_list;

CREATE PROCEDURE `sp_purchase_list` (
	IN p_date_from DATE,
	IN p_date_to DATE,
	IN p_supplier VARCHAR(255),
	IN p_payment VARCHAR(50),
	IN p_page INT,
	IN p_per_page INT
)
BEGIN
	DECLARE v_offset INT;

	SET v_offset = (GREATEST(p_page, 1) - 1) * p_per_page;

	SELECT
		p.id,
		s.name AS supplier_name,
		w.name AS warehouse_name,
		p.total_amount,
		p.paid_amount,
		p.debt_amount,
		p.status,
		p.payment,
		p.created_at
	FROM purchases p
	LEFT JOIN suppliers s ON s.id = p.supplier_id
	LEFT JOIN warehouses w ON w.id = p.warehouse_id
	WHERE
		(
			p_supplier IS NULL
			OR p_supplier = ''
			OR s.name LIKE CONCAT('%', p_supplier, '%')
		)
		AND (
			p_payment IS NULL
			OR p_payment = ''
			OR p.payment = p_payment
		)
		AND (
			p_date_from IS NULL
			OR DATE(p.created_at) >= p_date_from
		)
		AND (
			p_date_to IS NULL
			OR DATE(p.created_at) <= p_date_to
		)
	ORDER BY p.id DESC
	LIMIT p_per_page
	OFFSET v_offset;

	SELECT
		COUNT(*) AS total,
		COALESCE(SUM(p.total_amount), 0) AS total_amount,
		COALESCE(SUM(p.paid_amount), 0) AS paid_amount,
		COALESCE(SUM(p.debt_amount), 0) AS debt_amount
	FROM purchases p
	LEFT JOIN suppliers s ON s.id = p.supplier_id
	WHERE
		(
			p_supplier IS NULL
			OR p_supplier = ''
			OR s.name LIKE CONCAT('%', p_supplier, '%')
		)
		AND (
			p_payment IS NULL
			OR p_payment = ''
			OR p.payment = p_payment
		)
		AND (
			p_date_from IS NULL
			OR DATE(p.created_at) >= p_date_from
		)
		AND (
			p_date_to IS NULL
			OR DATE(p.created_at) <= p_date_to
		);

END;