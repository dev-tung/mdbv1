<?php

class ProductController
{
	/**
	 * VIEW (UI shell - không load data products nếu dùng fetch)
	 */
	public function index(): void
	{
		View::render('product/index');
	}

	public function create(): void
	{
		View::render('product/create');
	}

	public function edit($id): void
	{
		View::render('product/edit', compact('id'));
	}
}
