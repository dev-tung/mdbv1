// =========================================================
// modules/shop/page/purchase/create.js
// =========================================================

import { PurchaseDetailState } from "../../state/PurchaseState.js";

import { WarehouseApi } from "../../api/WarehouseApi.js";

import { PurchaseService } from "../../service/PurchaseService.js";
import { PurchaseRenderer } from "../../render/PurchaseRenderer.js";
import { PurchaseEvent } from "../../event/PurchaseEvent.js";

document.addEventListener("DOMContentLoaded", async () => {

    try {

        // ==========================================
        // Loading
        // ==========================================

        PurchaseDetailState.ui.loading = true;

        // ==========================================
        // Init State
        // ==========================================

        PurchaseDetailState.suppliers = [];

        PurchaseDetailState.products = [];

        // ==========================================
        // Load Master Data
        // ==========================================

        const warehouses = await WarehouseApi.getList();

        PurchaseDetailState.warehouses =
            warehouses.data ?? [];

        // ==========================================
        // Default Warehouse
        // ==========================================

        if (PurchaseDetailState.warehouses.length > 0) {

            const warehouse = PurchaseDetailState.warehouses[0];

            PurchaseDetailState.warehouse.id = warehouse.id;

            PurchaseDetailState.warehouse.name = warehouse.name;
        }

        // ==========================================
        // Calculate
        // ==========================================

        PurchaseService.calculateSummary();

        // ==========================================
        // Render
        // ==========================================

        PurchaseRenderer.render();

        // ==========================================
        // Bind Events
        // ==========================================

        PurchaseEvent.init();

    }
    catch (error) {

        console.error(error);

    }
    finally {

        PurchaseDetailState.ui.loading = false;

    }

});