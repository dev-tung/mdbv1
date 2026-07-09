DROP PROCEDURE IF EXISTS sp_order_create;

CREATE PROCEDURE sp_order_create (
	IN p_customer_id INT,
	IN p_description VARCHAR(255),
	IN p_note TEXT,
	IN p_status VARCHAR(20),
	IN p_payment VARCHAR(20),
	IN p_subtotal_amount DECIMAL(15, 2),
	IN p_discount_amount DECIMAL(15, 2),
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
		discount_amount,
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
		p_discount_amount,
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
		purchase_id,
		product_id,
		product_name,
		quantity,
		purchase_price,
		selling_price,
		subtotal_amount,
		discount_amount,
		is_gift,
		vat_rate,
		vat_amount,
		total_amount
	)
SELECT
	v_order_id,
	purchase_id,
	product_id,
	product_name,
	quantity,
	purchase_price,
	selling_price,
	subtotal_amount,
	discount_amount,
	is_gift,
	vat_rate,
	vat_amount,
	total_amount
FROM
	JSON_TABLE (
		p_items,
		'$[*]' COLUMNS (
			purchase_id INT PATH '$.purchase_id',
			product_id INT PATH '$.product_id',
			product_name VARCHAR(255) PATH '$.product_name',
			quantity INT PATH '$.quantity',
			purchase_price DECIMAL(15, 2) PATH '$.purchase_price',
			selling_price DECIMAL(15, 2) PATH '$.selling_price',
			subtotal_amount DECIMAL(15, 2) PATH '$.subtotal_amount',
			discount_amount DECIMAL(15, 2) PATH '$.discount_amount',
			is_gift TINYINT PATH '$.is_gift',
			vat_rate DECIMAL(5, 2) PATH '$.vat_rate',
			vat_amount DECIMAL(15, 2) PATH '$.vat_amount',
			total_amount DECIMAL(15, 2) PATH '$.total_amount'
		)
	) jt;

/* =====================================
UPDATE INVENTORY
TRỪ TỒN KHO
===================================== */
UPDATE inventories i
INNER JOIN JSON_TABLE (
	p_items,
	'$[*]' COLUMNS (
		purchase_id INT PATH '$.purchase_id',
		product_id INT PATH '$.product_id',
		quantity INT PATH '$.quantity'
	)
) jt ON i.purchase_id = jt.purchase_id
AND i.product_id = jt.product_id
SET
	i.quantity = i.quantity - jt.quantity;

COMMIT;

END;
