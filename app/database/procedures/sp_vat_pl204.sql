DROP PROCEDURE IF EXISTS sp_vat_pl204;

CREATE PROCEDURE `sp_vat_pl204` (
    IN p_date_from DATE,
    IN p_date_to DATE,
    IN p_page INT,
    IN p_per_page INT
)
BEGIN

    /* =====================================================
       RESULT 1
       BẢNG 1 - HÀNG HÓA, DỊCH VỤ MUA VÀO

       CHỈ LẤY PHIẾU MUA CÓ VAT > 0
    ===================================================== */

    SELECT
        pi.purchase_id,
        pi.product_id,
        pi.product_name AS name,
        pi.quantity,
        pi.purchase_price,
        pi.subtotal_amount AS value,
        pi.vat_amount AS vat,
        pi.total_amount,
        pu.created_at

    FROM purchase_items pi

    INNER JOIN purchases pu
        ON pu.id = pi.purchase_id

    WHERE

        /* CHỈ LẤY PHIẾU MUA CÓ VAT > 0 */
        COALESCE(pu.vat_rate, 0) > 0

        AND
        (
            p_date_from IS NULL
            OR DATE(pu.created_at) >= p_date_from
        )

        AND
        (
            p_date_to IS NULL
            OR DATE(pu.created_at) <= p_date_to
        )

    ORDER BY
        pi.purchase_id ASC;


    /* =====================================================
       RESULT 2
       TỔNG BẢNG 1 - MUA VÀO

       CHỈ TÍNH PHIẾU MUA CÓ VAT > 0
    ===================================================== */

    SELECT
        COUNT(*) AS total,

        COALESCE(
            SUM(pi.subtotal_amount),
            0
        ) AS total_value,

        COALESCE(
            SUM(pi.vat_amount),
            0
        ) AS total_vat

    FROM purchase_items pi

    INNER JOIN purchases pu
        ON pu.id = pi.purchase_id

    WHERE

        /* CHỈ TÍNH PHIẾU MUA CÓ VAT > 0 */
        COALESCE(pu.vat_rate, 0) > 0

        AND
        (
            p_date_from IS NULL
            OR DATE(pu.created_at) >= p_date_from
        )

        AND
        (
            p_date_to IS NULL
            OR DATE(pu.created_at) <= p_date_to
        );


    /* =====================================================
       RESULT 3
       BẢNG 2 - HÀNG HÓA, DỊCH VỤ BÁN RA

       CHỈ LẤY ĐƠN HÀNG CÓ VAT > 0

       TÍNH SẴN:
       - vat_rate
       - vat_rate_after_reduction
       - vat_reduced
    ===================================================== */

    SELECT
        oi.order_id,
        oi.product_id,
        oi.product_name AS name,
        oi.quantity,
        oi.selling_price,

        oi.subtotal_amount AS value,

        o.vat_rate,

        CASE
            WHEN COALESCE(o.vat_rate, 0) > 0
            THEN o.vat_rate * 0.8
            ELSE 0
        END AS vat_rate_after_reduction,

        CASE
            WHEN COALESCE(o.vat_rate, 0) > 0
            THEN
                oi.subtotal_amount
                * (
                    (
                        o.vat_rate
                        - (o.vat_rate * 0.8)
                    ) / 100
                )
            ELSE 0
        END AS vat_reduced,

        oi.vat_amount AS vat,

        oi.total_amount,

        o.created_at

    FROM order_items oi

    INNER JOIN orders o
        ON o.id = oi.order_id

    WHERE

        /* CHỈ LẤY ĐƠN HÀNG CÓ VAT > 0 */
        COALESCE(o.vat_rate, 0) > 0

        AND
        (
            p_date_from IS NULL
            OR DATE(o.created_at) >= p_date_from
        )

        AND
        (
            p_date_to IS NULL
            OR DATE(o.created_at) <= p_date_to
        )

    ORDER BY
        oi.order_id ASC;


    /* =====================================================
       RESULT 4
       TỔNG BẢNG 2 - BÁN RA

       CHỈ TÍNH ĐƠN HÀNG CÓ VAT > 0
    ===================================================== */

    SELECT
        COUNT(*) AS total,

        COALESCE(
            SUM(oi.subtotal_amount),
            0
        ) AS total_value,

        COALESCE(
            SUM(oi.vat_amount),
            0
        ) AS total_vat,

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) > 0
                    THEN
                        oi.subtotal_amount
                        * (
                            (
                                o.vat_rate
                                - (o.vat_rate * 0.8)
                            ) / 100
                        )
                    ELSE 0
                END
            ),
            0
        ) AS total_vat_reduced

    FROM order_items oi

    INNER JOIN orders o
        ON o.id = oi.order_id

    WHERE

        /* CHỈ TÍNH ĐƠN HÀNG CÓ VAT > 0 */
        COALESCE(o.vat_rate, 0) > 0

        AND
        (
            p_date_from IS NULL
            OR DATE(o.created_at) >= p_date_from
        )

        AND
        (
            p_date_to IS NULL
            OR DATE(o.created_at) <= p_date_to
        );

END;