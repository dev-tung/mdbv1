<style>
  @media (max-width: 767.98px) {
    .order-form-page {
      padding: 16px 12px 24px !important;
    }

    .order-form-page h3 {
      margin-bottom: 16px !important;
      font-size: 1.35rem;
    }

    #order-form .row.g-3 {
      --bs-gutter-y: 0.75rem;
    }

    #order-form .form-label {
      margin-bottom: 0.35rem;
      font-size: 0.9rem;
    }

    #order-form .form-control,
    #order-form .form-select {
      min-height: 44px;
    }

    .order-form-page .position-relative > .list-group.position-absolute {
      z-index: 1050;
      max-height: 50vh;
      overflow-y: auto;
    }

    #order-form .product-search-section {
      margin-top: 0.75rem !important;
    }

    #order-form .product-list-panel {
      padding: 0.75rem !important;
    }

    #order-form .product-table-responsive {
      overflow: visible;
    }

    #order-form .product-table thead {
      display: none;
    }

    #order-form .product-table,
    #order-form .product-table tbody {
      display: block;
      width: 100%;
    }

    #order-form .product-table tbody tr {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 8px;
      margin-bottom: 10px;
      padding: 12px;
      border: 1px solid #e8ecf2;
      border-radius: 8px;
      background: #fff;
    }

    #order-form .product-table tbody td {
      display: flex;
      min-width: 0;
      flex-direction: column;
      justify-content: center;
      padding: 0 !important;
      border: 0 !important;
      color: #58657a;
      font-size: 13px;
      white-space: normal;
    }

    #order-form .product-table tbody td::before {
      margin-bottom: 4px;
      color: #7c8799;
      font-size: 11px;
      line-height: 1.2;
      content: attr(data-label);
    }

    #order-form .product-table tbody td.product-name {
      grid-column: 1 / -1;
      color: #172033;
      font-weight: 600;
    }

    #order-form .product-table tbody td.total_amount {
      grid-column: 1 / -1;
    }

    #order-form .product-table tbody td.remove-cell::before {
      content: none;
    }

    #order-form .product-table .quantity,
    #order-form .product-table .selling-price {
      min-height: 40px;
      padding: 6px 8px;
      font-size: 16px;
    }

    #order-form .product-table .is-gift {
      width: 20px;
      height: 20px;
      margin: 0;
    }

    #order-form .product-table .remove-item {
      min-height: 36px;
    }

    #order-form .order-amount-summary {
      display: grid !important;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 8px !important;
    }

    #order-form .order-amount-summary > span {
      display: flex;
      min-width: 0;
      min-height: 54px;
      flex-direction: column;
      justify-content: center;
      padding: 7px 9px;
      border: 1px solid #e8ecf2;
      border-radius: 8px;
      background: #f7f8fb;
      color: #58657a;
      font-size: 13px !important;
      line-height: 1.3;
    }

    #order-form .order-amount-summary b {
      margin-top: 3px;
      color: #172033;
      font-size: 14px;
    }

    #order-form .order-submit-button {
      width: 100%;
      min-height: 44px;
      margin-top: 0.25rem !important;
    }
  }
</style>

