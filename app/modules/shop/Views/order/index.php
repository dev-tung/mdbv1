<style>
  .order-index-page .order-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
  }

  .order-index-page .order-toolbar-actions {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .order-index-page .order-mobile-title,
  .order-index-page .order-card-list {
    display: none;
  }

  @media (max-width: 767.98px) {
    .order-index-page .order-toolbar {
      flex-direction: column;
      align-items: stretch;
    }

    .order-index-page .order-toolbar-actions {
      order: -1;
      justify-content: space-between;
    }

    .order-index-page .order-mobile-title {
      display: block;
      margin: 0;
      color: #172033;
      font-size: 22px;
      font-weight: 700;
    }

    .order-index-page .order-filters {
      display: grid;
      grid-template-columns: 1fr 1fr;
      width: 100%;
      margin: 0;
    }

    .order-index-page .order-filter-customer,
    .order-index-page .order-filter-payment {
      grid-column: 1 / -1;
    }

    .order-index-page .order-filter-customer {
      grid-row: 1;
    }

    .order-index-page .order-filter-date {
      grid-row: 2;
    }

    .order-index-page .order-filter-payment {
      grid-row: 3;
    }

    .order-index-page .order-filter-customer,
    .order-index-page .order-filter-date,
    .order-index-page .order-filter-payment {
      width: 100%;
    }

    .order-index-page .order-filter-customer .form-control,
    .order-index-page .order-filter-date .form-control,
    .order-index-page .order-filter-payment .form-select {
      width: 100%;
    }

    .order-index-page .order-summary {
      flex-wrap: wrap;
      gap: 7px !important;
      margin-bottom: 16px;
    }

    .order-index-page .order-summary-item {
      display: inline-flex;
      min-height: 28px;
      align-items: center;
      padding: 0 6px;
      border: 1px solid #e8ecf2;
      border-radius: 12px;
      color: #58657a;
      font-size: 12px;
      font-weight: 400;
      line-height: 13px;
    }

    .order-index-page .order-summary-item strong {
      margin-right: 4px;
      font-weight: 400;
    }

    .order-index-page .order-summary-total {
      border-color: #d8e5fb;
      background: #eaf1ff;
    }

    .order-index-page .order-summary-paid {
      border-color: #d9ebdf;
      background: #edf6f1;
    }

    .order-index-page .order-summary-debt {
      border-color: #f2e7ca;
      background: #fff7e6;
    }

    .order-index-page .order-card-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .order-index-page .order-card {
      padding: 13px;
      border: 1px solid #e8ecf2;
      border-radius: 8px;
      background: #fff;
    }

    .order-index-page .order-card-heading {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
    }

    .order-index-page .order-card-customer {
      min-width: 0;
      flex: 1;
      overflow: hidden;
      color: #172033;
      font-size: 15px;
      font-weight: 700;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .order-index-page .order-card-id {
      color: #9aa3b0;
      font-size: 12px;
    }

    .order-index-page .order-card-details {
      display: flex;
      flex-wrap: wrap;
      align-items: flex-start;
      gap: 7px;
      margin-top: 14px;
    }

    .order-index-page .order-badge {
      display: inline-flex;
      max-width: 100%;
      height: 28px;
      align-items: center;
      justify-content: center;
      padding: 0 6px;
      border: 1px solid #e8ecf2;
      border-radius: 12px;
      color: #58657a;
      font-size: 12px;
      font-weight: 400;
      line-height: 13px;
      overflow: hidden;
    }

    .order-index-page .order-total-badge {
      border-color: #d8e5fb;
      background: #eaf1ff;
    }

    .order-index-page .order-paid-badge {
      border-color: #d9ebdf;
      background: #edf6f1;
    }

    .order-index-page .order-debt-badge {
      border-color: #f2e7ca;
      background: #fff7e6;
    }

    .order-index-page .order-date-badge {
      border-color: #e8ecf2;
      background: #f1f3f5;
    }

    .order-index-page .order-card-controls {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
      margin-top: 10px;
    }

    .order-index-page .order-card-control {
      display: flex;
      min-width: 0;
      flex-direction: column;
      gap: 4px;
      color: #7c8799;
      font-size: 12px;
    }

    .order-index-page .order-card-control .form-select {
      min-height: 34px;
      padding: 4px 26px 4px 8px;
      border-color: #dce2eb;
      border-radius: 8px;
      color: #41506a;
      font-size: 12px;
    }

    .order-index-page .order-card-actions {
      display: flex;
      gap: 9px;
      margin-top: 15px;
    }

    .order-index-page .order-card-actions .edit-item {
      flex: 1;
      min-height: 36px;
      border-color: #dce2eb;
      color: #41506a;
    }

    .order-index-page .order-card-actions .delete-item {
      min-width: 70px;
      min-height: 36px;
      border-color: #f1d8d8;
      color: #bb4242;
    }

    .order-index-page .order-card-empty {
      padding: 40px 0;
      color: #7c8799;
      text-align: center;
    }

    .order-index-page .order-table-wrap {
      overflow: visible;
    }

    .order-index-page .order-table {
      display: none;
    }
  }
</style>

<div class="container-fluid py-4 mt-5 order-index-page">
  <div class="order-toolbar mb-3">
    <div class="row g-2 order-filters">
      <div class="col-auto order-filter-date">
        <input type="date" id="filter-date-from" class="form-control form-control-sm">
      </div>

      <div class="col-auto order-filter-date">
        <input type="date" id="filter-date-to" class="form-control form-control-sm">
      </div>

      <div class="col-auto order-filter-customer">
        <input
          type="text"
          id="filter-customer"
          class="form-control form-control-sm"
          placeholder="Tìm khách hàng"
        >
      </div>

      <div class="col-auto order-filter-payment">
        <select id="filter-payment" class="form-select form-select-sm">
          <option value="">Thanh toán</option>
        </select>
      </div>
    </div>

    <div class="order-toolbar-actions">
      <h1 class="order-mobile-title">Đơn hàng</h1>
      <a href="/admin/orders/create" class="btn btn-sm btn-outline-secondary">
        Thêm đơn hàng
      </a>
    </div>
  </div>

  <div class="d-flex gap-3 mb-3 order-summary">
    <div class="order-summary-item order-summary-total">
      <strong>Tổng tiền</strong>
      <span id="sum-total-amount">0</span>
    </div>

    <div class="order-summary-item order-summary-paid">
      <strong>Đã thu</strong>
      <span id="sum-paid-amount">0</span>
    </div>

    <div class="order-summary-item order-summary-debt">
      <strong>Còn nợ</strong>
      <span id="sum-debt-amount">0</span>
    </div>
  </div>

  <div id="order-list-container">
    <div id="order-card-list" class="order-card-list"></div>

    <div class="table-responsive order-table-wrap">
      <table class="table table-sm align-middle order-table">
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
      <a class="btn btn-sm btn-outline-secondary edit-item">Sửa</a>
      <button type="button" class="btn btn-sm btn-outline-danger delete-item">
        Xóa
      </button>
    </td>
  </tr>
</template>

<template id="order-card-template">
  <article class="order-card" data-id="">
    <div class="order-card-heading">
      <span class="order-card-customer"></span>
      <span class="order-card-id"></span>
    </div>

    <div class="order-card-details">
      <span class="order-badge order-total-badge"></span>
      <span class="order-badge order-paid-badge"></span>
      <span class="order-badge order-debt-badge"></span>
      <span class="order-badge order-date-badge"></span>
    </div>

    <div class="order-card-controls">
      <label class="order-card-control">
        Trạng thái
        <select class="form-select form-select-sm status"></select>
      </label>
      <label class="order-card-control">
        Thanh toán
        <select class="form-select form-select-sm payment"></select>
      </label>
    </div>

    <div class="order-card-actions">
      <a class="btn btn-sm btn-outline-secondary edit-item">Sửa</a>
      <button type="button" class="btn btn-sm btn-outline-danger delete-item">
        Xóa
      </button>
    </div>
  </article>
</template>
