<main class="container py-3">
    <div class="mb-4">
        <h1 class="h3 fw-bold">Thông tin đặt hàng</h1>
        <div class="d-flex flex-wrap gap-2 small" aria-label="Các bước đặt hàng">
            <span class="badge text-bg-success">1. Giỏ hàng</span>
            <span class="badge text-bg-success">2. Thông tin nhận hàng</span>
            <span class="badge text-bg-secondary">3. Thanh toán</span>
        </div>
    </div>

    <div class="row g-3">

        <!-- LEFT: ORDER -->
        <div class="col-12 col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        Đơn hàng của bạn
                    </h5>
                </div>

                <div class="card-body">

                    <div id="checkout-items">
                        Đang tải...
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Số lượng sản phẩm</span>
                        <span id="checkout-quantity">0</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <span id="checkout-subtotal">0 ₫</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Phí giao hàng</span>
                        <span class="text-secondary">Chưa áp dụng trong bản mẫu</span>
                    </div>

                    <div class="d-flex justify-content-between fw-bold fs-5">

                        <span>
                            Tạm tính đơn hàng
                        </span>

                        <span
                            id="checkout-total"
                            class="text-danger"
                        >
                            0 ₫
                        </span>

                    </div>
                    <p class="form-text mt-2 mb-0">Tạm tính chưa gồm phí vận chuyển. Phương thức nhận hàng và phí giao sẽ được lưu khi tích hợp backend.</p>

                </div>

            </div>

        </div>


        <!-- RIGHT: CUSTOMER -->
        <div class="col-12 col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-bold">
                        Thông tin nhận hàng
                    </h5>

                </div>

                <div class="card-body">

                    <!-- NAME -->
                    <div class="mb-3">

                        <label
                            for="customer_name"
                            class="form-label"
                        >
                            Họ tên
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="customer_name"
                            class="form-control"
                            placeholder="Nhập họ tên"
                            autocomplete="name"
                        >

                    </div>


                    <!-- PHONE -->
                    <div class="mb-3">

                        <label
                            for="phone"
                            class="form-label"
                        >
                            Số điện thoại
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            class="form-control"
                            placeholder="Nhập số điện thoại"
                            autocomplete="tel"
                        >

                    </div>


                    <!-- EMAIL -->
                    <div class="mb-3">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email
                            <span class="text-muted">
                                (không bắt buộc)
                            </span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            class="form-control"
                            placeholder="Nhập email"
                            autocomplete="email"
                        >

                    </div>

                    <div class="mb-3">
                        <label for="delivery-method" class="form-label">
                            Phương thức nhận hàng
                        </label>
                        <select id="delivery-method" class="form-select">
                            <option value="delivery">Giao hàng tận nơi</option>
                            <option value="pickup">Nhận tại cửa hàng</option>
                        </select>
                        <div class="form-text">Phương thức nhận hàng và địa chỉ sẽ được lưu cùng đơn hàng.</div>
                    </div>


                    <!-- ADDRESS -->
                    <div class="mb-3">

                        <label
                            for="address"
                            class="form-label"
                        >
                            Địa chỉ nhận hàng
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            id="address"
                            class="form-control"
                            rows="2"
                            placeholder="Nhập địa chỉ nhận hàng"
                            autocomplete="street-address"
                        ></textarea>

                    </div>


                    <!-- NOTE -->
                    <div class="mb-3">

                        <label
                            for="note"
                            class="form-label"
                        >
                            Ghi chú
                            <span class="text-muted">
                                (không bắt buộc)
                            </span>
                        </label>

                        <textarea
                            id="note"
                            class="form-control"
                            rows="3"
                            placeholder="Ghi chú cho đơn hàng"
                        ></textarea>

                    </div>


                    <!-- SUBMIT -->
                    <button
                        type="button"
                        id="btn-submit-order"
                        class="btn btn-success w-100"
                        onclick="submitOrder()"
                    >
                        Đặt hàng
                    </button>


                    <!-- BACK CART -->
                    <a
                        href="/cart"
                        class="btn btn-outline-secondary w-100 mt-2"
                    >
                        Quay lại giỏ hàng
                    </a>

                </div>

            </div>

        </div>

    </div>

</main>


<script>

/* =================================================
   CART
================================================= */

function getCart() {

    try {

        const cart =
            JSON.parse(
                localStorage.getItem('cart')
            );

        return Array.isArray(cart)
            ? cart
            : [];

    } catch (error) {

        return [];
    }
}


