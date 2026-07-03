// =========================================================
// modules/shop/purchase/form/index.js
// =========================================================

import { state } from "./state.js";
import { api } from "../api.js";
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
        // Init State
        // ==========================================

        state.suppliers = [];

        state.products = [];

        // ==========================================
        // Load Master Data
        // ==========================================

        const response = await api.purchase.getList();

        state.warehouses = response.data ?? [];

        // ==========================================
        // Detect Create / Edit
        // ==========================================

        const purchaseId = document.body.dataset.purchaseId;

        if (purchaseId) {

            // Edit
            await service.loadPurchase(purchaseId);

        } else {

            // Create
            if (state.warehouses.length) {

                service.setWarehouse(
                    state.warehouses[0]
                );

            }

        }

        // ==========================================
        // Calculate
        // ==========================================

        service.calculateSummary();

        // ==========================================
        // Render
        // ==========================================

        render.render();

        // ==========================================
        // Bind Events
        // ==========================================

        event.init();

    }
    catch (error) {

        console.error(error);

    }
    finally {

        state.ui.loading = false;

    }

});