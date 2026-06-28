<?php 
$statuses = config('shop.option.order_status') ?? [];
$payments = config('shop.option.payment') ?? [];
?>

<div class="container-fluid py-4 mt-5">

  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="row g-2">

      <div class="col-auto">
        <input type="date" id="filter-date-from" class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <input type="date" id="filter-date-to" class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <select id="filter-customer" class="form-select form-select-sm">
          <option value="">Khách hàng</option>
        </select>
      </div>

      <div class="col-auto">
        <select id="filter-payment" class="form-select form-select-sm">
          <option value="">Thanh toán</option>
        </select>
      </div>

    </div>

    <a href="/admin/orders/create" class="btn btn-sm btn-outline-secondary">
      Thêm đơn hàng
    </a>

  </div>

  <div class="mb-3">
    <strong>Tổng đơn hàng:</strong>
    <span id="total-amount">0</span>
  </div>

  <div class="table-responsive">
    <table class="table table-sm align-middle">

      <thead>
        <tr>
          <th>#</th>
          <th>Khách hàng</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Thanh toán</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody id="order-table-body">
        <tr>
          <td colspan="7" class="text-center text-muted">Đang tải dữ liệu...</td>
        </tr>
      </tbody>

    </table>
  </div>

  <nav class="mt-3">
    <ul class="pagination pagination-sm" id="pagination">

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(1)">Đầu</a>
      </li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(prevPage)">Trước</a>
      </li>

      <li class="page-item d-flex" id="pagination-pages"></li>

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

    const API = {
        customers: '/api/customers',
        orders: '/api/orders',
        status: '/api/orders/status',
        payment: '/api/orders/payment',
        delete: '/api/orders/delete'
    };

    let currentPage = 1;
    let lastPage = 1;
    let prevPage = 1;
    let nextPage = 1;

    const STATUS_CONFIG = <?= json_encode($statuses) ?>;
    const PAYMENT_CONFIG = <?= json_encode($payments) ?>;

    function getVal(id) {
        const el = document.getElementById(id);
        return el ? el.value : '';
    }

    async function loadCustomers() {
        const el = document.getElementById('filter-customer');
        const res = await fetch(API.customers);
        const json = await res.json();

        el.innerHTML = `<option value="">Khách hàng</option>`;

        (json.data || []).forEach(c => {
            el.innerHTML += `<option value="${c.id}">${c.name}</option>`;
        });
    }

    async function loadOrders(page = 1) {

        currentPage = page;

        const query = new URLSearchParams({
            page,
            customer_id: getVal('filter-customer'),
            payment: getVal('filter-payment')
        });

        const res = await fetch(`${API.orders}?${query}`);
        const json = await res.json();

        const tbody = document.getElementById('order-table-body');
        tbody.innerHTML = '';

        if (!json.data.length) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">Không có đơn hàng</td></tr>`;
            return;
        }

        json.data.forEach((o, index) => {

            tbody.innerHTML += `
                <tr>
                    <td>${(json.meta.page - 1) * json.meta.perPage + index + 1}</td>

                    <td>${o.customer_name ?? '---'}</td>
                    <td>${Number(o.total_amount || 0).toLocaleString()} ₫</td>

                    <td>
                        <select onchange="updateStatus(${o.id}, this.value)"
                            class="form-select form-select-sm text-${STATUS_CONFIG[o.status]?.color || 'secondary'}">

                            ${Object.keys(STATUS_CONFIG).map(k => `
                                <option value="${k}" ${k == o.status ? 'selected' : ''}>
                                    ${STATUS_CONFIG[k].label}
                                </option>
                            `).join('')}
                        </select>
                    </td>

                    <td>
                        <select onchange="updatePayment(${o.id}, this.value)"
                            class="form-select form-select-sm text-${PAYMENT_CONFIG[o.payment]?.color || 'secondary'}">

                            ${Object.keys(PAYMENT_CONFIG).map(k => `
                                <option value="${k}" ${k == o.payment ? 'selected' : ''}>
                                    ${PAYMENT_CONFIG[k].label}
                                </option>
                            `).join('')}
                        </select>
                    </td>

                    <td>${o.created_at}</td>

                    <td>
                        <a href="/admin/orders/edit/${o.id}" class="btn btn-sm btn-outline-secondary">Sửa</a>
                        <button onclick="deleteOrder(${o.id})" class="btn btn-sm btn-outline-secondary">Xóa</button>
                    </td>
                </tr>
            `;
        });

        lastPage = json.meta.totalPages;
        prevPage = Math.max(1, json.meta.page - 1);
        nextPage = Math.min(lastPage, json.meta.page + 1);

        renderPages(json.meta.page, json.meta.totalPages);
    }

    async function updateStatus(id, status) {
        const form = new FormData();
        form.append('id', id);
        form.append('status', status);

        await fetch(API.status, { method: 'POST', body: form });
        loadOrders(currentPage);
    }

    async function updatePayment(id, payment) {
        const form = new FormData();
        form.append('id', id);
        form.append('payment', payment);

        await fetch(API.payment, { method: 'POST', body: form });
        loadOrders(currentPage);
    }

    async function deleteOrder(id) {
        if (!confirm('Xóa đơn hàng?')) return;

        const form = new FormData();
        form.append('id', id);

        await fetch(API.delete, { method: 'POST', body: form });
        loadOrders(currentPage);
    }

    function goToPage(page) {
        loadOrders(page);
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

    // expose functions ra global để onclick dùng được
    window.loadOrders = loadOrders;
    window.updateStatus = updateStatus;
    window.updatePayment = updatePayment;
    window.deleteOrder = deleteOrder;
    window.goToPage = goToPage;

    // init
    loadCustomers();
    loadOrders(1);

});
</script>