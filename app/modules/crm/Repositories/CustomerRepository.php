<?php

namespace App\CRM\Repositories;

use App\Core\Database;
use App\Core\Repository;

class CustomerRepository extends Repository
{
	protected string $table = 'customers';


	/* =================================================
	   LIST
	================================================= */

	public function getList(array $filters = []): array
	{
		return Database::call(
			'CALL sp_customer_list(?, ?, ?, ?, ?)',
			array_params(
				[
					'keyword',
					'date_from',
					'date_to',
					'page',
					'per_page'
				],
				$filters
			),
		);
	}


	/* =================================================
	   BUILD DATA
	================================================= */

	private function buildData(array $data): array
	{
		return [
			'name' => $data['name'] ?? null,

			'group_id' =>
				$data['group_id'] ?? null,

			'phone' =>
				$data['phone'] ?? null,

			'email' =>
				$data['email'] ?? null,

			'address' =>
				$data['address'] ?? null,

			'description' =>
				$data['description'] ?? null,
		];
	}

	public function findById(int $id): ?array
	{
		$customer = parent::findById($id);
		if (!$customer) {
			return null;
		}

		$equipment = Database::get(
			'SELECT id, equipment_type, parent_id, brand, model, size,
				string_type, tension, strung_at, note
			FROM customer_equipment
			WHERE customer_id = ?
			ORDER BY FIELD(equipment_type, \'racket\', \'shoe\', \'stringing\'), id',
			[$id],
		);

		$customer['rackets'] = [];
		$customer['shoes'] = [];
		$racketIndexes = [];

		foreach ($equipment as $item) {
			if ($item['equipment_type'] === 'racket') {
				$racketIndexes[$item['id']] = count($customer['rackets']);
				$customer['rackets'][] = [
					'id' => $item['id'],
					'brand' => $item['brand'],
					'model' => $item['model'],
					'note' => $item['note'],
					'stringings' => [],
				];
			} elseif ($item['equipment_type'] === 'shoe') {
				$customer['shoes'][] = [
					'id' => $item['id'],
					'brand' => $item['brand'],
					'model' => $item['model'],
					'size' => $item['size'],
					'note' => $item['note'],
				];
			} elseif (
				$item['equipment_type'] === 'stringing' &&
				isset($racketIndexes[$item['parent_id']])
			) {
				$customer['rackets'][$racketIndexes[$item['parent_id']]]['stringings'][] = [
					'id' => $item['id'],
					'string_type' => $item['string_type'],
					'tension' => $item['tension'],
					'strung_at' => $item['strung_at'],
					'note' => $item['note'],
				];
			}
		}

		return $customer;
	}


	/* =================================================
	   FIND EXISTING CUSTOMER
	================================================= */

	public function findExisting(
		?string $phone = null,
		?string $email = null
	): ?array
	{
		$phone =
			trim((string) $phone);

		$email =
			trim((string) $email);


		// =========================
		// PHONE
		// =========================

		if ($phone !== '') {

			$customer =
				Database::first(
					'SELECT *
					FROM customers
					WHERE phone = ?
					LIMIT 1',
					[$phone]
				);

			if ($customer) {
				return $customer;
			}
		}


		// =========================
		// EMAIL
		// =========================

		if ($email !== '') {

			$customer =
				Database::first(
					'SELECT *
					FROM customers
					WHERE email = ?
					LIMIT 1',
					[$email]
				);

			if ($customer) {
				return $customer;
			}
		}


		return null;
	}


	/* =================================================
	   CREATE
	================================================= */

	public function create(array $data): int
	{
		return Database::transaction(function () use ($data): int {
			$phone = trim((string) ($data['phone'] ?? ''));
			$email = trim((string) ($data['email'] ?? ''));
			$existing = $this->findExisting($phone ?: null, $email ?: null);

			$id = $existing
				? (int) $existing['id']
				: parent::create($this->buildData($data));

			$this->saveEquipment($id, $data);

			return $id;
		});
	}


	/* =================================================
	   UPDATE
	================================================= */

	public function update(
		int $id,
		array $data
	): bool
	{
		return Database::transaction(function () use ($id, $data): bool {
			if (!parent::findById($id)) {
				return false;
			}

			$phone = trim((string) ($data['phone'] ?? ''));
			$email = trim((string) ($data['email'] ?? ''));

			if ($phone !== '') {
				$customer = Database::first(
					'SELECT id FROM customers WHERE phone = ? AND id <> ? LIMIT 1',
					[$phone, $id],
				);
				if ($customer) {
					throw new \Exception('Số điện thoại đã được sử dụng bởi khách hàng khác.');
				}
			}

			if ($email !== '') {
				$customer = Database::first(
					'SELECT id FROM customers WHERE email = ? AND id <> ? LIMIT 1',
					[$email, $id],
				);
				if ($customer) {
					throw new \Exception('Email đã được sử dụng bởi khách hàng khác.');
				}
			}

			parent::updateById($id, $this->buildData($data));
			$this->saveEquipment($id, $data);

			return true;
		});
	}

	private function saveEquipment(int $customerId, array $data): void
	{
		if (array_key_exists('rackets', $data)) {
			foreach ($data['rackets'] as $racket) {
				$racketId = $this->saveRacket($customerId, $racket);
				if ($racketId === null) {
					continue;
				}

				foreach ($racket['stringings'] ?? [] as $record) {
					if (!empty($record['id'])) {
						$existing = Database::first(
							'SELECT id FROM customer_equipment
							WHERE id = ? AND equipment_type = \'stringing\'
								AND parent_id = ? AND parent_type = \'racket\' AND customer_id = ?',
							[(int) $record['id'], $racketId, $customerId],
						);
						if (!$existing) {
							throw new \InvalidArgumentException('Lần căng cước không thuộc cây vợt này.');
						}
						continue;
					}

					if (!$this->hasValue($record, ['string_type', 'tension', 'strung_at', 'note'])) {
						continue;
					}

					Database::query(
						'INSERT INTO customer_equipment (
							equipment_type, customer_id, parent_id, parent_type,
							string_type, tension, strung_at, note
						)
						VALUES (
							\'stringing\', :customer_id, :parent_id, \'racket\',
							:string_type, :tension, :strung_at, :note
						)',
						[
							'customer_id' => $customerId,
							'parent_id' => $racketId,
							'string_type' => $this->nullableText($record['string_type'] ?? ''),
							'tension' => $this->nullableText($record['tension'] ?? ''),
							'strung_at' => $this->nullableText($record['strung_at'] ?? ''),
							'note' => $this->nullableText($record['note'] ?? ''),
						],
					);
				}
			}
		}

		if (array_key_exists('shoes', $data)) {
			foreach ($data['shoes'] as $shoe) {
				$this->saveShoe($customerId, $shoe);
			}
		}
	}

