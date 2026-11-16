<main class="container-fluid py-4 mt-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Đơn hàng online</h1>
            <p class="text-secondary mb-0">Quản lý đơn trực tuyến và đối soát chứng từ thanh toán.</p>
        </div>
        <a href="/admin/orders" class="btn btn-outline-secondary">Danh sách đơn hàng hiện có</a>
    </div>

    <div class="alert alert-info" role="status">
        Dữ liệu và bộ lọc dưới đây là giao diện minh họa. Xác nhận thanh toán / cập nhật trạng thái chưa kết nối API.
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card h-100"><div class="card-body">
                <p class="text-secondary mb-1">Tổng đơn hôm nay</p>
                <p class="h3 fw-bold mb-0">18</p>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100"><div class="card-body">
                <p class="text-secondary mb-1">Chờ thanh toán</p>
                <p class="h3 fw-bold text-warning mb-0">6</p>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100"><div class="card-body">
                <p class="text-secondary mb-1">Chờ xác minh</p>
                <p class="h3 fw-bold text-primary mb-0">4</p>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100"><div class="card-body">
                <p class="text-secondary mb-1">Đã xác nhận</p>
                <p class="h3 fw-bold text-success mb-0">8</p>
            </div></div>
        </div>
    </div>

    <section class="card">
        <div class="card-body">
            <form class="row g-2 align-items-end mb-3">
                <div class="col-12 col-md-4">
                    <label for="online-order-search" class="form-label">Tìm đơn hàng</label>
                    <input id="online-order-search" class="form-control" type="search" placeholder="Mã đơn, tên hoặc số điện thoại">
                </div>
                <div class="col-6 col-md-3">
                    <label for="online-payment-filter" class="form-label">Thanh toán</label>
                    <select id="online-payment-filter" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option>Chờ thanh toán</option>
                        <option>Chờ xác minh</option>
                        <option>Đã thanh toán</option>
                        <option>Cần kiểm tra lại</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="online-order-filter" class="form-label">Đơn hàng</label>
                    <select id="online-order-filter" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option>Chờ xác nhận</option>
                        <option>Đang xử lý</option>
                        <option>Hoàn tất</option>
                        <option>Đã hủy</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button class="btn btn-outline-success" type="button" disabled>Lọc đơn hàng</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Mã đơn</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Điện thoại</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col" class="text-end">Tổng tiền</th>
                            <th scope="col">Thanh toán</th>
                            <th scope="col">Trạng thái đơn</th>
                            <th scope="col">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">#1024</th>
                            <td>Nguyễn Minh Anh</td>
                            <td>09xx xxx 124</td>
                            <td>12/11/2026 10:24</td>
                            <td class="text-end">4.690.000₫</td>
                            <td><span class="badge text-bg-info">Chờ xác minh</span></td>
                            <td><span class="badge text-bg-warning">Chờ xử lý</span></td>
                            <td><a href="/admin/online-orders/1024" class="btn btn-sm btn-outline-success">Xem</a></td>
                        </tr>
                        <tr>
                            <th scope="row">#1023</th>
                            <td>Trần Hoàng Nam</td>
                            <td>09xx xxx 873</td>
                            <td>12/11/2026 09:48</td>
                            <td class="text-end">2.790.000₫</td>
                            <td><span class="badge text-bg-warning">Chờ thanh toán</span></td>
                            <td><span class="badge text-bg-secondary">Chờ thanh toán</span></td>
                            <td><a href="/admin/online-orders/1023" class="btn btn-sm btn-outline-success">Xem</a></td>
                        </tr>
                        <tr>
                            <th scope="row">#1022</th>
                            <td>Lê Ngọc Hà</td>
                            <td>09xx xxx 531</td>
                            <td>12/11/2026 09:05</td>
                            <td class="text-end">1.490.000₫</td>
                            <td><span class="badge text-bg-success">Đã thanh toán</span></td>
                            <td><span class="badge text-bg-primary">Đang xử lý</span></td>
                            <td><a href="/admin/online-orders/1022" class="btn btn-sm btn-outline-success">Xem</a></td>
                        </tr>
                        <tr>
                            <th scope="row">#1021</th>
                            <td>Phạm Quốc Bảo</td>
                            <td>09xx xxx 098</td>
                            <td>11/11/2026 18:31</td>
                            <td class="text-end">890.000₫</td>
                            <td><span class="badge text-bg-danger">Cần kiểm tra lại</span></td>
                            <td><span class="badge text-bg-warning">Chờ khách bổ sung</span></td>
                            <td><a href="/admin/online-orders/1021" class="btn btn-sm btn-outline-success">Xem</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Phân trang đơn hàng">
                <ul class="pagination justify-content-end mb-0">
                    <li class="page-item disabled"><span class="page-link">Trước</span></li>
                    <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="/admin/online-orders?page=2">2</a></li>
                    <li class="page-item"><a class="page-link" href="/admin/online-orders?page=2">Sau</a></li>
                </ul>
            </nav>
        </div>
    </section>
</main>
