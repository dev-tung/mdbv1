import Event from './event.js';
import Renderer from './renderer.js';
import Service from './service.js';

try {
	Renderer.render();

	Event.bind();
} catch (error) {
	console.error(error);
}
