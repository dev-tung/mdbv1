DROP PROCEDURE IF EXISTS sp_purchase_update;

CREATE PROCEDURE sp_purchase_update (
	IN p_purchase_id INT,
	IN p_supplier_id INT,
	IN p_warehouse_id INT,
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
	IN p_items JSON
) BEGIN DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN ROLLBACK;

RESIGNAL;

END;

START TRANSACTION;

/* =====================================
CHECK PURCHASE USED
===================================== */
IF EXISTS (
	SELECT
		1
	FROM
		order_items
	WHERE
		purchase_id = p_purchase_id
	LIMIT
		1
) THEN SIGNAL SQLSTATE '45000'
SET
	MESSAGE_TEXT = 'Phiếu nhập đã được xuất hàng, không thể cập nhật.';

END IF;

/* =====================================
UPDATE PURCHASE
===================================== */
UPDATE purchases
SET
	supplier_id = p_supplier_id,
	warehouse_id = p_warehouse_id,
	description = p_description,
	note = p_note,
	status = p_status,
	payment = p_payment,
	subtotal_amount = p_subtotal_amount,
	vat_rate = p_vat_rate,
	vat_amount = p_vat_amount,
	total_amount = p_total_amount,
	paid_amount = p_paid_amount,
	debt_amount = p_debt_amount,
	updated_at = NOW ()
WHERE
	id = p_purchase_id;

/* =====================================
DELETE OLD PURCHASE ITEMS
===================================== */
DELETE FROM purchase_items
WHERE
	purchase_id = p_purchase_id;

/* =====================================
INSERT PURCHASE ITEMS
===================================== */
INSERT INTO
	purchase_items (
		purchase_id,
		product_id,
		product_name,
		quantity,
		purchase_price,
		selling_price,
		subtotal_amount,
		vat_amount,
		total_amount
	)
SELECT
	p_purchase_id,
	product_id,
	product_name,
	quantity,
	purchase_price,
	selling_price,
	subtotal_amount,
	vat_amount,
	total_amount
FROM
	JSON_TABLE (
		p_items,
		'$[*]' COLUMNS (
			product_id INT PATH '$.product_id',
			product_name VARCHAR(255) PATH '$.product_name',
			quantity INT PATH '$.quantity',
			purchase_price DECIMAL(15, 2) PATH '$.purchase_price',
			selling_price DECIMAL(15, 2) PATH '$.selling_price',
			subtotal_amount DECIMAL(15, 2) PATH '$.subtotal_amount',
			vat_amount DECIMAL(15, 2) PATH '$.vat_amount',
			total_amount DECIMAL(15, 2) PATH '$.total_amount'
		)
	) jt;

/* =====================================
DELETE INVENTORY
===================================== */
DELETE FROM inventories
WHERE
	purchase_id = p_purchase_id;

/* =====================================
CREATE INVENTORY
===================================== */
IF p_status = 'completed' THEN
INSERT INTO
	inventories (
		purchase_id,
		product_id,
		product_name,
		purchase_price,
		selling_price,
		quantity,
		vat_amount,
		total_amount
	)
SELECT
	p_purchase_id,
	product_id,
	product_name,
	purchase_price,
	selling_price,
	quantity,
	vat_amount,
	total_amount
FROM
	JSON_TABLE (
		p_items,
		'$[*]' COLUMNS (
			product_id INT PATH '$.product_id',
			product_name VARCHAR(255) PATH '$.product_name',
			quantity INT PATH '$.quantity',
			purchase_price DECIMAL(15, 2) PATH '$.purchase_price',
			selling_price DECIMAL(15, 2) PATH '$.selling_price',
			vat_amount DECIMAL(15, 2) PATH '$.vat_amount',
			total_amount DECIMAL(15, 2) PATH '$.total_amount'
		)
	) jt;

END IF;

COMMIT;

SELECT
	p_purchase_id AS id;

END;
