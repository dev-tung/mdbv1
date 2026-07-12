import Dom from '../helpers/dom.js';

const Select = {

    render(selector, options, selected = null) {

        const select = Dom.find(selector);

        select.innerHTML = '';

        Object.entries(options).forEach(([value, option]) => {

            const element = document.createElement('option');

            element.value = value;
            element.textContent = option.label;
            element.selected = value === selected;

            select.appendChild(element);

        });

    },

};

export default Select;