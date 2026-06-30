// /public/assets/js/modules/purchases/submit.js

import { Api } from "../../../common/api.js";
import { Notify } from "../../../common/notify.js";
import { Messages } from "../../../common/messages.js";
import { Product } from "./product.js";

export const Submit = {

    async create(url) {
        return this.send(url, "CREATE_SUCCESS");
    },

    async update(url) {
        return this.send(url, "UPDATE_SUCCESS");
    },

    async send(url, successKey) {

        const data = this.collect();

        const validate = this.validate(data);
        if (!validate.valid) {
            Notify.error(validate.message);
            return;
        }

        const json = await Api.post(url, data);

        if (json.success) {

            Notify.success(
                json.message || Messages.PURCHASE[successKey]
            );

            if (json.redirect) {
                location.href = json.redirect;
            }

            return;
        }

        Notify.error(
            json.message || Messages.COMMON.UNKNOWN_ERROR
        );
    },

    collect() {

        return {

            supplier_id: document.getElementById("supplier_id")?.value || "",
            warehouse_id: document.getElementById("warehouse_id")?.value || "",
            description: document.getElementById("description")?.value || "",
            status: document.getElementById("status")?.value || "",
            payment: document.getElementById("payment")?.value || "",

            products: Product.getItems()

        };

    },

    validate(data) {

        if (!data.supplier_id) {
            return {
                valid: false,
                message: Messages.PURCHASE.SUPPLIER_REQUIRED
            };
        }

        if (!data.warehouse_id) {
            return {
                valid: false,
                message: Messages.PURCHASE.WAREHOUSE_REQUIRED
            };
        }

        if (!data.products.length) {
            return {
                valid: false,
                message: Messages.PURCHASE.PRODUCT_REQUIRED
            };
        }

        return { valid: true };
    }

};