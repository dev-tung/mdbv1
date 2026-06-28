<main class="container py-4">

    <div class="row g-5">
        <div class="col-lg-12">

            <div class="bg-white border rounded p-4 text-center">

                <h2 class="fw-bold mb-3 text-success">
                    Đặt hàng thành công
                </h2>

                <p class="text-secondary mb-4">
                    Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ xác nhận và giao hàng sớm nhất.
                </p>

                <a href="/product" class="btn btn-success ">
                    Tiếp tục mua hàng
                </a>

                <a href="/" class="btn btn-outline-secondary">
                    Về trang chủ
                </a>

            </div>

        </div>
    </div>

</main>

<script>
const params = new URLSearchParams(window.location.search);
const id = params.get('id');

document.getElementById('orderId').innerText = id ? ('#' + id) : '---';
</script>