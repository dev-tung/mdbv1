DROP PROCEDURE IF EXISTS sp_purchase_delete;

CREATE PROCEDURE sp_purchase_delete (IN p_id INT) BEGIN DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN ROLLBACK;

RESIGNAL;

END;

START TRANSACTION;

/* =====================================
CHECK EXPORTED
===================================== */
IF EXISTS (
	SELECT
		1
	FROM
		order_items
	WHERE
		purchase_id = p_id
) THEN SIGNAL SQLSTATE '45000'
SET
	MESSAGE_TEXT = 'Phiếu nhập đã được xuất kho, không thể xóa';

END IF;

/* ================================
DELETE ORDERS
================================ */
DELETE o
FROM
	orders o
	INNER JOIN order_items oi ON oi.order_id = o.id
	INNER JOIN inventories i ON i.product_id = oi.product_id
WHERE
	i.purchase_id = p_id;

/* ================================
DELETE ORDER ITEMS
================================ */
DELETE oi
FROM
	order_items oi
	INNER JOIN inventories i ON i.product_id = oi.product_id
WHERE
	i.purchase_id = p_id;

/* ================================
DELETE INVENTORIES
================================ */
DELETE FROM inventories
WHERE
	purchase_id = p_id;

/* ================================
DELETE PURCHASE ITEMS
================================ */
DELETE FROM purchase_items
WHERE
	purchase_id = p_id;

/* ================================
DELETE PURCHASE
================================ */
DELETE FROM purchases
WHERE
	id = p_id;

COMMIT;

END;
