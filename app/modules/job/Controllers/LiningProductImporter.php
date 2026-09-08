<?php

namespace App\Job\Controllers;

use App\Core\Database;
use Exception;
use Throwable;

class LiningProductImporter
{
	protected string $productFile;

	protected array $categoryMap = [];

	protected int $brandId = 0;

	protected array $insertedSlugs = [];

	public function __construct()
	{
		$this->productFile =
			PATH_ROOT .
			'/public/craw/json/lining_product_detail.json';
	}

	public function run(): void
	{
		$products = $this->loadJson($this->productFile);

		$this->resetData();

		try {
			Database::beginTransaction();

			$this->importCategories($products);
			$this->importProducts($products);

			Database::commit();

			echo "<pre style='color:green'>IMPORT LINING DONE!</pre>";
		} catch (Throwable $e) {

			Database::rollback();

			echo "<pre style='color:red'>";
			echo "❌ LINING IMPORT FAILED\n\n";
			echo 'Message: ' . $e->getMessage() . "\n";
			echo 'File: ' . $e->getFile() . "\n";
			echo 'Line: ' . $e->getLine() . "\n";
			echo '</pre>';

			exit();
		}
	}

	/* =========================
	 * LOAD JSON
	 * ========================= */

	protected function loadJson(string $path): array
	{
		if (!file_exists($path)) {
			throw new Exception(
				"File not found: {$path}"
			);
		}

		$data = json_decode(
			file_get_contents($path),
			true,
		);

		if (!is_array($data)) {
			throw new Exception(
				"Invalid JSON format: {$path}"
			);
		}

		return $data;
	}

	/* =========================
	 * RESET DATA
	 * ========================= */

	protected function resetData(): void
	{
		Database::query(
			'SET FOREIGN_KEY_CHECKS = 0'
		);

		Database::query(
			'TRUNCATE TABLE product_images'
		);

		Database::query(
			'TRUNCATE TABLE product_attributes'
		);

		Database::query(
			'TRUNCATE TABLE products'
		);

		Database::query(
			'TRUNCATE TABLE categories'
		);

		Database::query(
			'SET FOREIGN_KEY_CHECKS = 1'
		);
	}

	/* =========================
	 * CATEGORIES
	 * ========================= */

	protected function importCategories(array $products): void
	{
		$firstImageByCategory = [];

		/*
		 * Lining crawler hiện tại chỉ lấy:
		 *
		 * vot
		 * vợt
		 *
		 * => racquets
		 */

		foreach ($products as $product) {

			$cat = $this->cleanSlug(
				$product['category'] ?? ''
			);

			if (
				$cat === 'vot' ||
				$cat === 'vợt'
			) {
				$cat = 'racquets';
			}

			if (
				$cat === '' ||
				isset($firstImageByCategory[$cat])
			) {
				continue;
			}

			$images =
				$product['local_images'] ?? [];

			$firstImage = null;

			foreach ((array) $images as $image) {

				if (is_array($image)) {
					$image =
						$image[0] ?? null;
				}

				if (!empty($image)) {
					$firstImage = $image;
					break;
				}
			}

			if (!empty($firstImage)) {
				$firstImageByCategory[$cat] =
					$firstImage;
			}
		}

		/*
		 * Nếu crawler chỉ có vợt thì tạo category
		 * racquets.
		 */

		if (
			empty(
				$firstImageByCategory['racquets']
			)
		) {

			foreach ($products as $product) {

				$images =
					$product['local_images'] ?? [];

				foreach ((array) $images as $image) {

					if (is_array($image)) {
						$image =
							$image[0] ?? null;
					}

					if (!empty($image)) {

						$firstImageByCategory[
							'racquets'
						] = $image;

						break;
					}
				}

				if (
					!empty(
						$firstImageByCategory[
							'racquets'
						]
					)
				) {
					break;
				}
			}
		}

		/*
		 * Insert categories
		 */

		foreach (
			$firstImageByCategory
			as $slug => $thumbnail
		) {

			Database::insert(
				'INSERT INTO categories
				(name, slug, thumbnail)
				VALUES
				(:name, :slug, :thumbnail)',
				[
					'name' =>
						$this->categoryNameVi(
							$slug
						),

					'slug' =>
						$slug,

					'thumbnail' =>
						$thumbnail,
				],
			);
		}

		$this->categoryMap =
			$this->getCategoryMap();
	}

