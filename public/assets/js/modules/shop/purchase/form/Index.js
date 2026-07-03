import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';
import Event from './Event.js';

document.addEventListener('DOMContentLoaded', async () => {

    try {
        State.reset();
        await Service.init();
        Event.init();
        Renderer.init();
    } catch (error) {
        console.error(error);
    }

});