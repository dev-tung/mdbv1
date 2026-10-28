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

function getSaleProducts(products, limit) {
	return products.filter((product) =>
		Number(product.sale_price) < Number(product.price)
	).slice(0, limit);
}

function createSaleListImage(canvas, products, day, month, year) {
	const context = canvas.getContext('2d');

	if (!context) {
		throw new Error('Trình duyệt không hỗ trợ tạo ảnh.');
	}

	const width = 1080;
	const height = 1920;
	const headerHeight = 190;
	const footerHeight = 90;
	const contentTop = headerHeight - 5;
	const contentBottom = height - footerHeight - 90;
	const rowHeight = Math.min(116, (contentBottom - contentTop) / products.length);
	const sidePadding = 90;

	canvas.width = width;
	canvas.height = height;
	const background = context.createLinearGradient(0, 0, width, height);
	background.addColorStop(0, '#69a84f');
	background.addColorStop(1, '#3f7f35');
	context.fillStyle = background;
	context.fillRect(0, 0, width, height);

	context.fillStyle = '#ffffff';
	context.font = '700 46px Arial, sans-serif';
	context.textAlign = 'center';
	context.textBaseline = 'middle';
	context.fillText(`KHUYẾN MÃI NGÀY ${day}/${month}/${year} TẠI MDB SHOP`, width / 2, headerHeight / 2, width - sidePadding * 2);
	context.textAlign = 'left';
	context.textBaseline = 'alphabetic';

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

		context.fillStyle = '#69a84f';
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

		const priceFontSize = Math.max(12, Math.min(26, rowHeight * 0.22));
		const priceRight = width - sidePadding - 24;
		const priceY = top + cardHeight / 2;
		const originalPriceText = formatPrice(product.price);
		const salePriceText = formatPrice(product.sale_price);
		context.textBaseline = 'middle';
		context.font = `400 ${Math.max(11, priceFontSize * 0.78)}px Arial, sans-serif`;
		const originalPriceWidth = context.measureText(originalPriceText).width;
		context.font = `700 ${priceFontSize}px Arial, sans-serif`;
		const salePriceWidth = context.measureText(salePriceText).width;
		const gap = Math.max(8, priceFontSize * 0.45);
		const originalPriceX = priceRight - salePriceWidth - gap - originalPriceWidth;
		const originalPriceY = priceY;
		context.fillStyle = '#68736c';
		context.textAlign = 'left';
		context.font = `400 ${Math.max(11, priceFontSize * 0.78)}px Arial, sans-serif`;
		context.fillText(originalPriceText, originalPriceX, originalPriceY);
		context.beginPath();
		context.moveTo(originalPriceX, originalPriceY);
		context.lineTo(originalPriceX + originalPriceWidth, originalPriceY);
		context.strokeStyle = '#68736c';
		context.lineWidth = Math.max(1, priceFontSize * 0.055);
		context.stroke();
		context.fillStyle = '#c62828';
		context.font = `700 ${priceFontSize}px Arial, sans-serif`;
		context.fillText(salePriceText, originalPriceX + originalPriceWidth + gap, priceY);
		context.textAlign = 'left';
		context.textBaseline = 'alphabetic';
	});

	context.fillStyle = '#ffffff';
	context.font = '700 26px Arial, sans-serif';
	context.textAlign = 'center';
	context.fillText('Liên hệ SĐT 0973359165 để đặt hàng', width / 2, height - 90);
	context.textAlign = 'left';

	return canvas;
}

document.addEventListener('DOMContentLoaded', () => {
	const button = document.getElementById('download-sale-poster');
	const dataElement = document.getElementById('sale-poster-data');
	const status = document.getElementById('sale-poster-status');
	const preview = document.getElementById('sale-poster-preview');

	if (!button || !dataElement || !status) {
		return;
	}

	let products;
	let visibleProducts = [];

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

	visibleProducts = getSaleProducts(products, Number(dataElement.dataset.limit));
	button.disabled = visibleProducts.length === 0 || !preview;

	if (preview && visibleProducts.length > 0) {
		createSaleListImage(
			preview,
			visibleProducts,
			Number(dataElement.dataset.day),
			Number(dataElement.dataset.month),
			dataElement.dataset.year
		);
	}

	button.addEventListener('click', async () => {
		button.disabled = true;
		button.setAttribute('aria-busy', 'true');
		status.textContent = 'Đang tạo ảnh khuyến mãi…';

		try {
			const imageBlob = await new Promise((resolve, reject) => {
				preview.toBlob((blob) => {
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
			button.disabled = visibleProducts.length === 0 || !preview;
			button.removeAttribute('aria-busy');
		}
	});
});