/* =================================================
   FORMAT MONEY
================================================= */

function formatMoney(value) {

    return Number(value || 0)
        .toLocaleString('vi-VN') + ' ₫';
}


/* =================================================
   RENDER CHECKOUT
================================================= */

function renderCheckout() {

    const cart =
        getCart();

    const container =
        document.getElementById(
            'checkout-items'
        );

    const totalElement =
        document.getElementById(
            'checkout-total'
        );


    if (!container) {
        return;
    }


    // =========================
    // EMPTY CART
    // =========================

    if (!cart.length) {

        container.innerHTML = `
            <div class="alert alert-warning mb-0">
                Giỏ hàng trống.
            </div>
        `;

        if (totalElement) {

            totalElement.textContent =
                '0 ₫';
        }

        return;
    }


    // =========================
    // RENDER
    // =========================

    let total = 0;
    let totalQuantity = 0;

    container.replaceChildren();


    cart.forEach((item) => {

        const price =
            Number(item.price || 0);

        const quantity =
            Number(item.quantity || 0);

        const subtotal =
            price * quantity;

        total += subtotal;
        totalQuantity += quantity;


        // =========================
        // ROW
        // =========================

        const row =
            document.createElement('div');

        row.className =
            'd-flex align-items-start border-bottom py-3';


        // =========================
        // IMAGE
        // =========================

        const imageWrapper =
            document.createElement('div');

        imageWrapper.className =
            'flex-shrink-0 d-flex align-items-center';


        const image =
            document.createElement('img');

        image.src =
            item.image ||
            '/assets/image/no-image.svg';

        image.width =
            60;

        image.height =
            60;

        image.className =
            'rounded';

        image.alt =
            item.name || '';


        image.onerror = () => {

            image.onerror = null;

            image.src =
                '/assets/image/no-image.svg';
        };


        imageWrapper.appendChild(
            image
        );


        // =========================
        // CONTENT
        // =========================

        const content =
            document.createElement('div');

        content.className =
            'flex-grow-1 ps-3 d-flex flex-column justify-content-center';


        // NAME

        const name =
            document.createElement('div');

        name.className =
            'fw-semibold text-break';

        name.textContent =
            item.name || 'Sản phẩm';

        const variantElement =
            document.createElement('small');

        variantElement.className =
            'text-secondary';

        variantElement.textContent =
            item.variant ? `Phiên bản: ${item.variant}` : '';


        // QUANTITY

        const quantityElement =
            document.createElement('small');

        quantityElement.className =
            'text-muted';

        quantityElement.textContent =
            `Số lượng: ${quantity}`;


        // SUBTOTAL

        const subtotalElement =
            document.createElement('div');

        subtotalElement.className =
            'fw-bold text-danger';

        subtotalElement.textContent =
            formatMoney(subtotal);


        content.appendChild(
            name
        );

        if (item.variant) {
            content.appendChild(variantElement);
        }

        content.appendChild(
            quantityElement
        );

        content.appendChild(
            subtotalElement
        );


        row.appendChild(
            imageWrapper
        );

        row.appendChild(
            content
        );


        container.appendChild(
            row
        );

    });


    // =========================
    // TOTAL
    // =========================

    if (totalElement) {

        totalElement.textContent =
            formatMoney(total);
    }

    document.getElementById('checkout-quantity').textContent =
        String(totalQuantity);

    document.getElementById('checkout-subtotal').textContent =
        formatMoney(total);
}


/* =================================================
   SUBMIT ORDER
================================================= */

