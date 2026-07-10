import Controller from './controller.js';
import Event from './event.js';

/* =================================================
   INIT
================================================= */

document.addEventListener('DOMContentLoaded', init);

async function init() {
	Event.init();

	await Controller.init();
}
