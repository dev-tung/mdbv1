<style>
  .customer-form-page .customer-equipment-type-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .customer-form-page .customer-equipment-type-button {
    min-height: 38px;
    padding: 6px 12px;
    border: 1px solid #dce2eb;
    border-radius: 18px;
    background: #f5f6f8;
    color: #58657a;
    font-size: 13px;
    font-weight: 600;
  }

  .customer-form-page .customer-equipment-card {
    border-color: #e8ecf2;
    border-radius: 12px;
  }

  .customer-form-page .customer-equipment-card .card-body {
    padding: 14px;
  }

  .customer-form-page .customer-stringing-picker {
    padding: 12px;
    border: 1px solid #e8ecf2;
    border-radius: 12px;
    background: #f8f9fb;
  }

  @media (max-width: 767.98px) {
    .customer-form-page {
      padding: 16px 12px 24px !important;
    }

    .customer-form-page h3 {
      margin-bottom: 16px !important;
      font-size: 1.35rem;
    }

    #customer-form .form-control,
    #customer-form .form-select {
      min-height: 44px;
    }

    .customer-form-page .customer-equipment-card .card-body {
      padding: 12px;
    }

    .customer-form-page [data-stringing-record] {
      padding: 12px !important;
    }

    .customer-form-page .remove-customer-racket,
    .customer-form-page .remove-customer-shoe,
    .customer-form-page .remove-stringing-record {
      min-height: 36px;
    }

    #customer-form > .btn[type="submit"] {
      width: 100%;
      min-height: 44px;
    }
  }
</style>

<div class="container-fluid py-4 mt-5 customer-form-page">
  <h3 class="mb-4">Khách hàng</h3>

  <form id="customer-form" novalidate>

    <input type="hidden" id="customer_id" value="<?= $id ?>">

    <div class="row">

      <div class="col-lg-12">

        <div class="row g-3">

          <!-- NAME -->
          <div class="col-6">
            <label class="form-label">Tên khách hàng</label>

            <input
              type="text"
              id="name"
              class="form-control"
              placeholder="Nhập tên khách hàng">
          </div>


          <!-- CUSTOMER GROUP -->
          <div class="col-6">
            <label class="form-label">Nhóm khách hàng</label>

            <div class="input-group">
              <select id="customer_group_id" class="form-select">
                <option value="">-- Chọn nhóm khách hàng --</option>
              </select>

              <button
                type="button"
                class="btn btn-outline-secondary"
                data-bs-toggle="modal"
                data-bs-target="#customer-group-modal">
                +
              </button>
            </div>
          </div>


          <!-- PHONE -->
          <div class="col-md-6">
            <label class="form-label">Số điện thoại</label>

            <input
              type="text"
              id="phone"
              class="form-control"
              placeholder="Nhập số điện thoại">
          </div>


          <!-- EMAIL -->
          <div class="col-md-6">
            <label class="form-label">Email</label>

            <input
              type="email"
              id="email"
              class="form-control"
              placeholder="Nhập email">
          </div>


          <!-- DESCRIPTION -->
          <div class="col-12">
            <label class="form-label">Mô tả</label>

            <textarea
              id="description"
              rows="3"
              class="form-control"
              placeholder="Nhập mô tả khách hàng"></textarea>
          </div>


          <!-- ADDRESS -->
          <div class="col-12">
            <label class="form-label">Địa chỉ</label>

            <textarea
              id="address"
              rows="3"
              class="form-control"
              placeholder="Nhập địa chỉ khách hàng"></textarea>
          </div>

        </div>

      </div>

    </div>

    <section class="customer-equipment-section mt-4">
      <h4 class="mb-1">Thiết bị</h4>
      <p class="text-secondary small mb-3">
        Chọn loại thiết bị để thêm thông tin cho khách hàng
      </p>

      <div class="customer-equipment-type-buttons mb-3">
        <button type="button" class="customer-equipment-type-button" id="add-customer-racket">
          + Vợt
        </button>
        <button type="button" class="customer-equipment-type-button" id="add-customer-shoe">
          + Giày
        </button>
        <button type="button" class="customer-equipment-type-button" id="add-customer-stringing">
          + Căng cước
        </button>
      </div>

      <div id="customer-stringing-picker" class="customer-stringing-picker d-none mb-3">
        <h6 class="mb-2">Chọn vợt để thêm lần căng cước</h6>
        <div id="customer-stringing-racket-options" class="d-grid gap-2"></div>
        <button type="button" class="btn btn-sm btn-light mt-2" id="cancel-stringing-picker">
          Hủy chọn
        </button>
      </div>

      <div id="customer-rackets" class="vstack gap-3"></div>
      <div id="customer-shoes" class="vstack gap-3 mt-3"></div>
    </section>


    <!-- BUTTON -->
    <button
      type="submit"
      class="btn btn-outline-secondary mt-4">
      Lưu khách hàng
    </button>

  </form>
