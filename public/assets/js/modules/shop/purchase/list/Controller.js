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

        await Promise.all([
            Service.loadSuppliers(),
            Service.loadPurchases()
        ]);

        Event.init();

    } catch (error) {

        console.error(error);

    }

}
