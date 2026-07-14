
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
        <input
          type="text"
          id="filter-supplier"
          class="form-control form-control-sm"
          placeholder="Nhà cung cấp"
        >
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

<div class="d-flex gap-3 mb-3">
    <div>
        <strong>Tổng tiền</strong>
        <span id="sum-total-amount">0</span>
    </div>

    <div>
        <strong>Tổng trả</strong>
        <span id="sum-paid-amount">0</span>
    </div>

    <div>
        <strong>Tổng nợ</strong>
        <span id="sum-debt-amount">0</span>
    </div>
</div>

  <div class="table-responsive">
    <table class="table table-sm align-middle">

      <thead>
        <tr>
          <th>#</th>
          <th>Nhà cung cấp</th>
          <th>Kho</th>
          <th>Tổng tiền</th>
          <th>Đã trả</th>
          <th>Còn nợ</th>
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
</div>

<template id="purchase-row-template">
    <tr>
        <td class="index"></td>
        <td class="supplier-name"></td>
        <td class="warehouse-name"></td>
        <td class="total-amount text-end"></td>
        <td class="paid-amount text-end"></td>
        <td class="debt-amount text-end"></td>
        <td class="status"></td>
        <td class="payment"></td>
        <td class="created-at"></td>
        <td class="action"></td>
    </tr>
</template>