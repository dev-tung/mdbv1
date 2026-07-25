DROP PROCEDURE IF EXISTS sp_report_revenue;

CREATE PROCEDURE sp_report_revenue (
    IN p_mode VARCHAR(10),
    IN p_date DATE,
    IN p_month INT,
    IN p_year INT
)
BEGIN

    /* ===========================================
       RESULT 1: CHI TIẾT
    =========================================== */

    SELECT
        o.id AS revenue_id,
        o.created_at,

        oi.purchase_id,

        oi.product_id,
        oi.product_name,

        oi.quantity,

        /* Giá nhập đã gồm VAT */
        (pi.total_amount / pi.quantity) AS purchase_price,

        /* Giá bán */
        oi.selling_price,

        /* Giá vốn đã gồm VAT */
        ((pi.total_amount / pi.quantity) * oi.quantity) AS cost,

        /* Doanh thu */
        oi.subtotal_amount AS revenue_subtotal,
        oi.vat_rate,
        oi.vat_amount AS revenue_vat,
        oi.total_amount AS revenue,

        /* Lợi nhuận */
        (
            oi.total_amount
            - ((pi.total_amount / pi.quantity) * oi.quantity)
        ) AS profit

    FROM orders o

    INNER JOIN order_items oi
        ON oi.order_id = o.id

    INNER JOIN purchase_items pi
        ON pi.purchase_id = oi.purchase_id
        AND pi.product_id = oi.product_id

    WHERE
        (
            (
                p_mode = 'day'
                AND DATE(o.created_at) = p_date
            )

            OR

            (
                p_mode = 'month'
                AND YEAR(o.created_at) = p_year
                AND MONTH(o.created_at) = p_month
            )

            OR

            (
                p_mode = 'year'
                AND YEAR(o.created_at) = p_year
            )
        )

    ORDER BY
        profit DESC,
        o.created_at DESC,
        o.id DESC;


    /* ===========================================
       RESULT 2: TỔNG HỢP
    =========================================== */

    SELECT
        COUNT(DISTINCT o.id) AS total_orders,

        COALESCE(
            SUM(oi.quantity),
            0
        ) AS total_quantity,

        COALESCE(
            SUM(oi.subtotal_amount),
            0
        ) AS total_revenue_subtotal,

        COALESCE(
            SUM(oi.vat_amount),
            0
        ) AS total_revenue_vat,

        COALESCE(
            SUM(oi.total_amount),
            0
        ) AS total_revenue,

        COALESCE(
            SUM((pi.total_amount / pi.quantity) * oi.quantity),
            0
        ) AS total_cost,

        COALESCE(
            SUM(
                oi.total_amount
                - ((pi.total_amount / pi.quantity) * oi.quantity)
            ),
            0
        ) AS total_profit

    FROM orders o

    INNER JOIN order_items oi
        ON oi.order_id = o.id

    INNER JOIN purchase_items pi
        ON pi.purchase_id = oi.purchase_id
        AND pi.product_id = oi.product_id

    WHERE
        (
            (
                p_mode = 'day'
                AND DATE(o.created_at) = p_date
            )

            OR

            (
                p_mode = 'month'
                AND YEAR(o.created_at) = p_year
                AND MONTH(o.created_at) = p_month
            )

            OR

            (
                p_mode = 'year'
                AND YEAR(o.created_at) = p_year
            )
        );

END;