<style>
  .customer-index-page .customer-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
  }

  .customer-index-page .customer-toolbar-actions {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .customer-index-page .customer-mobile-title,
  .customer-index-page .customer-card-list {
    display: none;
  }

  .customer-index-page .customer-summary {
    margin-bottom: 16px;
    color: #50617d;
    font-size: 13px;
  }

  @media (max-width: 767.98px) {
    .customer-index-page .customer-toolbar {
      flex-direction: column;
      align-items: stretch;
    }

    .customer-index-page .customer-toolbar-actions {
      order: -1;
      justify-content: space-between;
    }

    .customer-index-page .customer-mobile-title {
      display: block;
      margin: 0;
      color: #172033;
      font-size: 22px;
      font-weight: 700;
    }

    .customer-index-page .customer-filters {
      display: grid;
      grid-template-columns: 1fr 1fr;
      width: 100%;
      margin: 0;
    }

    .customer-index-page .customer-filter-keyword {
      grid-column: 1 / -1;
    }

    .customer-index-page .customer-filter-keyword,
    .customer-index-page .customer-filter-date {
      width: 100%;
    }

    .customer-index-page .customer-filter-keyword .form-control,
    .customer-index-page .customer-filter-date .form-control {
      width: 100%;
    }

    .customer-index-page .customer-card-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .customer-index-page .customer-card {
      padding: 13px;
      border: 1px solid #e8ecf2;
      border-radius: 8px;
      background: #fff;
    }

    .customer-index-page .customer-card-heading {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
    }

    .customer-index-page .customer-card-name {
      min-width: 0;
      flex: 1;
      overflow: hidden;
      color: #172033;
      font-size: 15px;
      font-weight: 700;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .customer-index-page .customer-card-group {
      max-width: 45%;
      overflow: hidden;
      color: #9aa3b0;
      font-size: 12px;
      text-align: right;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .customer-index-page .customer-card-details {
      display: flex;
      flex-wrap: wrap;
      align-items: flex-start;
      gap: 7px;
      margin-top: 14px;
    }

    .customer-index-page .customer-badge {
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

    .customer-index-page .customer-phone-badge {
      border-color: #d8e5fb;
      background: #eaf1ff;
      color: #41506a;
      font-size: 13px;
      text-decoration: none;
    }

    .customer-index-page .customer-racket-badge {
      border-color: #d9ebdf;
      background: #edf6f1;
    }

    .customer-index-page .customer-shoe-badge {
      border-color: #e5dcf2;
      background: #f2edfa;
    }

    .customer-index-page .customer-stringing-badge {
      border-color: #dae5f4;
      background: #edf3fc;
    }

    .customer-index-page .customer-address-badge {
      border-color: #f2e7ca;
      background: #fff7e6;
    }

    .customer-index-page .customer-card-actions {
      display: flex;
      gap: 9px;
      margin-top: 15px;
    }

    .customer-index-page .customer-card-actions .edit-item {
      flex: 1;
      min-height: 36px;
      border-color: #dce2eb;
      color: #41506a;
    }

    .customer-index-page .customer-card-actions .delete-item {
      min-width: 70px;
      min-height: 36px;
      border-color: #f1d8d8;
      color: #bb4242;
    }

    .customer-index-page .customer-card-empty {
      padding: 40px 0;
      color: #7c8799;
      text-align: center;
    }

    .customer-index-page .customer-table-wrap {
      overflow: visible;
    }

    .customer-index-page .customer-table {
      display: none;
    }
  }
</style>

<div class="container-fluid py-4 mt-5 customer-index-page">

  <div class="customer-toolbar mb-3">

    <div class="row g-2 customer-filters">

      <div class="col-auto customer-filter-keyword">
        <input
          type="text"
          id="filter-keyword"
          class="form-control form-control-sm"
          placeholder="Tìm thông tin khách hàng và thiết bị">
      </div>


      <div class="col-auto customer-filter-date">
        <input
          type="date"
          id="filter-date-from"
          class="form-control form-control-sm">
      </div>


      <div class="col-auto customer-filter-date">
        <input
          type="date"
          id="filter-date-to"
          class="form-control form-control-sm">
      </div>

    </div>


    <div class="customer-toolbar-actions">
      <h1 class="customer-mobile-title">Khách hàng</h1>
      <a
        href="<?= route('/admin/customers/create') ?>"
        class="btn btn-sm btn-outline-secondary">
        Thêm khách hàng
      </a>
    </div>

  </div>



  <!-- SUMMARY -->

  <div class="customer-summary">
    Tổng khách hàng <span id="sum-total-customer">0</span>
  </div>




  <!-- TABLE -->

  <div id="customer-list-container">

    <div id="customer-card-list" class="customer-card-list"></div>

    <div class="table-responsive customer-table-wrap">

      <table class="table table-sm align-middle customer-table">

      <thead>

        <tr>
          <th>#</th>
          <th>Tên khách hàng</th>
          <th>Số điện thoại</th>
          <th>Email</th>
          <th>Địa chỉ</th>
          <th>Giày / vợt / căng cước</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>

      </thead>



      <tbody id="customer-table-body">

        <tr>

          <td colspan="8" class="text-center text-muted">
            Đang tải dữ liệu...
          </td>

        </tr>

      </tbody>


      </table>

    </div>

  </div>

</div>




<!-- ROW TEMPLATE -->

<template id="customer-row-template">

  <tr data-id="">


    <td class="index"></td>


    <td class="customer-name-cell">
      <span class="customer-name"></span>
    </td>


    <td class="phone"></td>


    <td class="email"></td>


    <td class="address"></td>

    <td class="equipment"></td>


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

<template id="customer-card-template">

  <article class="customer-card" data-id="">

    <div class="customer-card-heading">
      <span class="customer-card-name"></span>
      <span class="customer-card-group"></span>
    </div>

    <div class="customer-card-details"></div>

    <div class="customer-card-actions">
      <a class="btn btn-sm btn-outline-secondary edit-item">Sửa</a>
      <button type="button" class="btn btn-sm btn-outline-danger delete-item">Xóa</button>
    </div>

  </article>

</template>