	private function saveRacket(int $customerId, array $racket): ?int
	{
		$hasDetails = $this->hasValue($racket, ['brand', 'model', 'note']);
		$hasStringing = !empty($racket['stringings']);
		$id = (int) ($racket['id'] ?? 0);

		if (!$id && !$hasDetails && !$hasStringing) {
			return null;
		}

		$values = [
			'brand' => $this->nullableText($racket['brand'] ?? ''),
			'model' => $this->nullableText($racket['model'] ?? ''),
			'note' => $this->nullableText($racket['note'] ?? ''),
		];

		if ($id) {
			$existing = Database::first(
				'SELECT id FROM customer_equipment
				WHERE id = ? AND equipment_type = \'racket\' AND customer_id = ?',
				[$id, $customerId],
			);
			if (!$existing) {
				throw new \InvalidArgumentException('Cây vợt không thuộc khách hàng này.');
			}

			Database::execute(
				'UPDATE customer_equipment SET brand = :brand, model = :model, note = :note
				WHERE id = :id AND equipment_type = \'racket\' AND customer_id = :customer_id',
				$values + ['id' => $id, 'customer_id' => $customerId],
			);

			return $id;
		}

		Database::query(
			'INSERT INTO customer_equipment (equipment_type, customer_id, brand, model, note)
			VALUES (\'racket\', :customer_id, :brand, :model, :note)',
			$values + ['customer_id' => $customerId],
		);

		return Database::lastInsertId();
	}

	private function saveShoe(int $customerId, array $shoe): void
	{
		$hasDetails = $this->hasValue($shoe, ['brand', 'model', 'size', 'note']);
		$id = (int) ($shoe['id'] ?? 0);
		if (!$id && !$hasDetails) {
			return;
		}

		$values = [
			'brand' => $this->nullableText($shoe['brand'] ?? ''),
			'model' => $this->nullableText($shoe['model'] ?? ''),
			'size' => $this->nullableText($shoe['size'] ?? ''),
			'note' => $this->nullableText($shoe['note'] ?? ''),
		];

		if ($id) {
			$existing = Database::first(
				'SELECT id FROM customer_equipment
				WHERE id = ? AND equipment_type = \'shoe\' AND customer_id = ?',
				[$id, $customerId],
			);
			if (!$existing) {
				throw new \InvalidArgumentException('Đôi giày không thuộc khách hàng này.');
			}

			Database::execute(
				'UPDATE customer_equipment SET brand = :brand, model = :model, size = :size, note = :note
				WHERE id = :id AND equipment_type = \'shoe\' AND customer_id = :customer_id',
				$values + ['id' => $id, 'customer_id' => $customerId],
			);
			return;
		}

		Database::query(
			'INSERT INTO customer_equipment (equipment_type, customer_id, brand, model, size, note)
			VALUES (\'shoe\', :customer_id, :brand, :model, :size, :note)',
			$values + ['customer_id' => $customerId],
		);
	}

	private function hasValue(array $data, array $fields): bool
	{
		foreach ($fields as $field) {
			if (trim((string) ($data[$field] ?? '')) !== '') {
				return true;
			}
		}

		return false;
	}

	private function nullableText(mixed $value): ?string
	{
		$value = trim((string) $value);

		return $value === '' ? null : $value;
	}


	/* =================================================
	   DELETE
	================================================= */

	public function delete(int $id): bool
	{
		if (!parent::findById($id)) {
			return false;
		}


		$order =
			Database::first(
				'SELECT COUNT(*) AS total
				FROM orders
				WHERE customer_id = ?',
				[$id]
			);


		if (
			($order['total'] ?? 0) > 0
		) {

			throw new \Exception(
				'Khách hàng đã phát sinh đơn hàng, không thể xóa.'
			);
		}


		return parent::deleteById($id) > 0;
	}
}