<main class="container py-3">

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

                    <div class="d-flex justify-content-between fw-bold fs-5">

                        <span>
                            Tổng tiền
                        </span>

                        <span
                            id="checkout-total"
                            class="text-danger"
                        >
                            0 ₫
                        </span>

                    </div>

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

    container.replaceChildren();


    cart.forEach((item) => {

        const price =
            Number(item.price || 0);

        const quantity =
            Number(item.quantity || 0);

        const subtotal =
            price * quantity;

        total += subtotal;


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

        image.style.objectFit =
            'contain';

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
}


/* =================================================
   SUBMIT ORDER
================================================= */

async function submitOrder() {

    const button =
        document.getElementById(
            'btn-submit-order'
        );

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


        if (!address) {

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


        // =========================
        // CREATE CUSTOMER
        // =========================

        const customerResponse =
            await fetch(
                '/api/customers',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json'
                    },

                    body:
                        JSON.stringify({

                            name:
                                customerName,

                            phone:
                                phone,

                            email:
                                email || null,

                            group_id:
                                null,

                            address:
                                address,

                            description:
                                note || null

                        })
                }
            );


        const customerJson =
            await customerResponse.json();


        if (
            !customerResponse.ok ||
            !customerJson.success ||
            !customerJson.id
        ) {

            throw new Error(
                customerJson.message ||
                'Không thể tạo khách hàng.'
            );
        }


        const customerId =
            Number(
                customerJson.id
            );


        // =========================
        // CALCULATE ITEMS
        // =========================

        let subtotalAmount = 0;

        let discountAmount = 0;

        let vatAmount = 0;


        const items =
            cart.map((item) => {

                const price =
                    Number(
                        item.price || 0
                    );

                const salePrice =
                    Number(
                        item.sale_price || 0
                    );


                const sellingPrice =
                    salePrice > 0 &&
                    price > 0 &&
                    salePrice < price
                        ? salePrice
                        : price;


                const quantity =
                    Number(
                        item.quantity || 0
                    );


                const isGift =
                    Number(
                        item.is_gift || 0
                    );


                // =========================
                // SUBTOTAL
                // =========================

                const itemSubtotal =
                    sellingPrice *
                    quantity;


                // =========================
                // DISCOUNT
                // =========================

                const itemDiscount =
                    Number(
                        item.discount_amount || 0
                    );


                // =========================
                // VAT
                // =========================

                const vatRate =
                    Number(
                        item.vat_rate || 0
                    );


                const taxableAmount =
                    Math.max(
                        0,
                        itemSubtotal -
                        itemDiscount
                    );


                const itemVat =
                    taxableAmount *
                    vatRate /
                    100;


                // =========================
                // TOTAL
                // =========================

                const itemTotal =
                    taxableAmount +
                    itemVat;


                // =========================
                // ORDER TOTAL
                // =========================

                subtotalAmount +=
                    itemSubtotal;

                discountAmount +=
                    itemDiscount;

                vatAmount +=
                    itemVat;


                return {

                    purchase_id:
                        Number(
                            item.purchase_id
                        ),

                    product_id:
                        Number(
                            item.product_id
                        ),

                    product_name:
                        item.name || '',

                    quantity:
                        quantity,

                    selling_price:
                        Number(
                            sellingPrice.toFixed(2)
                        ),

                    subtotal_amount:
                        Number(
                            itemSubtotal.toFixed(2)
                        ),

                    discount_amount:
                        Number(
                            itemDiscount.toFixed(2)
                        ),

                    vat_rate:
                        vatRate,

                    vat_amount:
                        Number(
                            itemVat.toFixed(2)
                        ),

                    total_amount:
                        Number(
                            itemTotal.toFixed(2)
                        ),

                    is_gift:
                        isGift
                };
            });


        // =========================
        // ORDER TOTAL
        // =========================

        subtotalAmount =
            Number(
                subtotalAmount.toFixed(2)
            );

        discountAmount =
            Number(
                discountAmount.toFixed(2)
            );

        vatAmount =
            Number(
                vatAmount.toFixed(2)
            );


        const totalAmount =
            Number(
                (
                    subtotalAmount -
                    discountAmount +
                    vatAmount
                ).toFixed(2)
            );


        // =========================
        // VALIDATE ITEMS
        // =========================

        for (const item of items) {

            if (
                !item.purchase_id ||
                item.purchase_id <= 0
            ) {

                throw new Error(
                    'Sản phẩm không có phiếu nhập hợp lệ.'
                );
            }


            if (
                !item.product_id ||
                item.product_id <= 0
            ) {

                throw new Error(
                    'Sản phẩm trong giỏ hàng không hợp lệ.'
                );
            }


            if (!item.product_name) {

                throw new Error(
                    'Sản phẩm không có tên hợp lệ.'
                );
            }


            if (
                !item.quantity ||
                item.quantity <= 0
            ) {

                throw new Error(
                    'Số lượng sản phẩm không hợp lệ.'
                );
            }


            if (
                item.selling_price < 0
            ) {

                throw new Error(
                    'Giá bán sản phẩm không hợp lệ.'
                );
            }


            if (
                ![0, 1].includes(
                    item.is_gift
                )
            ) {

                throw new Error(
                    'Trạng thái quà tặng không hợp lệ.'
                );
            }
        }


        // =========================
        // CREATE ORDER
        // =========================

        const orderResponse =
            await fetch(
                '/api/orders',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json'
                    },

                    body:
                        JSON.stringify({

                            customer_id:
                                customerId,

                            subtotal_amount:
                                subtotalAmount,

                            discount_amount:
                                discountAmount,

                            vat_amount:
                                vatAmount,

                            total_amount:
                                totalAmount,

                            items:
                                items,

                            description:
                                note || null

                        })
                }
            );


        const orderJson =
            await orderResponse.json();


        if (
            !orderResponse.ok ||
            !orderJson.success
        ) {

            throw new Error(
                orderJson.message ||
                'Đặt hàng thất bại.'
            );
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
            '/checkout/success';


    } catch (error) {

        console.error(
            'Submit order error:',
            error
        );


        alert(
            error.message ||
            'Lỗi hệ thống.'
        );


        if (button) {

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