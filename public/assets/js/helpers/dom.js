const Dom = {

    /* =================================================
       FIND
    ================================================= */

    find(selector, parent = document) {
        return parent.querySelector(selector);
    },

    findAll(selector, parent = document) {
        return [...parent.querySelectorAll(selector)];
    },

    /* =================================================
       VALUE
    ================================================= */

    value(selector, value, parent = document) {

        const element = this.find(selector, parent);

        if (!element) return;

        if (arguments.length < 2) {
            return element.value;
        }

        element.value = value ?? '';

    },

    text(selector, value, parent = document) {

        const element = this.find(selector, parent);

        if (!element) return;

        if (arguments.length < 2) {
            return element.textContent;
        }

        element.textContent = value ?? '';

    },

    html(selector, value, parent = document) {

        const element = this.find(selector, parent);

        if (!element) return;

        if (arguments.length < 2) {
            return element.innerHTML;
        }

        element.innerHTML = value ?? '';

    },

    /* =================================================
       ELEMENT
    ================================================= */

    clear(selector, parent = document) {

        const element = this.find(selector, parent);

        if (element) {
            element.innerHTML = '';
        }

    },

    append(selector, child, parent = document) {

        const element = this.find(selector, parent);

        if (element) {
            element.appendChild(child);
        }

    },

    remove(selector, parent = document) {

        const element = this.find(selector, parent);

        if (element) {
            element.remove();
        }

    },

    /* =================================================
       DISPLAY
    ================================================= */

    show(selector, parent = document) {

        const element = this.find(selector, parent);

        if (element) {
            element.hidden = false;
        }

    },

    hide(selector, parent = document) {

        const element = this.find(selector, parent);

        if (element) {
            element.hidden = true;
        }

    },

    toggle(selector, parent = document) {

        const element = this.find(selector, parent);

        if (element) {
            element.hidden = !element.hidden;
        }

    },

    /* =================================================
       CLASS
    ================================================= */

    addClass(selector, className, parent = document) {
        this.find(selector, parent)?.classList.add(className);
    },

    removeClass(selector, className, parent = document) {
        this.find(selector, parent)?.classList.remove(className);
    },

    toggleClass(selector, className, parent = document) {
        this.find(selector, parent)?.classList.toggle(className);
    },

    hasClass(selector, className, parent = document) {
        return this.find(selector, parent)?.classList.contains(className);
    }

};

export default Dom;