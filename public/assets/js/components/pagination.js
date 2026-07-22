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

		const createItem = (label, targetPage, disabled = false, active = false) => {
			const li = document.createElement('li');

			li.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;

			const a = document.createElement('a');

			a.href = '#';

			a.className = 'page-link';

			a.textContent = label;

			if (!disabled) {
				a.addEventListener('click', (e) => {
					e.preventDefault();

					onChange(targetPage);
				});
			}

			li.appendChild(a);

			return li;
		};

		container.appendChild(createItem('Đầu', 1, page === 1));

		container.appendChild(createItem('Trước', page - 1, page === 1));

		for (let i = 1; i <= last_page; i++) {
			container.appendChild(
				createItem(i, i, false, i === page),
			);
		}

		container.appendChild(createItem('Sau', page + 1, page === last_page));

		container.appendChild(createItem('Cuối', last_page, page === last_page));
	},
};

export default Pagination;