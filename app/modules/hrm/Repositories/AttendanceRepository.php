<?php

namespace App\HRM\Repositories;

use App\Core\Database;
use App\Core\Repository;

class AttendanceRepository extends Repository
{
    protected string $table = 'employee_attendances';

    /* =================================================
       LIST
    ================================================= */

    public function getList(array $filters = []): array
    {
        return Database::call(
            'CALL sp_attendance_list(?, ?, ?, ?, ?)',
            array_params(
                ['keyword', 'date_from', 'date_to', 'page', 'per_page'],
                $filters
            ),
        );
    }

    /* =================================================
      CHECK IN
    ================================================= */

    public function in(int $userId, string $ip): void
    {
        Database::call(
            'CALL sp_attendance_in(?, ?)',
            [
                $userId,
                $ip,
            ]
        );
    }


    /* =================================================
      CHECK OUT
    ================================================= */

    public function out(int $userId, string $ip): void
    {
        Database::call(
            'CALL sp_attendance_out(?, ?)',
            [
                $userId,
                $ip,
            ]
        );
    }
}