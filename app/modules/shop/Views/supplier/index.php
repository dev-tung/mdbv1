<div class="container-fluid py-4 mt-5">

  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="row g-2">

      <div class="col-auto">
        <input
          type="text"
          id="filter-keyword"
          class="form-control form-control-sm"
          placeholder="Tìm nhà cung cấp">
      </div>

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

    </div>


    <a
      href="<?= route('/admin/suppliers/create') ?>"
      class="btn btn-sm btn-outline-secondary">
      Thêm nhà cung cấp
    </a>

  </div>


  <!-- SUMMARY -->

  <div class="d-flex gap-3 mb-3">

    <div>
      <strong> Tổng nhà cung cấp </strong>

      <span id="sum-total-supplier">0</span>
    </div>

  </div>


  <!-- TABLE -->

  <div class="table-responsive">

    <table class="table table-sm align-middle">

      <thead>
        <tr>
          <th>#</th>
          <th>Tên nhà cung cấp</th>
          <th>Số điện thoại</th>
          <th>Email</th>
          <th>Địa chỉ</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>


      <tbody id="supplier-table-body">

        <tr>
          <td colspan="7" class="text-center text-muted">
            Đang tải dữ liệu...
          </td>
        </tr>

      </tbody>

    </table>

  </div>

</div>



<!-- ROW TEMPLATE -->

<template id="supplier-row-template">

  <tr data-id="">

    <td class="index"></td>

    <td class="supplier-name"></td>

    <td class="phone"></td>

    <td class="email"></td>

    <td class="address"></td>

    <td class="created-at"></td>

    <td>

      <a
        class="btn btn-sm btn-outline-secondary edit-item">
        Sửa
      </a>


      <button
        type="button"
        class="btn btn-sm btn-outline-danger delete-item">
        Xóa
      </button>

    </td>

  </tr>

</template>