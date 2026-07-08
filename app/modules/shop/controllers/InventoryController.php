<?php

class InventoryController
{
    public function index(): void
    {
        View::render('inventory/index');
    }

    public function create(): void
    {
        View::render('inventory/create');
    }

    public function edit($id): void
    {
        View::render('inventory/edit', [
            'id' => $id,
        ]);
    }
}
