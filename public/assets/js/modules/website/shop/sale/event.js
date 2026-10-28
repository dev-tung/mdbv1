function roundRect(context, x, y, width, height, radius) {
	const r = Math.min(radius, width / 2, height / 2);

	context.beginPath();
	context.moveTo(x + r, y);
	context.lineTo(x + width - r, y);
	context.quadraticCurveTo(x + width, y, x + width, y + r);
	context.lineTo(x + width, y + height - r);
	context.quadraticCurveTo(x + width, y + height, x + width - r, y + height);
	context.lineTo(x + r, y + height);
	context.quadraticCurveTo(x, y + height, x, y + height - r);
	context.lineTo(x, y + r);
	context.quadraticCurveTo(x, y, x + r, y);
	context.closePath();
}

function formatPrice(price) {
	return `${new Intl.NumberFormat('vi-VN').format(price)}₫`;
}

function getSaleProducts(products, profitPercent, categoryId) {
	return products
		.filter((product) => !categoryId || Number(product.category_id) === Number(categoryId))
		.map((product) => {
			const originalProfit = Math.max(
				0,
				Number(product.price) - Number(product.import_price)
			);
			const salePrice = Math.round(
				Number(product.import_price) + originalProfit * profitPercent / 100
			);

			return {
				...product,
				sale_price: salePrice,
				discount_percent: Math.round(
					(Number(product.price) - salePrice) / Number(product.price) * 100
				),
			};
		})
		.filter((product) => product.sale_price < Number(product.price));
}

function createSaleListImage(products, month, year, categoryName) {
	const canvas = document.createElement('canvas');
	const context = canvas.getContext('2d');

	if (!context) {
		throw new Error('Trình duyệt không hỗ trợ tạo ảnh.');
	}

	const width = 1080;
	const height = 1920;
	const headerHeight = 330;
	const footerHeight = 110;
	const contentTop = headerHeight + 30;
	const contentBottom = height - footerHeight - 30;
	const rowHeight = Math.min(116, (contentBottom - contentTop) / products.length);
	const sidePadding = 54;

	canvas.width = width;
	canvas.height = height;
	context.fillStyle = '#f3f7f3';
	context.fillRect(0, 0, width, height);

	const banner = context.createLinearGradient(0, 0, width, headerHeight);
	banner.addColorStop(0, '#123f2b');
	banner.addColorStop(1, '#27834b');
	context.fillStyle = banner;
	context.fillRect(0, 0, width, headerHeight);

	context.fillStyle = '#f8d86a';
	context.font = '700 23px Arial, sans-serif';
	context.fillText('MDB SPORTS  •  ƯU ĐÃI CÓ HẠN', sidePadding, 62);

	context.fillStyle = '#ffffff';
	context.font = '700 49px Arial, sans-serif';
	context.fillText(`KHUYẾN MÃI THÁNG ${month}`, sidePadding, 137);
	context.font = '400 27px Arial, sans-serif';
	context.fillText(`Năm ${year}  •  ${categoryName}`, sidePadding, 188, width - sidePadding * 2);

	roundRect(context, sidePadding, 220, width - sidePadding * 2, 72, 18);
	context.fillStyle = 'rgba(255, 255, 255, 0.14)';
	context.fill();
	context.fillStyle = '#ffffff';
	context.font = '600 23px Arial, sans-serif';
	context.fillText(`KHUYẾN MÃI THÁNG ${month}`, sidePadding + 24, 264);
	context.textAlign = 'right';
	context.fillText(`${products.length} MẶT HÀNG`, width - sidePadding - 22, 264);
	context.textAlign = 'left';

	products.forEach((product, index) => {
		const x = sidePadding;
		const y = contentTop + index * rowHeight;
		const cardWidth = width - sidePadding * 2;
		const compact = rowHeight < 62;
		const cardHeight = Math.max(rowHeight - 10, 34);
		const top = y + (rowHeight - cardHeight) / 2;

		roundRect(context, x, top, cardWidth, cardHeight, 16);
		context.fillStyle = index % 2 === 0 ? '#ffffff' : '#fafffa';
		context.fill();

		context.fillStyle = '#27834b';
		context.font = `700 ${compact ? 14 : 18}px Arial, sans-serif`;
		context.textBaseline = 'middle';
		context.fillText(String(index + 1).padStart(2, '0'), x + 20, top + cardHeight / 2);

		const nameX = x + 74;
		const nameWidth = compact ? 390 : 440;
		const nameFontSize = Math.max(14, Math.min(24, rowHeight * 0.24));
		context.fillStyle = '#19241d';
		context.font = `700 ${nameFontSize}px Arial, sans-serif`;

		if (compact) {
			context.fillText(product.name, nameX, top + cardHeight / 2, nameWidth);
		} else {
			context.fillText(product.name, nameX, top + cardHeight * 0.38, nameWidth);
			context.fillStyle = '#68736c';
			context.font = `${Math.max(13, nameFontSize * 0.68)}px Arial, sans-serif`;
			context.fillText(product.category_name || 'Sản phẩm', nameX, top + cardHeight * 0.73, nameWidth);
		}

		const priceText = `${formatPrice(product.price)} → ${formatPrice(product.sale_price)}  (-${product.discount_percent}%)`;
		context.fillStyle = '#c62828';
		context.font = `700 ${Math.max(12, Math.min(20, rowHeight * 0.18))}px Arial, sans-serif`;
		context.textAlign = 'right';
		context.fillText(priceText, width - sidePadding - 24, top + cardHeight / 2, compact ? 390 : 410);
		context.textAlign = 'left';
		context.textBaseline = 'alphabetic';
	});

	context.fillStyle = '#244d34';
	context.font = '700 22px Arial, sans-serif';
	context.textAlign = 'center';
	context.fillText('Liên hệ MDB SHOP để được tư vấn và đặt hàng', width / 2, height - 58);
	context.textAlign = 'left';

	return canvas;
}

