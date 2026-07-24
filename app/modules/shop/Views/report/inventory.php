<div class="container-fluid py-4 mt-5">

  <!-- Filter 1 hàng -->
  <div class="d-flex gap-2 mb-3 align-items-center">

    <!-- Tên sản phẩm -->
    <div class="w-auto">
      <input
        type="text"
        id="filter-name"
        class="form-control form-control-sm"
        placeholder="Tìm theo tên sản phẩm"
      >
    </div>

    <!-- Giá nhập từ -->
    <div class="w-auto">
      <input
        type="number"
        id="filter-price-min"
        class="form-control form-control-sm"
        placeholder="Giá nhập từ"
      >
    </div>

    <!-- Giá nhập đến -->
    <div class="w-auto">
      <input
        type="number"
        id="filter-price-max"
        class="form-control form-control-sm"
        placeholder="Giá nhập đến"
      >
    </div>

    <!-- Số ngày tồn từ -->
    <div class="w-auto">
      <input
        type="number"
        id="filter-days-min"
        class="form-control form-control-sm"
        placeholder="Ngày tồn từ"
      >
    </div>

    <!-- Số lượng tồn từ -->
    <div class="w-auto">
      <input
        type="number"
        id="filter-quantity-min"
        class="form-control form-control-sm"
        placeholder="SL tồn từ"
      >
    </div>

    <!-- Số lượng tồn đến -->
    <div class="w-auto">
      <input
        type="number"
        id="filter-quantity-max"
        class="form-control form-control-sm"
        placeholder="SL tồn đến"
      >
    </div>

    <!-- Loại sản phẩm -->
    <div class="w-auto">
      <select id="filter-type" class="form-select form-select-sm">
        <option value="">-- Tất cả loại --</option>
      </select>
    </div>

    <!-- Sort -->
    <div class="w-auto">
      <select id="sort-type" class="form-select form-select-sm">
        <option value="">-- Sắp xếp --</option>
        <option value="quantity_asc">Số lượng tồn ↑</option>
        <option value="quantity_desc">Số lượng tồn ↓</option>
        <option value="days_asc">Số ngày tồn kho ↑</option>
        <option value="days_desc">Số ngày tồn kho ↓</option>
      </select>
    </div>

  </div>

  <!-- Tổng hợp -->
  <div class="my-3">
    <strong>Tổng sản phẩm</strong> <span id="total-products">0</span> |
    <strong>Tổng tồn kho</strong> <span id="total-quantity">0</span> |
    <strong>Tổng tiền nhập</strong> <span id="total-import-amount">0</span>
  </div>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table table-sm table-striped table-borderless align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>ID SP</th>
          <th>Tên sản phẩm</th>
          <th>Loại</th>
          <th>Số lượng tồn</th>
          <th>Ngày nhập cũ nhất</th>
          <th>Số ngày tồn kho</th>
          <th>Đơn giá nhập</th>
          <th>Tổng tiền nhập</th>
        </tr>
      </thead>
      <tbody id="inventory-table-body"></tbody>
    </table>
  </div>

  <!-- Pagination -->
  <nav aria-label="Phân trang" class="mt-3">
    <ul class="pagination pagination-sm" id="pagination"></ul>
  </nav>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

  let allInventory = [];
  let currentPage = 1;
  const itemsPerPage = 100;

  const filterInput       = document.getElementById("filter-name");
  const sortSelect        = document.getElementById("sort-type");
  const priceMinInput     = document.getElementById("filter-price-min");
  const priceMaxInput     = document.getElementById("filter-price-max");
  const typeSelect        = document.getElementById("filter-type");
  const daysMinInput      = document.getElementById("filter-days-min");
  const quantityMinInput  = document.getElementById("filter-quantity-min");
  const quantityMaxInput  = document.getElementById("filter-quantity-max");

  function formatVND(value) {
    return Number(value || 0).toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND"
    });
  }

  async function loadInventory() {
    const res  = await fetch("/api/report/inventory");
    const json = await res.json();

    allInventory = json.success ? json.data : [];
    currentPage = 1;

    populateTypeFilter(allInventory);
    render();
  }

  function populateTypeFilter(data) {
    const types = [...new Set(
      data.map(i => i.product_group_name).filter(Boolean)
    )];

    typeSelect.innerHTML = `<option value="">-- Tất cả --</option>`;

    types.forEach(t => {
      const opt = document.createElement("option");
      opt.value = t;
      opt.textContent = t;
      typeSelect.appendChild(opt);
    });
  }

  function filteredInventory() {
    const keyword    = filterInput.value.toLowerCase();
    const minPrice   = Number(priceMinInput.value) || 0;
    const maxPrice   = Number(priceMaxInput.value) || Infinity;
    const minDays    = Number(daysMinInput.value) || 0;
    const type       = typeSelect.value;
    const minQty     = Number(quantityMinInput.value) || 0;
    const maxQty     = Number(quantityMaxInput.value) || Infinity;

    return allInventory.filter(item => {
      const nameMatch  = item.name.toLowerCase().includes(keyword);
      const price      = Number(item.import_price) || 0;
      const priceMatch = price >= minPrice && price <= maxPrice;
      const typeMatch  = !type || item.product_group_name === type;
      const days       = Number(item.days_in_stock) || 0;
      const daysMatch  = minDays > 0 ? (days >= minDays && Number(item.quantity) > 0) : true;
      const qty        = Number(item.quantity) || 0;
      const qtyMatch   = qty >= minQty && qty <= maxQty;

      return nameMatch && priceMatch && typeMatch && daysMatch && qtyMatch;
    });
  }

  function sortInventory(items) {
    const val = sortSelect.value;
    if (!val) return items;

    const field = val.includes("quantity")
      ? "quantity"
      : "days_in_stock";

    const asc = val.endsWith("asc");

    return items.slice().sort((a, b) =>
      asc
        ? (a[field] || 0) - (b[field] || 0)
        : (b[field] || 0) - (a[field] || 0)
    );
  }

  function renderTable(items) {
    const tbody = document.getElementById("inventory-table-body");
    tbody.innerHTML = "";

    if (!items.length) {
      tbody.innerHTML =
        `<tr><td colspan="9" class="text-center text-muted">
          Không có dữ liệu
        </td></tr>`;
      renderPagination(0);
      return;
    }

    const sorted = sortInventory(items);
    const start  = (currentPage - 1) * itemsPerPage;
    const page   = sorted.slice(start, start + itemsPerPage);

    page.forEach((item, index) => {
      tbody.insertAdjacentHTML("beforeend", `
        <tr>
          <td>${start + index + 1}</td>
          <td>${item.product_id}</td>
          <td>${item.name}</td>
          <td>${item.product_group_name || "—"}</td>
          <td>${item.quantity}</td>
          <td>${item.import_date || "—"}</td>
          <td>${item.days_in_stock}</td>
          <td>${formatVND(item.import_price)}</td>
          <td>${formatVND(item.total_import_amount)}</td>
        </tr>
      `);
    });

    renderPagination(items.length);
  }

  function renderSummary(items) {
    const totalQty  = items.reduce((s, i) => s + (+i.quantity || 0), 0);
    const totalAmt  = items.reduce((s, i) => s + (+i.total_import_amount || 0), 0);
    const totalProd = new Set(items.map(i => i.product_id)).size;

    document.getElementById("total-quantity").textContent = totalQty;
    document.getElementById("total-products").textContent = totalProd;
    document.getElementById("total-import-amount").textContent = formatVND(totalAmt);
  }

  function renderPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement("li");
      li.className = `page-item ${i === currentPage ? "active" : ""}`;
      li.innerHTML = `
        <a class="page-link text-secondary ${i === currentPage ? "bg-light border-secondary" : ""}" href="#">
          ${i}
        </a>
      `;

      li.addEventListener("click", function (e) {
        e.preventDefault();
        currentPage = i;
        render();
      });

      pagination.appendChild(li);
    }
  }

  function render() {
    const data = filteredInventory();
    renderTable(data);
    renderSummary(data);
  }

  // Gắn sự kiện input
  [
    filterInput,
    priceMinInput,
    priceMaxInput,
    typeSelect,
    daysMinInput,
    quantityMinInput,
    quantityMaxInput
  ].forEach(el => el.addEventListener("input", () => {
    currentPage = 1;
    render();
  }));

  sortSelect.addEventListener("change", render);

  loadInventory();
});
</script>