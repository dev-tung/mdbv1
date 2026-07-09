DROP PROCEDURE IF EXISTS sp_purchase_status;

CREATE PROCEDURE sp_purchase_status (IN p_id INT, IN p_status VARCHAR(20)) BEGIN DECLARE v_old_status VARCHAR(20);

DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN ROLLBACK;

RESIGNAL;

END;

START TRANSACTION;

/* =====================================
CHECK PURCHASE
===================================== */
IF NOT EXISTS (
    SELECT
        1
    FROM
        purchases
    WHERE
        id = p_id
) THEN SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Phiếu nhập không tồn tại';

END IF;

/* =====================================
CHECK STATUS
===================================== */
IF p_status NOT IN ('draft', 'confirmed', 'received') THEN SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Trạng thái không hợp lệ';

END IF;

/* =====================================
LOCK PURCHASE
===================================== */
SELECT
    status INTO v_old_status
FROM
    purchases
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
CHECK EXPORTED
===================================== */
IF EXISTS (
    SELECT
        1
    FROM
        order_items
    WHERE
        purchase_id = p_id
    LIMIT
        1
) THEN SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Phiếu nhập đã được xuất kho, không thể thay đổi trạng thái';

END IF;

/* =====================================
RECEIVED -> OTHER
===================================== */
IF v_old_status = 'received'
AND p_status <> 'received' THEN
DELETE FROM inventories
WHERE
    purchase_id = p_id;

END IF;

/* =====================================
OTHER -> RECEIVED
===================================== */
IF v_old_status <> 'received'
AND p_status = 'received' THEN
DELETE FROM inventories
WHERE
    purchase_id = p_id;

INSERT INTO
    inventories (
        purchase_id,
        product_id,
        product_name,
        purchase_price,
        selling_price,
        quantity,
        vat_rate,
        vat_amount,
        total_amount,
        total_amount_with_vat
    )
SELECT
    p.id,
    pi.product_id,
    pi.product_name,
    pi.purchase_price,
    pi.selling_price,
    pi.quantity,
    pi.vat_rate,
    pi.vat_amount,
    pi.total_amount,
    pi.total_amount_with_vat
FROM
    purchases p
    INNER JOIN purchase_items pi ON pi.purchase_id = p.id
WHERE
    p.id = p_id;

END IF;

/* =====================================
UPDATE STATUS
===================================== */
UPDATE purchases
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
