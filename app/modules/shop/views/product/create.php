<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">Thêm sản phẩm</h3>

    <form id="product-create-form" enctype="multipart/form-data" novalidate>

        <div class="row">

            <!-- LEFT -->
            <div class="col-lg-6">

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label">Tên sản phẩm</label>
                        <input
                            type="text"
                            id="name"
                            class="form-control"
                            placeholder="Nhập tên sản phẩm"
                        >
                    </div>

                    <div class="col-12">
                        <label class="form-label">Giá</label>
                        <input
                            type="number"
                            id="price"
                            class="form-control"
                            placeholder="Nhập giá"
                        >
                    </div>

                    <div class="col-12">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input
                            type="number"
                            id="sale_price"
                            class="form-control"
                            placeholder="Nhập giá khuyến mãi"
                        >
                    </div>

                    <div class="col-12">
                        <label class="form-label">Mô tả</label>

                        <textarea
                            id="description"
                            rows="5"
                            class="form-control"
                            placeholder="Nhập mô tả"
                        ></textarea>
                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-6">

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label">Danh mục</label>

                        <select
                            id="category_id"
                            class="form-select"
                        >
                            <option value="">-- Chọn danh mục --</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Thương hiệu</label>

                        <select
                            id="brand_id"
                            class="form-select"
                        >
                            <option value="">-- Chọn thương hiệu --</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Trạng thái</label>

                        <select
                            id="status"
                            class="form-select"
                        >
                            <option value="active">Đang bán</option>
                            <option value="inactive">Ngừng bán</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Ảnh sản phẩm</label>

                        <input
                            type="file"
                            id="thumbnail"
                            class="form-control"
                            accept="image/*"
                        >

                        <img
                            id="thumbnail-preview"
                            src=""
                            class="img-thumbnail mt-2 d-none"
                            style="max-height:120px"
                        >
                    </div>

                </div>

            </div>

        </div>

        <button
            type="submit"
            class="btn btn-outline-secondary mt-4"
        >
            Thêm sản phẩm
        </button>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    loadCategories();
    loadBrands();

    // =========================
    // PREVIEW IMAGE
    // =========================
    document.querySelector('#thumbnail').addEventListener('change', e => {

        const file = e.target.files[0];

        if (!file) {
            return;
        }

        const preview = document.querySelector('#thumbnail-preview');

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');

    });

    // =========================
    // SUBMIT
    // =========================
    document.querySelector('#product-create-form').addEventListener('submit', async e => {

        e.preventDefault();

        const formData = new FormData();

        formData.append('name', document.querySelector('#name').value);
        formData.append('price', document.querySelector('#price').value);
        formData.append('sale_price', document.querySelector('#sale_price').value);
        formData.append('category_id', document.querySelector('#category_id').value);
        formData.append('brand_id', document.querySelector('#brand_id').value);
        formData.append('status', document.querySelector('#status').value);
        formData.append('description', document.querySelector('#description').value);

        const thumbnail = document.querySelector('#thumbnail').files[0];

        if (thumbnail) {
            formData.append('thumbnail', thumbnail);
        }

        try {

            const response = await fetch('/api/products', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            alert(result.message);

            if (result.success) {
                window.location.href = '/admin/products';
            }

        } catch (error) {

            console.error(error);

            alert('Lỗi hệ thống');

        }

    });

    // =========================
    // CATEGORY
    // =========================
    async function loadCategories() {

        try {

            const response = await fetch('/api/categories');

            const result = await response.json();

            const select = document.querySelector('#category_id');

            select.innerHTML = '<option value="">-- Chọn danh mục --</option>';

            result.data.forEach(item => {

                select.innerHTML += `
                    <option value="${item.id}">
                        ${item.name}
                    </option>
                `;

            });

        } catch (error) {

            console.error(error);

        }

    }

    // =========================
    // BRAND
    // =========================
    async function loadBrands() {

        try {

            const response = await fetch('/api/brands');

            const result = await response.json();

            const select = document.querySelector('#brand_id');

            select.innerHTML = '<option value="">-- Chọn thương hiệu --</option>';

            result.data.forEach(item => {

                select.innerHTML += `
                    <option value="${item.id}">
                        ${item.name}
                    </option>
                `;

            });

        } catch (error) {

            console.error(error);

        }

    }

});
</script>