</div>

<template id="customer-racket-template">
  <div class="card customer-equipment-card">
    <div class="card-body">
      <input type="hidden" data-racket-field="id">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 equipment-card-title"></h5>
        <button type="button" class="btn btn-sm btn-outline-danger remove-customer-racket">Bỏ vợt</button>
      </div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Thương hiệu</label>
          <input type="text" class="form-control" data-racket-field="brand" maxlength="100">
        </div>
        <div class="col-md-6">
          <label class="form-label">Tên / model vợt</label>
          <input type="text" class="form-control" data-racket-field="model" maxlength="150">
        </div>
        <div class="col-12">
          <label class="form-label">Ghi chú</label>
          <input type="text" class="form-control" data-racket-field="note" maxlength="1000">
        </div>
      </div>
      <div class="mt-4 d-none" data-stringing-section>
        <div class="mb-2">
          <h6 class="mb-0">Lịch sử căng cước</h6>
        </div>
        <p class="form-text">Các lần căng đã lưu được giữ nguyên; thêm lần mới để ghi tiếp lịch sử.</p>
        <div class="vstack gap-2" data-stringing-list></div>
      </div>
    </div>
  </div>
</template>

<template id="customer-stringing-template">
  <div class="border rounded p-3" data-stringing-record>
    <input type="hidden" data-stringing-field="id">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Loại cước</label>
        <input type="text" class="form-control" data-stringing-field="string_type" maxlength="100">
      </div>
      <div class="col-md-2">
        <label class="form-label">Mức căng</label>
        <input type="text" class="form-control" data-stringing-field="tension" maxlength="40" placeholder="Ví dụ: 24 lbs">
      </div>
      <div class="col-md-3">
        <label class="form-label">Ngày căng</label>
        <input type="date" class="form-control" data-stringing-field="strung_at">
      </div>
      <div class="col-md-3">
        <label class="form-label">Ghi chú</label>
        <input type="text" class="form-control" data-stringing-field="note" maxlength="1000">
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-outline-danger remove-stringing-record">Bỏ</button>
      </div>
    </div>
  </div>
</template>

<template id="customer-shoe-template">
  <div class="card customer-equipment-card">
    <div class="card-body">
      <input type="hidden" data-shoe-field="id">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 equipment-card-title"></h5>
        <button type="button" class="btn btn-sm btn-outline-danger remove-customer-shoe">Bỏ giày</button>
      </div>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Thương hiệu</label>
          <input type="text" class="form-control" data-shoe-field="brand" maxlength="100">
        </div>
        <div class="col-md-5">
          <label class="form-label">Tên / model giày</label>
          <input type="text" class="form-control" data-shoe-field="model" maxlength="150">
        </div>
        <div class="col-md-3">
          <label class="form-label">Cỡ giày</label>
          <input type="text" class="form-control" data-shoe-field="size" maxlength="20" placeholder="Ví dụ: 39.5">
        </div>
        <div class="col-12">
          <label class="form-label">Ghi chú</label>
          <input type="text" class="form-control" data-shoe-field="note" maxlength="1000">
        </div>
      </div>
    </div>
  </div>
</template>


<!-- =================================================
     CUSTOMER GROUP MODAL
================================================= -->

<div
  class="modal fade"
  id="customer-group-modal"
  tabindex="-1"
  aria-labelledby="customer-group-modal-label"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header">

        <h5
          class="modal-title"
          id="customer-group-modal-label">
          Thêm nhóm khách hàng
        </h5>

        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close">
        </button>

      </div>


      <!-- BODY -->
      <div class="modal-body">

        <div class="mb-3">

          <label
            for="customer-group-name"
            class="form-label">
            Tên nhóm khách hàng
          </label>

          <input
            type="text"
            id="customer-group-name"
            class="form-control"
            placeholder="Nhập tên nhóm khách hàng">

        </div>

      </div>


      <!-- FOOTER -->
      <div class="modal-footer">

        <button
          type="button"
          class="btn btn-light"
          data-bs-dismiss="modal">
          Đóng
        </button>

        <button
          type="button"
          class="btn btn-outline-secondary"
          id="btn-save-customer-group">
          Lưu
        </button>

      </div>

    </div>

  </div>
</div>