DROP PROCEDURE IF EXISTS sp_vat_pl011;

CREATE PROCEDURE sp_vat_pl011 (
    IN p_date_from DATE,
    IN p_date_to DATE,
    IN p_page INT,
    IN p_per_page INT
)
BEGIN

    /* =====================================================
       RESULT 1
       DANH SÁCH HÓA ĐƠN, CHỨNG TỪ BÁN RA
    ===================================================== */

    SELECT

        o.id AS order_id,

        /* Mẫu số HĐ */
        NULL AS invoice_form,

        /* Ký hiệu HĐ */
        NULL AS invoice_symbol,

        /* Số hóa đơn */
        NULL AS invoice_number,

        /* Ngày lập hóa đơn */
        DATE(o.created_at) AS invoice_date,

        /* Khách hàng */
        o.customer_id,

        /* Tên người mua */
        COALESCE(
            c.name,
            ''
        ) AS customer_name,

        /* MST người mua
           Hiện tại bảng customers chưa có cột MST */
        NULL AS customer_tax_code,

        /* Doanh thu chưa có thuế GTGT */
        COALESCE(
            o.subtotal_amount,
            0
        ) AS value,

        /* Thuế GTGT */
        COALESCE(
            o.vat_amount,
            0
        ) AS vat,

        /* Thuế suất */
        COALESCE(
            o.vat_rate,
            0
        ) AS vat_rate,

        /* Ghi chú */
        COALESCE(
            o.note,
            ''
        ) AS note

    FROM orders o

    LEFT JOIN customers c
        ON c.id = o.customer_id

    WHERE
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
        o.id ASC;


    /* =====================================================
       RESULT 2
       TỔNG TOÀN BỘ HÓA ĐƠN BÁN RA
    ===================================================== */

    SELECT

        COUNT(*) AS total,

        COALESCE(
            SUM(o.subtotal_amount),
            0
        ) AS total_value,

        COALESCE(
            SUM(o.vat_amount),
            0
        ) AS total_vat

    FROM orders o

    WHERE
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