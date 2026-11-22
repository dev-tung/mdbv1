import State from './state.js';

import Dom from '../../../../helpers/dom.js';

import Table from '../../../../components/table.js';

const hasDisplayValue = (value) => {
	const text = String(value ?? '').trim();

	return text !== '' && text !== '0';
};

const getEquipment = (summary) => {
	if (!hasDisplayValue(summary)) {
		return [];
	}

	return summary
		.split(' | ')
		.map((item) => item.replace(/^(Vợt|Giày|Căng cước)\s*:\s*/, '$1 ').trim())
		.filter((item) => hasDisplayValue(item.replace(/^(Vợt|Giày|Căng cước)\s*/, '')));
};

const getEquipmentBadges = (summary) => {
	const equipment = getEquipment(summary);
	const rackets = equipment.filter((item) => item.startsWith('Vợt '));
	const shoes = equipment.filter((item) => item.startsWith('Giày '));
	const otherEquipment = equipment.filter(
		(item) => !item.startsWith('Vợt ') && !item.startsWith('Giày '),
	);
	const badges = [];

	if (rackets.length) {
		badges.push({
			label: `Vợt ${rackets.map((item) => item.slice('Vợt '.length)).join(', ')}`,
			className: 'customer-racket-badge',
		});
	}

	if (shoes.length) {
		badges.push({
			label: `Giày ${shoes.map((item) => item.slice('Giày '.length)).join(', ')}`,
			className: 'customer-shoe-badge',
		});
	}

	otherEquipment.forEach((item) => {
		badges.push({
			label: item,
			className: item.startsWith('Căng cước ')
				? 'customer-stringing-badge'
				: 'customer-racket-badge',
		});
	});

	return badges;
};

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderTable();

		this.renderSummary();
	},

	/* =================================================
	   TABLE
	================================================= */

	renderTable() {
		Table.renderBody(State.customers, (customer, index) => {
			const fragment = Dom.template('#customer-row-template');

			const row = fragment.querySelector('tr');

			row.dataset.id = customer.id;

			// =========================
			// TEXT
			// =========================

			const texts = {
				'.index': index + 1,

				'.customer-name': customer.name,

				'.phone': customer.phone || '',

				'.email': customer.email || '',

				'.address': customer.address || '',

				'.equipment': customer.equipment_summary || '-',

				'.created-at': customer.created_at,
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});

			// =========================
			// EDIT
			// =========================

			row.querySelector('.edit-item').href = `/admin/customers/edit/${customer.id}`;

			// =========================
			// DELETE
			// =========================

			row.querySelector('.delete-item').dataset.id = customer.id;

			return fragment;
		});

		Renderer.renderMobileCards();
	},

	renderMobileCards() {
		const container = Dom.find('#customer-card-list');

		if (!container) {
			return;
		}

		container.replaceChildren();

		if (!State.customers.length) {
			const empty = document.createElement('p');
			empty.className = 'customer-card-empty';
			empty.textContent = 'Không tìm thấy khách hàng';
			container.appendChild(empty);
			return;
		}

		State.customers.forEach((customer) => {
			const fragment = Dom.template('#customer-card-template');
			const card = fragment.querySelector('.customer-card');
			const details = card.querySelector('.customer-card-details');

			card.dataset.id = customer.id;
			Dom.text('.customer-card-name', hasDisplayValue(customer.name) ? customer.name : '', card);
			Dom.text(
				'.customer-card-group',
				hasDisplayValue(customer.group_name) ? customer.group_name : '',
				card,
			);

			if (hasDisplayValue(customer.phone)) {
				const digits = customer.phone.replace(/\D/g, '');
				const phone = digits.startsWith('0084')
					? `0${digits.slice(4)}`
					: digits.startsWith('84')
						? `0${digits.slice(2)}`
						: digits;

				if (phone) {
					const phoneLink = document.createElement('a');
					phoneLink.className = 'customer-badge customer-phone-badge';
					phoneLink.href = `https://zalo.me/${phone}`;
					phoneLink.textContent = customer.phone;
					details.appendChild(phoneLink);
				}
			}

			getEquipmentBadges(customer.equipment_summary).forEach(({ label, className }) => {
				const badge = document.createElement('span');
				badge.className = `customer-badge ${className}`;
				badge.textContent = label;
				details.appendChild(badge);
			});

			if (hasDisplayValue(customer.address)) {
				const address = document.createElement('span');
				address.className = 'customer-badge customer-address-badge';
				address.textContent = customer.address;
				details.appendChild(address);
			}

			card.querySelector('.edit-item').href = `/admin/customers/edit/${customer.id}`;
			card.querySelector('.delete-item').dataset.id = customer.id;

			container.appendChild(fragment);
		});
	},

	/* =================================================
	   SUMMARY
	================================================= */

	renderSummary() {
		Dom.text('#sum-total-customer', State.summary.total);
	},
};

export default Renderer;