async function submitOrder() {

    const button =
        document.getElementById(
            'btn-submit-order'
        );

    let orderMayHaveBeenCreated = false;

    try {

        const cart =
            getCart();

        if (!cart.length) {
            throw new Error(
                'Giỏ hàng trống.'
            );
        }


        // =========================
        // CUSTOMER
        // =========================

        const customerName =
            document
                .getElementById('customer_name')
                .value
                .trim();

        const phone =
            document
                .getElementById('phone')
                .value
                .trim();

        const email =
            document
                .getElementById('email')
                .value
                .trim();

        const address =
            document
                .getElementById('address')
                .value
                .trim();

        const note =
            document
                .getElementById('note')
                .value
                .trim();

        const deliveryMethod =
            document
                .getElementById('delivery-method')
                .value;


        // =========================
        // VALIDATE CUSTOMER
        // =========================

        if (!customerName) {

            document
                .getElementById('customer_name')
                .focus();

            throw new Error(
                'Vui lòng nhập họ tên.'
            );
        }


        if (!phone) {

            document
                .getElementById('phone')
                .focus();

            throw new Error(
                'Vui lòng nhập số điện thoại.'
            );
        }


        if (deliveryMethod === 'delivery' && !address) {

            document
                .getElementById('address')
                .focus();

            throw new Error(
                'Vui lòng nhập địa chỉ nhận hàng.'
            );
        }


        // =========================
        // DISABLE BUTTON
        // =========================

        if (button) {

            button.disabled =
                true;

            button.textContent =
                'Đang đặt hàng...';
        }


        const items = cart.map((item) => {
            const productId = Number(item.product_id);
            const quantity = Number(item.quantity);

            if (!Number.isInteger(productId) || productId < 1) {
                throw new Error('Sản phẩm trong giỏ hàng không hợp lệ.');
            }

            if (!Number.isInteger(quantity) || quantity < 1) {
                throw new Error('Số lượng sản phẩm không hợp lệ.');
            }

            return {
                product_id: productId,
                quantity,
                variant: item.variant || ''
            };
        });


        // =========================
        // CREATE ORDER
        // =========================

        const orderResponse =
            await fetch(
                '/api/online-orders',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json'
                    },

                    body:
                        JSON.stringify({
                            customer: {
                                name: customerName,
                                phone,
                                email: email || null,
                                address: address || null
                            },
                            items:
                                items,
                            note,
                            delivery_method: deliveryMethod
                        })
                }
            );


        const responseBody =
            await orderResponse.text();
        let orderJson;

        try {
            orderJson = JSON.parse(responseBody);
        } catch {
            throw new Error(
                `Máy chủ không trả dữ liệu JSON khi tạo đơn (HTTP ${orderResponse.status}). Vui lòng thử lại hoặc liên hệ cửa hàng.`
            );
        }


        if (
            !orderResponse.ok ||
            !orderJson.success
        ) {

            throw new Error(
                orderJson.message ||
                'Đặt hàng thất bại.'
            );
        }

        orderMayHaveBeenCreated = true;
        const orderId = orderJson.data && orderJson.data.id;
        if (!orderId) {
            throw new Error('Đơn hàng đã được gửi nhưng chưa nhận được mã đơn. Vui lòng liên hệ cửa hàng để kiểm tra.');
        }

        const totalAmount = Number(orderJson.data.total_amount);
        if (!Number.isFinite(totalAmount) || !Array.isArray(orderJson.data.items)) {
            throw new Error('Đơn hàng đã được gửi nhưng máy chủ chưa trả đủ thông tin xác nhận. Vui lòng giữ liên hệ với cửa hàng.');
        }

        try {
            sessionStorage.setItem('mdb:lastOrder', JSON.stringify({
                id: orderId,
                customer: {
                    name: customerName,
                    phone,
                    email,
                    address
                },
                deliveryMethod,
                note,
                items: orderJson.data.items.map((item) => ({
                    name: item.product_name,
                    quantity: item.quantity,
                    price: item.selling_price,
                    variant: item.variant || ''
                })),
                total: totalAmount
            }));
        } catch (error) {
            console.error('Không thể lưu thông tin đơn hàng trong phiên hiện tại.', error);
            window.alert('Đơn hàng đã được tạo. Một số chi tiết có thể không hiển thị sau khi chuyển trang.');
        }

        // =========================
        // SUCCESS
        // =========================

        localStorage.removeItem(
            'cart'
        );


        alert(
            'Đặt hàng thành công!'
        );


        window.location.href =
            `/checkout/success?id=${encodeURIComponent(orderId)}`;


    } catch (error) {

        console.error(
            'Submit order error:',
            error
        );


        alert(
            error.message ||
            'Lỗi hệ thống.'
        );


        if (button && orderMayHaveBeenCreated) {
            button.disabled = true;
            button.textContent = 'Vui lòng liên hệ cửa hàng';
        } else if (button) {

            button.disabled =
                false;

            button.textContent =
                'Đặt hàng';
        }
    }
}


/* =================================================
   INIT
================================================= */

document.addEventListener(
    'DOMContentLoaded',
    () => {

        renderCheckout();

    }
);

</script>