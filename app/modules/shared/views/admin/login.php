<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm col-12 col-sm-8 col-md-6 col-lg-4">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Đăng nhập</h3>
            <form id="login-form">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" placeholder="Nhập username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Đăng nhập</button>
            </form>
            <div class="mt-3 text-center">
                <a href="#" class="text-dark">Chưa có tài khoản? Đăng ký</a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("login-form").addEventListener("submit", async function(e){
    e.preventDefault();

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    try {
        const response = await fetch("/api/admin/login", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ username: username, password: password })
        });

        const result = await response.json();

        if(result.success){
            // Login thành công → redirect về trang chính
            window.location.href = "/admin/orders";
        } else {
            alert(result.message);
        }
    } catch(err){
        console.error(err);
        alert("Có lỗi xảy ra, vui lòng thử lại.");
    }
});
</script>
