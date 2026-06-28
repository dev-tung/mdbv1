    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">

        <div class="collapse navbar-collapse d-flex align-items-center" id="navbarNav">

        <ul class="navbar-nav">

            <!-- SẢN PHẨM -->
            <li class="nav-item">
            <a class="nav-link <?= active_menu('/admin/products') ?>" href="<?= route('/admin/products') ?>">
                Sản phẩm
            </a>
            </li>

            <!-- THÊM SẢN PHẨM -->
            <li class="nav-item">
            <a class="nav-link <?= active_menu('/admin/products/create') ?>" href="<?= route('/admin/products/create') ?>">
                Thêm sản phẩm
            </a>
            </li>

            <!-- MUA HÀNG -->
            <li class="nav-item">
            <a class="nav-link <?= active_menu('/admin/purchases') ?>" href="<?= route('/admin/purchases') ?>">
                Mua hàng
            </a>
            </li>

            <!-- THÊM PHIẾU MUA -->
            <li class="nav-item">
            <a class="nav-link <?= active_menu('/admin/purchases/create') ?>" href="<?= route('/admin/purchases/create') ?>">
                Thêm phiếu mua
            </a>
            </li>

            <!-- ĐƠN HÀNG -->
            <li class="nav-item">
            <a class="nav-link <?= active_menu('/admin/orders') ?>" href="<?= route('/admin/orders') ?>">
                Đơn hàng
            </a>
            </li>

            <!-- THÊM ĐƠN HÀNG -->
            <li class="nav-item">
            <a class="nav-link <?= active_menu('/admin/orders/create') ?>" href="<?= route('/admin/orders/create') ?>">
                Thêm đơn hàng
            </a>
            </li>

            <!-- BÁO CÁO DOANH THU -->
            <li class="nav-item">
            <a class="nav-link <?= active_menu('/admin/reports/revenue') ?>" href="<?= route('/admin/reports/revenue') ?>">
                Báo cáo doanh thu
            </a>
            </li>

        </ul>

        <!-- RIGHT SIDE -->
        <ul class="navbar-nav ms-auto align-items-center">

            <!-- NOTIFICATION -->
            <li class="nav-item me-4 position-relative">
            <a href="#" id="notificationBell"
                class="nav-link d-flex align-items-center position-relative">
                <span class="position-absolute badge rounded-pill bg-danger"
                    style="display:none;">0</span>
            </a>

            <div id="notificationDropdown"
                class="dropdown-menu p-0"
                style="top:100%; right:0; display:none; min-width:320px; height:500px; overflow-y:auto;">
            </div>
            </li>

            <!-- LOGOUT -->
            <li class="nav-item">
            <a class="nav-link pe-0"
                href="<?= route('/admin/logout') ?>"
                onclick="return confirm('Bạn có chắc muốn đăng xuất không?');">
                Logout
            </a>
            </li>

        </ul>

        </div>
    </div>
    </nav>