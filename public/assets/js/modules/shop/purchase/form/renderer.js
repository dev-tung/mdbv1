import State from './state.js';

import Dom from '../../../../helpers/dom.js';
import Formatter from '../../../../helpers/formatter.js';

import Pricing from '../../shared/pricing.js';

const Renderer = {

    /* =================================================
       PUBLIC
    ================================================= */

    render() {
        this.renderPurchase();
        this.renderProducts();
    },

    /* =================================================
       PURCHASE
    ================================================= */

    renderPurchase() {

        const purchase = State.purchase;

        Dom.value('#purchase_id', purchase.id);
        Dom.value('#supplier_id', purchase.supplier_id);
        Dom.value('#supplier_search', purchase.supplier_name);
        Dom.value('#description', purchase.description);
        Dom.value('#status', purchase.status);
        Dom.value('#warehouse_id', purchase.warehouse_id);
        Dom.value('#vat_rate', purchase.vat_rate);
        Dom.value('#payment', purchase.payment);
        Dom.value('#paid_amount', purchase.paid_amount);

    },

    /* =================================================
       PRODUCTS
    ================================================= */

    renderProducts() {

        const tbody = Dom.find('#selected_products');

        Dom.clear('#selected_products');

        const summary = {
            subtotal: 0,
            tax: 0,
            total: 0,
        };

        State.items.forEach((item, index) => {

            const subtotal = Pricing.subtotal(
                item.quantity,
                item.purchase_price
            );

            const tax = Pricing.tax(
                subtotal,
                State.purchase.vat_rate
            );

            const total = Pricing.total(
                subtotal,
                tax
            );

            summary.subtotal += subtotal;
            summary.tax += tax;
            summary.total += total;

            tbody.appendChild(
                this.createProductRow(
                    item,
                    index,
                    subtotal,
                    tax,
                    total
                )
            );

        });

        this.renderSummary(summary);

    },

    createProductRow(item, index, subtotal, tax, total) {

        const fragment = document
            .getElementById('purchase-item-template')
            .content
            .cloneNode(true);

        const row = fragment.querySelector('tr');

        row.dataset.index = index;

        Dom.text('.product-name', item.name, row);
        Dom.value('.quantity', item.quantity, row);
        Dom.value('.purchase-price', item.purchase_price, row);
        Dom.value('.selling-price', item.selling_price, row);

        Dom.text('.subtotal', Formatter.money(subtotal), row);
        Dom.text('.vat', Formatter.money(tax), row);
        Dom.text('.total', Formatter.money(total), row);

        return fragment;

    },

    /* =================================================
       SUMMARY
    ================================================= */

    renderSummary(summary) {

        Dom.text(
            '#subtotal_amount',
            Formatter.money(summary.subtotal)
        );

        Dom.text(
            '#vat_amount',
            Formatter.money(summary.tax)
        );

        Dom.text(
            '#total_amount',
            Formatter.money(summary.total)
        );

        Dom.text(
            '#debt_amount',
            Formatter.money(
                Pricing.debt(
                    summary.total,
                    State.purchase.paid_amount
                )
            )
        );

    }

};

export default Renderer;