<div class="container-fluid py-4 mt-5">
  <h3 class="mb-4">Phiếu nhập hàng</h3>
  <form id="purchase-form" novalidate>
    <input type="hidden" id="purchase_id">
    <div class="row g-3">
      <!-- Supplier -->
      <div class="col-md-3 position-relative">
        <label class="form-label">Nhà cung cấp</label>
        <input type="text" id="supplier_search" class="form-control" placeholder="Tìm nhà cung cấp..." autocomplete="off">
        <input type="hidden" id="supplier_id">
        <div id="supplier_suggestions" class="list-group position-absolute d-none"></div>
      </div>
      <!-- Description -->
      <div class="col-md-6">
        <label class="form-label">Mô tả</label>
        <input type="text" id="description" class="form-control" placeholder="Nhập mô tả phiếu nhập">
      </div>
      <!-- Status -->
      <div class="col-md-3">
        <label class="form-label">Trạng thái</label>
        <select id="status" class="form-select"></select>
      </div>
      <!-- Warehouse -->
      <div class="col-md-3">
        <label class="form-label">Kho nhập</label>
        <select id="warehouse_id" class="form-select"></select>
      </div>
      <!-- VAT -->
      <div class="col-md-3">
        <label class="form-label">VAT (%)</label>
        <input type="number" id="vat_rate" class="form-control" min="0" max="100">
      </div>
      <!-- Payment -->
      <div class="col-md-3">
        <label class="form-label">Thanh toán</label>
        <select id="payment" class="form-select"></select>
      </div>
      <!-- Paid -->
      <div class="col-md-3" id="paid_amount_wrapper">
        <label class="form-label">Đã thanh toán</label>
        <input type="number" id="paid_amount" class="form-control" min="0">
      </div>
      <!-- Product Search -->
      <div class="col-12 position-relative mt-4">
        <label class="form-label">Sản phẩm</label>
        <input type="text" id="product_search" class="form-control" placeholder="Tìm sản phẩm..." autocomplete="off">
        <div id="product_suggestions" class="list-group position-absolute d-none"></div>
      </div>
      <!-- Product Table -->
      <div class="col-12">
        <div class="border rounded p-3">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead>
                <tr>
                  <th>Tên</th>
                  <th>SL</th>
                  <th>Giá nhập</th>
                  <th>Giá bán</th>
                  <th>Thành tiền</th>
                  <th>VAT</th>
                  <th>Tổng sau VAT</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="selected_products"></tbody>
            </table>
          </div>
          <div class="mt-3">
            <div class="mb-0 d-flex flex-wrap gap-5">
              <span class="fs-5"> Tạm tính <b id="subtotal_amount">0</b> ₫ </span>
              <span class="fs-5"> VAT <b id="vat_amount">0</b> ₫ </span>
              <span class="fs-5"> Tổng tiền <b id="total_amount">0</b> ₫ </span>
              <span class="fs-5"> Còn nợ <b id="debt_amount">0</b> ₫ </span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-outline-secondary mt-3"> Lưu phiếu nhập </button>
      </div>
    </div>
  </form>
</div>

<template id="supplier-item-template">
  <button type="button" class="list-group-item list-group-item-action supplier-item"></button>
</template>

<template id="product-item-template">
  <button type="button" class="list-group-item list-group-item-action product-item"></button>
</template>

<template id="purchase-item-template">
  <tr>
    <td class="product-name"></td>
    <td>
      <input type="number" class="form-control form-control-sm quantity">
    </td>
    <td>
      <input type="number" class="form-control form-control-sm purchase-price">
    </td>
    <td>
      <input type="number" class="form-control form-control-sm selling-price">
    </td>
    <td class="subtotal"></td>
    <td class="vat"></td>
    <td class="total"></td>
    <td>
      <button type="button" class="btn btn-sm btn-outline-danger remove-item"> Xóa </button>
    </td>
  </tr>
</template>