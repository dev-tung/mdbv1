<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/bootstrap.css') ?>">
</head>

<body>

<?php

    if($menu){
        require BASE_PATH. '/app/common/layouts/admin/navbar.php';
    }

?>