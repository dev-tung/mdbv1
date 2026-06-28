<?php

class PurchaseController
{
    public function index(): void
    {
        View::render('purchase/index');
    }

    public function create(): void
    {
        View::render('purchase/create');
    }

    public function edit($id): void
    {
        View::render('purchase/edit', [
            'id' => $id
        ]);
    }
}
