DROP PROCEDURE IF EXISTS sp_order_payment;

CREATE PROCEDURE `sp_order_payment` (IN p_id INT, IN p_payment VARCHAR(20)) BEGIN
UPDATE orders
SET
    payment = p_payment,
    paid_amount = CASE
        WHEN p_payment = 'paid' THEN total_amount
        WHEN p_payment = 'unpaid' THEN 0
        ELSE paid_amount
    END,
    debt_amount = CASE
        WHEN p_payment = 'paid' THEN 0
        WHEN p_payment = 'unpaid' THEN total_amount
        ELSE debt_amount
    END
WHERE
    id = p_id;

SELECT
    ROW_COUNT () AS affected_rows;

END
