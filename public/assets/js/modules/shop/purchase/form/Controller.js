import State from './State.js';
import Api from './Api.js';
import Render from './Render.js';
import Event from './Event.js';

const Controller = {

    async init(purchaseId = null) {

        try {

            State.reset();

            State.purchase.id = purchaseId;

            await this.loadMasterData();

            if (State.purchase.id) {
                await this.loadPurchase();
            }

            Render.renderWarehouses();
            Render.renderProducts();
            Render.renderSummary();

            Event.bind();

        } catch (error) {

            console.error(error);

            alert('Không thể khởi tạo form nhập hàng.');

        }

    },

    async loadMasterData() {

        State.warehouse.list = await Api.getWarehouses();

    },

    async loadPurchase() {

        const purchase = await Api.getPurchase(
            State.purchase.id
        );

        // map purchase -> State

    }

};

document.addEventListener('DOMContentLoaded', () => {

    const purchaseId =
        document.body.dataset.purchaseId || null;

    Controller.init(purchaseId);

});

export default Controller;