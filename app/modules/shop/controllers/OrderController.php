<?php

class OrderController
{
    public function index(): void
    {
        View::render('order/index');
    }

    public function form($id = null): void
    {
        View::render('order/form', [
            'id' => $id
        ]);
    }
}
