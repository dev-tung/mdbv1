DROP PROCEDURE IF EXISTS sp_purchase_vatpl204;

CREATE PROCEDURE `sp_purchase_vatpl204` (
    IN p_date_from DATE,
    IN p_date_to DATE,
    IN p_page INT,
    IN p_per_page INT
)
BEGIN


    /* =====================================================
       RESULT 1
       BẢNG 1 - HÀNG HÓA, DỊCH VỤ MUA VÀO
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
        (
            p_date_from IS NULL
            OR DATE(pu.created_at) >= p_date_from
        )
        AND (
            p_date_to IS NULL
            OR DATE(pu.created_at) <= p_date_to
        )

    ORDER BY
        pi.purchase_id ASC;


    /* =====================================================
       RESULT 2
       TỔNG BẢNG 1
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
        (
            p_date_from IS NULL
            OR DATE(pu.created_at) >= p_date_from
        )
        AND (
            p_date_to IS NULL
            OR DATE(pu.created_at) <= p_date_to
        );


    /* =====================================================
       RESULT 3
       BẢNG 2 - HÀNG HÓA, DỊCH VỤ BÁN RA

       Tạm thời chưa xác định bảng bán hàng
    ===================================================== */

    SELECT
        NULL AS id,
        NULL AS name,
        0 AS value,
        0 AS vat_rate,
        0 AS vat

    WHERE 1 = 0;


    /* =====================================================
       RESULT 4
       TỔNG BẢNG 2
    ===================================================== */

    SELECT
        0 AS total,
        0 AS total_value,
        0 AS total_vat;

END;