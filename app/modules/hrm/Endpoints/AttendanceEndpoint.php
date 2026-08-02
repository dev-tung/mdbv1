<?php

namespace App\HRM\Endpoints;

use App\Core\Response;
use App\HRM\Repositories\AttendanceRepository;
use App\Core\Auth;

class AttendanceEndpoint
{
    private readonly AttendanceRepository $attendanceRepository;


    public function __construct()
    {
        $this->attendanceRepository = new AttendanceRepository();
    }


    // =========================
    // LIST
    // =========================

    public function apiList()
    {
        $data = $this->attendanceRepository->getList(request_all());

        return Response::json([
            'success' => true,
            'data' => $data,
        ]);
    }


    // =========================
    // CHECK IN
    // =========================

    public function apiIn()
    {
        check_wifi();

        $this->attendanceRepository->in(
            Auth::id(),
            current_ip()
        );

        return Response::json([
            'success' => true,
            'message' => 'Check-in thành công!',
        ]);
    }


    // =========================
    // CHECK OUT
    // =========================

    public function apiOut()
    {
        check_wifi();

        $this->attendanceRepository->out(
            Auth::id(),
            current_ip()
        );

        return Response::json([
            'success' => true,
            'message' => 'Check-out thành công!',
        ]);
    }
}