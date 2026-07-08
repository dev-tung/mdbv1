import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';
import Event from './Event.js';

/* =================================================
   INIT
================================================= */

document.addEventListener('DOMContentLoaded', init);

async function init() {
    try {
        State.reset();

        const purchaseId = getPurchaseId();

        await loadData(purchaseId);

        Renderer.init();
        Event.bind();
    } catch (error) {
        console.error(error);
    }
}

/* =================================================
   LOAD DATA
================================================= */

async function loadData(purchaseId) {
    const tasks = [Service.loadWarehouses()];

    if (purchaseId) {
        tasks.push(Service.loadPurchase(purchaseId));
    }

    await Promise.all(tasks);
}

/* =================================================
   ROUTE
================================================= */

function getPurchaseId() {
    const purchaseId = document.querySelector('#purchase_id').value;
    return purchaseId ? parseInt(purchaseId) : null;
}
