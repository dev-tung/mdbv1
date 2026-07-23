<main class="container py-3">

    <div class="row g-3">

        <!-- LEFT: CART -->
        <div class="col-12 col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Đơn hàng của bạn</h5>
                </div>

                <div class="card-body">

                    <div id="checkout-items">Đang tải...</div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Tổng tiền</span>
                        <span id="checkout-total">0 ₫</span>
                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT: CUSTOMER -->
        <div class="col-12 col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Thông tin nhận hàng</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" id="customer_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" id="phone" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" id="address" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea id="note" class="form-control" rows="3"></textarea>
                    </div>

                    <button class="btn btn-success w-100" onclick="submitOrder()">
                        Đặt hàng
                    </button>

                </div>

            </div>

        </div>

    </div>

</main>

<script>

function getCart() {
    return JSON.parse(localStorage.getItem('cart')) || [];
}

// =========================
// RENDER CHECKOUT (FIXED MOBILE BUG)
// =========================
function renderCheckout() {

    const cart = getCart();
    const container = document.getElementById('checkout-items');

    if (!container) return;

    if (!cart.length) {
        container.innerHTML = `
            <div class="alert alert-warning mb-0">
                Giỏ hàng trống
            </div>`;
        document.getElementById('checkout-total').innerText = '0 ₫';
        return;
    }

    let total = 0;
    container.innerHTML = '';

    cart.forEach(item => {

        const price = Number(item.price) || 0;
        const qty   = Number(item.quantity) || 1;
        const line  = price * qty;

        total += line;

        container.innerHTML += `
            <div class="d-flex align-items-start border-bottom py-3">

                <!-- IMAGE -->
                <div class="flex-shrink-0 d-flex align-items-center">
                    <img src="${item.image}"
                         width="60"
                         height="60"
                         class="rounded"
                         style="object-fit:contain">
                </div>

                <!-- CONTENT -->
                <div class="flex-grow-1 ps-3 d-flex flex-column justify-content-center">

                    <div class="fw-semibold text-break">
                        ${item.name}
                    </div>

                    <small class="text-muted">
                        Số lượng ${qty}
                    </small>

                    <div class="fw-bold text-danger">
                        ${line.toLocaleString('vi-VN')} ₫
                    </div>

                </div>

            </div>
        `;
    });

    document.getElementById('checkout-total').innerText =
        total.toLocaleString('vi-VN') + ' ₫';
}

// =========================
// SUBMIT ORDER
// =========================
async function submitOrder() {

    try {

        const cart = getCart();

        if (!cart.length) {
            alert('Giỏ hàng trống');
            return;
        }

        const customer_name = document.getElementById('customer_name').value.trim();
        const phone         = document.getElementById('phone').value.trim();
        const address       = document.getElementById('address').value.trim();
        const note          = document.getElementById('note').value.trim();

        if (!customer_name || !phone || !address) {
            alert('Vui lòng nhập đầy đủ thông tin');
            return;
        }

        // CREATE CUSTOMER
        const cusRes = await fetch('/api/customers', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: customer_name,
                phone,
                email: '',
                group_id: 0,
                address,
                description: note
            })
        });

        const cusJson = await cusRes.json();

        if (!cusJson.success) {
            alert(cusJson.message || 'Không tạo được khách hàng');
            return;
        }

        const products = cart.map(item => ({
            product_id: Number(item.product_id),
            quantity: Number(item.quantity),
            price: Number(item.price),
            discount: 0
        }));

        const orderRes = await fetch('/api/orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                customer_id: cusJson.id,
                products,
                description: note
            })
        });

        const orderJson = await orderRes.json();

        if (orderJson.success) {

            localStorage.removeItem('cart');

            alert('Đặt hàng thành công');

            window.location.href = '/cart/success';

        } else {
            alert(orderJson.message || 'Đặt hàng thất bại');
        }

    } catch (err) {
        console.error(err);
        alert('Lỗi hệ thống');
    }
}

// INIT
document.addEventListener('DOMContentLoaded', renderCheckout);

</script>