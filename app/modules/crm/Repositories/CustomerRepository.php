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
		$phone =
			trim((string) ($data['phone'] ?? ''));

		$email =
			trim((string) ($data['email'] ?? ''));


		// =========================
		// CHECK EXISTING CUSTOMER
		// =========================

		$existing =
			$this->findExisting(
				$phone ?: null,
				$email ?: null
			);


		if ($existing) {

			return (int) $existing['id'];
		}


		// =========================
		// CREATE
		// =========================

		return parent::create(
			$this->buildData($data)
		);
	}


	/* =================================================
	   UPDATE
	================================================= */

	public function update(
		int $id,
		array $data
	): bool
	{
		if (!parent::findById($id)) {
			return false;
		}


		$phone =
			trim((string) ($data['phone'] ?? ''));

		$email =
			trim((string) ($data['email'] ?? ''));


		// =========================
		// CHECK PHONE
		// =========================

		if ($phone !== '') {

			$customer =
				Database::first(
					'SELECT id
					FROM customers
					WHERE phone = ?
					AND id <> ?
					LIMIT 1',
					[
						$phone,
						$id
					]
				);

			if ($customer) {

				throw new \Exception(
					'Số điện thoại đã được sử dụng bởi khách hàng khác.'
				);
			}
		}


		// =========================
		// CHECK EMAIL
		// =========================

		if ($email !== '') {

			$customer =
				Database::first(
					'SELECT id
					FROM customers
					WHERE email = ?
					AND id <> ?
					LIMIT 1',
					[
						$email,
						$id
					]
				);

			if ($customer) {

				throw new \Exception(
					'Email đã được sử dụng bởi khách hàng khác.'
				);
			}
		}


		// =========================
		// UPDATE
		// =========================

		return parent::updateById(
			$id,
			$this->buildData($data)
		) > 0;
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