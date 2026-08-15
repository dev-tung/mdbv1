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


    <!-- BUTTON -->
    <button
      type="submit"
      class="btn btn-outline-secondary mt-4">
      Lưu khách hàng
    </button>

  </form>
</div>


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