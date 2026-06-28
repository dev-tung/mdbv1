<?php 
$statuses = config('shop.option.purchase_status') ?? [];
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
        <select id="filter-supplier" class="form-select form-select-sm">
          <option value="">Nhà cung cấp</option>
        </select>
      </div>

      <div class="col-auto">
        <select id="filter-payment" class="form-select form-select-sm">
          <option value="">Thanh toán</option>
        </select>
      </div>

    </div>

    <a href="/admin/purchases/create" class="btn btn-sm btn-outline-secondary">
      Thêm phiếu nhập
    </a>

  </div>

  <div class="mb-3">
    <strong>Tổng phiếu nhập:</strong>
    <span id="total-amount">0</span>
  </div>

  <div class="table-responsive">
    <table class="table table-sm align-middle">

      <thead>
        <tr>
          <th>#</th>
          <th>Nhà cung cấp</th>
          <th>Kho</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Thanh toán</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody id="purchase-table-body">
        <tr>
          <td colspan="9" class="text-center text-muted">Đang tải dữ liệu...</td>
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

    /* =========================
       API
    ========================= */
    const API = {
        suppliers: '/api/suppliers',
        purchases: '/api/purchases',
        status: '/api/purchases/status',
        payment: '/api/purchases/payment',
        delete: '/api/purchases/delete'
    };

    /* =========================
       STATE
    ========================= */
    let currentPage = 1;
    let lastPage = 1;
    let prevPage = 1;
    let nextPage = 1;

    /* =========================
       PHP CONFIG
    ========================= */
    const STATUS_CONFIG = <?= json_encode($statuses) ?>;
    const PAYMENT_CONFIG = <?= json_encode($payments) ?>;

    /* =========================
       SAFE GET VALUE
    ========================= */
    function getVal(id) {
        const el = document.getElementById(id);
        return el ? el.value : '';
    }

    /* =========================
       SUPPLIERS
    ========================= */
    async function loadSuppliers() {
        const el = document.getElementById('filter-supplier');
        if (!el) return;

        const res = await fetch(API.suppliers);
        const json = await res.json();

        el.innerHTML = `<option value="">Nhà cung cấp</option>`;

        (json.data || []).forEach(s => {
            el.innerHTML += `<option value="${s.id}">${s.name}</option>`;
        });
    }

    /* =========================
       LOAD PAYMENT OPTIONS FROM PHP
    ========================= */
    function initPaymentFilter() {
        const el = document.getElementById('filter-payment');
        if (!el) return;

        el.innerHTML = `<option value="">Thanh toán</option>`;

        Object.keys(PAYMENT_CONFIG).forEach(k => {
            el.innerHTML += `<option value="${k}">${PAYMENT_CONFIG[k].label}</option>`;
        });
    }

    /* =========================
       LOAD PURCHASES
    ========================= */
    async function loadPurchases(page = 1) {

        currentPage = page;

        const query = new URLSearchParams({
            page,
            supplier_id: getVal('filter-supplier'),
            date_from: getVal('filter-date-from'),
            date_to: getVal('filter-date-to'),
            payment: getVal('filter-payment')
        });

        const res = await fetch(`${API.purchases}?${query}`);
        const json = await res.json();

        const tbody = document.getElementById('purchase-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (!json.data || json.data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        Không có phiếu nhập nào
                    </td>
                </tr>`;
            return;
        }

        json.data.forEach((p, index) => {

            tbody.innerHTML += `
                <tr>
                    <td>${(json.meta.page - 1) * json.meta.perPage + index + 1}</td>

                    <td>${p.supplier_name ?? '---'}</td>
                    <td>${p.warehouse_name ?? '---'}</td>
                    <td>${Number(p.total_cost).toLocaleString()} ₫</td>

                    <td>
                        <select class="form-select form-select-sm text-${STATUS_CONFIG[p.status]?.color || 'secondary'}"
                                onchange="updateStatus(${p.id}, this.value)">

                            ${Object.keys(STATUS_CONFIG).map(k => `
                                <option value="${k}" ${String(k) === String(p.status) ? 'selected' : ''}>
                                    ${STATUS_CONFIG[k].label}
                                </option>
                            `).join('')}

                        </select>
                    </td>

                    <td>
                        <select class="form-select form-select-sm text-${PAYMENT_CONFIG[p.payment]?.color || 'secondary'}"
                                onchange="updatePayment(${p.id}, this.value)">

                            ${Object.keys(PAYMENT_CONFIG).map(k => `
                                <option value="${k}" ${String(p.payment) === String(k) ? 'selected' : ''}>
                                    ${PAYMENT_CONFIG[k].label}
                                </option>
                            `).join('')}

                        </select>
                    </td>

                    <td>${p.created_at ?? ''}</td>

                    <td>
                        <a href="/admin/purchases/edit/${p.id}" class="btn btn-sm btn-outline-secondary">Sửa</a>
                        <button class="btn btn-sm btn-outline-secondary"
                                onclick="deletePurchase(${p.id})">
                            Xóa
                        </button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('total-amount').innerText = json.meta.total;

        lastPage = json.meta.totalPages;
        prevPage = Math.max(1, json.meta.page - 1);
        nextPage = Math.min(lastPage, json.meta.page + 1);

        renderPages(json.meta.page, json.meta.totalPages);
    }

    /* =========================
       UPDATE STATUS
    ========================= */
    async function updateStatus(id, status) {
        const form = new FormData();
        form.append('id', id);
        form.append('status', status);

        await fetch(API.status, { method: 'POST', body: form });

        loadPurchases(currentPage);
    }

    /* =========================
       UPDATE PAYMENT
    ========================= */
    async function updatePayment(id, status) {
        const form = new FormData();
        form.append('id', id);
        form.append('payment', status);

        await fetch(API.payment, { method: 'POST', body: form });

        loadPurchases(currentPage);
    }

    /* =========================
       DELETE
    ========================= */
    async function deletePurchase(id) {
        if (!confirm('Bạn có chắc muốn xóa?')) return;

        const form = new FormData();
        form.append('id', id);

        const res = await fetch(API.delete, {
            method: 'POST',
            body: form
        });

        const json = await res.json();

        if (json.success) {
            loadPurchases(currentPage);
        } else {
            alert(json.message || 'Xóa thất bại');
        }
    }

    /* =========================
       PAGINATION
    ========================= */
    function goToPage(page) {
        loadPurchases(page);
    }

    function renderPages(page, totalPages) {

        const container = document.getElementById('pagination-pages');
        if (!container) return;

        container.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {

            if (
                i === 1 ||
                i === totalPages ||
                (i >= page - 2 && i <= page + 2)
            ) {

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
       GLOBAL FUNCTIONS
    ========================= */
    window.updateStatus = updateStatus;
    window.updatePayment = updatePayment;
    window.deletePurchase = deletePurchase;
    window.goToPage = goToPage;

    /* =========================
       FILTER EVENTS
    ========================= */
    document.querySelectorAll(
        '#filter-supplier, #filter-date-from, #filter-date-to, #filter-payment'
    ).forEach(el => {
        el.addEventListener('change', () => loadPurchases(1));
        el.addEventListener('input', () => loadPurchases(1));
    });

    /* =========================
       INIT
    ========================= */
    loadSuppliers();
    initPaymentFilter();
    loadPurchases(1);

});

</script>