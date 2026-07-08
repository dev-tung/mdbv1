<?php

class SupplierController
{
    public function index(): void
    {
        View::render('supplier/index');
    }

    public function create(): void
    {
        View::render('supplier/create');
    }

    public function edit($id): void
    {
        View::render('supplier/edit', [
            'id' => $id,
        ]);
    }
}
