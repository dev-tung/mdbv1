DROP PROCEDURE IF EXISTS sp_vat;

CREATE PROCEDURE sp_vat (
    IN p_date_from DATE,
    IN p_date_to DATE,
    IN p_vat_previous DECIMAL(15,2)
)
BEGIN

    /* =========================================================
       BIẾN TỔNG HỢP
    ========================================================= */

    DECLARE v_purchase_value DECIMAL(15,2) DEFAULT 0;
    DECLARE v_purchase_vat DECIMAL(15,2) DEFAULT 0;

    DECLARE v_sales_no_vat_value DECIMAL(15,2) DEFAULT 0;
    DECLARE v_sales_vat_value DECIMAL(15,2) DEFAULT 0;

    DECLARE v_sales_0_value DECIMAL(15,2) DEFAULT 0;
    DECLARE v_sales_0_vat DECIMAL(15,2) DEFAULT 0;

    DECLARE v_sales_5_value DECIMAL(15,2) DEFAULT 0;
    DECLARE v_sales_5_vat DECIMAL(15,2) DEFAULT 0;

    DECLARE v_sales_10_value DECIMAL(15,2) DEFAULT 0;
    DECLARE v_sales_10_vat DECIMAL(15,2) DEFAULT 0;

    DECLARE v_vat_deductible DECIMAL(15,2) DEFAULT 0;

    DECLARE v_vat_arising DECIMAL(15,2) DEFAULT 0;
    DECLARE v_vat_payable DECIMAL(15,2) DEFAULT 0;
    DECLARE v_vat_carry_forward DECIMAL(15,2) DEFAULT 0;


    /* =========================================================
       1. HÀNG HÓA, DỊCH VỤ MUA VÀO
       [23] - Giá trị HHDV mua vào
       [24] - Thuế GTGT mua vào
    ========================================================= */

    SELECT
        COALESCE(SUM(pu.subtotal_amount), 0),
        COALESCE(SUM(pu.vat_amount), 0)

    INTO
        v_purchase_value,
        v_purchase_vat

    FROM purchases pu

    WHERE
        (
            p_date_from IS NULL
            OR DATE(pu.created_at) >= p_date_from
        )
        AND
        (
            p_date_to IS NULL
            OR DATE(pu.created_at) <= p_date_to
        );


    /* =========================================================
       2. THUẾ GTGT ĐƯỢC KHẤU TRỪ KỲ NÀY
       [25]

       Tạm thời lấy toàn bộ VAT mua vào đủ điều kiện
       khấu trừ.
    ========================================================= */

    SET v_vat_deductible = v_purchase_vat;


    /* =========================================================
       3. HÀNG HÓA, DỊCH VỤ BÁN RA
       [26] - Không chịu thuế
       [27] - Tổng chịu thuế
       [28] - VAT của hàng chịu thuế
    ========================================================= */

    SELECT

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) = 0
                    THEN o.subtotal_amount
                    ELSE 0
                END
            ),
            0
        ),

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) > 0
                    THEN o.subtotal_amount
                    ELSE 0
                END
            ),
            0
        ),

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) > 0
                    THEN o.vat_amount
                    ELSE 0
                END
            ),
            0
        )

    INTO

        v_sales_no_vat_value,
        v_sales_vat_value,
        v_sales_vat_value

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


    /* =========================================================
       4. CHI TIẾT THEO THUẾ SUẤT

       [29] / [30]  -> 0%
       [30] / [31]  -> 5%
       [32] / [33]  -> 10%

       LƯU Ý:
       Trong database hiện tại vat_rate nằm ở orders.
    ========================================================= */

    SELECT
        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) = 0
                    THEN o.subtotal_amount
                    ELSE 0
                END
            ),
            0
        ),

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) = 0
                    THEN o.vat_amount
                    ELSE 0
                END
            ),
            0
        ),

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) = 5
                    THEN o.subtotal_amount
                    ELSE 0
                END
            ),
            0
        ),

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) = 5
                    THEN o.vat_amount
                    ELSE 0
                END
            ),
            0
        ),

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) = 10
                    THEN o.subtotal_amount
                    ELSE 0
                END
            ),
            0
        ),

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(o.vat_rate, 0) = 10
                    THEN o.vat_amount
                    ELSE 0
                END
            ),
            0
        )

    INTO
        v_sales_0_value,
        v_sales_0_vat,
        v_sales_5_value,
        v_sales_5_vat,
        v_sales_10_value,
        v_sales_10_vat

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


    /* =========================================================
       5. THUẾ GTGT PHÁT SINH
       [36] = [35] - [25]

       [35] = VAT bán ra
       [25] = VAT được khấu trừ
    ========================================================= */

    SET v_vat_arising =
        v_sales_vat_value - v_vat_deductible;


    /* =========================================================
       6. XÁC ĐỊNH THUẾ PHẢI NỘP / CÒN ĐƯỢC KHẤU TRỪ
    ========================================================= */

    IF (
        v_vat_arising
        - COALESCE(p_vat_previous, 0)
    ) > 0 THEN

        SET v_vat_payable =
            v_vat_arising
            - COALESCE(p_vat_previous, 0);

        SET v_vat_carry_forward = 0;

    ELSE

        SET v_vat_payable = 0;

        SET v_vat_carry_forward =
            ABS(
                v_vat_arising
                - COALESCE(p_vat_previous, 0)
            );

    END IF;


    /* =========================================================
       7. TRẢ KẾT QUẢ

       Toàn bộ chỉ tiêu dùng cho PL01-1
    ========================================================= */

    SELECT

        /* [21] */
        0 AS field_21,

        /* [22] */
        COALESCE(p_vat_previous, 0) AS field_22,

        /* [23] */
        v_purchase_value AS field_23,

        /* [24] */
        v_purchase_vat AS field_24,

        /* [25] */
        v_vat_deductible AS field_25,

        /* [26] */
        v_sales_no_vat_value AS field_26,

        /* [27] */
        (
            v_sales_0_value
            + v_sales_5_value
            + v_sales_10_value
        ) AS field_27,

        /* [28] */
        (
            v_sales_0_vat
            + v_sales_5_vat
            + v_sales_10_vat
        ) AS field_28,

        /* [29] */
        v_sales_0_value AS field_29,

        /* [30] */
        v_sales_0_vat AS field_30,

        /* [31] */
        v_sales_5_vat AS field_31,

        /* [32] */
        v_sales_10_value AS field_32,

        /* [33] */
        v_sales_10_vat AS field_33,

        /* [34] */
        (
            v_sales_no_vat_value
            + v_sales_0_value
            + v_sales_5_value
            + v_sales_10_value
        ) AS field_34,

        /* [35] */
        (
            v_sales_0_vat
            + v_sales_5_vat
            + v_sales_10_vat
        ) AS field_35,

        /* [36] */
        v_vat_arising AS field_36,

        /* [37] */
        0 AS field_37,

        /* [38] */
        0 AS field_38,

        /* [39a] */
        0 AS field_39a,

        /* [40a] */
        v_vat_payable AS field_40a,

        /* [40b] */
        0 AS field_40b,

        /* [40] */
        v_vat_payable AS field_40,

        /* [41] */
        v_vat_carry_forward AS field_41,

        /* [42] */
        0 AS field_42,

        /* [43] */
        v_vat_carry_forward AS field_43;

END;