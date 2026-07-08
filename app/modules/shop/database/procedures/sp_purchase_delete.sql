DROP PROCEDURE IF EXISTS sp_purchase_delete;

CREATE PROCEDURE sp_purchase_delete
(
    IN p_id INT
)
BEGIN

    START TRANSACTION;

    DELETE FROM inventories
    WHERE purchase_id = p_id;

    DELETE FROM purchase_items
    WHERE purchase_id = p_id;

    DELETE FROM purchases
    WHERE id = p_id;

    COMMIT;

END