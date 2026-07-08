<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">Thêm sản phẩm</h3>

    <form id="product-create-form" novalidate>

        <div class="row">

            <div class="col-6">
                <div class="row g-3">

                    <div class="col-md-12">
                        <label class="form-label">Tên sản phẩm</label>
                        <input
                            type="text"
                            id="name"
                            class="form-control"
                            placeholder="Nhập tên sản phẩm"
                        >
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Giá</label>
                        <input
                            type="number"
                            id="price"
                            class="form-control"
                            placeholder="Nhập giá"
                        >
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input
                            type="number"
                            id="sale_price"
                            class="form-control"
                            placeholder="Nhập giá khuyến mãi"
                        >
                    </div>

                    <div class="col-md-12">
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

            <div class="col-6">
                <div class="row g-3">

                    <div class="col-md-12">
                        <label class="form-label">Danh mục</label>

                        <select id="category_id" class="form-select">
                            <option value="">-- Chọn danh mục --</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Ảnh sản phẩm</label>

                        <input
                            type="file"
                            id="thumbnail"
                            class="form-control mb-2"
                        >

                        <img
                            id="thumbnail-preview"
                            src=""
                            alt=""
                            class="img-thumbnail d-none"
                            style="max-height:120px"
                        >
                    </div>

                </div>
            </div>

        </div>

        <div class="col-12">
            <button
                type="submit"
                class="btn btn-outline-secondary mt-3"
            >
                Thêm sản phẩm
            </button>
        </div>

    </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    async function loadCategories() {
        try {
            const res = await fetch('/api/categories');
            const json = await res.json();

            const data = json.data;

            const select = document.querySelector('#category_id');

            select.innerHTML = '<option value="">-- Chọn danh mục --</option>';

            data.forEach(c => {
                select.innerHTML += `
                    <option value="${c.id}">
                        ${c.name}
                    </option>
                `;
            });

        } catch (err) {
            console.error(err);
        }
    }

    document
        .querySelector('#thumbnail')
        .addEventListener('change', function () {

            const file = this.files[0];

            if (!file) return;

            const preview = document.querySelector('#thumbnail-preview');

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');

        });

    document.querySelector('#product-create-form').addEventListener('submit', async function(e) {

        e.preventDefault();

        const formData = new FormData();

        formData.append('name', document.querySelector('#name').value);
        formData.append('price', document.querySelector('#price').value);
        formData.append('sale_price', document.querySelector('#sale_price').value);
        formData.append('category_id', document.querySelector('#category_id').value);
        formData.append('description', document.querySelector('#description').value);

        const thumbnail = document.querySelector('#thumbnail').files[0];

        if (thumbnail) {
            formData.append('thumbnail', thumbnail);
        }

        try {

            const res = await fetch('/api/products', {
                method: 'POST',
                body: formData
            });

            const result = await res.json();

            if (result.success) {

                alert('Thêm sản phẩm thành công');

                window.location.href = '/admin/products';

            } else {

                alert(result.message || 'Thêm thất bại');

            }

        } catch (err) {

            console.error(err);

            alert('Lỗi hệ thống');

        }

    });

    loadCategories();

});
</script>