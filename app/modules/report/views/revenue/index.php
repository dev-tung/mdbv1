<div class="container-fluid py-4 mt-5">

  <!-- =========================
      FILTERS
  ========================= -->
  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="row g-2">

      <div class="col-auto">
        <input type="date"
               id="filter-date-from"
               class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <input type="date"
               id="filter-date-to"
               class="form-control form-control-sm">
      </div>

    </div>

  </div>

  <!-- =========================
      SUMMARY
  ========================= -->
  <div class="mb-3">
    <strong>Lợi nhuận tháng này:</strong>
    <span id="total-profit-month">0</span> ₫
  </div>

  <!-- =========================
      TABLE
  ========================= -->
  <div class="table-responsive">
    <table class="table table-sm align-middle">

      <thead>
        <tr>
          <th>#</th>
          <th>Ngày</th>
          <th>Đơn hàng</th>
          <th>Doanh thu</th>
          <th>Lợi nhuận</th>
        </tr>
      </thead>

      <tbody id="revenue-table-body">
        <tr>
          <td colspan="5" class="text-center text-muted">
            Đang tải dữ liệu...
          </td>
        </tr>
      </tbody>

    </table>
  </div>

  <!-- =========================
      PAGINATION
  ========================= -->
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
       LOAD REVENUE
    ========================= */
    async function loadRevenue(page = 1) {

        currentPage = page;

        const dateFrom = document.getElementById('filter-date-from').value;
        const dateTo   = document.getElementById('filter-date-to').value;

        const query = new URLSearchParams({
            page,
            date_from: dateFrom,
            date_to: dateTo
        });

        const res = await fetch(`/api/reports/revenue?${query.toString()}`);
        const json = await res.json();

        console.log("RESPONSE:", json); // DEBUG

        const tbody = document.getElementById('revenue-table-body');
        tbody.innerHTML = '';

        let totalProfit = 0;

        if (!json.data || json.data.length === 0) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Không có dữ liệu
                    </td>
                </tr>`;

            document.getElementById('total-profit-month').innerText = '0';

            renderPages(1, 1);
            return;
        }

        json.data.forEach((row, index) => {

            const profit = Number(row.profit || 0);
            totalProfit += profit;

            tbody.innerHTML += `
                <tr>
                    <td>${(page - 1) * (json.meta?.perPage || 10) + index + 1}</td>
                    <td>${row.date}</td>
                    <td>${row.orders}</td>
                    <td>${Number(row.revenue || 0).toLocaleString()} ₫</td>
                    <td>${profit.toLocaleString()} ₫</td>
                </tr>
            `;
        });

        document.getElementById('total-profit-month').innerText =
        Number(json.summary?.profit || 0).toLocaleString();

        const meta = json.meta || {
            page: 1,
            totalPages: 1,
            perPage: 10
        };

        lastPage = meta.totalPages;
        prevPage = Math.max(1, meta.page - 1);
        nextPage = Math.min(lastPage, meta.page + 1);

        renderPages(meta.page, meta.totalPages);
    }

    /* =========================
       PAGINATION
    ========================= */
    function renderPages(page, totalPages) {

        const container = document.getElementById('pagination-pages');

        if (!container) return;

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
       GLOBAL FUNCTION (IMPORTANT FIX)
    ========================= */
    window.goToPage = function(page) {
        loadRevenue(page);
    };

    /* =========================
       FILTER EVENTS
    ========================= */
    document.querySelectorAll('#filter-date-from, #filter-date-to')
        .forEach(el => {
            el.addEventListener('input', () => loadRevenue(1));
            el.addEventListener('change', () => loadRevenue(1));
        });

    /* =========================
       INIT
    ========================= */
    loadRevenue(1);

});

</script>