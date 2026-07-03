import State from './State.js';
import * as Render from './Render.js';

let bound = false;

export function bind() {

    if (bound) return;

    bound = true;

    warehouse();

    product();

    quantity();

    price();

    removeItem();

    submit();

}

function warehouse() {

    document.addEventListener('change', (e) => {

        if (!e.target.matches('#warehouse_id')) return;

        State.purchase.warehouseId = Number(e.target.value) || null;

    });

}

function product() {

    document.addEventListener('change', (e) => {

        if (!e.target.matches('.purchase-product')) return;

        const index = Number(e.target.dataset.index);

        State.purchase.items[index].productId =
            Number(e.target.value) || null;

    });

}

function quantity() {

    document.addEventListener('input', (e) => {

        if (!e.target.matches('.purchase-qty')) return;

        const index = Number(e.target.dataset.index);

        State.purchase.items[index].quantity =
            Number(e.target.value) || 0;

        Render.renderSummary();

    });

}

function price() {

    document.addEventListener('input', (e) => {

        if (!e.target.matches('.purchase-price')) return;

        const index = Number(e.target.dataset.index);

        State.purchase.items[index].purchasePrice =
            Number(e.target.value) || 0;

        Render.renderSummary();

    });

}

function removeItem() {

    document.addEventListener('click', (e) => {

        const button = e.target.closest('.btn-remove-item');

        if (!button) return;

        const index = Number(button.dataset.index);

        State.purchase.items.splice(index, 1);

        Render.renderProducts();

        Render.renderSummary();

    });

}

function submit() {

    document.addEventListener('submit', async (e) => {

        if (!e.target.matches('#purchase-create-form')) return;

        e.preventDefault();

        console.log(State.purchase);

        // Controller.save() sẽ gọi sau
    });

}