DROP PROCEDURE IF EXISTS sp_attendance_out;

CREATE PROCEDURE sp_attendance_out (
    IN p_user_id INT,
    IN p_ip VARCHAR(45)
)
BEGIN

    DECLARE v_employee_id INT;
    DECLARE v_attendance_id INT;



    /* =================================================
       FIND EMPLOYEE
    ================================================= */

    SELECT
        e.id
    INTO
        v_employee_id
    FROM
        employees e
    WHERE
        e.user_id = p_user_id
    LIMIT 1;



    IF v_employee_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Không tìm thấy nhân viên.';
    END IF;



    /* =================================================
       FIND TODAY ATTENDANCE
    ================================================= */

    SELECT
        ea.id
    INTO
        v_attendance_id
    FROM
        employee_attendances ea
    WHERE
        ea.employee_id = v_employee_id
        AND ea.work_date = CURDATE()
    LIMIT 1;



    IF v_attendance_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Bạn chưa check-in hôm nay.';
    END IF;



    /* =================================================
       CHECK OUT EXIST
    ================================================= */

    IF EXISTS (

        SELECT
            1

        FROM
            employee_attendances ea

        WHERE
            ea.id = v_attendance_id
            AND ea.check_out_at IS NOT NULL

    ) THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Bạn đã check-out hôm nay.';

    END IF;



    /* =================================================
       UPDATE CHECK OUT
    ================================================= */

    UPDATE
        employee_attendances

    SET
        check_out_at = NOW(),

        check_out_ip = p_ip,

        check_out_method = 'wifi',

        working_minutes = TIMESTAMPDIFF(
            MINUTE,
            check_in_at,
            NOW()
        ),

        status = 'completed',

        updated_by = p_user_id

    WHERE
        id = v_attendance_id;


END;