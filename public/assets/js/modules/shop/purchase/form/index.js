import Service from './service.js';

import Renderer from './renderer.js';

import Event from './event.js';

const Purchase = {
	async init() {
		await Service.load();

		Renderer.init();

		Event.bind();
	},
};

document.addEventListener('DOMContentLoaded', () => {
	Purchase.init();
});
