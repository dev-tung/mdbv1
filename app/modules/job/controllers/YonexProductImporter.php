<?php

class YonexProductImporter
{
	protected string $categoryFile;

	protected string $productFile;

	protected array $categoryMap = [];

	protected int $brandId = 0;

	protected array $insertedSlugs = [];

	public function __construct()
	{
		$this->categoryFile = PATH_ROOT . '/public/craw/json/yonex_category.json';
		$this->productFile = PATH_ROOT . '/public/craw/json/yonex_product_detail.json';
	}

	public function run(): void
	{
		$categories = $this->loadJson($this->categoryFile);
		$products = $this->loadJson($this->productFile);

		$this->resetData();

		try {
			Database::beginTransaction();

			$this->importCategories($categories, $products);
			$this->importProducts($products);

			Database::commit();

			echo "<pre style='color:green'>IMPORT DONE!</pre>";
		} catch (Throwable $e) {
			Database::rollback();

			echo "<pre style='color:red'>";
			echo "❌ IMPORT FAILED\n\n";
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
			throw new Exception("File not found: {$path}");
		}

		$data = json_decode(file_get_contents($path), true);

		if (!is_array($data)) {
			throw new Exception("Invalid JSON format: {$path}");
		}

		return $data;
	}

	/* =========================
	 * RESET DATA
	 * ========================= */
	protected function resetData(): void
	{
		Database::query('SET FOREIGN_KEY_CHECKS = 0');

		Database::query('TRUNCATE TABLE product_images');
		Database::query('TRUNCATE TABLE product_attributes');
		Database::query('TRUNCATE TABLE products');
		Database::query('TRUNCATE TABLE categories');

		Database::query('SET FOREIGN_KEY_CHECKS = 1');
	}

	/* =========================
	 * CATEGORIES
	 * ========================= */
	protected function importCategories(array $categories, array $products): void
	{
		$firstImageByCategory = [];

		$seriesMap = [
			'nanoflare' => 'racquets',
			'astrox' => 'racquets',
			'arcsaber' => 'racquets',
			'duora' => 'racquets',
		];

		/* STEP 1: build image map */
		foreach ($products as $p) {
			$cat = $this->cleanSlug($p['category'] ?? '');

			if (isset($seriesMap[$cat])) {
				$cat = $seriesMap[$cat];
			}

			if ($cat === '' || isset($firstImageByCategory[$cat])) {
				continue;
			}

			$imgs = $p['local_images'] ?? [];
			$img = null;

			foreach ((array) $imgs as $i) {
				if (is_array($i)) {
					$i = $i[0] ?? null;
				}
				if (!empty($i)) {
					$img = $i;
					break;
				}
			}

			if (!empty($img)) {
				$firstImageByCategory[$cat] = $img;
			}
		}

		/* STEP 2: insert categories */
		foreach ($categories as $item) {
			$slug = $this->cleanSlug($item['slug'] ?? '');
			if ($slug === '') {
				continue;
			}

			$thumbnail = null;

			if (!empty($firstImageByCategory[$slug])) {
				$relativePath = ltrim($firstImageByCategory[$slug], '/');

				// FIX: bỏ duplicate uploads/
				$relativePath = preg_replace('#^uploads/#', '', $relativePath);

				$fullPath = PATH_ROOT . '/public/uploads/' . $relativePath;

				// KHÔNG chặn file_exists (tránh mất ảnh do path sai)
				if (file_exists($fullPath)) {
					$thumbnail = 'uploads/' . $relativePath;
				} else {
					// fallback luôn lấy path gốc để không mất ảnh
					$thumbnail = 'uploads/' . $relativePath;
				}
			}

			Database::insert(
				'INSERT INTO categories (name, slug, thumbnail)
                 VALUES (:name, :slug, :thumbnail)',
				[
					'name' => $this->categoryNameVi($slug, $item['name'] ?? ''),
					'slug' => $slug,
					'thumbnail' => $thumbnail,
				],
			);
		}

		$this->categoryMap = $this->getCategoryMap();
	}

	protected function getCategoryMap(): array
	{
		$rows = Database::get('SELECT id, slug FROM categories');

		$map = [];
		foreach ($rows as $row) {
			$map[trim($row['slug'])] = (int) $row['id'];
		}

		return $map;
	}

	protected function resolveCategoryId(string $catSlug): ?int
	{
		$catSlug = $this->cleanSlug($catSlug);

		$seriesMap = [
			'nanoflare' => 'racquets',
			'astrox' => 'racquets',
			'arcsaber' => 'racquets',
			'duora' => 'racquets',
		];

		if (isset($seriesMap[$catSlug])) {
			$catSlug = $seriesMap[$catSlug];
		}

		return $this->categoryMap[$catSlug] ?? null;
	}

	protected function cleanSlug(string $slug): string
	{
		$slug = strtolower(trim($slug));
		$slug = str_replace([' ', '_'], '-', $slug);
		$slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
		return preg_replace('/-+/', '-', $slug);
	}

