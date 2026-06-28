<?php

class CategoryController
{
    public function index(): void
    {
        View::render('category/index');
    }

    public function create(): void
    {
        View::render('category/create');
    }

    public function edit($id): void
    {
        View::render('category/edit', [
            'id' => $id
        ]);
    }
}
