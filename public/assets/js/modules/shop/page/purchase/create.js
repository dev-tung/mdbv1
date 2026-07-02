// =========================================================
// modules/shop/page/purchase/create.js
// =========================================================

import { PurchaseDetailState } from "../../state/PurchaseState.js";

import { PurchaseApi } from "../../api/PurchaseApi.js";
import { SupplierApi } from "../../api/SupplierApi.js";
import { WarehouseApi } from "../../api/WarehouseApi.js";
import { ProductApi } from "../../api/ProductApi.js";

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
        // Load Master Data
        // ==========================================

        const [

            suppliers,

            warehouses,

            products

        ] = await Promise.all([

            SupplierApi.getList(),

            WarehouseApi.getList(),

            ProductApi.getList()

        ]);

        PurchaseDetailState.master.suppliers =
            suppliers.data ?? [];

        PurchaseDetailState.master.warehouses =
            warehouses.data ?? [];

        PurchaseDetailState.master.products =
            products.data ?? [];

        // ==========================================
        // Default Warehouse
        // ==========================================

        if (PurchaseDetailState.master.warehouses.length > 0) {

            const warehouse = PurchaseDetailState.master.warehouses[0];

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
    finally {

        PurchaseDetailState.ui.loading = false;

    }

});