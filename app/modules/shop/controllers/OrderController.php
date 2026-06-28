<?php

class OrderController
{
    public function index(): void
    {
        View::render('order/index');
    }

    public function create(): void
    {
        View::render('order/create');
    }

    public function edit($id): void
    {
        View::render('order/edit', [
            'id' => $id
        ]);
    }
}