	protected function categoryNameVi(string $slug, string $name = ''): string
	{
		return match ($slug) {
			'racquets' => 'Vợt cầu lông',
			'strings' => 'Cước cầu lông',
			'stringing-machines' => 'Máy đan vợt',
			'shuttlecocks' => 'Quả cầu lông',
			'apparel' => 'Quần áo cầu lông',
			'footwear' => 'Giày cầu lông',
			'bags' => 'Túi cầu lông',
			'accessories' => 'Phụ kiện cầu lông',
			default => $name ?: ucfirst(str_replace('-', ' ', $slug)),
		};
	}

	protected function productPrefix(string $slug): string
	{
		return match ($slug) {
			'racquets' => 'Vợt cầu lông ',
			'strings' => 'Cước cầu lông ',
			'stringing-machines' => 'Máy đan vợt ',
			'shuttlecocks' => 'Quả cầu lông ',
			'apparel' => 'Quần áo cầu lông ',
			'footwear' => 'Giày cầu lông ',
			'bags' => 'Túi cầu lông ',
			'accessories' => 'Phụ kiện cầu lông ',
			default => '',
		};
	}

	/* =========================
	 * PRODUCTS
	 * ========================= */
	protected function importProducts(array $products): void
	{
		$this->brandId = $this->getBrandId();

		foreach ($products as $item) {
			$slug = $this->cleanSlug($item['slug'] ?? '');
			$name = $item['name'] ?? ($item['title'] ?? '');

			if ($slug === '' || $name === '') {
				throw new Exception('Missing slug or name');
			}

			if (isset($this->insertedSlugs[$slug])) {
				continue;
			}
			$this->insertedSlugs[$slug] = true;

			$catSlug = $item['category'] ?? '';
			$categoryId = $this->resolveCategoryId($catSlug);

			if (!$categoryId) {
				throw new Exception("Category not found: {$catSlug} | product: {$slug}");
			}

			$prefix = $this->productPrefix($catSlug);

			if ($prefix !== '' && mb_stripos($name, trim($prefix)) === false) {
				$name = $prefix . $name;
			}

			$productId = Database::insert(
				'INSERT INTO products
                (category_id, brand_id, name, slug, thumbnail, description, price, status)
                VALUES
                (:category_id, :brand_id, :name, :slug, :thumbnail, :description, :price, :status)',
				[
					'category_id' => $categoryId,
					'brand_id' => $this->brandId,
					'name' => $name,
					'slug' => $slug,
					'thumbnail' => isset($item['image_file'])
						? 'uploads/' . ltrim($item['image_file'], '/')
						: null,
					'description' => $item['description'] ?? null,
					'price' => 0,
					'status' => 1,
				],
			);

			$this->importImages($productId, $item['local_images'] ?? []);
			$this->importAttributes($productId, $item['specs'] ?? []);
		}
	}

	protected function getBrandId(): int
	{
		$row = Database::first('SELECT id FROM brands WHERE id = 1');

		if (!$row) {
			return Database::insert(
				"INSERT INTO brands (id, name, slug)
                 VALUES (1, 'Yonex', 'yonex')",
			);
		}

		return (int) $row['id'];
	}

	/* =========================
	 * ATTRIBUTES
	 * ========================= */
	protected function importAttributes(int $productId, array $specs): void
	{
		foreach ($specs as $key => $value) {
			$key = trim((string) $key);
			$value = trim((string) $value);

			if ($key === '' || $value === '') {
				continue;
			}

			Database::insert(
				'INSERT INTO product_attributes
                (product_id, attribute_name, attribute_value)
                VALUES
                (:product_id, :attribute_name, :attribute_value)',
				[
					'product_id' => $productId,
					'attribute_name' => $this->specKeyVi($key),
					'attribute_value' => $value,
				],
			);
		}
	}

	/* =========================
	 * IMAGES
	 * ========================= */
	protected function importImages(int $productId, array $images): void
	{
		foreach ($images as $index => $image) {
			if (is_array($image)) {
				$image = $image[0] ?? '';
			}

			$image = trim((string) $image);
			if ($image === '') {
				continue;
			}

			Database::insert(
				'INSERT INTO product_images
                (product_id, image, sort_order)
                VALUES
                (:product_id, :image, :sort_order)',
				[
					'product_id' => $productId,
					'image' => 'uploads/' . ltrim($image, '/'),
					'sort_order' => $index + 1,
				],
			);
		}
	}

	/* =========================
	 * SPEC MAP
	 * ========================= */
	protected function specKeyVi(string $key): string
	{
		$map = [
			'Flex' => 'Độ dẻo',
			'Frame' => 'Khung vợt',
			'Shaft' => 'Thân vợt',
			'Joint' => 'Khớp nối',
			'Length' => 'Chiều dài',
			'Weight' => 'Trọng lượng',
			'Material' => 'Chất liệu',
			'Color(s)' => 'Màu sắc',
			'Made In' => 'Xuất xứ',
		];

		return $map[$key] ?? $key;
	}
}
