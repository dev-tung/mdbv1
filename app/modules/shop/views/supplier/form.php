<div class="container-fluid py-4 mt-5">
  <h3 class="mb-4"> Nhà cung cấp </h3>

  <form id="supplier-form" novalidate>
    <input type="hidden" id="supplier_id" value="<?= $id ?>">

    <div class="row">
      <!-- LEFT -->
      <div class="col-lg-12">
        <div class="row g-3">
          <!-- NAME -->
          <div class="col-12">
            <label class="form-label"> Tên nhà cung cấp </label>
            <input
              type="text"
              id="name"
              class="form-control"
              placeholder="Nhập tên nhà cung cấp">
          </div>

          <!-- PHONE -->
          <div class="col-md-6">
            <label class="form-label"> Số điện thoại </label>
            <input
              type="text"
              id="phone"
              class="form-control"
              placeholder="Nhập số điện thoại">
          </div>

          <!-- EMAIL -->
          <div class="col-md-6">
            <label class="form-label"> Email </label>
            <input
              type="email"
              id="email"
              class="form-control"
              placeholder="Nhập email">
          </div>

          <!-- DESCRIPTION -->
          <div class="col-12">
            <label class="form-label"> Mô tả </label>
            <textarea
              id="description"
              rows="3"
              class="form-control"
              placeholder="Nhập mô tả nhà cung cấp"></textarea>
          </div>


          <!-- ADDRESS -->
          <div class="col-12">
            <label class="form-label"> Địa chỉ </label>
            <textarea
              id="address"
              rows="3"
              class="form-control"
              placeholder="Nhập địa chỉ nhà cung cấp"></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- BUTTON -->
    <button type="submit" class="btn btn-outline-secondary mt-4">
      Lưu nhà cung cấp
    </button>
  </form>
</div>