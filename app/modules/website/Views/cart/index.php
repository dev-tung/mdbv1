<main class="container py-3">

    <div class="row g-3">

        <!-- CART LIST -->
        <div class="col-12 col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        Giỏ hàng
                    </h5>
                </div>

                <div class="card-body">

                    <div id="cart-items">
                        Đang tải...
                    </div>

                </div>

            </div>

        </div>


        <!-- SUMMARY -->
        <div class="col-12 col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        Tổng đơn hàng
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Tạm tính
                        </span>

                        <span
                            id="cart-total"
                            class="fw-bold">
                            0 ₫
                        </span>

                    </div>


                    <button
                        type="button"
                        id="checkout-button"
                        class="btn btn-success w-100 mb-2"
                        onclick="checkout()">

                        Thanh toán

                    </button>


                    <a
                        href="/product"
                        class="btn btn-outline-secondary w-100">

                        Tiếp tục mua hàng

                    </a>

                </div>

            </div>

        </div>

    </div>

</main>


<script>

/**
 * =========================================================
 * CART STORAGE
 * =========================================================
 */

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

        console.error(
            'Cart parse error:',
            error
        );

        return [];
    }
}


function saveCart(cart) {

    localStorage.setItem(
        'cart',
        JSON.stringify(cart)
    );
}


/**
 * =========================================================
 * FORMAT MONEY
 * =========================================================
 */

function formatMoney(value) {

    return (
        Number(value || 0)
            .toLocaleString('vi-VN')
        + ' ₫'
    );
}


/**
 * =========================================================
 * ESCAPE HTML
 * =========================================================
 */

