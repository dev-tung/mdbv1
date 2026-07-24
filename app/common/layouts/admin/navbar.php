
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">

        <div class="collapse navbar-collapse d-flex align-items-center" id="navbarNav">

            <ul class="navbar-nav">

                <!-- ========================= -->
                <!-- SẢN PHẨM -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/shop/products') ?:
                    	active_menu('/admin/shop/products/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Sản phẩm
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route('/admin/shop/products') ?>">Danh sách sản phẩm</a></li>
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/shop/products/create',
                        ) ?>">Thêm sản phẩm</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- NHÀ CUNG CẤP -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/shop/suppliers') ?:
                    	active_menu('/admin/shop/suppliers/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Nhà cung cấp
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/shop/suppliers',
                        ) ?>">Danh sách nhà cung cấp</a></li>
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/shop/suppliers/create',
                        ) ?>">Thêm nhà cung cấp</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- MUA HÀNG -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/shop/purchases') ?:
                    	active_menu('/admin/shop/purchases/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Mua hàng
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/shop/purchases',
                        ) ?>">Danh sách phiếu mua</a></li>
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/shop/purchases/create',
                        ) ?>">Thêm phiếu mua</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- KHÁCH HÀNG -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/crm/customers') ?:
                    	active_menu('/admin/crm/customers/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Khách hàng
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/crm/customers',
                        ) ?>">Danh sách khách hàng</a></li>
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/crm/customers/create',
                        ) ?>">Thêm khách hàng</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- ĐƠN HÀNG -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/shop/orders') ?:
                    	active_menu('/admin/shop/orders/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Đơn hàng
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route('/admin/shop/orders') ?>">Danh sách đơn hàng</a></li>
                        <li><a class="dropdown-item" href="<?= route('/admin/shop/orders/create') ?>">Thêm đơn hàng</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- BÁO CÁO -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/shop/revenue') ?> <?= active_menu('/admin/shop/inventory') ?> <?= active_menu('/admin/shop/buyer') ?>"
                        href="#"
                        data-bs-toggle="dropdown"
                    >
                        Báo cáo
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?= route('/admin/shop/revenue') ?>">
                                Doanh thu
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= route('/admin/shop/inventory') ?>">
                                Tồn kho
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= route('/admin/shop/buyer') ?>">
                                Khách hàng
                            </a>
                        </li>
                    </ul>
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
                href="<?= route('/admin/shop/logout') ?>"
                onclick="return confirm('Bạn có chắc muốn đăng xuất không?');">
                Logout
            </a>
            </li>

        </ul>

        </div>
    </div>
</nav>