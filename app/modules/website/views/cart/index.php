<main class="container py-3">

    <div class="row g-3">

        <!-- CART LIST -->
        <div class="col-12 col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Giỏ hàng</h5>
                </div>

                <div class="card-body">

                    <div id="cart-items">Đang tải...</div>

                </div>

            </div>

        </div>

        <!-- SUMMARY -->
        <div class="col-12 col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Tổng đơn hàng</h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <span>Tạm tính</span>
                        <span id="cart-total">0 ₫</span>
                    </div>

                    <button class="btn btn-success w-100 mb-2"
                            onclick="goCheckout()">
                        Thanh toán
                    </button>

                    <a href="/product" class="btn btn-outline-secondary w-100">
                        Tiếp tục mua hàng
                    </a>

                </div>

            </div>

        </div>

    </div>

</main>

<script>

function getCart() {
    return JSON.parse(localStorage.getItem('cart')) || [];
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// =========================
// RENDER CART (MOBILE FIXED)
// =========================
function renderCart() {

    const cart = getCart();
    const container = document.getElementById('cart-items');

    if (!container) return;

    if (!cart.length) {
        container.innerHTML = `
            <div class="alert alert-warning mb-0">
                Giỏ hàng trống
            </div>
        `;
        document.getElementById('cart-total').innerText = '0 ₫';
        return;
    }

    let total = 0;
    container.innerHTML = '';

    cart.forEach((item, index) => {

        const price = Number(item.price || 0);
        const qty = Number(item.quantity || 0);
        const stock = Number(item.stock || 999999);

        const subtotal = price * qty;
        total += subtotal;

        container.innerHTML += `
            <div class="d-flex flex-column flex-md-row align-items-start justify-content-between border-bottom py-3 gap-3">

                <!-- LEFT -->
                <div class="d-flex align-items-start gap-3 flex-grow-1">

                    <img src="${item.image}"
                         width="60"
                         height="60"
                         class="rounded"
                         style="object-fit:contain">

                    <div class="flex-grow-1">

                        <div class="fw-semibold text-break">
                            ${item.name}
                        </div>

                        <div class="text-danger fw-bold">
                            ${price.toLocaleString('vi-VN')} ₫
                        </div>

                        <small class="text-muted">
                            Tồn kho: ${stock}
                        </small>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <input type="number"
                           min="1"
                           max="${stock}"
                           value="${qty}"
                           class="form-control form-control-sm"
                           style="width:80px"
                           onchange="updateQty(${index}, this.value)">

                    <button class="btn btn-outline-danger btn-sm"
                            onclick="removeItem(${index})">
                        Xóa
                    </button>

                </div>

            </div>
        `;
    });

    document.getElementById('cart-total').innerText =
        total.toLocaleString('vi-VN') + ' ₫';
}

// =========================
// UPDATE QTY
// =========================
function updateQty(index, qty) {

    let cart = getCart();

    qty = parseInt(qty);

    if (isNaN(qty) || qty < 1) qty = 1;

    const stock = Number(cart[index].stock || 999999);

    if (qty > stock) {
        alert(`Chỉ còn ${stock} sản phẩm trong kho!`);
        qty = stock;
    }

    cart[index].quantity = qty;

    saveCart(cart);
    renderCart();
}

// =========================
// REMOVE ITEM
// =========================
function removeItem(index) {

    let cart = getCart();

    cart.splice(index, 1);

    saveCart(cart);
    renderCart();
}

// =========================
// CHECKOUT
// =========================
function goCheckout() {
    window.location.href = '/checkout';
}

// INIT
document.addEventListener('DOMContentLoaded', renderCart);

</script>