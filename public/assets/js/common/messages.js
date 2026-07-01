// /common/messages.js

export const Messages = {

    // ======================
    // GLOBAL / COMMON
    // ======================
    COMMON: {
        UNKNOWN_ERROR: "Có lỗi xảy ra, vui lòng thử lại",
        NETWORK_ERROR: "Không thể kết nối server",
        INVALID_ACTION: "Hành động không hợp lệ"
    },

    // ======================
    // PURCHASE MODULE
    // ======================
    PURCHASE: {
        SUPPLIER_REQUIRED: "Vui lòng chọn nhà cung cấp",
        WAREHOUSE_REQUIRED: "Vui lòng chọn kho",
        PRODUCT_REQUIRED: "Vui lòng chọn sản phẩm",

        CREATE_SUCCESS: "Tạo phiếu nhập hàng thành công",
        UPDATE_SUCCESS: "Cập nhật phiếu nhập hàng thành công",

        CREATE_FAILED: "Tạo phiếu nhập hàng thất bại",
        UPDATE_FAILED: "Cập nhật phiếu nhập hàng thất bại"
    },

    // ======================
    // ORDER MODULE
    // ======================
    ORDER: {
        CUSTOMER_REQUIRED: "Vui lòng chọn khách hàng",
        PRODUCT_REQUIRED: "Vui lòng chọn sản phẩm",

        CREATE_SUCCESS: "Tạo phiếu nhập hàng thành công",
        UPDATE_SUCCESS: "Cập nhật phiếu nhập hàng thành công",

        CREATE_FAILED: "Tạo phiếu nhập hàng thất bại",
        UPDATE_FAILED: "Cập nhật phiếu nhập hàng thất bại"
    },

    // ======================
    // PRODUCT MODULE
    // ======================
    PRODUCT: {
        NOT_FOUND: "Không tìm thấy sản phẩm",
        OUT_OF_STOCK: "Sản phẩm đã hết hàng"
    },

    // ======================
    // VALIDATION GENERIC
    // ======================
    VALIDATION: {
        REQUIRED: "Trường này không được để trống",
        INVALID_NUMBER: "Giá trị không hợp lệ",
        MIN_QUANTITY: "Số lượng phải lớn hơn 0"
    }

};