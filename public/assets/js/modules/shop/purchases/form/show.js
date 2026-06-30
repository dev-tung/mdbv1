import { Api } from '/assets/js/common/api.js';
import { Product } from './product.js';

export const Show = {

    url: null,

    async init(url) {
        this.url = url;

        await this.load();
    },

    async load() {

        const json = await Api.get(this.url);
        if (!json?.success) return;

        const p = json.data;

        // =========================
        // SUPPLIER
        // =========================
        document.getElementById('supplier_id').value = p.supplier_id ?? '';
        document.getElementById('supplier_search').value = p.supplier?.name ?? '';

        // =========================
        // BASIC INFO
        // =========================
        document.getElementById('description').value = p.description ?? '';
        document.getElementById('status').value = p.status ?? 'draft';
        document.getElementById('payment').value = p.payment ?? 'unpaid';
        document.getElementById('payment').dispatchEvent(new Event('change', { bubbles: true }));
        document.getElementById('paid_amount').value = p.paid_amount ?? 0;


        // =========================
        // WAREHOUSE
        // =========================
        const warehouseEl = document.getElementById('warehouse_id');
        if (warehouseEl) {
            warehouseEl.value = p.warehouse_id ?? '';
            warehouseEl.dispatchEvent(new Event('change'));
        }

        // =========================
        // PRODUCTS
        // =========================
        const products = (p.products ?? []).map(item => ({
            product_id: item.product_id,
            name: item.name,
            price: Number(item.price) || 0,
            quantity: Number(item.quantity) || 1,
            subtotal: Number(item.subtotal) || (Number(item.price) * Number(item.quantity))
        }));

        Product.setItems(products);

        // =========================
        // TOTAL
        // =========================
        const totalEl = document.getElementById('total_amount');
        if (totalEl) {
            totalEl.innerText = p.total_amount.toLocaleString() ?? 0;
        }
    }
};