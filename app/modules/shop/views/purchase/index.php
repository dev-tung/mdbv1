
<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="row g-2">

      <div class="col-auto">
        <input type="date" id="filter-date-from" class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <input type="date" id="filter-date-to" class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <select id="filter-supplier" class="form-select form-select-sm">
          <option value="">Nhà cung cấp</option>
        </select>
      </div>

      <div class="col-auto">
        <select id="filter-payment" class="form-select form-select-sm">
          <option value="">Thanh toán</option>
        </select>
      </div>

    </div>

    <a href="/admin/purchases/create" class="btn btn-sm btn-outline-secondary">
      Thêm phiếu nhập
    </a>

  </div>

  <div class="mb-3">
    <strong>Tổng phiếu nhập:</strong>
    <span id="total-amount">0</span>
  </div>

  <div class="table-responsive">
    <table class="table table-sm align-middle">

      <thead>
        <tr>
          <th>#</th>
          <th>Nhà cung cấp</th>
          <th>Kho</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Thanh toán</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody id="purchase-table-body">
        <tr>
          <td colspan="9" class="text-center text-muted">Đang tải dữ liệu...</td>
        </tr>
      </tbody>

    </table>
  </div>

  <nav class="mt-3">
    <ul class="pagination pagination-sm" id="pagination">

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(1)">Đầu</a>
      </li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(prevPage)">Trước</a>
      </li>

      <li class="page-item d-flex" id="pagination-pages"></li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(nextPage)">Sau</a>
      </li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(lastPage)">Cuối</a>
      </li>

    </ul>
  </nav>
</div>

<script type="module">
    import { Table } from "/assets/js/modules/shop/purchases/list/table.js";
    import { Filters } from "/assets/js/modules/shop/purchases/list/filters.js";
    import { Status } from "/assets/js/modules/shop/purchases/list/status.js";
    import { Payment } from "/assets/js/modules/shop/purchases/list/payment.js";
    import { Delete } from "/assets/js/modules/shop/purchases/list/delete.js";

    document.addEventListener('DOMContentLoaded', async () => {

        window.APP_CONFIG = {
            statuses: <?= json_encode(config('shop.option.purchase_status')) ?>,
            payments: <?= json_encode(config('shop.option.payment')) ?>
        };

        Table.init('/api/purchases', window.APP_CONFIG);
        Filters.init('/api/suppliers', window.APP_CONFIG);
        Status.init('/api/purchases/status');
        Payment.init('/api/purchases/payment');
        Delete.init('/api/purchases/delete');

        await Table.load(1);
    });
</script>