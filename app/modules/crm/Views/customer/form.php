<div class="container-fluid py-4 mt-5">
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

    <section class="mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Vợt và lịch sử căng cước</h4>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="add-customer-racket">
          Thêm vợt
        </button>
      </div>
      <div id="customer-rackets" class="vstack gap-3"></div>
    </section>

    <section class="mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Giày</h4>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="add-customer-shoe">
          Thêm giày
        </button>
      </div>
      <div id="customer-shoes" class="vstack gap-3"></div>
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
  <div class="card">
    <div class="card-body">
      <input type="hidden" data-racket-field="id">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Thông tin vợt</h5>
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
      <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0">Lịch sử căng cước</h6>
          <button type="button" class="btn btn-sm btn-outline-secondary add-stringing-record">Thêm lần căng</button>
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
  <div class="card">
    <div class="card-body">
      <input type="hidden" data-shoe-field="id">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Thông tin giày</h5>
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