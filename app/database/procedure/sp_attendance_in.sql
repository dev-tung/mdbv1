DROP PROCEDURE IF EXISTS sp_attendance_in;

CREATE PROCEDURE sp_attendance_in (
    IN p_user_id INT,
    IN p_ip VARCHAR(45)
)
BEGIN

    DECLARE v_employee_id INT;
    DECLARE v_total INT;


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
       CHECK EXIST TODAY
    ================================================= */

    SELECT
        COUNT(*)
    INTO
        v_total
    FROM
        employee_attendances ea
    WHERE
        ea.employee_id = v_employee_id
        AND ea.work_date = CURDATE();



    IF v_total > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Bạn đã check-in hôm nay.';
    END IF;



    /* =================================================
       INSERT CHECK IN
    ================================================= */

    INSERT INTO employee_attendances
    (
        employee_id,
        work_date,
        check_in_at,
        check_in_ip,
        check_in_method,
        status,
        created_by
    )
    VALUES
    (
        v_employee_id,
        CURDATE(),
        NOW(),
        p_ip,
        'wifi',
        'working',
        p_user_id
    );


END;