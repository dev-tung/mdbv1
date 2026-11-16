<main class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/product" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item"><a href="/checkout" class="text-decoration-none">Đặt hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Xác nhận đơn hàng</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <section class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <span class="badge text-bg-success mb-3">Đã tạo đơn hàng</span>
                        <h1 class="h2 fw-bold text-success">Cảm ơn bạn đã đặt hàng</h1>
                        <p class="text-secondary">Đơn hàng đang chờ thanh toán. Vui lòng kiểm tra số tiền, chuyển khoản và tải ảnh chứng từ để cửa hàng xác minh.</p>
                        <p class="h5">Mã đơn hàng: <strong id="order-number">Đang tải...</strong></p>
                    </div>

                    <div class="alert alert-warning">
                        <strong>Trạng thái:</strong> Chờ thanh toán
                        <p class="mb-0 mt-1">Thông tin này sẽ được cập nhật sau khi kết nối xác minh thanh toán.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <h2 class="h5 fw-bold">Thông tin nhận hàng</h2>
                            <dl class="row small">
                                <dt class="col-4">Họ tên</dt><dd id="confirmation-name" class="col-8">—</dd>
                                <dt class="col-4">Điện thoại</dt><dd id="confirmation-phone" class="col-8">—</dd>
                                <dt class="col-4">Email</dt><dd id="confirmation-email" class="col-8">—</dd>
                                <dt class="col-4">Địa chỉ</dt><dd id="confirmation-address" class="col-8">—</dd>
                                <dt class="col-4">Nhận hàng</dt><dd id="confirmation-delivery" class="col-8">—</dd>
                                <dt class="col-4">Ghi chú</dt><dd id="confirmation-note" class="col-8">—</dd>
                            </dl>
                        </div>
                        <div class="col-12 col-md-6">
                            <h2 class="h5 fw-bold">Sản phẩm đã đặt</h2>
                            <ul id="confirmation-items" class="list-group mb-3">
                                <li class="list-group-item text-secondary">Không có thông tin chi tiết trong phiên này. Vui lòng giữ mã đơn để tra cứu.</li>
                            </ul>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Tạm tính đơn hàng</span>
                                <span id="confirmation-total" class="text-danger">—</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        <a id="payment-link" href="/payment" class="btn btn-success btn-lg">Tiếp tục thanh toán</a>
                        <a id="status-link" href="/order/status" class="btn btn-outline-success btn-lg">Tra cứu trạng thái</a>
                        <a href="/product" class="btn btn-outline-secondary btn-lg">Tiếp tục mua hàng</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<script>
(() => {
    const params = new URLSearchParams(window.location.search);
    const orderId = params.get('id') || '';
    let order = {};

    try {
        order = JSON.parse(sessionStorage.getItem('mdb:lastOrder') || '{}');
    } catch (error) {
        console.error('Không thể đọc thông tin xác nhận đơn hàng.', error);
    }

    const currentId = orderId || order.id || '';
    document.getElementById('order-number').textContent = currentId ? `#${currentId}` : '—';
    document.getElementById('payment-link').href = currentId
        ? `/payment?id=${encodeURIComponent(currentId)}`
        : '/payment';
    document.getElementById('status-link').href = currentId
        ? `/order/status?order=${encodeURIComponent(currentId)}`
        : '/order/status';
    document.getElementById('confirmation-name').textContent = order.customer && order.customer.name || '—';
    document.getElementById('confirmation-phone').textContent = order.customer && order.customer.phone || '—';
    document.getElementById('confirmation-email').textContent = order.customer && order.customer.email || '—';
    document.getElementById('confirmation-address').textContent = order.customer && order.customer.address || '—';
    document.getElementById('confirmation-delivery').textContent =
        order.deliveryMethod === 'pickup' ? 'Nhận tại cửa hàng' : order.deliveryMethod === 'delivery' ? 'Giao hàng tận nơi' : '—';
    document.getElementById('confirmation-note').textContent = order.note || '—';
    document.getElementById('confirmation-total').textContent = order.total
        ? Number(order.total).toLocaleString('vi-VN') + ' ₫'
        : '—';

    const itemsList = document.getElementById('confirmation-items');
    if (Array.isArray(order.items) && order.items.length) {
        itemsList.replaceChildren();
        order.items.forEach((item) => {
            const row = document.createElement('li');
            row.className = 'list-group-item d-flex justify-content-between gap-3';
            const title = document.createElement('span');
            title.textContent = `${item.name || 'Sản phẩm'}${item.variant ? ` (${item.variant})` : ''} × ${Number(item.quantity || 0)}`;
            const price = document.createElement('strong');
            price.textContent =
                (Number(item.price || 0) * Number(item.quantity || 0)).toLocaleString('vi-VN') + ' ₫';
            row.append(title, price);
            itemsList.appendChild(row);
        });
    }
})();
</script>
