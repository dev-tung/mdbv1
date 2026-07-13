DROP PROCEDURE IF EXISTS sp_purchase_create;

CREATE PROCEDURE sp_purchase_create (
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
	IN p_created_by INT,
	IN p_items JSON
)
BEGIN
	DECLARE v_purchase_id INT;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
		RESIGNAL;
	END;

	START TRANSACTION;

	/* =====================================
	   CREATE PURCHASE
	===================================== */
	INSERT INTO purchases (
		supplier_id,
		warehouse_id,
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
	VALUES (
		p_supplier_id,
		p_warehouse_id,
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

	SET v_purchase_id = LAST_INSERT_ID();

	/* =====================================
	   CREATE PURCHASE ITEMS
	===================================== */
	INSERT INTO purchase_items (
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
		v_purchase_id,
		product_id,
		product_name,
		quantity,
		purchase_price,
		selling_price,
		subtotal_amount,
		vat_amount,
		total_amount
	FROM JSON_TABLE (
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
	   CREATE INVENTORY
	===================================== */
	IF p_status = 'received' THEN

		INSERT INTO inventories (
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
			v_purchase_id,
			product_id,
			product_name,
			purchase_price,
			selling_price,
			quantity,
			vat_amount,
			total_amount
		FROM JSON_TABLE (
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

	SELECT v_purchase_id AS id;

END;