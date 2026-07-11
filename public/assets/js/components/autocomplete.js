const Autocomplete = {

    /* =================================================
       PUBLIC
    ================================================= */

    init(options) {

        const element = document.querySelector(options.element);

        if (!element) {
            return;
        }

        const dropdown = this.createDropdown(element);

        let timer;

        element.addEventListener('input', () => {

            clearTimeout(timer);

            const keyword = element.value.trim();

            if (!keyword) {
                this.close(dropdown);
                return;
            }

            timer = setTimeout(async () => {

                const items = await options.source(keyword);

                this.render(
                    dropdown,
                    items,
                    options.render,
                    options.select
                );

            }, options.delay ?? 300);

        });

        document.addEventListener('click', (e) => {

            if (
                !element.contains(e.target) &&
                !dropdown.contains(e.target)
            ) {
                this.close(dropdown);
            }

        });

    },

    /* =================================================
       RENDER
    ================================================= */

    render(dropdown, items, render, select) {

        dropdown.innerHTML = '';

        if (!items.length) {
            this.close(dropdown);
            return;
        }

        items.forEach(item => {

            const option = document.createElement('div');

            option.className = 'autocomplete-item';

            option.innerHTML = render(item);

            option.addEventListener('click', () => {

                select(item);

                this.close(dropdown);

            });

            dropdown.appendChild(option);

        });

        this.open(dropdown);

    },

    /* =================================================
       DROPDOWN
    ================================================= */

    createDropdown(element) {

        const dropdown = document.createElement('div');

        dropdown.className = 'autocomplete-dropdown';

        element.parentNode.appendChild(dropdown);

        return dropdown;

    },

    open(dropdown) {
        dropdown.style.display = 'block';
    },

    close(dropdown) {
        dropdown.style.display = 'none';
    }

};

export default Autocomplete;