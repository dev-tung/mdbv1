DROP PROCEDURE IF EXISTS sp_order_create;

CREATE PROCEDURE sp_order_create (
	IN p_customer_id INT,
	IN p_description VARCHAR(255),
	IN p_note TEXT,
	IN p_status VARCHAR(20),
	IN p_payment VARCHAR(20),
	IN p_subtotal_amount DECIMAL(15, 2),
	IN p_vat_rate DECIMAL(5, 2),
	IN p_vat_amount DECIMAL(15, 2),
	IN p_total_amount DECIMAL(15, 2),
	IN p_paid_amount DECIMAL(15, 2),
	IN p_debt_amount DECIMAL(15, 2),
	IN p_created_by INT,
	IN p_items JSON
) BEGIN DECLARE v_order_id INT;

DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN ROLLBACK;

RESIGNAL;

END;

START TRANSACTION;

/* =====================================
CREATE ORDER
===================================== */
INSERT INTO
	orders (
		customer_id,
		description,
		note,
		status,
		payment,
		subtotal_amount,
		vat_rate,
		vat_amount,
		total_amount,
		paid_amount,
		debt_amount,
		created_by
	)
VALUES
	(
		p_customer_id,
		p_description,
		p_note,
		p_status,
		p_payment,
		p_subtotal_amount,
		p_vat_rate,
		p_vat_amount,
		p_total_amount,
		p_paid_amount,
		p_debt_amount,
		p_created_by
	);

SET
	v_order_id = LAST_INSERT_ID ();

/* =====================================
CREATE ORDER ITEMS
===================================== */
INSERT INTO
	order_items (
		order_id,
		product_id,
		product_name,
		quantity,
		selling_price,
		subtotal_amount,
		vat_amount,
		total_amount,
		is_gift
	)
SELECT
	v_order_id,
	product_id,
	product_name,
	quantity,
	selling_price,
	subtotal_amount,
	vat_amount,
	total_amount,
	is_gift
FROM
	JSON_TABLE (
		p_items,
		'$[*]' COLUMNS (
			product_id INT PATH '$.product_id',
			product_name VARCHAR(255) PATH '$.product_name',
			quantity INT PATH '$.quantity',
			selling_price DECIMAL(15, 2) PATH '$.selling_price',
			subtotal_amount DECIMAL(15, 2) PATH '$.subtotal_amount',
			vat_amount DECIMAL(15, 2) PATH '$.vat_amount',
			total_amount DECIMAL(15, 2) PATH '$.total_amount',
			is_gift INT PATH '$.is_gift'
		)
	) jt;

COMMIT;

SELECT
	v_order_id AS id;

END;
