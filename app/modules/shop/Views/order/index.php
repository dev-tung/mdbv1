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
          id="filter-customer"
          class="form-control form-control-sm"
          placeholder="Khách hàng"
        >
      </div>

      <div class="col-auto">
        <select id="filter-payment" class="form-select form-select-sm">
          <option value="">Thanh toán</option>
        </select>
      </div>

    </div>

    <a href="/admin/orders/create" class="btn btn-sm btn-outline-secondary">
      Thêm đơn hàng
    </a>

  </div>

  <div class="d-flex gap-3 mb-3">
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
</div>

<template id="order-row-template">
  <tr>
    <td class="index"></td>
    <td class="customer-name"></td>
    <td class="total-amount"></td>
    <td class="paid-amount"></td>
    <td class="debt-amount"></td>

    <td>
      <select class="form-select form-select-sm status"></select>
    </td>

    <td>
      <select class="form-select form-select-sm payment"></select>
    </td>

    <td class="created-at"></td>

    <td>
      <a class="btn btn-sm btn-outline-secondary edit-item">
        Sửa
      </a>

      <button type="button" class="btn btn-sm btn-outline-danger delete-item">
        Xóa
      </button>
    </td>
  </tr>
</template>