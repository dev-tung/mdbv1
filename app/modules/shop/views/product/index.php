<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="row g-2">

      <div class="col-auto">
        <input
          type="text"
          id="filter-name"
          class="form-control form-control-sm"
          placeholder="Tìm theo tên sản phẩm">
      </div>

      <div class="col-auto">
        <input
          type="date"
          id="filter-date-from"
          class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <input
          type="date"
          id="filter-date-to"
          class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <select id="filter-status" class="form-select form-select-sm">
          <option value="">Trạng thái</option>
          <option value="active">Đang bán</option>
          <option value="inactive">Ngừng bán</option>
        </select>
      </div>

      <div class="col-auto">
        <select id="filter-category" class="form-select form-select-sm">
          <option value="">Danh mục</option>
        </select>
      </div>

    </div>

    <a href="<?= route('/admin/products/create') ?>" class="btn btn-sm btn-outline-secondary">
      Thêm sản phẩm
    </a>

  </div>

  <div class="mb-3">
    <strong>Tổng sản phẩm:</strong>
    <span id="total-amount">0</span>
  </div>

  <div class="table-responsive">
    <table class="table table-sm align-middle">

      <thead>
          <tr>
              <th>#</th>
              <th>Sản phẩm</th>
              <th>Danh mục</th>
              <th>Giá gốc</th>
              <th>Giá bán</th>
              <th>Trạng thái</th>
              <th>Ngày tạo</th>
              <th>Hành động</th>
          </tr>
      </thead>

      <tbody id="product-table-body">
        <tr>
          <td colspan="8" class="text-center text-muted">
            Đang tải dữ liệu...
          </td>
        </tr>
      </tbody>

    </table>
  </div>

  <!-- PAGINATION -->
  <nav class="mt-3">
    <ul class="pagination pagination-sm" id="pagination">

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(1)">Đầu</a>
      </li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(prevPage)">Trước</a>
      </li>

      <div id="pagination-pages" class="d-flex"></div>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(nextPage)">Sau</a>
      </li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(lastPage)">Cuối</a>
      </li>

    </ul>
  </nav>
</div>
<script>

document.addEventListener("DOMContentLoaded", function () {

    let currentPage = 1;
    let lastPage = 1;
    let prevPage = 1;
    let nextPage = 1;

    /* =========================
      LOAD CATEGORY
    ========================= */
    async function loadCategories() {
        const res = await fetch('/api/categories');
        const json = await res.json();

        const select = document.getElementById('filter-category');
        select.innerHTML = `<option value="">Danh mục</option>`;

        json.data.forEach(c => {
            select.innerHTML += `
                <option value="${c.id}">${c.name}</option>
            `;
        });
    }

    /* =========================
      LOAD PRODUCTS
    ========================= */
    async function loadProducts(page = 1) {

        currentPage = page;

        const keyword = document.getElementById('filter-name').value;
        const status = document.getElementById('filter-status').value;
        const category = document.getElementById('filter-category').value;
        const dateFrom = document.getElementById('filter-date-from').value;
        const dateTo = document.getElementById('filter-date-to').value;

        const query = new URLSearchParams({
            page,
            keyword,
            status,
            category_id: category,
            date_from: dateFrom,
            date_to: dateTo
        });

        const res = await fetch(`/api/products?${query.toString()}`);
        const json = await res.json();

        const tbody = document.getElementById('product-table-body');
        tbody.innerHTML = '';

        if (!json.data || json.data.length === 0) {
          tbody.innerHTML = `
              <tr>
                  <td colspan="8" class="text-center text-muted">
                      Không có sản phẩm nào
                  </td>
              </tr>`;
        } else {

          json.data.forEach((p, index) => {
              tbody.innerHTML += `
                  <tr>
                      <td>${(json.meta.page - 1) * json.meta.perPage + index + 1}</td>

                      <td>
                          <div class="d-flex align-items-center gap-2">
                              <img
                                  src="/${p.thumbnail || 'https://placehold.co/50x50?text=No+Image'}"
                                  alt="${p.name}"
                                  width="20"
                                  height="20"
                                  class="rounded border"
                                  style="object-fit:cover">

                              <div>${p.name}</div>
                          </div>
                      </td>

                      <td>${p.category_name ?? '---'}</td>
                      <td>${Number(p.price).toLocaleString()} ₫</td>
                      <td>${Number(p.sale_price ?? p.price).toLocaleString()} ₫</td>

                      <td>
                          <select class="form-select form-select-sm">
                              <option ${p.status == 'active' ? 'selected' : ''}>Đang bán</option>
                              <option ${p.status == 'inactive' ? 'selected' : ''}>Ngừng bán</option>
                          </select>
                      </td>

                      <td>${p.created_at ?? ''}</td>

                      <td>
                          <a href="/admin/products/edit/${p.id}" class="btn btn-sm btn-outline-secondary">
                              Sửa
                          </a>

                          <button
                              class="btn btn-sm btn-outline-secondary"
                              onclick="deleteProduct(${p.id})">
                              Xóa
                          </button>
                      </td>
                  </tr>`;
          });
        }

        document.getElementById('total-amount').innerText = json.meta.total;

        lastPage = json.meta.totalPages;
        prevPage = Math.max(1, json.meta.page - 1);
        nextPage = Math.min(lastPage, json.meta.page + 1);

        renderPages(json.meta.page, json.meta.totalPages);
    }

    /* =========================
      DELETE PRODUCT
    ========================= */
    async function deleteProduct(id) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

        const formData = new FormData();
        formData.append('id', id);

        const res = await fetch('/api/products/delete', {
            method: 'POST',
            body: formData
        });

        const json = await res.json();

        if (json.success) {
            alert('Xóa thành công');
            loadProducts(currentPage);
        } else {
            alert(json.message || 'Xóa thất bại');
        }
    }

    /* =========================
      PAGINATION
    ========================= */
    function goToPage(page) {
        loadProducts(page);
    }

    function renderPages(page, totalPages) {

        const container = document.getElementById('pagination-pages');
        container.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {

            if (i === 1 || i === totalPages || (i >= page - 2 && i <= page + 2)) {

                container.innerHTML += `
                    <li class="page-item ${i === page ? 'active' : ''}">
                        <a class="page-link text-secondary ${i === page ? 'bg-light border-secondary' : ''}"
                           href="javascript:void(0)"
                           onclick="goToPage(${i})">
                            ${i}
                        </a>
                    </li>`;
            }
        }
    }

    /* =========================
      FILTER EVENTS
    ========================= */
    document.querySelectorAll(
        '#filter-name, #filter-status, #filter-category, #filter-date-from, #filter-date-to'
    ).forEach(el => {
        el.addEventListener('input', () => loadProducts(1));
        el.addEventListener('change', () => loadProducts(1));
    });

    /* =========================
      INIT
    ========================= */
    (async function init() {
        await loadCategories();
        await loadProducts(1);
    })();
});

</script>