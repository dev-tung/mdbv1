<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">Cập nhật sản phẩm</h3>

    <form id="product-edit-form" novalidate>

        <div class="row">
          <div class="col-6">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" id="name" class="form-control" placeholder="Nhập tên sản phẩm">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Giá</label>
                    <input type="number" id="price" class="form-control" placeholder="Nhập giá">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Giá khuyến mãi</label>
                    <input type="number" id="sale_price" class="form-control" placeholder="Nhập giá khuyến mãi">
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

                    <input type="file" id="thumbnail" class="form-control mb-2">

                    <img
                        id="thumbnail-preview"
                        src=""
                        alt=""
                        class="img-thumbnail"
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
                Cập nhật sản phẩm
            </button>
        </div>

    </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const productId = window.location.pathname.split('/').pop();

    async function loadCategories() {

        try {

            const res = await fetch('/api/categories');
            const json = await res.json();

            const select = document.querySelector('#category_id');

            select.innerHTML = '<option value="">-- Chọn danh mục --</option>';

            json.data.forEach(category => {

                select.innerHTML += `
                    <option value="${category.id}">
                        ${category.name}
                    </option>
                `;

            });

        } catch (error) {

            console.error(error);

        }

    }

    async function loadProduct() {

        try {

            const res = await fetch(`/api/products/show/${productId}`);
            const json = await res.json();

            const product = json.data;

            document.querySelector('#name').value = product.name ?? '';
            document.querySelector('#price').value = product.price ?? '';
            document.querySelector('#sale_price').value = product.sale_price ?? '';
            document.querySelector('#category_id').value = product.category_id ?? '';
            document.querySelector('#description').value = product.description ?? '';

            if (product.thumbnail) {

                document.querySelector('#thumbnail-preview').src =
                    product.thumbnail;

            }

        } catch (error) {

            console.error(error);

        }

    }

    document
        .querySelector('#thumbnail')
        .addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            document.querySelector('#thumbnail-preview').src =
                URL.createObjectURL(file);

        });

    document
        .querySelector('#product-edit-form')
        .addEventListener('submit', async function (e) {

            e.preventDefault();

            const formData = new FormData();

            formData.append('id', productId);
            formData.append('name', document.querySelector('#name').value);
            formData.append('price', document.querySelector('#price').value);
            formData.append('sale_price', document.querySelector('#sale_price').value);
            formData.append('category_id', document.querySelector('#category_id').value);
            formData.append('description', document.querySelector('#description').value);

            const thumbnail =
                document.querySelector('#thumbnail').files[0];

            if (thumbnail) {

                formData.append(
                    'thumbnail',
                    thumbnail
                );

            }

            try {

                const res = await fetch(
                    '/api/products/update',
                    {
                        method: 'POST',
                        body: formData
                    }
                );

                const result = await res.json();

                if (result.success) {

                    alert('Cập nhật sản phẩm thành công');

                    window.location.href =
                        '/admin/products';

                } else {

                    alert(
                        result.message ??
                        'Cập nhật thất bại'
                    );

                }

            } catch (error) {

                console.error(error);

                alert('Lỗi hệ thống');

            }

        });

    (async function () {

        await loadCategories();
        await loadProduct();

    })();
});
</script>