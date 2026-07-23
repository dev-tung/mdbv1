<div class="container-fluid py-4 mt-5">
  <h3 class="mb-4"> Khách hàng </h3>

  <form id="customer-form" novalidate>

    <input type="hidden" id="customer_id" value="<?= $id ?>">

    <div class="row">

      <div class="col-lg-12">

        <div class="row g-3">

          <!-- NAME -->
          <div class="col-12">
            <label class="form-label"> Tên khách hàng </label>

            <input
              type="text"
              id="name"
              class="form-control"
              placeholder="Nhập tên khách hàng">
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
              placeholder="Nhập mô tả khách hàng"></textarea>
          </div>


          <!-- ADDRESS -->
          <div class="col-12">
            <label class="form-label"> Địa chỉ </label>

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
    <button type="submit" class="btn btn-outline-secondary mt-4">
      Lưu khách hàng
    </button>

  </form>

</div>