	protected function getCategoryMap(): array
	{
		$rows =
			Database::get(
				'SELECT id, slug FROM categories'
			);

		$map = [];

		foreach ($rows as $row) {

			$map[
				trim($row['slug'])
			] = (int) $row['id'];
		}

		return $map;
	}

	protected function resolveCategoryId(
		string $catSlug
	): ?int {

		$catSlug =
			strtolower(
				trim($catSlug)
			);

		if (
			$catSlug === 'vot' ||
			$catSlug === 'vợt'
		) {
			$catSlug = 'racquets';
		}

		$catSlug =
			$this->cleanSlug(
				$catSlug
			);

		return
			$this->categoryMap[
				$catSlug
			] ?? null;
	}

	protected function categoryNameVi(
		string $slug
	): string {

		return match ($slug) {

			'racquets' =>
				'Vợt cầu lông',

			'strings' =>
				'Cước cầu lông',

			'stringing-machines' =>
				'Máy đan vợt',

			'shuttlecocks' =>
				'Quả cầu lông',

			'apparel' =>
				'Quần áo cầu lông',

			'footwear' =>
				'Giày cầu lông',

			'bags' =>
				'Túi cầu lông',

			'accessories' =>
				'Phụ kiện cầu lông',

			default =>
				ucfirst(
					str_replace(
						'-',
						' ',
						$slug
					)
				),
		};
	}

	protected function productPrefix(
		string $slug
	): string {

		return match ($slug) {

			'racquets' =>
				'Vợt cầu lông ',

			'strings' =>
				'Cước cầu lông ',

			'stringing-machines' =>
				'Máy đan vợt ',

			'shuttlecocks' =>
				'Quả cầu lông ',

			'apparel' =>
				'Quần áo cầu lông ',

			'footwear' =>
				'Giày cầu lông ',

			'bags' =>
				'Túi cầu lông ',

			'accessories' =>
				'Phụ kiện cầu lông ',

			default =>
				'',
		};
	}

	/* =========================
	 * PRODUCTS
	 * ========================= */

	protected function importProducts(
		array $products
	): void {

		$this->brandId =
			$this->getBrandId();

		foreach ($products as $item) {

			$slug =
				$this->cleanSlug(
					$item['slug'] ?? ''
				);

			$name =
				$item['name'] ??
				$item['title'] ??
				$item['product_name'] ??
				'';

			if (
				$slug === '' ||
				$name === ''
			) {

				continue;
			}

			if (
				isset(
					$this->insertedSlugs[
						$slug
					]
				)
			) {
				continue;
			}

			$this->insertedSlugs[
				$slug
			] = true;

			/*
			 * Category
			 */

			$catSlug =
				$item['category'] ?? '';

			$categoryId =
				$this->resolveCategoryId(
					$catSlug
				);

			if (!$categoryId) {

				throw new Exception(
					'Category not found: ' .
					$catSlug .
					' | product: ' .
					$slug
				);
			}

			/*
			 * Product name
			 */

			$prefix =
				$this->productPrefix(
					'racquets'
				);

			if (
				$prefix !== '' &&
				mb_stripos(
					$name,
					trim($prefix)
				) === false
			) {

				$name =
					$prefix .
					$name;
			}

			/*
			 * Thumbnail
			 *
			 * Ưu tiên local_images vì crawler
			 * lưu ảnh local ở đây.
			 */

			$thumbnail =
				$this->getProductThumbnail(
					$item
				);

			/*
			 * Insert product
			 */

			$productId =
				Database::insert(
					'INSERT INTO products
					(
						category_id,
						brand_id,
						name,
						slug,
						thumbnail,
						description,
						price,
						status
					)
					VALUES
					(
						:category_id,
						:brand_id,
						:name,
						:slug,
						:thumbnail,
						:description,
						:price,
						:status
					)',
					[
						'category_id' =>
							$categoryId,

						'brand_id' =>
							$this->brandId,

						'name' =>
							$name,

						'slug' =>
							$slug,

						'thumbnail' =>
							$thumbnail,

						'description' =>
							$item['description']
							?? null,

						'price' =>
							0,

						'status' =>
							1,
					],
				);

			/*
			 * Images
			 */

			$this->importImages(
				$productId,
				$item['local_images']
				?? []
			);

			/*
			 * Attributes
			 */

			$this->importAttributes(
				$productId,
				$item['specs'] ?? []
			);
		}
	}

