import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';
import Event from './Event.js';

document.addEventListener('DOMContentLoaded', async () => {

    try {

        State.reset();
        await Service.loadWarehouses();
        Renderer.warehouses();
        Event.init();

    } catch (error) {
        console.error(error);
    }

});