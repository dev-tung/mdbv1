<div class="container-fluid py-4 mt-5">
  <h3 class="mb-4"> Sản phẩm </h3>
  <form id="product-form" enctype="multipart/form-data" novalidate>
    <input type="hidden" id="product_id" value="<?= $id ?>">
    <div class="row">
      <!-- LEFT -->
      <div class="col-lg-6">
        <div class="row g-3">
          <!-- NAME -->
          <div class="col-12">
            <label class="form-label"> Tên sản phẩm </label>
            <input type="text" id="name" class="form-control" placeholder="Nhập tên sản phẩm">
          </div>
          <!-- PRICE -->
          <div class="col-md-6">
            <label class="form-label"> Giá gốc </label>
            <input type="number" id="price" class="form-control" min="0" placeholder="Nhập giá gốc">
          </div>
          <!-- SALE PRICE -->
          <div class="col-md-6">
            <label class="form-label"> Giá khuyến mãi </label>
            <input type="number" id="sale_price" class="form-control" min="0" placeholder="Nhập giá khuyến mãi">
          </div>
          <!-- DESCRIPTION -->
          <div class="col-12">
            <label class="form-label"> Mô tả </label>
            <textarea id="description" rows="6" class="form-control" placeholder="Nhập mô tả sản phẩm"></textarea>
          </div>
        </div>
      </div>
      <!-- RIGHT -->
      <div class="col-lg-6">
        <div class="row g-3">
          <!-- CATEGORY -->
          <div class="col-12">
            <label class="form-label">Danh mục</label>

            <div class="input-group">
              <select id="category_id" class="form-select">
                <option value="">-- Chọn danh mục --</option>
              </select>

              <button
                type="button"
                class="btn btn-outline-secondary"
                data-bs-toggle="modal"
                data-bs-target="#category-modal">
                +
              </button>
            </div>
          </div>

          <div
            class="modal fade"
            id="category-modal"
            tabindex="-1"
            aria-labelledby="category-modal-label"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">

                <div class="modal-header">
                  <h5 class="modal-title" id="category-modal-label">
                    Thêm danh mục
                  </h5>

                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                  </button>
                </div>

                <div class="modal-body">
                  <div class="mb-3">
                    <label for="category-name" class="form-label">
                      Tên danh mục
                    </label>

                    <input
                      type="text"
                      id="category-name"
                      class="form-control"
                      placeholder="Nhập tên danh mục"
                    >
                  </div>
                </div>

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
                    id="btn-save-category">
                    Lưu
                  </button>
                </div>

              </div>
            </div>
          </div>

          <!-- BRAND -->
          <div class="col-12">
            <label class="form-label">Thương hiệu</label>

            <div class="input-group">
              <select id="brand_id" class="form-select">
                <option value="">-- Chọn thương hiệu --</option>
              </select>

              <button
                type="button"
                class="btn btn-outline-secondary"
                data-bs-toggle="modal"
                data-bs-target="#brand-modal">
                +
              </button>
            </div>
          </div>

          <div
            class="modal fade"
            id="brand-modal"
            tabindex="-1"
            aria-labelledby="brand-modal-label"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">

                <div class="modal-header">
                  <h5 class="modal-title" id="brand-modal-label">
                    Thêm thương hiệu
                  </h5>

                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                  </button>
                </div>

                <div class="modal-body">
                  <div class="mb-3">
                    <label for="brand-name" class="form-label">
                      Tên thương hiệu
                    </label>

                    <input
                      type="text"
                      id="brand-name"
                      class="form-control"
                      placeholder="Nhập tên thương hiệu"
                    >
                  </div>
                </div>

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
                    id="btn-save-brand">
                    Lưu
                  </button>
                </div>

              </div>
            </div>
          </div>

          <!-- STATUS -->
          <div class="col-12">
            <label class="form-label"> Trạng thái </label>
            <select id="status" class="form-select">
              <option value="active"> Đang bán </option>
              <option value="inactive"> Ngừng bán </option>
            </select>
          </div>
          <!-- THUMBNAIL -->
          <div class="col-12">
            <label class="form-label"> Ảnh sản phẩm </label>
            <input type="file" id="thumbnail" class="form-control" accept="image/*">
            <img id="thumbnail-preview" src="" class="img-thumbnail mt-3 d-none" style="max-height:150px">
          </div>
        </div>
      </div>
    </div>
    <!-- BUTTON -->
    <button type="submit" class="btn btn-outline-secondary mt-4"> Lưu sản phẩm </button>
  </form>
</div>