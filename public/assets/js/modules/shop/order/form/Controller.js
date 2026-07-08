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

        const orderId = getOrderId();

        await loadData(orderId);

        Renderer.init();

        Event.bind();

    } catch (error) {

        console.error(error);

    }

}

/* =================================================
   LOAD DATA
================================================= */

async function loadData(orderId) {

    const tasks = [];

    if (orderId) {

        tasks.push(Service.loadOrder(orderId));

    }

    await Promise.all(tasks);

}

/* =================================================
   ROUTE
================================================= */

function getOrderId() {

    const orderId = document.querySelector('#order_id').value;

    return orderId
        ? parseInt(orderId)
        : null;

}