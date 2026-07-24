<?php

namespace App\Website\Controllers;

use App\Core\View;

class ShopController
{
    public function index(): void
    {
        View::render('shop/index');
    }
}