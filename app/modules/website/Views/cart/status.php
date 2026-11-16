<main class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tra cứu đơn hàng</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <section class="card mb-4">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold">Tra cứu trạng thái đơn hàng</h1>
                    <p class="text-secondary">Nhập mã đơn hàng và số điện thoại đặt hàng. Chức năng tra cứu sẽ kết nối API ở bước backend.</p>
                    <form action="/order/status" method="get" class="row g-3">
                        <div class="col-12 col-md-5">
                            <label for="lookup-order" class="form-label">Mã đơn hàng</label>
                            <input id="lookup-order" name="order" class="form-control" type="text" placeholder="Ví dụ: 1024" required>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="lookup-phone" class="form-label">Số điện thoại</label>
                            <input id="lookup-phone" name="phone" class="form-control" type="tel" autocomplete="tel" placeholder="Số điện thoại đặt hàng" required>
                        </div>
                        <div class="col-12 col-md-2 d-grid align-items-end">
                            <button class="btn btn-success" type="button" disabled>Tra cứu</button>
                        </div>
                    </form>
                    <div class="alert alert-info mt-3 mb-0">Tra cứu chưa hoạt động ở bản giao diện này; không có thông tin đơn hàng thật được truy vấn.</div>
                </div>
            </section>

            <h2 class="h4 fw-bold mb-3">Các trạng thái đơn hàng</h2>
            <div class="list-group mb-4">
                <article class="list-group-item">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <h3 class="h6 fw-bold mb-0">Chờ thanh toán</h3>
                        <span class="badge text-bg-warning">Chờ thanh toán</span>
                    </div>
                    <p class="small text-secondary mt-2 mb-0">Hoàn tất chuyển khoản theo thông tin trên trang thanh toán.</p>
                </article>
                <article class="list-group-item">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <h3 class="h6 fw-bold mb-0">Đang xác minh thanh toán</h3>
                        <span class="badge text-bg-info">Chờ xác minh</span>
                    </div>
                    <p class="small text-secondary mt-2 mb-0">Cửa hàng đang đối soát giao dịch và ảnh chứng từ.</p>
                </article>
                <article class="list-group-item">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <h3 class="h6 fw-bold mb-0">Thanh toán được xác nhận</h3>
                        <span class="badge text-bg-success">Đã thanh toán</span>
                    </div>
                    <p class="small text-secondary mt-2 mb-0">Đơn hàng sẽ được chuyển sang bước chuẩn bị hàng.</p>
                </article>
                <article class="list-group-item">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <h3 class="h6 fw-bold mb-0">Thanh toán cần kiểm tra lại</h3>
                        <span class="badge text-bg-danger">Cần bổ sung</span>
                    </div>
                    <p class="small text-secondary mt-2 mb-0">Vui lòng xem lý do admin cung cấp và gửi lại chứng từ phù hợp.</p>
                </article>
                <article class="list-group-item">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <h3 class="h6 fw-bold mb-0">Đang xử lý đơn hàng</h3>
                        <span class="badge text-bg-primary">Đang xử lý</span>
                    </div>
                    <p class="small text-secondary mt-2 mb-0">Đơn hàng đã được xác nhận và đang chuẩn bị giao / nhận tại cửa hàng.</p>
                </article>
                <article class="list-group-item">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <h3 class="h6 fw-bold mb-0">Đơn hàng hoàn tất</h3>
                        <span class="badge text-bg-success">Hoàn tất</span>
                    </div>
                    <p class="small text-secondary mt-2 mb-0">Cảm ơn bạn đã mua hàng tại Manh Dung Sports.</p>
                </article>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="/product" class="btn btn-success">Tiếp tục mua sắm</a>
                <a href="/payment" class="btn btn-outline-secondary">Quay lại thanh toán</a>
            </div>
        </div>
    </div>
</main>

<script>
(() => {
    const orderNumber = new URLSearchParams(window.location.search).get('order');
    if (orderNumber) {
        document.getElementById('lookup-order').value = orderNumber;
    }
})();
</script>
