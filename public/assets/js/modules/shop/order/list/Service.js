import State from './State.js';
import Api from './Api.js';
import Renderer from './Renderer.js';

const Service = {
    async loadOrders() {
        const response = await Api.getOrders(State.filter);

        State.orders = response.data || [];

        State.summary = {
            total_amount: State.orders.reduce((sum, item) => sum + Number(item.total_amount || 0), 0),

            paid_amount: State.orders.reduce((sum, item) => sum + Number(item.paid_amount || 0), 0),

            debt_amount: State.orders.reduce((sum, item) => sum + Number(item.debt_amount || 0), 0),
        };

        State.pagination = {
            current_page: 1,
            last_page: 1,
            per_page: State.orders.length,
            total: State.orders.length,
        };

        Renderer.render();
    },

    async loadCustomers() {
        const response = await Api.getCustomers();

        State.customers = response.data || [];

        Renderer.renderCustomers();
    },

    async status(id, status) {
        await Api.status(id, status);

        await this.loadOrders();
    },

    async payment(id, payment) {
        await Api.payment(id, payment);

        await this.loadOrders();
    },

    async deleteOrder(id) {
        await Api.deleteOrder(id);

        await this.loadOrders();
    },
};

export default Service;
