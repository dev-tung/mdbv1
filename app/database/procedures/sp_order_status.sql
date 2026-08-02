DROP PROCEDURE IF EXISTS sp_order_status;

CREATE PROCEDURE sp_order_status (IN p_id INT, IN p_status VARCHAR(20)) BEGIN DECLARE v_old_status VARCHAR(20);

DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN ROLLBACK;

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
CHECK STATUS
===================================== */
IF p_status NOT IN ('pending', 'completed') THEN SIGNAL SQLSTATE '45000'
SET
	MESSAGE_TEXT = 'Trạng thái không hợp lệ';

END IF;

/* =====================================
LOCK ORDER
===================================== */
SELECT
	status INTO v_old_status
FROM
	orders
WHERE
	id = p_id FOR
UPDATE;

/* =====================================
STATUS NOT CHANGED
===================================== */
IF v_old_status = p_status THEN COMMIT;

SELECT
	TRUE AS success,
	p_id AS id,
	p_status AS status,
	'Trạng thái không thay đổi' AS message;

ELSE
/* =====================================
UPDATE STATUS
===================================== */
UPDATE orders
SET
	status = p_status
WHERE
	id = p_id;

COMMIT;

SELECT
	TRUE AS success,
	p_id AS id,
	p_status AS status,
	'Cập nhật trạng thái thành công' AS message;

END IF;

END;
