<?php

class BrandController
{
    public function index(): void
    {
        View::render('brand/index');
    }

    public function create(): void
    {
        View::render('brand/create');
    }

    public function edit($id): void
    {
        View::render('brand/edit', [
            'id' => $id,
        ]);
    }
}
