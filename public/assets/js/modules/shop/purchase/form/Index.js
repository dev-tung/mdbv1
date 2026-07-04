import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';
import Event from './Event.js';

document.addEventListener('DOMContentLoaded', async () => {

    try {
        State.reset();
        await pageInit();
        Event.init();
        Renderer.init();
    } catch (error) {
        console.error(error);
    }

    async function pageInit() {
        const purchaseId = getPurchaseId();

        if (purchaseId) {
            await loadEditData(purchaseId);
        } else {
            await loadCreateData();
        }
    }

    function getPurchaseId() {
        const match = window.location.pathname.match(
            /\/admin\/purchases\/edit\/(\d+)/
        );

        return match ? Number(match[1]) : null;
    }

    async function loadCreateData() {
        await Service.loadWarehouses();
    }

    async function loadEditData(purchaseId) {
        await Promise.all([
            Service.loadWarehouses(),
            Service.loadPurchase(purchaseId)
        ]);
    }

});