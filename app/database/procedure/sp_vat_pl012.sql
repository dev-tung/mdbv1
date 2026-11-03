DROP PROCEDURE IF EXISTS sp_vat_pl012;

CREATE PROCEDURE sp_vat_pl012 (
    IN p_date_from DATE,
    IN p_date_to DATE,
    IN p_page INT,
    IN p_per_page INT
)
BEGIN

    /* ===================================================== */
    /* BẢNG 1 - DANH SÁCH MUA VÀO */
    /* CHỈ LẤY HÀNG MUA CÓ VAT > 0 */
    /* ===================================================== */

    SELECT

        pu.id AS purchase_id,

        NULL AS invoice_form,
        NULL AS invoice_symbol,
        NULL AS invoice_number,

        DATE(pu.created_at) AS invoice_date,

        pu.supplier_id,

        COALESCE(
            pu.subtotal_amount,
            0
        ) AS value,

        COALESCE(
            pu.vat_amount,
            0
        ) AS vat,

        COALESCE(
            pu.note,
            ''
        ) AS note

    FROM purchases pu

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
        pu.id ASC;


    /* ===================================================== */
    /* TỔNG */
    /* CHỈ TÍNH HÀNG MUA CÓ VAT > 0 */
    /* ===================================================== */

    SELECT

        COUNT(*) AS total,

        COALESCE(
            SUM(pu.subtotal_amount),
            0
        ) AS total_value,

        COALESCE(
            SUM(pu.vat_amount),
            0
        ) AS total_vat

    FROM purchases pu

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

END;