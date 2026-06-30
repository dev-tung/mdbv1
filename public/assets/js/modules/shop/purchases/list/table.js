export const Table = {

    init(url, config) {
        this.url = url;
        this.config = config;
    },

    async load(page = 1) {

        const query = new URLSearchParams({ page });

        const res = await fetch(`${this.url}?${query}`);
        const json = await res.json();

        this.render(json);
    },

    render(json) {

        const tbody = document.getElementById('purchase-table-body');
        if (!tbody) return;

        const statuses = this.config.statuses;
        const payments = this.config.payments;

        if (!json.data?.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Không có phiếu nhập nào
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = json.data.map((p, index) => `

            <tr>
                <td>${(json.meta.page - 1) * json.meta.perPage + index + 1}</td>

                <td>${p.supplier_name ?? '---'}</td>
                <td>${p.warehouse_name ?? '---'}</td>

                <td>${Number(p.total_amount).toLocaleString()} ₫</td>

                <!-- STATUS -->
                <td>
                    <select data-action="status" data-id="${p.id}" class="form-select form-select-sm">
                        ${Object.keys(statuses).map(k => `
                            <option value="${k}" ${String(p.status) === String(k) ? 'selected' : ''}>
                                ${statuses[k].label}
                            </option>
                        `).join('')}
                    </select>
                </td>

                <!-- PAYMENT -->
                <td>
                    <select data-action="payment" data-id="${p.id}" class="form-select form-select-sm">
                        ${Object.keys(payments).map(k => `
                            <option value="${k}" ${String(p.payment) === String(k) ? 'selected' : ''}>
                                ${payments[k].label}
                            </option>
                        `).join('')}
                    </select>
                </td>

                <td>${p.created_at ?? ''}</td>

                <!-- HÀNH ĐỘNG (GIỮ BUTTON + STYLE CŨ) -->
                <td>
                    <a href="/admin/purchases/edit/${p.id}"
                       class="btn btn-sm btn-outline-secondary">
                        Sửa
                    </a>

                    <button class="btn btn-sm btn-outline-secondary"
                            data-action="delete"
                            data-id="${p.id}">
                        Xóa
                    </button>
                </td>

            </tr>
        `).join('');

        this.renderPagination(json.meta);
    },

    /* =========================
       PAGINATION (GIỮ BOOTSTRAP NHƯ CŨ)
    ========================= */
    renderPagination(meta) {

        const container = document.getElementById('pagination-pages');
        if (!container || !meta) return;

        const page = meta.page;
        const totalPages = meta.totalPages;

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
                           data-action="page"
                           data-page="${i}">
                            ${i}
                        </a>
                    </li>
                `;
            }
        }
    }
};