function escapeHtml(value) {

    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


/**
 * =========================================================
 * RENDER CART
 * =========================================================
 */

function renderCart() {

    const cart =
        getCart();

    const container =
        document.getElementById(
            'cart-items'
        );

    const totalElement =
        document.getElementById(
            'cart-total'
        );

    const checkoutButton =
        document.getElementById(
            'checkout-button'
        );


    if (!container) {
        return;
    }


    // =========================
    // EMPTY
    // =========================

    if (!cart.length) {

        container.innerHTML = `
            <div class="alert alert-warning mb-0">
                Giỏ hàng trống.
            </div>
        `;

        if (totalElement) {
            totalElement.innerText =
                '0 ₫';
        }

        if (checkoutButton) {
            checkoutButton.disabled =
                true;
        }

        return;
    }


    if (checkoutButton) {
        checkoutButton.disabled =
            false;
    }


    let total = 0;

    let html = '';


    // =========================
    // ITEMS
    // =========================

    cart.forEach((item, index) => {

        const price =
            Number(item.price || 0);

        let quantity =
            Number(item.quantity || 1);


        if (quantity < 1) {
            quantity = 1;
        }


        const stock =
            Number(item.stock || 0);

        const purchaseId =
            Number(item.purchase_id || 0);

        const productId =
            Number(item.product_id || 0);


        const subtotal =
            price * quantity;


        total += subtotal;


        const name =
            escapeHtml(
                item.name || 'Sản phẩm'
            );


        const image =
            item.image ||
            '/assets/images/no-image.png';


        html += `

            <div
                class="
                    d-flex
                    flex-column
                    flex-md-row
                    align-items-start
                    justify-content-between
                    border-bottom
                    py-3
                    gap-3
                "
            >

                <!-- PRODUCT -->

                <div
                    class="
                        d-flex
                        align-items-start
                        gap-3
                        flex-grow-1
                        w-100
                    "
                >

                    <img
                        src="${escapeHtml(image)}"
                        width="60"
                        height="60"
                        class="rounded border"
                        style="object-fit:contain"
                        alt="${name}"
                        onerror="
                            this.onerror=null;
                            this.src='/assets/images/no-image.png';
                        "
                    >


                    <div class="flex-grow-1">

                        <div
                            class="
                                fw-semibold
                                text-break
                            "
                        >
                            ${name}
                        </div>


                        <div class="text-danger fw-bold mt-1">
                            ${formatMoney(price)}
                        </div>


                        ${
                            purchaseId > 0
                                ? `
                                    <small class="text-muted">
                                        Tồn kho: ${stock}
                                    </small>
                                `
                                : `
                                    <small class="text-danger">
                                        Thiếu phiếu nhập
                                    </small>
                                `
                        }

                    </div>

                </div>


                <!-- ACTION -->

                <div
                    class="
                        d-flex
                        align-items-center
                        gap-2
                        flex-wrap
                    "
                >

                    <input
                        type="number"
                        min="1"
                        ${
                            stock > 0
                                ? `max="${stock}"`
                                : ''
                        }
                        value="${quantity}"
                        class="form-control form-control-sm"
                        style="width:80px"
                        onchange="
                            updateQty(
                                ${index},
                                this.value
                            )
                        "
                    >


                    <button
                        type="button"
                        class="btn btn-outline-danger btn-sm"
                        onclick="
                            removeItem(
                                ${index}
                            )
                        "
                    >
                        Xóa
                    </button>

                </div>

            </div>

        `;
    });


    container.innerHTML =
        html;


    if (totalElement) {

        totalElement.innerText =
            formatMoney(total);
    }
}


/**
 * =========================================================
 * UPDATE QUANTITY
 * =========================================================
 */

function updateQty(index, value) {

    const cart =
        getCart();


    if (!cart[index]) {
        return;
    }


    let quantity =
        parseInt(
            value,
            10
        );


    if (
        Number.isNaN(quantity) ||
        quantity < 1
    ) {
        quantity = 1;
    }


    const stock =
        Number(
            cart[index].stock || 0
        );


    if (
        stock > 0 &&
        quantity > stock
    ) {

        alert(
            `Chỉ còn ${stock} sản phẩm trong kho!`
        );

        quantity =
            stock;
    }


    if (stock === 0) {

        alert(
            'Sản phẩm hiện đã hết hàng.'
        );

        quantity =
            1;
    }


    cart[index].quantity =
        quantity;


    saveCart(
        cart
    );

    renderCart();
}


/**
 * =========================================================
 * REMOVE ITEM
 * =========================================================
 */

function removeItem(index) {

    const cart =
        getCart();


    if (!cart[index]) {
        return;
    }


    cart.splice(
        index,
        1
    );


    saveCart(
        cart
    );

    renderCart();
}


/**
 * =========================================================
 * CHECKOUT
 * =========================================================
 */

function checkout() {

    const cart =
        getCart();


    if (!cart.length) {

        alert(
            'Giỏ hàng đang trống!'
        );

        return;
    }


    // =========================
    // VALIDATE CART
    // =========================

    const invalidItem =
        cart.find(item => {

            const productId =
                Number(
                    item.product_id || 0
                );

            const purchaseId =
                Number(
                    item.purchase_id || 0
                );

            const quantity =
                Number(
                    item.quantity || 0
                );


            return (
                productId <= 0 ||
                purchaseId <= 0 ||
                quantity <= 0
            );
        });


    if (invalidItem) {

        alert(
            'Giỏ hàng có sản phẩm không hợp lệ hoặc thiếu phiếu nhập.'
        );

        return;
    }


    // =========================
    // CHECK STOCK
    // =========================

    const outOfStock =
        cart.find(item => {

            const stock =
                Number(
                    item.stock || 0
                );

            const quantity =
                Number(
                    item.quantity || 0
                );


            return (
                stock <= 0 ||
                quantity > stock
            );
        });


    if (outOfStock) {

        alert(
            'Số lượng sản phẩm trong giỏ hàng vượt quá tồn kho.'
        );

        renderCart();

        return;
    }


    // =========================
    // GO CHECKOUT
    // =========================

    window.location.href =
        '/checkout';
}


/**
 * =========================================================
 * INIT
 * =========================================================
 */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        renderCart();

    }
);

</script>