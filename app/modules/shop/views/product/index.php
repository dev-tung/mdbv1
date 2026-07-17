<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="row g-2">
      <div class="col-auto">
        <input type="text" id="filter-keyword" class="form-control form-control-sm" placeholder="Tìm sản phẩm">
      </div>
      <div class="col-auto">
        <input type="date" id="filter-date-from" class="form-control form-control-sm">
      </div>
      <div class="col-auto">
        <input type="date" id="filter-date-to" class="form-control form-control-sm">
      </div>
      <div class="col-auto">
        <select id="filter-status" class="form-select form-select-sm">
          <option value=""> Trạng thái </option>
          <option value="active"> Đang bán </option>
          <option value="inactive"> Ngừng bán </option>
        </select>
      </div>
      <div class="col-auto">
        <select id="filter-category" class="form-select form-select-sm">
          <option value=""> Danh mục </option>
        </select>
      </div>
    </div>
    <a href="<?= route('/admin/products/create') ?>" class="btn btn-sm btn-outline-secondary"> Thêm sản phẩm </a>
  </div>
  <!-- SUMMARY -->
  <div class="d-flex gap-3 mb-3">
    <div>
      <strong> Tổng sản phẩm </strong>
      <span id="sum-total-product"> 0 </span>
    </div>
  </div>
  <!-- TABLE -->
  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th> Sản phẩm </th>
          <th> Danh mục </th>
          <th> Giá gốc </th>
          <th> Giá bán </th>
          <th> Trạng thái </th>
          <th> Ngày tạo </th>
          <th> Hành động </th>
        </tr>
      </thead>
      <tbody id="product-table-body">
        <tr>
          <td colspan="8" class="text-center text-muted"> Đang tải dữ liệu... </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<!-- ROW TEMPLATE -->
<template id="product-row-template">
  <tr data-id="">
    <td class="index"></td>
    <td>
      <div class="product-name"></div>
      <small class="sku text-muted"></small>
    </td>
    <td class="category-name"></td>
    <td class="cost-price"></td>
    <td class="sale-price"></td>
    <td>
      <select class="form-select form-select-sm status"></select>
    </td>
    <td class="created-at"></td>
    <td>
      <a class="btn btn-sm btn-outline-secondary edit-item"> Sửa </a>
      <button type="button" class="btn btn-sm btn-outline-danger delete-item"> Xóa </button>
    </td>
  </tr>
</template>