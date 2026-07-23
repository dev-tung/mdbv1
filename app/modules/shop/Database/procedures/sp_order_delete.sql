DROP PROCEDURE IF EXISTS sp_order_delete;

CREATE PROCEDURE sp_order_delete (IN p_id INT) BEGIN DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN ROLLBACK;

RESIGNAL;

END;

START TRANSACTION;

/* =====================================
CHECK ORDER
===================================== */
IF NOT EXISTS (
	SELECT
		1
	FROM
		orders
	WHERE
		id = p_id
) THEN SIGNAL SQLSTATE '45000'
SET
	MESSAGE_TEXT = 'Đơn hàng không tồn tại';

END IF;

/* =====================================
RESTORE INVENTORY
CỘNG LẠI TỒN KHO
===================================== */
UPDATE inventories i
INNER JOIN order_items oi ON oi.purchase_id = i.purchase_id
AND oi.product_id = i.product_id
SET
	i.quantity = i.quantity + oi.quantity
WHERE
	oi.order_id = p_id;

/* =====================================
DELETE ORDER ITEMS
===================================== */
DELETE FROM order_items
WHERE
	order_id = p_id;

/* =====================================
DELETE ORDER
===================================== */
DELETE FROM orders
WHERE
	id = p_id;

COMMIT;

END;
