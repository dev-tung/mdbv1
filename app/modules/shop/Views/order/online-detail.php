<main class="container-fluid py-4 mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/online-orders">Đơn hàng online</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết #<?= htmlspecialchars((string) $id) ?></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Đơn hàng #<?= htmlspecialchars((string) $id) ?></h1>
            <p class="text-secondary mb-0">Tạo lúc 12/11/2026 10:24 · Kênh bán: Website</p>
        </div>
        <a href="/admin/online-orders" class="btn btn-outline-secondary">Quay lại danh sách</a>
    </div>

    <div class="alert alert-info" role="status">
        Đây là dữ liệu mẫu để duyệt giao diện. Không có thao tác xác nhận / từ chối thanh toán nào được thực hiện.
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-7">
            <section class="card mb-4">
                <div class="card-header bg-white"><h2 class="h5 mb-0">Thông tin khách hàng</h2></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Họ tên</dt><dd class="col-sm-8">Nguyễn Minh Anh</dd>
                        <dt class="col-sm-4">Số điện thoại</dt><dd class="col-sm-8">0900 000 124</dd>
                        <dt class="col-sm-4">Email</dt><dd class="col-sm-8">minhanh@example.com</dd>
                        <dt class="col-sm-4">Địa chỉ giao hàng</dt><dd class="col-sm-8">123 Nguyễn Trãi, Thanh Xuân, Hà Nội</dd>
                        <dt class="col-sm-4">Phương thức nhận</dt><dd class="col-sm-8">Giao hàng tận nơi</dd>
                        <dt class="col-sm-4">Ghi chú</dt><dd class="col-sm-8">Gọi trước khi giao hàng.</dd>
                    </dl>
                </div>
            </section>

            <section class="card mb-4">
                <div class="card-header bg-white"><h2 class="h5 mb-0">Sản phẩm trong đơn</h2></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Sản phẩm</th><th>Phiên bản</th><th class="text-end">SL</th><th class="text-end">Đơn giá</th><th class="text-end">Thành tiền</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>Vợt cầu lông NANOFLARE 700 PRO</td>
                                    <td>4U / G5</td>
                                    <td class="text-end">1</td>
                                    <td class="text-end">4.690.000₫</td>
                                    <td class="text-end">4.690.000₫</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr><th colspan="4" class="text-end">Tạm tính</th><td class="text-end">4.690.000₫</td></tr>
                                <tr><th colspan="4" class="text-end">Phí giao hàng</th><td class="text-end">0₫ (mẫu)</td></tr>
                                <tr><th colspan="4" class="text-end">Tổng thanh toán</th><th class="text-end text-danger">4.690.000₫</th></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>

            <section class="card">
                <div class="card-header bg-white"><h2 class="h5 mb-0">Trạng thái đơn hàng</h2></div>
                <div class="card-body">
                    <label for="order-status" class="form-label">Cập nhật trạng thái</label>
                    <select id="order-status" class="form-select mb-3" disabled>
                        <option>Chờ thanh toán</option>
                        <option selected>Chờ xác minh</option>
                        <option>Đã thanh toán</option>
                        <option>Đang xử lý</option>
                        <option>Hoàn tất</option>
                        <option>Đã hủy</option>
                    </select>
                    <button type="button" class="btn btn-outline-success" disabled>Lưu trạng thái</button>
                    <span class="form-text d-block mt-2">Điều khiển được bật sau khi API cập nhật trạng thái sẵn sàng.</span>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-5">
            <section class="card mb-4">
                <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h2 class="h5 mb-0">Xác minh thanh toán</h2>
                    <span class="badge text-bg-info">Chờ xác minh</span>
                </div>
                <div class="card-body">
                    <p class="mb-1"><span class="text-secondary">Số tiền dự kiến:</span> <strong>4.690.000₫</strong></p>
                    <p><span class="text-secondary">Nội dung chuyển khoản:</span> <strong>MDB-<?= htmlspecialchars((string) $id) ?></strong></p>
                    <h3 class="h6 fw-bold">Ảnh chứng từ khách gửi</h3>
                    <img
                        src="/assets/image/no-image.svg"
                        class="img-fluid rounded border mb-3"
                        alt="Vị trí ảnh chứng từ thanh toán"
                    >
                    <p class="small text-secondary">Chưa kết nối ảnh chứng từ thật. Admin cần đối chiếu giao dịch ngân hàng trước khi xác nhận.</p>
                    <label for="payment-rejection-reason" class="form-label">Lý do từ chối / yêu cầu bổ sung</label>
                    <textarea id="payment-rejection-reason" class="form-control mb-3" rows="3" placeholder="Nhập lý do rõ ràng để thông báo khách hàng" disabled></textarea>
                    <div class="d-grid gap-2 d-sm-flex">
                        <button type="button" class="btn btn-success flex-fill" disabled>Xác nhận đã nhận tiền</button>
                        <button type="button" class="btn btn-outline-danger flex-fill" disabled>Từ chối chứng từ</button>
                    </div>
                    <p class="form-text mt-2 mb-0">Các thao tác xác minh chưa được nối backend và đang khóa để tránh ghi nhận thanh toán giả.</p>
                </div>
            </section>

            <section class="card">
                <div class="card-header bg-white"><h2 class="h5 mb-0">Thông tin chuyển khoản</h2></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Ngân hàng</dt><dd class="col-7">Chờ cấu hình</dd>
                        <dt class="col-5">Số tài khoản</dt><dd class="col-7">Chưa cấu hình</dd>
                        <dt class="col-5">Mã tham chiếu</dt><dd class="col-7">MDB-<?= htmlspecialchars((string) $id) ?></dd>
                    </dl>
                </div>
            </section>
        </div>
    </div>
</main>
