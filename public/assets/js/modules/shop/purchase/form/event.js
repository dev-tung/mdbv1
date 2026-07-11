import Dom from '../../../../helpers/dom.js';

import Autocomplete from '../../../../components/autocomplete.js';

import Service from './service.js';
import Controller from './controller.js';

const Event = {

    /* =================================================
       PUBLIC
    ================================================= */

    bind() {

        this.bindSupplier();
        this.bindProduct();
        this.bindPurchase();
        this.bindItems();
        this.bindSubmit();

    },

    /* =================================================
       SUPPLIER
    ================================================= */

    bindSupplier() {

        Autocomplete.init({

            element: '#supplier_search',

            source: Service.searchSupplier,

            render(item) {

                return `
                    <strong>${item.name}</strong>
                    <div class="text-muted small">
                        ${item.phone ?? ''}
                    </div>
                `;

            },

            select: Controller.selectSupplier

        });

    },

    /* =================================================
       PRODUCT
    ================================================= */

    bindProduct() {

        Autocomplete.init({

            element: '#product_search',

            source: Service.searchProduct,

            render(item) {

                return `
                    <strong>${item.name}</strong>
                    <div class="text-muted small">
                        ${item.code ?? ''}
                    </div>
                `;

            },

            select: Controller.selectProduct

        });

    },

    /* =================================================
       PURCHASE
    ================================================= */

    bindPurchase() {

        Dom.find('#vat_rate')
            ?.addEventListener('change', Controller.changeVat);

        Dom.find('#paid_amount')
            ?.addEventListener('input', Controller.changePayment);

    },

    /* =================================================
       ITEMS
    ================================================= */

    bindItems() {

        const table = Dom.find('#selected_products');

        if (!table) {
            return;
        }

        table.addEventListener('input', Controller.changeItem);

        table.addEventListener('click', (e) => {

            if (e.target.matches('.btn-remove-item')) {
                Controller.removeItem(e);
            }

        });

    },

    /* =================================================
       SUBMIT
    ================================================= */

    bindSubmit() {

        Dom.find('#purchase-form')
            ?.addEventListener('submit', Controller.submit);

    }

};

export default Event;