<div class="container-fluid py-4 mt-5 order-form-page">
  <h3 class="mb-4">Đơn hàng</h3>

  <form id="order-form" novalidate>
    <input type="hidden" id="order_id" value="<?= $id ?>">

    <div class="row g-3">

      <!-- Customer -->
      <div class="col-md-3 position-relative">
        <label class="form-label">Khách hàng</label>

        <input
          type="text"
          id="customer_search"
          class="form-control"
          placeholder="Tìm khách hàng..."
          autocomplete="off">

        <input type="hidden" id="customer_id">

        <div
          id="customer_suggestions"
          class="list-group position-absolute d-none">
        </div>
      </div>


      <!-- Description -->
      <div class="col-md-6">
        <label class="form-label">Mô tả</label>

        <input
          type="text"
          id="description"
          class="form-control"
          placeholder="Nhập mô tả đơn hàng">
      </div>


      <!-- Status -->
      <div class="col-md-3">
        <label class="form-label">Trạng thái</label>

        <select
          id="status"
          class="form-select">
        </select>
      </div>


      <!-- VAT -->
      <div class="col-md-3">
        <label class="form-label">VAT (%)</label>

        <input
          type="number"
          id="vat_rate"
          class="form-control"
          min="0"
          max="100">
      </div>


      <!-- Payment -->
      <div class="col-md-3">
        <label class="form-label">Thanh toán</label>

        <select
          id="payment"
          class="form-select">
        </select>
      </div>


      <!-- Paid -->
      <div
        class="col-md-3 d-none"
        id="paid_amount_wrapper">

        <label class="form-label">
          Đã thanh toán
        </label>

        <input
          type="number"
          id="paid_amount"
          class="form-control"
          min="0">
      </div>


      <!-- Product Search -->
      <div class="col-12 position-relative mt-4 product-search-section">

        <label class="form-label">
          Sản phẩm
        </label>

        <input
          type="text"
          id="product_search"
          class="form-control"
          placeholder="Tìm sản phẩm..."
          autocomplete="off">

        <div
          id="product_suggestions"
          class="list-group position-absolute d-none">
        </div>

      </div>


      <!-- Product Table -->
      <div class="col-12">

        <div class="border rounded p-3 product-list-panel">

          <div class="table-responsive product-table-responsive">

            <table class="table table-sm align-middle mb-0 product-table">

              <thead>

                <tr>
                  <th>Tên</th>
                  <th>SL</th>
                  <th>Giá bán</th>
                  <th>Thành tiền</th>
                  <th>VAT</th>
                  <th>Tổng sau VAT</th>
                  <th>Quà tặng</th>
                  <th></th>
                </tr>

              </thead>


              <tbody id="selected_products"></tbody>


            </table>

          </div>


          <div class="mt-3">

            <div class="mb-0 d-flex flex-wrap gap-5 order-amount-summary">

              <span class="fs-5">
                Tạm tính
                <b id="subtotal_amount">0</b> ₫
              </span>


              <span class="fs-5">
                VAT
                <b id="vat_amount">0</b> ₫
              </span>


              <span class="fs-5">
                Tổng tiền
                <b id="total_amount">0</b> ₫
              </span>


              <span class="fs-5">
                Còn nợ
                <b id="debt_amount">0</b> ₫
              </span>


            </div>

          </div>


        </div>

      </div>


      <!-- Submit -->
      <div class="col-12">

        <button
          type="submit"
          class="btn btn-outline-secondary mt-3 order-submit-button">

          Lưu đơn hàng

        </button>

      </div>


    </div>

  </form>

</div>



<template id="order-item-template">

  <tr>


    <!-- Product -->
    <td class="product-name" data-label="Sản phẩm"></td>


    <!-- Quantity -->
    <td data-label="Số lượng">

      <input
        type="number"
        class="form-control form-control-sm quantity"
        min="1">

    </td>


    <!-- Selling Price -->
    <td data-label="Giá bán">

      <input
        type="number"
        class="form-control form-control-sm selling-price"
        min="0">

    </td>


    <!-- Subtotal -->
    <td class="subtotal_amount" data-label="Thành tiền"></td>


    <!-- VAT -->
    <td class="vat_amount" data-label="VAT"></td>


    <!-- Total -->
    <td class="total_amount" data-label="Tổng sau VAT"></td>


    <!-- Gift -->
    <td class="text-start" data-label="Quà tặng">

      <input
        type="checkbox"
        class="form-check-input is-gift">

    </td>


    <!-- Remove -->
    <td class="remove-cell">

      <button
        type="button"
        class="btn btn-sm btn-outline-danger remove-item">

        Xóa

      </button>

    </td>


  </tr>


</template>