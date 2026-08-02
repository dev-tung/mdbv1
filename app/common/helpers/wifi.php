<?php

const ATTENDANCE_IP = '172.20.0.1';


function current_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '';
}


function check_wifi(): void
{
    $ip = current_ip();

    if ($ip !== ATTENDANCE_IP) {

        throw new Exception(
            'Bạn không kết nối WiFi của cửa hàng. IP hiện tại: ' . $ip
        );

    }
}