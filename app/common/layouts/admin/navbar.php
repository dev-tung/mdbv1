
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top admin-navbar">
    <div class="container-fluid">

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Mở menu quản trị"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav">

            <!-- ========================= -->
            <!-- SẢN PHẨM -->
            <!-- ========================= -->
            <li class="nav-item dropdown">

                <a 
                    class="nav-link dropdown-toggle <?= active_menu('/admin/products') ?:
                        active_menu('/admin/products/create') ?>"
                    href="#"
                    data-bs-toggle="dropdown"
                >
                    Sản phẩm
                </a>


                <ul class="dropdown-menu">

                    <li>
                        <a 
                            class="dropdown-item" 
                            href="<?= route('/admin/products') ?>"
                        >
                            Danh sách sản phẩm
                        </a>
                    </li>


                    <li>
                        <a 
                            class="dropdown-item" 
                            href="<?= route('/admin/products/create') ?>"
                        >
                            Thêm sản phẩm
                        </a>
                    </li>

                    <li>
                        <a 
                            class="dropdown-item" 
                            href="<?= route('/job/ducan/list') ?>"
                        >
                            Đức An Sports
                        </a>
                    </li>


                </ul>

            </li>

                <!-- ========================= -->
                <!-- NHÀ CUNG CẤP -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/suppliers') ?:
                        active_menu('/admin/suppliers/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Nhà cung cấp
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/suppliers',
                        ) ?>">Danh sách nhà cung cấp</a></li>
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/suppliers/create',
                        ) ?>">Thêm nhà cung cấp</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- MUA HÀNG -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/purchases') ?:
                        active_menu('/admin/purchases/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Mua hàng
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/purchases',
                        ) ?>">Danh sách phiếu mua</a></li>
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/purchases/create',
                        ) ?>">Thêm phiếu mua</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- KHÁCH HÀNG -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/customers') ?:
                        active_menu('/admin/customers/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Khách hàng
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/customers',
                        ) ?>">Danh sách khách hàng</a></li>
                        <li><a class="dropdown-item" href="<?= route(
                        	'/admin/customers/create',
                        ) ?>">Thêm khách hàng</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- ĐƠN HÀNG -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= active_menu('/admin/orders') ?:
                        active_menu('/admin/orders/create') ?>"
                        href="#"
                        data-bs-toggle="dropdown">

                        Đơn hàng
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= route('/admin/orders') ?>">Danh sách đơn hàng</a></li>
                        <li><a class="dropdown-item" href="<?= route('/admin/online-orders') ?>">Đơn hàng online</a></li>
                        <li><a class="dropdown-item" href="<?= route('/admin/orders/create') ?>">Thêm đơn hàng</a></li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- BÁO CÁO -->
                <!-- ========================= -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/revenue') ?> <?= active_menu(
                        	'/admin/inventory',
                        ) ?> <?= active_menu('/admin/buyer') ?>"
                        href="#"
                        data-bs-toggle="dropdown"
                    >
                        Báo cáo
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?= route('/admin/revenue') ?>">
                                Doanh thu
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= route('/admin/inventory') ?>">
                                Tồn kho
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= route('/admin/buyer') ?>">
                                Khách hàng
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/vat') ?>"
                        href="#"
                        data-bs-toggle="dropdown"
                    >
                        Thuế
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?= route('/admin/vat') ?>">
                                Thuế GTGT
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ========================= -->
                <!-- CHẤM CÔNG -->
                <!-- ========================= -->
                <li class="nav-item">

                    <a
                        class="nav-link <?= active_menu('/admin/attendance') ?>"
                        href="<?= route('/admin/attendance') ?>"
                    >
                        Chấm công
                    </a>

                </li>
            </ul>

        <!-- RIGHT SIDE -->
        <ul class="navbar-nav ms-auto align-items-center">

            <!-- NOTIFICATION -->
            <li class="nav-item me-4 position-relative">
            <a href="#" id="notificationBell"
                class="nav-link d-flex align-items-center position-relative">
                <span class="position-absolute badge rounded-pill bg-danger d-none"
                    aria-label="Thông báo mới">0</span>
            </a>

            <div id="notificationDropdown"
                class="dropdown-menu dropdown-menu-end p-0">
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