import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';

const Event = {

    bind() {

        this.supplier();
        this.product();
        this.purchase();
        this.items();
        this.submit();

    },

    /* =================================================
       SUPPLIER
    ================================================= */

    supplier() {

        const input = document.querySelector('#supplier_search');

        const suggestions = document.querySelector('#supplier_suggestions');

        input?.addEventListener('input', async e => {

            State.supplier.keyword = e.target.value.trim();

            await Service.searchSuppliers();

            Renderer.supplierSuggestions();

        });

        suggestions?.addEventListener('click', e => {

            const button = e.target.closest('.supplier-item');

            if (!button) return;

            const supplier = State.supplier.suggestions.find(
                item => item.id == button.dataset.id
            );

            if (!supplier) return;

            Service.setSupplier(supplier);

            input.value = supplier.name;

            State.supplier.suggestions = [];

            Renderer.supplierSuggestions();

        });

    },

    /* =================================================
       PRODUCT
    ================================================= */

    product() {

        const input = document.querySelector('#product_search');

        const suggestions = document.querySelector('#product_suggestions');

        input?.addEventListener('input', async e => {

            State.product.keyword = e.target.value.trim();

            await Service.searchProducts();

            Renderer.productSuggestions();

        });

        suggestions?.addEventListener('click', e => {

            const button = e.target.closest('.product-item');

            if (!button) return;

            const product = State.product.suggestions.find(
                item => item.id == button.dataset.id
            );

            if (!product) return;

            Service.addProduct(product);

            Renderer.products();

            Renderer.summary();

            input.value = '';

            suggestions.classList.add('d-none');

        });

    },

    /* =================================================
       PURCHASE
    ================================================= */

    purchase() {

        document.querySelector('#description')
            ?.addEventListener('input', e => {

                Service.setDescription(e.target.value);

            });

        document.querySelector('#status')
            ?.addEventListener('change', e => {

                Service.setStatus(e.target.value);

            });

        document.querySelector('#warehouse_id')
            ?.addEventListener('change', e => {
                Service.setWarehouse(e.target.value);
            });

        document.querySelector('#payment')
            ?.addEventListener('change', e => {

                Service.setPayment(e.target.value);

                Renderer.payment();

                Renderer.summary();

            });

        document.querySelector('#paid_amount')
            ?.addEventListener('input', e => {

                Service.setPaidAmount(e.target.value);
                
                Service.recalc();
                Renderer.summary();

            });

    },

    /* =================================================
       ITEMS
    ================================================= */

    items() {

        const table = document.querySelector('#selected_products');

        if (!table) return;

        /* =================================================
        INPUT EVENTS
        ================================================= */
        table.addEventListener('input', e => {

            const row = e.target.closest('tr');
            if (!row) return;

            const index = Number(row.dataset.index);
            const value = e.target.value;
            const classList = e.target.classList;

            if (classList.contains('quantity')) {

                Service.setQuantity(index, value);

            }

            else if (classList.contains('purchase-price')) {

                Service.setPurchasePrice(index, value);

            }

            else if (classList.contains('order-price')) {

                Service.setOrderPrice(index, value);

            }

            else if (classList.contains('vat-rate')) {

                Service.setVatRate(index, value);

            }
            
            Renderer.productsUpdate(index); 
            Renderer.summary();

        });

        /* =================================================
        CLICK EVENTS
        ================================================= */
        table.addEventListener('click', e => {

            const button = e.target.closest('.btn-remove');
            if (!button) return;

            const row = button.closest('tr');
            if (!row) return;

            const index = Number(row.dataset.index);

            Service.removeProduct(index);

            Renderer.products();
            Renderer.summary();

        });

    },

    /* =================================================
       SUBMIT
    ================================================= */

    submit() {

        document.querySelector('#purchase-form')
            ?.addEventListener('submit', async e => {

                e.preventDefault();

                await Service.save();

            });

    }

};

export default Event;