	/* =========================
	 * BRAND
	 * ========================= */

	protected function getBrandId(): int
	{
		$row =
			Database::first(
				"SELECT id
				FROM brands
				WHERE slug = 'lining'
				LIMIT 1"
			);

		if ($row) {
			return (int) $row['id'];
		}

		return Database::insert(
			"INSERT INTO brands
			(name, slug)
			VALUES
			('Li-Ning', 'lining')"
		);
	}

	/* =========================
	 * PRODUCT THUMBNAIL
	 * ========================= */

	protected function getProductThumbnail(
		array $item
	): ?string {

		$images =
			$item['local_images'] ?? [];

		foreach ((array) $images as $image) {

			if (is_array($image)) {
				$image =
					$image[0] ?? null;
			}

			$image =
				trim(
					(string) $image
				);

			if ($image !== '') {
				return ltrim(
					$image,
					'/'
				);
			}
		}

		return null;
	}

	/* =========================
	 * ATTRIBUTES
	 * ========================= */

	protected function importAttributes(
		int $productId,
		array $specs
	): void {

		foreach (
			$specs as $key => $value
		) {

			$key =
				trim(
					(string) $key
				);

			$value =
				trim(
					(string) $value
				);

			if (
				$key === '' ||
				$value === ''
			) {
				continue;
			}

			Database::insert(
				'INSERT INTO product_attributes
				(
					product_id,
					attribute_name,
					attribute_value
				)
				VALUES
				(
					:product_id,
					:attribute_name,
					:attribute_value
				)',
				[
					'product_id' =>
						$productId,

					'attribute_name' =>
						$this->specKeyVi(
							$key
						),

					'attribute_value' =>
						$value,
				],
			);
		}
	}

	/* =========================
	 * IMAGES
	 * ========================= */

	protected function importImages(
		int $productId,
		array $images
	): void {

		$sortOrder = 1;

		foreach (
			$images as $image
		) {

			if (is_array($image)) {
				$image =
					$image[0] ?? '';
			}

			$image =
				trim(
					(string) $image
				);

			if ($image === '') {
				continue;
			}

			$image =
				ltrim(
					$image,
					'/'
				);

			Database::insert(
				'INSERT INTO product_images
				(
					product_id,
					image,
					sort_order
				)
				VALUES
				(
					:product_id,
					:image,
					:sort_order
				)',
				[
					'product_id' =>
						$productId,

					'image' =>
						$image,

					'sort_order' =>
						$sortOrder++,
				],
			);
		}
	}

	/* =========================
	 * SPEC MAP
	 * ========================= */

	protected function specKeyVi(
		string $key
	): string {

		$map = [

			'Flex' =>
				'Độ dẻo',

			'Frame' =>
				'Khung vợt',

			'Shaft' =>
				'Thân vợt',

			'Joint' =>
				'Khớp nối',

			'Length' =>
				'Chiều dài',

			'Weight' =>
				'Trọng lượng',

			'Material' =>
				'Chất liệu',

			'Color(s)' =>
				'Màu sắc',

			'Made In' =>
				'Xuất xứ',

			'Balance' =>
				'Điểm cân bằng',

			'Max Tension' =>
				'Sức căng tối đa',

			'Stringing Advice' =>
				'Khuyến nghị căng dây',

			'Head Shape' =>
				'Hình dạng đầu vợt',

			'Technology' =>
				'Công nghệ',
		];

		return
			$map[$key] ??
			$key;
	}

	/* =========================
	 * SLUG
	 * ========================= */

	protected function cleanSlug(
		string $slug
	): string {

		$slug =
			strtolower(
				trim($slug)
			);

		$slug =
			str_replace(
				[' ', '_'],
				'-',
				$slug
			);

		/*
		 * Giữ chữ cái ASCII cho slug.
		 */

		$slug =
			preg_replace(
				'/[^a-z0-9\-]/',
				'',
				$slug
			);

		return
			preg_replace(
				'/-+/',
				'-',
				$slug
			);
	}
}