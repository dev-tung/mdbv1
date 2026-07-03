import { state } from "./state.js";
import { api } from "../../api.js";
import { service } from "./service.js";
import { render } from "./render.js";
import { event } from "./event.js";

document.addEventListener("DOMContentLoaded", async () => {

    try {

        // ==========================================
        // Loading
        // ==========================================

        state.ui.loading = true;

        // ==========================================
        // Reset local state (nếu cần)
        // ==========================================

        state.supplier.search.results = [];
        state.products.items = [];

        // ==========================================
        // Load master data
        // ==========================================

        const resWarehouse = await api.warehouse.getList();
        state.warehouse.list = resWarehouse.data ?? [];

        // ==========================================
        // Detect Create / Edit
        // ==========================================

        const purchaseId = document.body.dataset.purchaseId;

        if (purchaseId) {

            await service.loadPurchase(purchaseId);

        } else {

            if (state.warehouse.list.length) {
                service.warehouseSelect(state.warehouse.list[0]);
            }

        }

        // ==========================================
        // Calculate
        // ==========================================

        service.calculateSummary();

        // ==========================================
        // Render
        // ==========================================

        render.init();

        // ==========================================
        // Events
        // ==========================================

        event.init();

    } catch (error) {

        console.error(error);

    } finally {

        state.ui.loading = false;

    }

});