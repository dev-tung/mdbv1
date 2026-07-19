<?php

class InventoryRepository extends Repository
{
	// =========================
	// LIST
	// =========================
	public function getList(array $filters = []): array
	{
		return Database::get('CALL sp_inventory_list(:keyword)', [
			'keyword' => $filters['keyword'] ?? null,
		]);
	}

	// =========================
	// STOCK
	// =========================
	public function getStock(array $filters = []): array
	{
		return Database::get('CALL sp_inventory_stock(:keyword)', [
			'keyword' => $filters['keyword'] ?? null,
		]);
	}

	// =========================
	// QUANTITY
	// =========================
	public function getQuantity(array $filters = []): ?array
	{
		return Database::first(
			'
			SELECT quantity
			FROM inventories
			WHERE product_id = :product_id
				AND purchase_id = :purchase_id
			LIMIT 1
			',
			[
				'product_id' => $filters['product_id'],
				'purchase_id' => $filters['purchase_id'],
			]
		);
	}
}
