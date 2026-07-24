<div class="container-fluid py-4 mt-5">
  <!-- ------------------------------- -->
  <!-- Filter: mode + date/month/year -->
  <!-- ------------------------------- -->
    <div class="d-flex justify-content-between mb-3 align-items-center flex-wrap">
    <!-- Filter chế độ + date/month/year -->
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <select id="mode" class="form-select form-select-sm" style="width:100px;">
        <option value="day">Ngày</option>
        <option value="month">Tháng</option>
        <option value="year">Năm</option>
        </select>

        <input type="date" id="input-date" class="form-control form-control-sm" style="width:150px;">
        <input type="number" id="input-year" class="form-control form-control-sm" placeholder="Năm" style="display:none;width:100px;">
        <input type="number" id="input-month" class="form-control form-control-sm" placeholder="Tháng" style="display:none;width:100px;">

        <button id="btn-filter" class="btn btn-sm btn-secondary">Lọc</button>
    </div>

    <!-- Filter tìm sản phẩm -->
    <div class="flex-grow-1 ms-3" style="min-width:200px; max-width:300px;">
        <input type="text" id="filter-name" class="form-control form-control-sm" placeholder="Tìm theo tên sản phẩm">
    </div>
    </div>

  <!-- Tổng hợp -->
  <div class="my-3">
    <strong>Tổng số lượng</strong> <span id="total-quantity">0</span> |
    <strong>Tổng doanh thu</strong> <span id="total-revenue">0</span> |
    <strong>Tổng lợi nhuận</strong> <span id="total-profit">0</span>
  </div>

  <!-- ------------------------------- -->
  <!-- Table chi tiết -->
  <!-- ------------------------------- -->
  <div class="table-responsive">
    <table class="table table-sm table-striped table-borderless align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>ID Đơn xuất</th>
          <th>Ngày xuất</th>
          <th>ID SP</th>
          <th>Tên sản phẩm</th>
          <th>Số lượng</th>
          <th>Đơn giá</th>
          <th>Giá nhập</th>
          <th>Doanh thu</th>
          <th>Lợi nhuận</th>
        </tr>
      </thead>
      <tbody id="report-table-body">
        <!-- JS load dữ liệu -->
      </tbody>
    </table>
  </div>

  <!-- Phân trang -->
  <nav aria-label="Phân trang" class="mt-3">
    <ul class="pagination pagination-sm" id="pagination">
      <!-- JS render pagination -->
    </ul>
  </nav>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  let allRevenues = [];
  let totalSummary = {
    total_quantity: 0,
    total_revenue: 0,
    total_profit: 0,
  };

  let currentPage = 1;
  const itemsPerPage = 100;

  const modeSelect = document.getElementById("mode");
  const inputDate = document.getElementById("input-date");
  const inputYear = document.getElementById("input-year");
  const inputMonth = document.getElementById("input-month");
  const btnFilter = document.getElementById("btn-filter");
  const filterInput = document.getElementById("filter-name");

  /* ===============================
   * FORMAT TIỀN
   * =============================== */
  function formatVND(value) {
    return Number(value || 0).toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
    });
  }

  /* ===============================
   * INPUT MODE
   * =============================== */
  modeSelect.addEventListener("change", function () {
    const mode = this.value;

    inputDate.style.display = mode === "day" ? "inline-block" : "none";
    inputYear.style.display =
      mode === "year" || mode === "month" ? "inline-block" : "none";
    inputMonth.style.display = mode === "month" ? "inline-block" : "none";
  });

  /* ===============================
   * LOAD API
   * =============================== */
  async function loadRevenues(params = {}) {
    try {
      const query = new URLSearchParams(params).toString();

      const res = await fetch("/api/report/revenue?" + query);
      const json = await res.json();

      if (json.success) {
        // Kết quả từ Stored Procedure
        allRevenues = json.data?.[0] || [];

        totalSummary = json.data?.[1]?.[0] || {
          total_quantity: 0,
          total_revenue: 0,
          total_profit: 0,
        };
      } else {
        allRevenues = [];
        totalSummary = {
          total_quantity: 0,
          total_revenue: 0,
          total_profit: 0,
        };
      }

      currentPage = 1;
      renderTable(filteredRevenues());
    } catch (err) {
      console.error(err);

      allRevenues = [];
      totalSummary = {
        total_quantity: 0,
        total_revenue: 0,
        total_profit: 0,
      };

      renderTable([]);
    }
  }

  /* ===============================
   * TÍNH TỔNG (KHI FILTER TÊN)
   * =============================== */
  function calculateSummary(data) {
    return data.reduce(
      (acc, item) => {
        acc.total_quantity += Number(item.quantity) || 0;
        acc.total_revenue += Number(item.revenue) || 0;
        acc.total_profit += Number(item.profit) || 0;
        return acc;
      },
      {
        total_quantity: 0,
        total_revenue: 0,
        total_profit: 0,
      }
    );
  }

  /* ===============================
   * FILTER TÊN
   * =============================== */
  filterInput.addEventListener("input", function () {
    currentPage = 1;
    renderTable(filteredRevenues());
  });

  function filteredRevenues() {
    const keyword = filterInput.value.trim().toLowerCase();

    if (!keyword) {
      return allRevenues;
    }

    return allRevenues.filter((item) =>
      (item.product_name || "").toLowerCase().includes(keyword)
    );
  }

  /* ===============================
   * RENDER TABLE
   * =============================== */
  function renderTable(items) {
    const tbody = document.getElementById("report-table-body");

    tbody.innerHTML = "";

    if (!items.length) {
      tbody.innerHTML = `
        <tr>
          <td colspan="10" class="text-center text-muted">
            Không có dữ liệu
          </td>
        </tr>
      `;

      renderPagination(0);
      renderSummary(null);

      return;
    }

    const start = (currentPage - 1) * itemsPerPage;

    const pageItems = items.slice(start, start + itemsPerPage);

    pageItems.forEach((item, index) => {
      const tr = document.createElement("tr");

      tr.innerHTML = `
        <th>${start + index + 1}</th>
        <td>${item.revenue_id}</td>
        <td>${item.created_at}</td>
        <td>${item.product_id}</td>
        <td>${item.product_name}</td>
        <td>${item.quantity}</td>
        <td>${formatVND(item.selling_price)}</td>
        <td>${formatVND(item.purchase_price)}</td>
        <td>${formatVND(item.revenue)}</td>
        <td>${formatVND(item.profit)}</td>
      `;

      tbody.appendChild(tr);
    });

    renderPagination(items.length);

    if (filterInput.value.trim()) {
      renderSummary(items);
    } else {
      renderSummary(null);
    }
  }

  /* ===============================
   * SUMMARY
   * =============================== */
  function renderSummary(items = null) {
    const summary =
      items === null
        ? totalSummary
        : calculateSummary(items);

    document.getElementById("total-quantity").textContent =
      summary.total_quantity || 0;

    document.getElementById("total-revenue").textContent =
      formatVND(summary.total_revenue);

    document.getElementById("total-profit").textContent =
      formatVND(summary.total_profit);
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

  /* ===============================
   * FILTER
   * =============================== */
  btnFilter.addEventListener("click", function () {
    const mode = modeSelect.value;

    const params = {
      mode,
    };

    if (mode === "day") {
      params.date = inputDate.value;
    } else if (mode === "month") {
      params.month = inputMonth.value || new Date().getMonth() + 1;
      params.year = inputYear.value || new Date().getFullYear();
    } else {
      params.year = inputYear.value || new Date().getFullYear();
    }

    loadRevenues(params);
  });

  /* ===============================
   * INIT
   * =============================== */
  inputDate.value = new Date().toISOString().split("T")[0];

  loadRevenues({
    mode: "day",
    date: inputDate.value,
  });
});
</script>
