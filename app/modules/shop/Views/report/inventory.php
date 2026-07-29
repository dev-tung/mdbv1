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
document.addEventListener("DOMContentLoaded", function() {
  // =====================================================
  // STATE
  // =====================================================
  let allInventory = [];
  let currentPage = 1;
  const itemsPerPage = 100;
  // =====================================================
  // ELEMENTS
  // =====================================================
  const filterInput = document.getElementById("filter-name");
  const sortSelect = document.getElementById("sort-type");
  const priceMinInput = document.getElementById("filter-price-min");
  const priceMaxInput = document.getElementById("filter-price-max");
  const typeSelect = document.getElementById("filter-type");
  const daysMinInput = document.getElementById("filter-days-min");
  const quantityMinInput = document.getElementById("filter-quantity-min");
  const quantityMaxInput = document.getElementById("filter-quantity-max");
  const tableBody = document.getElementById("inventory-table-body");
  const pagination = document.getElementById("pagination");
  // =====================================================
  // FORMAT
  // =====================================================
  function formatVND(value) {
    return Number(value || 0).toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
      maximumFractionDigits: 0
    });
  }

  function formatNumber(value) {
    return Number(value || 0).toLocaleString("vi-VN");
  }
  // =====================================================
  // LOAD INVENTORY
  // =====================================================
  async function loadInventory() {
    try {

      const params = new URLSearchParams({
        stock: 1
      });

      const response = await fetch(
        `/api/report/inventory?${params.toString()}`
      );

      if (!response.ok) {
        throw new Error("Không thể tải dữ liệu tồn kho");
      }

      const json = await response.json();

      allInventory =
        json.success &&
        Array.isArray(json.data) &&
        Array.isArray(json.data[0])
          ? json.data[0]
          : [];

      currentPage = 1;

      populateTypeFilter(allInventory);

      render();

    } catch (error) {

      console.error(error);

      allInventory = [];

      tableBody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center text-danger">
            Không thể tải dữ liệu
          </td>
        </tr>
      `;

      pagination.innerHTML = "";

      renderSummary([]);

    }
  }
  // =====================================================
  // TYPE FILTER
  // =====================================================
  function populateTypeFilter(data) {
    const types = [...new Set(data.map(item => item.category_name).filter(Boolean))];
    typeSelect.innerHTML = `

      <option value="">

        -- Tất cả loại --

      </option>

    `;
    types.forEach(type => {
      const option = document.createElement("option");
      option.value = type;
      option.textContent = type;
      typeSelect.appendChild(option);
    });
  }
  // =====================================================
  // FILTER
  // =====================================================
  function filteredInventory() {
    const keyword = String(filterInput.value || "").trim().toLowerCase();
    const minPrice = priceMinInput.value === "" ? 0 : Number(priceMinInput.value);
    const maxPrice = priceMaxInput.value === "" ? Infinity : Number(priceMaxInput.value);
    const minDays = daysMinInput.value === "" ? 0 : Number(daysMinInput.value);
    const minQuantity = quantityMinInput.value === "" ? 0 : Number(quantityMinInput.value);
    const maxQuantity = quantityMaxInput.value === "" ? Infinity : Number(quantityMaxInput.value);
    const selectedType = typeSelect.value;
    return allInventory.filter(item => {
      const productName = String(item.product_name || "").toLowerCase();
      const quantity = Number(item.quantity) || 0;
      const importPrice = Number(item.import_price) || 0;
      const daysInStock = Number(item.days_in_stock) || 0;
      const nameMatch = productName.includes(keyword);
      const priceMatch = importPrice >= minPrice && importPrice <= maxPrice;
      const typeMatch = !selectedType || item.category_name === selectedType;
      const daysMatch = daysInStock >= minDays;
      const quantityMatch = quantity >= minQuantity && quantity <= maxQuantity;
      return (nameMatch && priceMatch && typeMatch && daysMatch && quantityMatch);
    });
  }
  // =====================================================
  // SORT
  // =====================================================
  function sortInventory(items) {
    const value = sortSelect.value;
    if (!value) {
      return [...items];
    }
    const field = value.includes("quantity") ? "quantity" : "days_in_stock";
    const ascending = value.endsWith("asc");
    return [...items].sort(
      (a, b) => {
        const valueA = Number(a[field]) || 0;
        const valueB = Number(b[field]) || 0;
        return ascending ? valueA - valueB : valueB - valueA;
      });
  }
  // =====================================================
  // TABLE
  // =====================================================
  function renderTable(items) {
    const tbody = document.getElementById("inventory-table-body");
    tbody.innerHTML = "";
    if (!items.length) {
      tbody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center text-muted">
            Không có dữ liệu
          </td>
        </tr>
      `;
      renderPagination(0);
      return;
    }
    const sorted = sortInventory(items);
    const start = (currentPage - 1) * itemsPerPage;
    const page = sorted.slice(start, start + itemsPerPage);
    page.forEach((item, index) => {
      tbody.insertAdjacentHTML("beforeend", `
        <tr>

          <td>
            ${start + index + 1}
          </td>

          <td>
            ${item.product_name || "—"}
          </td>

          <td>
            ${item.category_name || "—"}
          </td>

          <td>
            ${Number(item.quantity || 0).toLocaleString("vi-VN")}
          </td>

          <td>
            ${item.import_date || "—"}
          </td>

          <td>
            ${Number(item.days_in_stock || 0)}
          </td>

          <td>
            ${formatVND(item.import_price)}
          </td>

          <td>
            ${formatVND(item.total_import_amount)}
          </td>

        </tr>
      `);
    });
    renderPagination(items.length);
  }
  // =====================================================
  // SUMMARY
  // =====================================================
  function renderSummary(items) {
    const totalQuantity = items.reduce(
      (total, item) => total + (Number(item.quantity) || 0), 0);
    const totalAmount = items.reduce(
      (total, item) => total + (Number(item.total_import_amount) || 0), 0);
    const totalProducts = new Set(items.map(item => item.product_id)).size;
    document.getElementById("total-quantity").textContent = formatNumber(totalQuantity);
    document.getElementById("total-products").textContent = formatNumber(totalProducts);
    document.getElementById("total-import-amount").textContent = formatVND(totalAmount);
  }
  /* ===============================
   * PAGINATION
   * =============================== */
  function renderPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    const pagination = document.getElementById("pagination");

    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement("li");

      li.className = `page-item ${i === currentPage ? "active" : ""}`;

      li.innerHTML = `
        <a class="page-link text-secondary ${
          i === currentPage ? "bg-light border-secondary" : ""
        }" href="#">
          ${i}
        </a>
      `;

      li.addEventListener("click", function (e) {
        e.preventDefault();

        currentPage = i;

        renderTable(filteredRevenues());
      });

      pagination.appendChild(li);
    }
  }
  // =====================================================
  // RENDER
  // =====================================================
  function render() {
    const data = filteredInventory();
    renderTable(data);
    renderSummary(data);
  }
  // =====================================================
  // EVENTS
  // =====================================================
  [
    filterInput,
    priceMinInput,
    priceMaxInput,
    daysMinInput,
    quantityMinInput,
    quantityMaxInput
  ].forEach(element => {
    element.addEventListener("input", function() {
      currentPage = 1;
      render();
    });
  });
  typeSelect.addEventListener("change", function() {
    currentPage = 1;
    render();
  });
  sortSelect.addEventListener("change", function() {
    currentPage = 1;
    render();
  });
  // =====================================================
  // INIT
  // =====================================================
  loadInventory();
});
</script>