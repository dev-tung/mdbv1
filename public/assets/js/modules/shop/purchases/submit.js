import { Api } from "../../../helpers/api.js";
import { PurchaseState } from "./state.js";

export const Submit = {

    init() {

        document
        .getElementById("purchase-create-form")
        .addEventListener(
            "submit",
            this.store
        );
    },

    async store(event) {

        event.preventDefault();

        const products =
            PurchaseState.get();

        if (!products.length) {

            alert("Vui lòng thêm sản phẩm");

            return;
        }

        const supplierId =
            document.getElementById("supplier_id");

        if (!supplierId.value) {

            alert("Vui lòng chọn nhà cung cấp");

            return;
        }

        const payload = {

            supplier_id: supplierId.value,

            warehouse_id:
                document.getElementById("warehouse_id").value,

            description:
                document.getElementById("description").value,

            status:
                document.getElementById("status").value,

            payment:
                document.getElementById("payment").value,

            products
        };

        const response =
            await Api.post(
                "/api/purchases",
                payload
            );

        if (!response.success) {

            alert(response.data.message || "Lỗi");

            return;
        }

        alert("Tạo phiếu nhập thành công");

        window.location.href =
            "/admin/purchases";
    }
};