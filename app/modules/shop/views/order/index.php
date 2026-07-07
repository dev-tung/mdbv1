<div class="container-fluid py-4 mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="row g-2">

            <div class="col-auto">
                <input
                    type="date"
                    id="filter-date-from"
                    class="form-control form-control-sm">
            </div>

            <div class="col-auto">
                <input
                    type="date"
                    id="filter-date-to"
                    class="form-control form-control-sm">
            </div>

            <div class="col-auto">
                <select
                    id="filter-customer"
                    class="form-select form-select-sm">

                    <option value="">
                        Khách hàng
                    </option>

                </select>
            </div>

            <div class="col-auto">
                <select
                    id="filter-payment"
                    class="form-select form-select-sm">

                    <option value="">
                        Thanh toán
                    </option>

                </select>
            </div>

        </div>

        <a
            href="/admin/orders/create"
            class="btn btn-sm btn-outline-secondary">

            Thêm đơn hàng

        </a>

    </div>



    <!-- SUMMARY -->

    <div class="d-flex gap-4 mb-3">

        <div>
            <strong>Tổng tiền</strong>
            <span id="sum-total-amount">0</span>
        </div>

        <div>
            <strong>Đã thu</strong>
            <span id="sum-paid-amount">0</span>
        </div>

        <div>
            <strong>Còn nợ</strong>
            <span id="sum-debt-amount">0</span>
        </div>

    </div>



    <div class="table-responsive">

        <table class="table table-sm align-middle">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Khách hàng</th>

                    <th>Tổng tiền</th>

                    <th>Đã thu</th>

                    <th>Còn nợ</th>

                    <th>Trạng thái</th>

                    <th>Thanh toán</th>

                    <th>Ngày tạo</th>

                    <th>Hành động</th>

                </tr>

            </thead>

            <tbody id="order-table-body">

                <tr>

                    <td colspan="9" class="text-center text-muted">

                        Đang tải dữ liệu...

                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    <!-- PAGINATION -->

    <nav class="mt-3">

        <ul class="pagination pagination-sm" id="pagination">

            <li class="page-item">

                <a
                    class="page-link text-secondary"
                    href="javascript:void(0)"
                    onclick="goToPage(1)">

                    Đầu

                </a>

            </li>

            <li class="page-item">

                <a
                    class="page-link text-secondary"
                    href="javascript:void(0)"
                    onclick="goToPage(prevPage)">

                    Trước

                </a>

            </li>

            <li
                class="page-item d-flex"
                id="pagination-pages">
            </li>

            <li class="page-item">

                <a
                    class="page-link text-secondary"
                    href="javascript:void(0)"
                    onclick="goToPage(nextPage)">

                    Sau

                </a>

            </li>

            <li class="page-item">

                <a
                    class="page-link text-secondary"
                    href="javascript:void(0)"
                    onclick="goToPage(lastPage)">

                    Cuối

                </a>

            </li>

        </ul>

    </nav>

</div>

<script>

window.OrderConfig = {

    options: {

        statuses: <?= json_encode(config('shop.option.order_status')) ?>,

        payments: <?= json_encode(config('shop.option.payment')) ?>

    }

};

</script>

<script
    type="module"
    src="<?= asset('js/modules/shop/order/list/Controller.js') ?>">
</script>