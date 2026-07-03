import Service from './Service.js';

let initialized = false;

/* =================================================
   INIT
================================================= */

async function init() {
    if (initialized) return;
    initialized = true;

    bindWarehouse();
    bindProducts();
    bindItems();
    bindActions();
    bindForm();
}


/* =================================================
   WAREHOUSE
================================================= */

function bindWarehouse() {
    const el = document.querySelector('#warehouse_id');
    if (!el) return;

    el.addEventListener('change', () => {
        Service.setWarehouse(parseInt(el.value, 10) || null);
    });
}

/* =================================================
   PRODUCTS
================================================= */

function bindProducts() {
    const selects = document.querySelectorAll('.purchase-product');
    if (!selects.length) return;

    selects.forEach((el) => {
        el.addEventListener('change', () => {
            Service.setProduct(
                parseInt(el.dataset.index, 10),
                parseInt(el.value, 10) || null
            );
        });
    });
}

/* =================================================
   ITEMS (QUANTITY + PRICE)
================================================= */

function bindItems() {
    const qtyInputs = document.querySelectorAll('.purchase-qty');
    const priceInputs = document.querySelectorAll('.purchase-price');

    qtyInputs.forEach((el) => {
        el.addEventListener('input', () => {
            Service.setQuantity(
                parseInt(el.dataset.index, 10),
                parseFloat(el.value) || 0
            );
        });
    });

    priceInputs.forEach((el) => {
        el.addEventListener('input', () => {
            Service.setPrice(
                parseInt(el.dataset.index, 10),
                parseFloat(el.value) || 0
            );
        });
    });
}

/* =================================================
   ACTIONS
================================================= */

function bindActions() {
    const removeBtns = document.querySelectorAll('.btn-remove-item');

    removeBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            Service.removeItem(parseInt(btn.dataset.index, 10));
        });
    });

    const addBtn = document.querySelector('.btn-add-item');
    if (addBtn) {
        addBtn.addEventListener('click', () => {
            Service.addItem();
        });
    }
}

/* =================================================
   FORM SUBMIT
================================================= */

function bindForm() {
    const form = document.querySelector('#purchase-create-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const result = await Service.save();

        if (!result?.success) {
            console.log('Validation errors:', result?.errors || []);
        }
    });
}

/* =================================================
   EXPORT
================================================= */

export default {
    init
};