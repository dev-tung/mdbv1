<div class="container-fluid py-4 mt-5">

  <!-- =============================== -->
  <!-- FILTER -->
  <!-- =============================== -->
  <div class="d-flex justify-content-between mb-3 align-items-center flex-wrap">

    <!-- Filter thời gian -->
    <div class="d-flex gap-2 align-items-center flex-wrap">
      <input type="date" id="date-from" class="form-control form-control-sm" style="width:150px;">
      <input type="date" id="date-to" class="form-control form-control-sm" style="width:150px;">
      <button id="btn-filter" class="btn btn-sm btn-secondary">Lọc</button>
    </div>

    <!-- Filter tên + nhóm -->
    <div class="flex-grow-1 ms-3 d-flex gap-2" style="min-width:260px; max-width:420px;">
      <input
        type="text"
        id="filter-name"
        class="form-control form-control-sm"
        placeholder="Tìm theo tên khách hàng"
      >

      <select id="filter-group" class="form-select form-select-sm">
        <option value="">Tất cả nhóm</option>
      </select>
    </div>
  </div>

  <!-- =============================== -->
  <!-- SUMMARY -->
  <!-- =============================== -->
  <div class="my-3">
    <strong>Số khách</strong> <span id="total-customers">0</span> |
    <strong>Tổng doanh thu</strong> <span id="total-revenue">0</span> |
    <strong>Tổng lợi nhuận</strong> <span id="total-profit">0</span>
  </div>

  <!-- =============================== -->
  <!-- TABLE -->
  <!-- =============================== -->
  <div class="table-responsive">
    <table class="table table-sm table-striped table-borderless align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>ID KH</th>
          <th>Tên khách hàng</th>
          <th>Nhóm KH</th>
          <th>SĐT</th>
          <th>Số đơn</th>
          <th>Tổng SL</th>
          <th>Doanh thu</th>
          <th>Lợi nhuận</th>
        </tr>
      </thead>
      <tbody id="report-table-body"></tbody>
    </table>
  </div>

  <!-- =============================== -->
  <!-- PAGINATION -->
  <!-- =============================== -->
  <nav class="mt-3">
    <ul class="pagination pagination-sm" id="pagination"></ul>
  </nav>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

  let allCustomers = [];
  let currentPage = 1;
  const itemsPerPage = 100;

  const dateFrom = document.getElementById("date-from");
  const dateTo = document.getElementById("date-to");
  const btnFilter = document.getElementById("btn-filter");
  const filterName = document.getElementById("filter-name");
  const filterGroup = document.getElementById("filter-group");

  function formatVND(value) {
    return Number(value || 0).toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND"
    });
  }

  // ===============================
  // LOAD DATA
  // ===============================
  async function loadCustomers(params = {}) {

    try {

      const query = new URLSearchParams(params).toString();

      const response = await fetch(`/api/report/buyer?${query}`);

      if (!response.ok) {
        throw new Error("Không thể tải dữ liệu");
      }

      const json = await response.json();

      allCustomers =
        json.success &&
        Array.isArray(json.data) &&
        Array.isArray(json.data[0])
          ? json.data[0]
          : [];

      currentPage = 1;

      renderGroupFilter(allCustomers);

      const items = filteredCustomers();

      renderTable(items);

      renderSummary(items);

    } catch (error) {

      console.error(error);

      allCustomers = [];

      renderTable([]);

      renderSummary([]);

    }

  }

  // ===============================
  // FILTER
  // ===============================
  filterName.addEventListener("input", applyClientFilter);

  filterGroup.addEventListener("change", applyClientFilter);

  function applyClientFilter() {

    currentPage = 1;

    const items = filteredCustomers();

    renderTable(items);

    renderSummary(items);

  }

  function filteredCustomers() {

    const keyword = filterName.value.trim().toLowerCase();

    const groupId = filterGroup.value;

    return allCustomers.filter(item => {

      const matchName =
        String(item.customer_name || "")
          .toLowerCase()
          .includes(keyword);

      const matchGroup =
        !groupId ||
        String(item.customer_group_id) === groupId;

      return matchName && matchGroup;

    });

  }

  // ===============================
  // SUMMARY
  // ===============================
  function renderSummary(items) {

    const totalCustomers = items.length;

    const totalRevenue = items.reduce(
      (sum, item) => sum + Number(item.total_revenue || 0),
      0
    );

    const totalProfit = items.reduce(
      (sum, item) => sum + Number(item.total_profit || 0),
      0
    );

    document.getElementById("total-customers").textContent =
      totalCustomers;

    document.getElementById("total-revenue").textContent =
      formatVND(totalRevenue);

    document.getElementById("total-profit").textContent =
      formatVND(totalProfit);

  }

  // ===============================
  // TABLE
  // ===============================
  function renderTable(items) {

    const tbody = document.getElementById("report-table-body");

    tbody.innerHTML = "";

    if (!items.length) {

      tbody.innerHTML = `
        <tr>
          <td colspan="9" class="text-center text-muted">
            Không có dữ liệu
          </td>
        </tr>
      `;

      renderPagination(0);

      return;

    }

    const start = (currentPage - 1) * itemsPerPage;

    const pageItems = items.slice(start, start + itemsPerPage);

    pageItems.forEach((item, index) => {

      tbody.insertAdjacentHTML("beforeend", `
        <tr>
          <td>${start + index + 1}</td>
          <td>${item.customer_id}</td>
          <td>${item.customer_name}</td>
          <td>${item.customer_group_name || "—"}</td>
          <td>${item.phone || "—"}</td>
          <td>${item.total_orders}</td>
          <td>${item.total_quantity}</td>
          <td>${formatVND(item.total_revenue)}</td>
          <td>${formatVND(item.total_profit)}</td>
        </tr>
      `);

    });

    renderPagination(items.length);

  }

  // ===============================
  // PAGINATION
  // ===============================
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

        renderTable(filteredCustomers());

      });

      pagination.appendChild(li);

    }

  }

  // ===============================
  // GROUP FILTER
  // ===============================
  function renderGroupFilter(customers) {

    const groups = {};

    customers.forEach(item => {

      if (item.customer_group_id) {

        groups[item.customer_group_id] =
          item.customer_group_name;

      }

    });

    filterGroup.innerHTML =
      `<option value="">Tất cả nhóm</option>`;

    Object.entries(groups).forEach(([id, name]) => {

      const option = document.createElement("option");

      option.value = id;

      option.textContent = name;

      filterGroup.appendChild(option);

    });

  }

  // ===============================
  // DATE FILTER
  // ===============================
  btnFilter.addEventListener("click", function () {

    loadCustomers({
      from_date: dateFrom.value,
      to_date: dateTo.value
    });

  });

  // ===============================
  // INIT
  // ===============================
  const now = new Date();

  const firstDay = new Date(
    now.getFullYear(),
    now.getMonth(),
    1
  );

  dateFrom.value = firstDay.toISOString().split("T")[0];

  dateTo.value = now.toISOString().split("T")[0];

  loadCustomers({
    from_date: dateFrom.value,
    to_date: dateTo.value
  });

});
</script>