import Dom from '../helpers/dom.js';

const Pagination = {
	render(selector, pagination, onChange) {
		const container = Dom.find(selector);

		if (!container) {
			return;
		}

		container.replaceChildren();

		const {
			page,
			last_page,
		} = pagination;

		if (last_page <= 1) {
			return;
		}

		const createItem = (label, target, disabled = false, active = false) => {
			const li = document.createElement('li');

			li.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;

			const a = document.createElement('a');

			a.href = '#';

			a.className = 'page-link';

			a.textContent = label;

			if (!disabled && !active && typeof target === 'number') {
				a.addEventListener('click', (e) => {
					e.preventDefault();

					onChange(target);
				});
			}

			li.appendChild(a);

			return li;
		};

		const createEllipsis = () => {
			const li = document.createElement('li');

			li.className = 'page-item disabled';

			li.innerHTML = '<span class="page-link">...</span>';

			return li;
		};

		container.appendChild(
			createItem('«', page - 1, page === 1),
		);

		let start = Math.max(2, page - 2);

		let end = Math.min(last_page - 1, page + 2);

		if (page <= 3) {
			end = Math.min(5, last_page - 1);
		}

		if (page >= last_page - 2) {
			start = Math.max(2, last_page - 4);
		}

		container.appendChild(createItem(1, 1, false, page === 1));

		if (start > 2) {
			container.appendChild(createEllipsis());
		}

		for (let i = start; i <= end; i++) {
			container.appendChild(
				createItem(i, i, false, i === page),
			);
		}

		if (end < last_page - 1) {
			container.appendChild(createEllipsis());
		}

		if (last_page > 1) {
			container.appendChild(
				createItem(last_page, last_page, false, page === last_page),
			);
		}

		container.appendChild(
			createItem('»', page + 1, page === last_page),
		);
	},
};

export default Pagination;