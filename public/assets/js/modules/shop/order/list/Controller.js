import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';
import Event from './Event.js';

const Controller = {
    async init() {
        await Promise.all([Service.loadCustomers(), Service.loadOrders()]);

        Renderer.render();

        Event.init();
    },
};

document.addEventListener('DOMContentLoaded', () => {
    Controller.init();
});

export default Controller;
