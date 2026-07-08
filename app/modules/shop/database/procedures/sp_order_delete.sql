DROP PROCEDURE IF EXISTS sp_order_delete;

CREATE PROCEDURE sp_order_delete (IN p_id INT) BEGIN START TRANSACTION;

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

DELETE FROM order_items
WHERE
    order_id = p_id;

DELETE FROM orders
WHERE
    id = p_id;

COMMIT;

END;