document.addEventListener('DOMContentLoaded', () => {
	const button = document.getElementById('download-sale-poster');
	const dataElement = document.getElementById('sale-poster-data');
	const status = document.getElementById('sale-poster-status');
	const categoryFilter = document.getElementById('sale-filter-category');
	const productList = document.getElementById('sale-product-list');
	const countElement = document.getElementById('sale-count');

	if (!button || !dataElement || !status || !categoryFilter || !productList || !countElement) {
		return;
	}

	let products;
	let visibleProducts = [];
	const profitPercent = Number(dataElement.dataset.profit);

	try {
		products = JSON.parse(dataElement.textContent);

		if (!Array.isArray(products)) {
			throw new Error('Dữ liệu sản phẩm không hợp lệ.');
		}
	} catch (error) {
		console.error('Không thể đọc danh sách khuyến mãi:', error);
		status.textContent = 'Không thể tải danh sách khuyến mãi.';
		button.disabled = true;
		return;
	}

	function renderList() {
		visibleProducts = getSaleProducts(products, profitPercent, categoryFilter.value);
		productList.replaceChildren();
		countElement.textContent = String(visibleProducts.length);
		button.disabled = visibleProducts.length === 0;
		status.textContent = visibleProducts.length
			? ''
			: 'Không có mặt hàng khuyến mãi phù hợp với bộ lọc.';

		if (!visibleProducts.length) {
			const row = document.createElement('tr');
			const cell = document.createElement('td');

			cell.className = 'text-center text-secondary py-4';
			cell.colSpan = 5;
			cell.textContent = 'Không có mặt hàng khuyến mãi phù hợp với bộ lọc.';
			row.appendChild(cell);
			productList.appendChild(row);
			return;
		}

		const fragment = document.createDocumentFragment();

		visibleProducts.forEach((product, index) => {
			const row = document.createElement('tr');
			const numberCell = document.createElement('td');
			const nameCell = document.createElement('td');
			const name = document.createElement('div');
			const category = document.createElement('small');
			const originalPrice = document.createElement('td');
			const salePrice = document.createElement('td');
			const discount = document.createElement('td');
			const badge = document.createElement('span');

			numberCell.className = 'text-secondary';
			numberCell.textContent = String(index + 1);
			name.className = 'fw-semibold';
			name.textContent = product.name;
			category.className = 'text-secondary';
			category.textContent = product.category_name;
			nameCell.append(name, category);
			originalPrice.className = 'text-end text-nowrap';
			originalPrice.textContent = formatPrice(product.price);
			salePrice.className = 'text-end fw-bold text-danger text-nowrap';
			salePrice.textContent = formatPrice(product.sale_price);
			discount.className = 'text-center';
			badge.className = 'badge text-bg-danger';
			badge.textContent = `-${product.discount_percent}%`;
			discount.appendChild(badge);
			row.append(numberCell, nameCell, originalPrice, salePrice, discount);
			fragment.appendChild(row);
		});

		productList.appendChild(fragment);
	}

	categoryFilter.addEventListener('change', renderList);

	button.addEventListener('click', async () => {
		button.disabled = true;
		button.setAttribute('aria-busy', 'true');
		status.textContent = 'Đang tạo ảnh khuyến mãi…';

		try {
			const selectedCategory = categoryFilter.selectedOptions[0]?.textContent || 'Tất cả danh mục';
			const canvas = createSaleListImage(
				visibleProducts,
				Number(dataElement.dataset.month),
				dataElement.dataset.year,
				selectedCategory
			);
			const imageBlob = await new Promise((resolve, reject) => {
				canvas.toBlob((blob) => {
					if (blob) {
						resolve(blob);
					} else {
						reject(new Error('Không thể xuất ảnh PNG.'));
					}
				}, 'image/png');
			});
			const downloadUrl = URL.createObjectURL(imageBlob);
			const link = document.createElement('a');

			link.href = downloadUrl;
			link.download = `khuyen-mai-tiktok-thang-${dataElement.dataset.month}-${dataElement.dataset.year}.png`;
			link.click();
			window.setTimeout(() => URL.revokeObjectURL(downloadUrl), 1000);
			status.textContent = 'Ảnh khuyến mãi đã được tải xuống.';
		} catch (error) {
			console.error('Không thể tạo ảnh khuyến mãi:', error);
			status.textContent = error.message || 'Không thể tạo ảnh. Vui lòng thử lại.';
		} finally {
			button.disabled = visibleProducts.length === 0;
			button.removeAttribute('aria-busy');
		}
	});

	renderList();
});
