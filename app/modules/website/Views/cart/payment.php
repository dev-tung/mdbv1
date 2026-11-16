<main class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/product" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item"><a href="/checkout/success" class="text-decoration-none">Xác nhận đơn hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Thanh toán chuyển khoản</h1>
            <p class="text-secondary mb-0">Quét mã QR hoặc chuyển khoản theo thông tin bên dưới.</p>
        </div>
        <span class="badge text-bg-warning fs-6">Chờ thanh toán</span>
    </div>

    <div class="alert alert-info" role="status">
        Mã QR được tạo nội bộ từ số tiền còn nợ và mã đơn. QR chỉ khởi tạo lệnh chuyển khoản; cửa hàng vẫn cần xác nhận tiền đã về.
    </div>

    <div id="payment-phone-lookup" class="alert alert-light border mt-4 d-none">
        <p class="mb-2">Để tải thông tin thanh toán, hãy nhập số điện thoại đã dùng khi đặt đơn.</p>
        <label for="payment-phone" class="form-label">Số điện thoại</label>
        <div class="input-group">
            <input id="payment-phone" class="form-control" type="tel" autocomplete="tel" placeholder="Nhập số điện thoại đặt hàng">
            <button id="load-payment-details" class="btn btn-success" type="button">Tải QR</button>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-5">
            <section class="card h-100">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Mã QR thanh toán</h2>
                </div>
                <div class="card-body text-center">
                    <img
                        id="payment-qr-image"
                        class="img-fluid rounded border mb-3 d-none"
                        width="320"
                        height="320"
                        alt="Mã QR chuyển khoản VietQR"
                    >
                    <div id="payment-qr-placeholder" class="border rounded p-5 text-secondary mb-3">
                        Mã QR sẽ hiển thị sau khi xác minh đơn hàng.
                    </div>
                    <div id="payment-qr-status" class="alert alert-info mb-0" role="status" aria-live="polite">
                        Đang tải thông tin thanh toán...
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-lg-7">
            <section class="card mb-4">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Thông tin chuyển khoản</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Mã đơn hàng</dt>
                        <dd class="col-sm-7 fw-semibold" id="payment-order-number">Đang tải...</dd>
                        <dt class="col-sm-5">Ngân hàng</dt>
                        <dd class="col-sm-7" id="payment-bank-name">Đang tải...</dd>
                        <dt class="col-sm-5">Số tài khoản</dt>
                        <dd class="col-sm-7 d-flex flex-wrap align-items-center gap-2">
                            <span id="bank-account-number">Chưa cấu hình</span>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-copy="bank-account-number" disabled>Sao chép</button>
                        </dd>
                        <dt class="col-sm-5">Chủ tài khoản</dt>
                        <dd class="col-sm-7" id="payment-account-name">Đang tải...</dd>
                        <dt class="col-sm-5">Số tiền đơn hàng</dt>
                        <dd class="col-sm-7 fw-bold text-danger" id="payment-amount">Đang tải...</dd>
                        <dt class="col-sm-5">Nội dung chuyển khoản</dt>
                        <dd class="col-sm-7 d-flex flex-wrap align-items-center gap-2">
                            <span id="payment-reference">Đang tải...</span>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-copy="payment-reference" disabled>Sao chép</button>
                        </dd>
                    </dl>
                    <p class="form-text mb-0">Số tiền hiển thị hiện chưa tính phí vận chuyển nếu phát sinh.</p>
                    <div id="copy-feedback" class="small text-success mt-3" role="status" aria-live="polite"></div>
                </div>
            </section>

            <section class="card">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Tải ảnh xác nhận thanh toán</h2>
                </div>
                <div class="card-body">
                    <p class="small text-secondary">Chọn ảnh biên lai chuyển khoản để gửi cho cửa hàng kiểm tra. Hệ thống chưa tải tệp lên máy chủ.</p>
                    <label for="payment-proof" class="form-label">Ảnh thanh toán</label>
                    <input
                        id="payment-proof"
                        class="form-control"
                        type="file"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >
                    <div class="form-text">Định dạng JPG, JPEG, PNG hoặc WEBP.</div>

                    <div id="payment-proof-preview-wrapper" class="d-none mt-3">
                        <img id="payment-proof-preview" class="img-fluid rounded border mb-2" alt="Ảnh thanh toán đã chọn">
                        <p class="small mb-2">Tệp: <span id="payment-proof-name" class="fw-semibold"></span></p>
                        <button type="button" id="remove-payment-proof" class="btn btn-outline-danger btn-sm">Bỏ ảnh đã chọn</button>
                    </div>

                    <button type="button" class="btn btn-success w-100 mt-3" disabled>Gửi ảnh xác nhận thanh toán</button>
                    <div class="form-text mt-2">Nút gửi sẽ được bật khi hoàn thành API tải ảnh và kiểm tra dữ liệu.</div>
                </div>
            </section>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h2 class="h6 fw-bold">Hướng dẫn thanh toán</h2>
            <ol class="mb-3">
                <li>Kiểm tra mã đơn hàng và số tiền cần thanh toán.</li>
                <li>Quét mã QR được tạo riêng cho đơn hàng hoặc chuyển khoản với đúng nội dung.</li>
                <li>Tải ảnh biên lai lên để admin đối soát. Đơn hàng chỉ được xác nhận sau khi admin kiểm tra giao dịch.</li>
            </ol>
            <a href="/order/status" class="btn btn-outline-success">Tra cứu trạng thái đơn hàng</a>
            <a href="/product" class="btn btn-link">Tiếp tục mua hàng</a>
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
        console.error('Không thể đọc thông tin đơn hàng trong phiên hiện tại.', error);
    }

    const displayedId = orderId || order.id || '';
    document.getElementById('payment-order-number').textContent = displayedId ? `#${displayedId}` : 'Chưa có mã đơn';
    const phoneLookup = document.getElementById('payment-phone-lookup');
    const phoneInput = document.getElementById('payment-phone');
    const loadButton = document.getElementById('load-payment-details');
    const qrImage = document.getElementById('payment-qr-image');
    const qrPlaceholder = document.getElementById('payment-qr-placeholder');
    const qrStatus = document.getElementById('payment-qr-status');

    const setQrStatus = (message, type) => {
        qrStatus.className = `alert alert-${type} mb-0`;
        qrStatus.textContent = message;
    };

    document.querySelectorAll('[data-copy]').forEach((button) => {
        button.addEventListener('click', async () => {
            const text = document.getElementById(button.dataset.copy).textContent.trim();
            const feedback = document.getElementById('copy-feedback');
            try {
                await navigator.clipboard.writeText(text);
                feedback.textContent = 'Đã sao chép thông tin.';
            } catch (error) {
                feedback.textContent = 'Không thể sao chép tự động. Vui lòng chọn và sao chép thủ công.';
                console.error('Không thể sao chép nội dung.', error);
            }
        });
    });

    async function loadPaymentDetails() {
        const phone = phoneInput.value.trim();

        if (!displayedId) {
            phoneLookup.classList.add('d-none');
            setQrStatus('Không tìm thấy mã đơn hàng. Hãy quay lại trang xác nhận đơn.', 'warning');
            return;
        }

        if (!phone) {
            phoneLookup.classList.remove('d-none');
            setQrStatus('Nhập số điện thoại đặt hàng để tải QR.', 'warning');
            phoneInput.focus();
            return;
        }

        loadButton.disabled = true;
        loadButton.textContent = 'Đang tải...';

        try {
            const response = await fetch('/api/online-orders/payment-qr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: displayedId,
                    phone
                })
            });
            const responseText = await response.text();
            let result;

            try {
                result = JSON.parse(responseText);
            } catch {
                throw new Error(`Máy chủ không trả JSON khi tải mã QR (HTTP ${response.status}).`);
            }

            if (!response.ok || !result.success || !result.data) {
                throw new Error(result.message || 'Không thể tải thông tin thanh toán.');
            }

            const payment = result.data;
            qrImage.src = payment.qr_data_uri;
            qrImage.classList.remove('d-none');
            qrPlaceholder.classList.add('d-none');
            document.getElementById('payment-bank-name').textContent = payment.bank_name;
            document.getElementById('bank-account-number').textContent = payment.account_number;
            document.getElementById('payment-account-name').textContent = payment.account_name;
            document.getElementById('payment-amount').textContent =
                Number(payment.amount).toLocaleString('vi-VN') + ' ₫';
            document.getElementById('payment-reference').textContent = payment.transfer_content;
            document.querySelectorAll('[data-copy]').forEach((button) => {
                button.disabled = false;
            });
            phoneLookup.classList.add('d-none');
            setQrStatus('Quét mã bằng ứng dụng ngân hàng và kiểm tra đúng số tiền, nội dung chuyển khoản.', 'success');
        } catch (error) {
            console.error('Không thể tải mã QR thanh toán.', error);
            setQrStatus(error.message || 'Không thể tải mã QR. Vui lòng thử lại.', 'danger');
        } finally {
            loadButton.disabled = false;
            loadButton.textContent = 'Tải QR';
        }
    }

    phoneInput.value = order.customer && order.customer.phone || '';
    loadButton.addEventListener('click', loadPaymentDetails);

    if (phoneInput.value) {
        loadPaymentDetails();
    } else {
        phoneLookup.classList.remove('d-none');
        setQrStatus('Nhập số điện thoại đặt hàng để tải QR thanh toán.', 'info');
    }

    const fileInput = document.getElementById('payment-proof');
    const previewWrapper = document.getElementById('payment-proof-preview-wrapper');
    const preview = document.getElementById('payment-proof-preview');
    const fileName = document.getElementById('payment-proof-name');
    let previewUrl = '';

    fileInput.addEventListener('change', () => {
        const file = fileInput.files && fileInput.files[0];
        if (!file) return;

        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            fileInput.value = '';
            previewWrapper.classList.add('d-none');
            window.alert('Vui lòng chọn ảnh JPG, JPEG, PNG hoặc WEBP.');
            return;
        }

        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        fileName.textContent = file.name;
        previewWrapper.classList.remove('d-none');
    });

    document.getElementById('remove-payment-proof').addEventListener('click', () => {
        fileInput.value = '';
        preview.removeAttribute('src');
        fileName.textContent = '';
        previewWrapper.classList.add('d-none');
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = '';
    });
})();
</script>
