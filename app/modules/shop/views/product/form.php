<div class="container-fluid py-4 mt-5">
  <h3 class="mb-4"> Thêm sản phẩm </h3>
  <form id="product-form" enctype="multipart/form-data" novalidate>
    <input type="hidden" id="id" name="id" value="<?= $id ?>">
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
            <label class="form-label"> Giá nhập </label>
            <input type="number" id="price" class="form-control" min="0" placeholder="Nhập giá nhập">
          </div>
          <!-- SALE PRICE -->
          <div class="col-md-6">
            <label class="form-label"> Giá bán </label>
            <input type="number" id="sale_price" class="form-control" min="0" placeholder="Nhập giá bán">
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
            <label class="form-label"> Danh mục </label>
            <select id="category_id" class="form-select">
              <option value=""> -- Chọn danh mục -- </option>
            </select>
          </div>
          <!-- BRAND -->
          <div class="col-12">
            <label class="form-label"> Thương hiệu </label>
            <select id="brand_id" class="form-select">
              <option value=""> -- Chọn thương hiệu -- </option>
            </select>
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
    <button type="submit" class="btn btn-outline-secondary mt-4"> Thêm sản phẩm </button>
  </form>
</div>