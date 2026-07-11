
import Renderer from './renderer.js';

const Controller = {
    async init() {
        try {
            Renderer.render();
        } catch (error) {
            console.error(error);
        }
    },
};

document.addEventListener('DOMContentLoaded', () => {
    Controller.init();
});

export default Controller;